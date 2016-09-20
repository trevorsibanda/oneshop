<?php
/**
 * Notifications cron job.
 *
 * Notifications are sent as email and sms. 
 * This is not thesameasalerts which are auto-generated messages
 *
 * @author      Trevor Sibanda
 * @date        10 October 2015
 * @package     Controllers/cron/Notifications
 */

class Notifications extends OS_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->data = $this->load_shop();
        $this->_shop = $this->data['shop'];
        $this->contact = $this->_shop['contact'];

        $this->load->library('email');
        $this->system->load_sms();

        
    }
    
    public function index()
    {
        $this->autorun();
    }
    
    /**
     * Runs all of the cron notification tasks and generates a report.
     *
     * This does not run the backup recovery module.
     *
     * Should ideally be run once every 5 minutes
     */
    public function autorun()
    {
        
    }
    
    /**
     * Extract sms messages from queue and send them out.
     *
     * NB: If shop is out of sms messages, the message is ignored.
     *
     * Ideally should be run every 5 minutes
     */
    public function run_sms_queue( )
    {
        
        
        set_time_limit(0);
        $cycle_time = 1;
        
        //10 sms every 5 minutes
        $smslist = $this->system->pushnotification->pop_sms_priority( 10 ,  True , $this->_shop['shop_id'] );
        
        if( empty($smslist ) )
            die('SMS queue is empty');
        
        $sender_id = $this->contact['sms_sender_id'];
        
        echo '[=] Starting SMS Queue at ' . date('r') . "\n";
        echo '[=] Account has ' . account_can('sms') . ' credit left' . "\n";

        
        $sms_left = account_can('sms');
        foreach( $smslist as $sms )
        {
            if( $sms_left == 0 )
            {
                echo '[!] Account ran out of sms credit ' . date('r') . "\n";
            }
            if( $sms['phone_number'] == '*' or empty($sms['phone_number'] ) )
            {
                $sms['phone_number'] = $this->_shop['contact']['notify_sms'];
            }
            
            if(! $this->system->is_valid_phone_number( $sms['phone_number'] ) )
            {
                echo "[!] Target phone number is not valid - \n" . $sms['phone_number']; 

                $try_2 = $this->system->make_international_number(  $sms['phone_number'] );
                if( $try_2 == False or $this->system->is_valid_phone_number($try_2) == False )
                {
                    //invalid none zim number
                    $sms['log'] .= '[!] Target phone number is invalid '; 
                    $sms['status'] = 'failed';
                    $sms['is_sent'] = True;
                    $this->system->pushnotification->update( $sms['notification_id'] , $sms );
                    continue;
                }
                echo "[+] Maybe target number was... trying " . $try_2;  
                $sms['phone_number'] = $try_2;    
                
            }
            echo '[+] Notification ID: ' . $sms['notification_id'] . "\n" ;
            echo '[+] Receipient: ' . $sms['phone_number'] . '( '. $this->system->sms->sms_pages( $sms['message'] ) . ' pages ) :: ' . "\n" ;
            
            $response = False;
            if( account() == 'free' )
            {
                echo "[=] Subscribed to free account. cannot send sms. \n";
                
            }
            else
            {
                $response = $this->system->sms->send_sms( $sms['phone_number'] , $sms['message'] , $sender_id );    
            }
            if( $response === FALSE )
			{
				$log = "[!] Error sending SMS message. Response was false, SMS flagged as FAILED [ SMS ID: " . $sms['notification_id'] . " ] \n";
				$sms['log'] .= ( $log . $response['raw'] );
                $sms['is_sent'] = true;
				echo $log;
				$this->system->pushnotification->update( $sms['notification_id'] , $sms );
			}
			else
			{
				$log = "";
				$do_exit = FALSE;
				switch( $response['status'] )
				{
					//update
					case 'failed':
					{
						$sms['status'] = 'failed';
                        $sms['is_sent'] = True;
						$log = "[!] Failed to send SMS Message to [SMS ID: {$sms['notification_id']} ] \n";
						$sms['log'] = $log  . $response['raw'];
					}
					break;
					//all is well
					case 'sent':
					{
						$sms['status'] = 'sent';
                        $sms['is_sent'] = True;
						$log = "[+] Message sent [ SMS ID: {$sms['notification_id']} ] \n";
						$sms['log'] .= $response['raw'];
					}
					break;
					//Odd, but still allowed
					case 'delivered':
					{
						$sms['status'] = 'delivered';
                        $sms['is_sent'] = True;
						$log = "[+] Message delivered  [ SMS ID: {$sms['notification_id']} ] \n"; 
						$sms['log'] .=   $response['raw'] ;
					}
					break;
					//Message was not sent because of too many requests.
					//have to quit process
					case 'throttled':
					{	
						$sms['status'] = 'queued';
						$sms['priority'] += 1;
						$log = "[!] Messsages to SMS gateway throttled down. Ending cron job  [ SMS ID: {$sms['notification_id']} ] {$response['raw']} \n";
						$sms['log'] .=  $response['raw'];
						$do_exit = TRUE;
					}
					break;
					//error on the side of the gateway, we must  retry
					case 'internal_error':
					{
						$sms['status'] = 'queued';
						$sms['priority'] += 1;
						$sms['log'] .= $response['raw'];
						$log = "[FATAL] An internal error occured on the side of the SMS gateway, Job will be terminated \n";
						$do_exit = TRUE;
					}
					break;
					//unknown error occured but whatever it is, its not good
					default:
						$sms['log'] .= $response['raw'];
						$sms['status'] ='failed';
                        $sms['is_sent'] = True; 
						$log = "[FATAL] An unknwon error has occured and forced the job to terminate. \n";
						$do_exit = TRUE; 
					break;
				}
				
				echo $log . "\n";
				//update sms
				$this->system->pushnotification->update( $sms['notification_id'] , $sms );
				//if we should quit
				if( $do_exit )
				{
					echo "[ERROR] Job will now terminate as requested !  " . date('r') . "\n";
					$this->shop->account->set_credit($sms['shop_id'] , 'sms' , $sms_left );
        
                    return;
				}
				
			}
		//each sms is 140 charcaters
            $sms_pages = $this->system->sms->sms_pages( $sms['shop_id'] ,  $sms['message'] , 140 );
	  if( $sms_pages <= 0 ) $sms_pages = 1;
	   $sms_left = $sms_left - $sms_pages;	


				
			//sleep
			sleep( $cycle_time );
        }

        //reduce account sms credit
        $this->shop->account->set_credit('sms' , $sms_left );
        
        echo '[=] Ended SMS queue at ' . date('r') . "\n";
    }
    
    /**
     * Get emails from queue and send them out.
     *
     * Ideally should be run 5 minutes
     */
    public function run_email_queue()
    {
        
        set_time_limit(0);
        $cycle_time = 0.25;//4 emails a second
        
        //100 emails every 5 minutes
        $emaillist = $this->system->pushnotification->pop_email_priority( 100 ,  True , $this->_shop['shop_id'] );
        
        if( empty($emaillist ) )
            die('[=] Email queue is empty');
        
        $sender_id = $this->contact['sms_sender_id'];
        
        echo '[=] Starting Email Queue at ' . date('r') . "\n";
        echo "[=] Found ". count($emaillist) . " emails to send. \n";

        

        $shop_admin = $this->shop->user->get( $this->data['shop']['admin_id'] );
        $shop = $this->data['shop'];

	$set_reply_to = (!filter_var($shop['contact_email'], FILTER_VALIDATE_EMAIL) === false) ?  $shop['contact_email'] : Null;

        foreach ($emaillist as $email) 
        {
            if( $email['email'] == '*' or empty($email['email'] ) )
            {
                $email['email'] = $shop_admin['email'];
            }
            
            if(! $this->system->is_valid_email( $email['email'] ) )
            {
                echo "[!] Target email is not valid\n";   
                continue;
            }
            echo '[+] Notification ID: ' . $email['notification_id'] . "\n" ;
            echo '[+] Receipient: ' . $email['fullname'] . ' - ' .  $email['email'] . '( '. count( $email['message'] )/1024 . ' Kbytes ) :: ' . "\n" ;
            
            $this->email->clear();

            $this->email->to(  $email['email'] );
            $this->email->from('no-reply@' . OS_DOMAIN , $shop['name'] );
	    if( ! is_null($set_reply_to ) )
		$this->email->reply_to( $set_reply_to );
	
            $this->email->subject( $email['title'] );
            $this->email->message(  $email['message'] );
            // You need to pass FALSE while sending in order for the email data
            // to not be cleared - if that happens, print_debugger() would have
            // nothing to output.
            if( ! $this->email->send(FALSE) )
            {
                echo "[!] Failed to send email to receipient. \n ";
                $email['status'] = 'failed';
                $email['is_sent']  = True;
            }
            else
            {
                echo "[=] Sent email at  " . date('r') . "\n ";
                $email['status'] = 'sent'; 
                $email['is_sent'] = True;   
            }

            $email['log'] = $this->email->print_debugger();

            $this->system->pushnotification->update($email['notification_id'] , $email );
            sleep($cycle_time);
        }
        echo "[-] Run email queue completed at " . date('r') . "\n";
    }
    
    /**
     * Get app messages from queue and send them out.
     *
     * Ideally should be run every minute
     */
    public function run_app_queue()
    {
        
    }
    
    /**
     * Backup sent emails,sms and app messages from queue
     * into logs and remove from queue to optimize perfomance. 
     *
     * Should be run every 14 days
     */
    public function run_backup_queue()
    {
        
    }
    
    
    
}



