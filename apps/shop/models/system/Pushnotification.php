<?php
/**
 * Push Notifications
 *
 * Push notifications are messages which need to be pushed to the
 * end user as quickly as possible.
 * This can be through an app notification or sms or email.
 *
 * This is somewhat similar to a message queue.
 * These messages are stored in the queue for a short time only and auomatically
 * removed by backing them up to increase perfomance.
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @package 	Models/System/Push_Notification
 * @date 		June 8 2015
 *
 */

class PushNotification extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get a push notification given its ID
	 *
	 * @param 		Int 		$	Notification ID
	 *
	 * @return 		Array 		$	Empty on fail
	 */
	public function get( $notification_id )
	{	
		$query = $this->db->get_where('push_notification' , array('notification_id' => $notification_id ) );
		return $query->result_array();
	}

	/**
	 * Push  an sms notification into the queue.
	 * NB: An sms message cannot exceed 10 pages
	 *
	 * @param 		Int 		$	Shop ID
	 * @param 		String 		$	Phone Number
	 * @param 		String 		$	Message
	 * @param 		Int 		$	Priority
	 *
	 * @return 		Int 		$	Returns false on fail
	 */
	public function push_sms( $shop_id ,  $phone_number , $message ,  $priority  = 9)
	{
		$pn = array();
		$pn['type'] = 'sms';
		$pn['shop_id'] = $shop_id;
		$pn['phone_number'] = $phone_number;
		$pn['message'] = substr($message, 0 , (160*10) );
		$pn['priority'] = $priority;
		$pn['title'] = 'SMS Message';
		$pn['time_created'] = date('Y-m-d H:m:s');
		//file_put_contents('/tmp/sms/' . $pn['shop_id'] . '-' . $pn['phone_number'] . '-' . $pn['time_created'] , json_encode($pn, JSON_PRETTY_PRINT) );
		$this->db->insert('push_notification' , $pn );
		$id = $this->db->insert_id();
		return ( $id > 0 ) ? $id : False;
	}


	/**
	 * Push an email notification into the queue
	 *
	 * @param 		Int 		$	Shop ID
	 * @param 		String 		$	Fullname
	 * @param 		String 		$	Email address
	 * @param 		String 		$	Email title/subject
	 * @param 		String 		$	Message
	 * @param 		Int 		$	Priority
	 *
	 * @return 		Int 		$	Rturns false on fail
	 */
	public function push_email( $shop_id , $fullname , $email , $title , $message , $priority = 5 )
	{
		$pn = array();
		$pn['type'] = 'email';
		$pn['shop_id'] = $shop_id;
		$pn['fullname'] = $fullname;
		$pn['email'] = $email;
		$pn['title'] = $title;
		$pn['message'] = $this->system->inline_css( $message );
		$pn['priority'] = $priority;
		$pn['time_created'] = date('Y-m-d H:m:s');
		//file_put_contents('/tmp/email/' . $pn['shop_id'] . '-' . $pn['title'] . '-' . $pn['time_created']. '.html' , $pn['message'] );
		$this->db->insert('push_notification' , $pn );
		$id = $this->db->insert_id();
		return ( $id > 0 ) ? $id : False;
	}

	/**
	 * Push an app notfication into the queue
	 *
	 * @param 		Int 		$	Shop ID
	 * @param 		String 		$	Title
	 * @param 		String 		$	Message
	 * @param 		Int 		$	Priority
	 *
	 * @return 		Int 		$	Returns false on fail
	 */
	public function push_app(  $shop_id , $title , $message , $priority = 9 )
	{
		$pn = array();
		$pn['type'] = 'app';
		$pn['shop_id'] = $shop_id;
		$pn['title'] = $title;
		$pn['message'] = $message;
		$pn['priority'] = $priority;
		$pn['time_created'] = date('Y-m-d H:m:s');
		$this->db->insert('push_notification' , $pn );
		$id = $this->db->insert_id();
		return ( $id > 0 ) ? $id : False;
	}

	/**
	 * Returns n number of app notifications from the queue.
	 *
	 * @param 		Int 		$	Number of items to fetch
	 * @param       Int         $   Shop ID *optional* 
     *
	 * @return 		Array 		$	Items frmo queue
	 */
	public function pop_app( $count = 5 , $shop_id = Null )
	{
        $conds = array('is_sent' => False , 'type' => 'app');
        if( ! is_null($shop_id) )
            $conds['shop_id'] = $shop_id;
        
		$query = $this->db->limit( $count )->get_where('push_notification' , $conds );
		return $query->result_array();
	}

	/**
	 * Returns n items from the queue. ordered by priority
	 *
	 * @param 		Int 		$	Number of items to pop from queue
	 * @param 		Bool 		$	Sort by highest to lowest 
	 *
	 * @return 		Array  		
	 */
	public function pop_app_priority( $count = 5 ,$highest = True , $shop_id = Null )
	{
		return $this->_pop_priority('app' , $count , $highest , $shop_id );
	}

	/**
	 * Returns n number of sms notifications from the queue.
	 *
	 * @param 		Int 		$	Number of items to fetch
     * @param       Int         $   Shop ID *optional*
	 *
	 * @return 		Array 		$	Items frmo queue
	 */
	public function pop_sms( $count = 5 , $shop_id = Null )
	{
		$conds = array('is_sent' => False , 'type' => 'sms');
        if( ! is_null($shop_id) )
            $conds['shop_id'] = $shop_id;
        
		$query = $this->db->limit( $count )->get_where('push_notification' , $conds );
		return $query->result_array();
	}

	public function pop_sms_priority( $count = 5 , $highest = True , $shop_id = Null )
	{
		return $this->_pop_priority('sms' , $count , $highest , $shop_id ); 
	}

	/**
	 * Returns n number of email notifications from the queue.
	 *
	 * @param 		Int 		$	Number of items to fetch
     * @param       Int         $   Shop ID *optional*
	 *
	 * @return 		Array 		$	Items frmo queue
	 */
	public function pop_email( $count = 5 , $shop_id = Null )
	{
		$conds = array('is_sent' => False , 'type' => 'email');
        if( ! is_null($shop_id) )
            $conds['shop_id'] = $shop_id;
        
		$query = $this->db->limit( $count )->get_where('push_notification' , $conds );
		return $query->result_array();
	}

	/**
	 * Pop email notifications using the priority
	 *
	 * @param 		Int 	$	Count
	 * @param 		Bool 	$	Order by ASC/DESC
	 *
	 * @return 		Array
	 */
	public function pop_email_priority( $count = 5 , $highest= True , $shop_id = Null)
	{
		return $this->_pop_priority('email' , $count , $highest , $shop_id );
	}

	/**
	 * @access Internal
	 *
	 */
	private function _pop_priority( $type , $count = 5 , $highest = True , $shop_id = Null )
	{
		$this->db->select('')
				 ->from('push_notification')
				 ->where('type' , $type)
				 ->where('is_sent' , False)
				 ->order_by('priority' , ($highest? 'ASC' : 'DESC' ) )
				 ->limit(  $count );
        if( ! is_null($shop_id) )
            $this->db->where('shop_id' , $shop_id );
		$query = $this->db->get();
		return $query->result_array();		 
	}


	/**
	 * @todo Implement
	 *
	 */
	public function remove_sent_week_old( )
	{

	}
	
	/**
	 * Update the notification queue
	 *
	 * @param 		Int 	$	Notification ID
	 * @param 		Array 	$	Notification 
	 *
	 * @return 		Bool
	 */
	public function update(  $notification_id , $notif )
	{
		unset($notif['notification_id'] );
		unset($notif['time_created']);
		unset($notif['shop_id'] );
		
		$this->db->where('notification_id' , $notification_id )
			 ->update('push_notification' , $notif );
		return True;	 
	}

	/**
	 * Get all shop notifications. including sent
	 *
	 * @param 		Int 		$	Shop ID
	 * @param 		String 		$	Type ['all' , 'email' , 'sms' , 'app']
	 * 
	 * @return 		Array
	 */
	public function get_all_shop( $shop_id , $type = 'all' )
	{
		$this->db->select('*')
				 ->from('push_notification')
				 ->where('shop_id' , $shop_id);
		if(  in_array($type, array('sms' , 'email' , 'app') ) )
			$this->db->where('type' , $type);
		$query = $this->db->get();
		return $query->result_array();
	}

};

