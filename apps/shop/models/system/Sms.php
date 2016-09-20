<?php
/**
 * Sms Model
 *
 * Sends SMS messages
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @package 	Models/System/Sms
 * @date 		June 10 2015
 *
 */

class Sms extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->config('sms');
		$rsms_config = $this->config->item('routesms_config');
		$this->load->library('routesms' , $rsms_config );
		$this->load->library('nexmo');
		
		$this->load->library('email');
	}

	

	/**
     * Send an sms message, this function automatically decides on the sms gateway to use
     * and then sends the sms using this particular gateway. 
     * This does not push the message to the queue but rather handles the sending of the sms, hence
     * this function might take long to execute. 
     *
     * @param 		String 		$ Receiver phone number
     * @param 		String 		$ Message to send	
     * @param		String		$ Sender ID
     *
     * @return      Array		$ Array('response' => '' , 'gateway_id' => '' , 'raw' => '' ) Repsonses are  "internal_error" , "sent" , "failed" , "throttled" , "invalid_sms"  
     */
    public function send_sms( $phone_number , $message , $from = '263SHOP' )
    {
		
		$gateway = $this->config->item('default_sms_gateway'); 
		$result = array();
		switch( $gateway )
		{
			case 'nexmo':
			{
				$response = $this->send_nexmo_sms( $phone_number , $message , $from );
				$result['raw'] = json_encode( $response );
				//$response = json_decode( $response , TRUE ); //assoc array
				if( is_array( $response ) )
				{
					if( $response['message-count'] )
					{
						$messages = $response['messages'];
						$msg_0 = $messages[0];
						switch( $msg_0['status'] )
						{
							//success
							case 0: { $result['status'] = 'sent'; } break;
							//message throttled
							case 1: { $result['status'] =  'throttled'; } break;
							//internal error on the side of nexmo
							case 5: { $result['status'] = 'internal_error'; }break;
							//Out of balance on Nexmo prepaid account
							case 9: 
							{ 
								$result['status'] = 'internal_error';
								//send notificatio message to admin
								$this->sms_admin_alert('Account has ran out of funds and an sms is supposed to be sent' , $result['raw'] );
								//@todo		Update database to notify funerally that this gateway has been maxed out
								//@todo		Allow system to automatically top up account and then retry
							}
							break;
							//nexmo refused credentials,alert admin
							case 4:
							{
								$this->sms_admin_alert('Nexmo refused the api key/ api_secret combo, please fix this' , $result['raw'] );
								$result['status'] = 'internal_error';
							}
							break;
							//Our account has been banned, contact admin
							case  8:
							{
								$this->sms_admin_alert('URGENT: Our Nexmo account has been barred from sending SMS messages' , $result['raw'] );
								$result['status'] = 'internal_error';
							}
							break;
							//INvalid number or message
							case 6:
							{
								$result['status'] = 'failed';
								//The user is still charged
							}
							break;
							//communication failed, must retry, almost the same as throttled
							case 11:
							case 12:
							case 13:
							case 14:
							{
								$result['status'] ='throttled';
							}
							break;
							//any other error is a rare system error which should be handled by the admin
							default:
							{
								//message is flagged as failed
								$this->sms_admin_alert('Unknown error encountered whilst sending SMS' , $result['raw'] . "\n\n\n\n" . json_encode( $sms ) );
								$result['status'] = 'failed';
							}
							break;
						} 
					}
					else
					{
						$result['status'] = 'failed';
					}
				}
			}
			break;
			case 'routesms':
			{
				$this->routesms->source_address( $from );
				$response = $this->routesms->send_plaintext( $phone_number , $message  );
				if( empty($response) or $response == False )
				{
					$result['raw'] = 'Failed with error:  ' . $this->routesms->last_error();
					$result['status'] = 'failed';
				}
				else
				{
					$result['raw'] = json_encode($response);
					switch(  $response['code'] )
					{
						
						case RouteSms::ERROR_SUCCESS:
						{
							
							$result['status'] = 'sent';
						}
						break;
						case RouteSms::ERROR_INVALID_URL:
						{
							$result['status'] = 'failed';
						}
						break;
						case RouteSms::ERROR_INVALID_USERNAME_PASSWORD_PAIR:
						{
							$result['status'] = 'failed';
							$this->sms_admin_alert('RouteSms refused the username-password combo, please fix this' , $result['raw'] );
								
						}
						break;
						case RouteSms::ERROR_INVALID_MESSAGE:
						{
							$result['status'] = 'failed';
						}
						break;
						case RouteSms::ERROR_INVALID_DESTINATION:
						{
							$result['status'] = 'failed';
						}
						break;
						case RouteSms::ERROR_INVALID_SOURCE:
						{
							$result['status'] = 'failed';
							//@todo log error
						}
						break;
						case RouteSms::ERROR_USER_AUTH_FAILED:
						{
							$this->sms_admin_alert('RouteSMS refused the api key/api_secret combo, please fix this' , $result['raw'] );
							$result['status'] = 'pending';
						}
						break;
						case RouteSms::ERROR_INSUFFICIENT_CREDIT:
						{
							//try again
							$result['status'] = 'pending';
							$this->sms_admin_alert('Account has ran out of funds and an sms is supposed to be sent' , $result['raw'] );
								
						}
						break;
						default:
						{
							//unknown error
							$result['status'] = 'internal_error';
						}
						break;
					}
				}
			}
			break;
			case 'debug':
			{
				$response = $this->send_debug_sms( $phone_number , $message , $from );
				$result['raw'] = 'DEBUG MESSAGE - Was printed to STDOUT';
				$result['status'] = 'sent';
			}
			break;
			default:
			{
				$response = array();
			}
			break;
		}
		return $result;
    }

    /**
     * NB: This function queries the payment gateway not the local database !
     * 
     * Queries the cost of sending an SMS to a particluar country
     * If the cost of sending an sms to that country is not available
     * The system reverts to the default price defined in funerally config
     *
     * @param       String  $ Country Code. i.e (ZW)
     * @param       INT     $ Number of SMS pages
     *
     * @todo	Nexmo returns the prices in Euros, must convert the prices to USD
     * 
     * @return      FLOAT   $ Cost in USD 
     */
    public function query_per_sms_cost( $country_code , $gateway = 'default' )
    {
		if( $gateway == 'default' )
			$gateway = $this->config->item('default_sms_gateway');
		switch( $gateway )
		{
			case 'nexmo':
			{
				$response = $this->nexmo->get_pricing( $country_code );
				
				if( ! is_object( $response ) )
				{
					return $this->config->item('fallback_sms_cost');
				}
				$price = $this->config->item('fallback_sms_price');
				if( isset( $response['mt'] ) )
					return $response['mt'];
				return $price;
			}
			break;
			case 'debug' :
			{
				return $this->config->item('fallback_sms_cost');
			}
			break;
			default:
			{
				return $this->config->item('fallback_sms_cost');
			}
		}
		return 0.00;
    
    }

    /**
     * Queries the SMS gateway to get the amount left in 
     * the account balance. (For that particluar SMS gateway )
     *
     * @param       String  $ Sms gateway to query, default is defined in funerally config
     *
     * @return      ARRAY   $ Array of details or FALSE
     *
     * @todo	Implement functionality
     */
    public function query_account_balance( $gateway = 'default' )
    {
		if( $gateway == 'default' )
			$gateway = $this->config->item('default_sms_gateway' );
		
		switch( $gateway )
		{
			case 'nexmo':
			{
				$balance = $this->nexmo->get_balance();
				//@todo Fix prices, Nexmo returns prices in Euros, we use dollars
				return (is_object( $balance ) ? $balance['value'] : 0.00 );
			}
			break;
			case 'debug':
			{
				return 0.00;
			}
			break;
			default:
			{
				return 0.00;
			}
		}
		return 0.00;
				
	}
    

	/**
     * Send an emergency alert message to the sys admin
     * 
     * @param	String	$ Alert message
     * @param	String	$ Extra data to send
     */ 
    public function sms_admin_alert( $alert_msg , $extra_data )
    {
		$phone = $this->config->item('admin_error_phone' );
		$email = $this->config->item('admin_error_email' );
		//our fallback API is nexmo
		//if( strlen( $phone) )
		//	$this->send_nexmo_sms( $phone , $alert_msg  );
		$message = "{$alert_msg} \n\n\n\n\n\n\n\n\n\n\n\n\n{$extra_data} ";
		//send email to admin
		$this->email->from('sms_errors@263shop.co.zw', 'Funerally Error');
		$this->email->to($email);
		
		$this->email->subject('SMS API Error');
		$this->email->message($message);

		$this->email->send();

	}

	/**
     * Send an SMS message using Nexmo SMS Gateway 
     * 
     * @param	String	$ Recipient phone number in international form
     * @param	String	$ Message to send as text message
     * @param	String	$ Who the message should appear to be from
     * 
     * @return	Array	$ Nexmo Response
     */ 
    public function send_nexmo_sms( $to , $message , $from = 'FUNERALLY' )
    {
		$msg = array(
        'text' => $message,
					);
		$response = $this->nexmo->send_message($from, $to, $msg );
		return $response;			
	}

	/**
     * Send a debug SMS messgae.
     * Simply prints the message out to stdout
     * 
     * 
     * @param	String	$ Recipient phone number in international form
     * @param	String	$ Message to send as text message
     * @param	String	$ Who the message should appear to be from
     * 
     * @return	Array	$ Nexmo Response
     * 
     */ 
    public function send_debug_sms( $to , $message , $from )
    {
			$msg = "<h1>From: {$from}</h1><h2>To: {$to}</h2><p><pre>{$message}</pre></p>";
			file_put_contents( APPPATH . "logs/sms.log", $msg);
			return array('status' => 0 );
	}

	/**
	 * Calculate the number of pages an SMS message is
	 * Note this function carries a 20 page limit which is fair for an SMS
	 *
	 * @param       String  $ Sms Message
	 * @param       INT     $ Character length of an SMS
	 * 
	 * @return      INT
	 */
    public function sms_pages( $message , $sms_length = 160 )
    {
        $char_len = strlen( $message );
		for( $i = 20; $i > 0 ; $i -= 1 )
		{
				if( ( ($i-1) * $sms_length ) <= $char_len )
					return $i;	
		}
		return 10000;   
     }

     /**
     * Determine which gateway to use for sending out a particular SMS message
     * 
     * @param	String	$ Country code , ZA, ZW...etc
     * 
     * @return	String	$ Gateway to use
     */  
    public function which_gateway( $country_code )
    {
		//check for config exptional cases
		$exceptions = $this->config->item('exception_gateways' );
		if( isset( $exceptions[ $country_code ] ) )
		{
			//exception gateway config
			return $exceptions[ $country_code ];
		}
		return $this->config->item('default_sms_gateway');
	}

	/**
     * Query the status of a particular gateway.
     * Find out if the gateway is online, offline, accepting messages.. etc
     * 
     * @param       String          $ Gateway       
     * 
     * @return      ARRAY           $ Gateway response
     */
    public function query_gateway_status( $gateway = 'default' )
    {
		return array('status' => 'active' );
    }
    
    /**
     * Check if a phone number is valid
     *
     * @param 		String 	$	Phone number
     */
    public function is_valid_number( $phone_number )
    {
    	//@todo regular expression to match phone number
    	return True;
    }

}
