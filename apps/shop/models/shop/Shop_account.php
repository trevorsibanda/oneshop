<?php
/**
 * Shop settings CRUD operations
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 *
 * @module		Model/Shop/Shop_Settings
 *
 * @date		6 May 2015
 */
 
 class Shop_account extends CI_Model
 {
	 
	
	 
	 /** Ctor */
	 public function __construct()
	 {
		 parent::__construct();
		 $this->load->database();
		 //account plans
		 $this->load->config('shop_account');
	 }
	 
	 /**
	  * Get the date in which the shop's last subscription 
	  * expires.
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	String 	$	Date
	  */
	 public function get_expire_date( $shop_id  )
	 {
	 	$shop_id = intval($shop_id);
	 	$sql = "SELECT month, year , CONCAT(year,'-',month,'-1') AS date_expires 
	 	        FROM shop_account_subscription 
	 	        WHERE shop_id = {$shop_id} 
	 	        ORDER BY DATE(date_expires) DESC";
	 	$query = $this->db->query( $sql );
	 	$row = $query->row_array();
	 	if( empty($row) )
	 	{
	 		return False;
	 	}	
	 	$time = strtotime($row['date_expires']);
		return date("Y-m-d", strtotime("+1 month", $time));

	 }
	 
	 /**
	  * Update a shop's settings
	  *
	  * @param		Int		$	Subscription ID
	  * @param		Array	$	Shop settings
	  *
	  * @return		Bool
	  */
	 public function update( $sub_id , $sub )
	 {
		
		unset( $shop['shop_id'] );
		unset( $shop['sub_id'] );
		
		$this->db->where ('sub_id' , $sub_id )
				 ->update( 'shop_account_subscription'  , $sub );
		
		return True;
	 }


	 
	 /**
	  * Add shop settings for a particular shop
	  *
	  * @param		Int		$	Shop ID	
	  * @param		Array	$	Settings
	  *
	  * @return		Int		$	ShopID on success, False in fail
	  */
	 public function add( $shop_id , $settings = array() )
	 {
		$keys = array();
		foreach( $this->empty_settings as $key => $value )
			$keys[ $key ] = '';
			
		$keys['shop_id'] = $shop_id;
		if( is_array($settings) )
		foreach( $settings as $key => $value )
			$keys[ $key ] = $settings[ $key ];
			
		$this->db->insert('shop_settings' , $keys );
		return $this->db->insert_id();	
		
	 }
	 
	 /**
	  * Delete a shop's settings
	  *
	  * @param		Int		$	Shop ID
	  *
	  * @return		Bool
	  */
	 public function delete( $shop_id )
	 {
		 
	 }
	 
	 /**
	  * Check if the settings object is valid
	  *
	  * @param		Array	$	Shop settings
	  *
	  * @return		Bool
	  */
	 public function is_valid( $settings )
	 {
		foreach( $this->empty_settings as $key => $value )
			if( ! isset( $settings[ $key ]  ) )
				return False;
		return True; 
	 }
	 
	 /**
	  * Check if a particular Shop has settings
	  * 
	  * @param		Int		$	Shop ID
	  *
	  * @return		Bool
	  */
	 public function has_account(  $shop_id )
	 {
		$this->db->select('shop_id')
				 ->from('shop_account')
				 ->where('shop_id' , $shop_id ); 
		return (  $this->db->count_all_results()  ) ? True : False; 
	 }

	 /**
	  * Get a shop's subscription for a given period
	  *
	  * @param 		Int 	$	Shop ID
	  * @param 		Int 	$	Year 
	  * @param 		Int 	$	Month
	  *
	  * @return 	Array 	$	empty on fail
	  */
	 public function get_subscription( $shop_id , $year , $month  )
	 {
	 	$conditions = array('shop_id' => $shop_id , 'year' => $year ,'month' => $month );
	 	$query = $this->db->get_where('shop_account_subscription' , $conditions);
	 	return $query->row_array();
	 }

	 public function set_credit( $shop_id , $type  , $amount )
	 {
	 		$sub = $this->get_subscription(  $shop_id , date('Y') , date('m') );
	 		if( empty($sub) )
	 			return False;
	 		if(  $sub[$type . '_credit']  == 0 )
	 			return True;
	 		$sub[ $type . '_credit'] = $amount;
	 		$this->update($sub['sub_id'] , $sub );
	 		return True;			
	 }

	 /**
	  * Get all subscriptions by a particular shop.
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_all_subscriptions(  $shop_id )
	 {
	 	$query = $this->db->get_where('shop_account_subscription' , array('shop_id' => $shop_id) );
	 	return $query->result_array(); 
	 }

	 /**
	  * Add a shop account subscription
	  *
	  * @param 		Int 	$	Shop ID
	  * @param 		Int 	$	Year subscribing for
	  * @param 		Int 	$	Month ( 1-12, 1->Jan)
	  * @param 		String 	$	Valid account type. must be defined in shop_accoount config
	  * @param 		Int 	$	Number of monts to add by
	  *
	  * @return 	Int 	$	Current subscription details ID or False on fail
	  */
	 public function add_subscription( $shop_id , $year , $month , $type , $n_months = 1)
	 {
	 		$type = strtolower($type);
	 		
	 		$account_plans = $this->config->item('account_plans');
	 		
	 		if( ! in_array($type , $account_plans) )
	 			return False;
	 		$sub = $this->config->item($type . '_plan' );
	 		if( is_null($sub))
	 			return False;
	 		$sub['year'] = $year;
	 		$sub['month'] = $month;
	 		$sub['type'] = $type;
	 		$sub['shop_id'] = $shop_id;

	 		//unset extra data
	 		unset($sub['name']);
	 		unset($sub['amount_year']);
	 		//check if available
	 		if( $this->is_subscribed( $shop_id , $year , $month ) )
	 		{
	 			return False;
	 			//cannot pay for period already paid for
	 			//use upgrade instead
	 		}
	 		$this->db->insert('shop_account_subscription' , $sub );
	 		$id = $this->db->insert_id();
	 		return ( $id > 0 )? $id  : False;
	 }

	 /**
	  * Subscribe for a period of time.
	  * If successful returns an array of subscription details. Starting with the current month 
	  *
	  * @param 		Int 	$	Shop ID
	  * @param 		Int 	$	Months. Maximum of 24
	  * @param 		String 	$	Account plan to subscribe to
	  *
	  * @return 	Array|False
	  */
	 public function subscribe_period( $shop_id , $months , $type  )
	 {
	 		$year = date('Y');
	 		$current_month = date('m');
	 		$month = $current_month;
	 		$ids = array();

	 		for( $x = 0 ; $x < $months ; $x++ )
	 		{
	 			//subscribed for current month ?
	 			if( $this->is_subscribed($shop_id , $year , $month ) )
	 			{
	 				$x--;
	 			}
	 			else
	 			{
	 				//subscribe
	 				//@todo potential denial of service code
	 				$id = $this->add_subscription( $shop_id , $year , $month , $type , $x );
	 				if( $id == False )
	 				{
	 					$x--;
	 					continue;
	 				}
	 				array_push(  $ids , $id );
	 			}
	 			if( $month+1 > 12 )
	 			{
	 				$month = 0;
	 				$year += 1;
	 			}
	 			$month += 1;
	 		}
	 		return $ids;
	 }

	 public function is_subscribed( $shop_id , $year , $month  )
	 {
	 	$sub = $this->get_subscription($shop_id , $year , $month);
	 	$b = empty($sub);
	 	return ( $b ? False : True );
	 }

	 /**
	  * Update the current subscription to a new plan.
	  *
	  * @param 		Int 	$	Shop ID
	  * @param 		Int 	$	Year	
	  * @param 		Int 	$	Month
	  * @param 		String 	$	New plan
	  *
	  * @return 	Int 	$	False on fail
	  */
	 public function upgrade_subscription( $shop_id , $year , $month , $new_plan )
	 {
	 	//must be subscribed for the period
	 	$new_plan = strtolower($new_plan);
	 	$sub = $this->get_subscription( $shop_id , $year , $month );
	 	if( empty($sub) )
	 		return False; //not subscribed to this month. cant upgrade, add first
	 	if( $sub['type'] == $new_plan )
	 	{
	 		//no need to upgrade
	 		return False;
	 	}
	 	//now do we need to upgrade or downgrade
	 	$upgrade = False;
	 	$seen_current = False;
	 	//use the orering of the plans to see if we should upgrade or downgrade
	 	//for example array('free' , 'basic' , 'premium' )
	 	//if the new plan is set to premium from the current plan of basic
	 	//just the read the code !
	 	foreach( $this->config->item('account_plans') as $plan )
	 	{
	 		if( $plan === $new_plan )
	 			$seen_current = True;
	 		if( $sub['type'] == $new_plan and $seen_current )
	 			$upgrade = True;
	 		else
	 			$upgrade = False;
	 	}
	 	//if downgrading we are done, he'll have to wait till the sub is invalid
	 	//if( ! $upgrade )
	 	//	return True;
	 	//must update the current sub
	 	$new_sub = $this->config->item( $new_plan . '_plan');
	 	
	 	if( is_null($new_sub))
	 		return False;
	 	$sub['log'] .= "\nUpgraded from plan " . $sub['type'] . ' to ' . $new_plan . ' providing ' . $new_sub['amount'] . ' on ' . date('r');
	 	$sub['type'] = $new_plan;
	 	$sub['amount'] += $new_sub['amount'];
	 	$to_copy = array('max_products','custom_domain' ,'max_users' ,'allow_pos' ,'allow_analytics','custom_payment_details' );
	 	
	 	foreach( $to_copy as $key  )
	 	{
	 		$sub[ $key ] = $new_sub[ $key ];
	 	}
	 	//now update
	 	$this->db->where('sub_id' , $sub['sub_id']);
	 	unset( $sub['sub_id']);
	 	$this->db->update('shop_account_subscription' , $sub );
	 	return True;
	 }
	 
	 public function upgrade_period(  $shop_id , $months , $type  )
	 {
	 		$year = date('Y');
	 		$current_month = date('m');
	 		$month = $current_month;
	 		$ids = array();
	 		for( $x = 1 ; $x <= $months ; $x++ )
	 		{
	 			$b = $this->is_subscribed($shop_id , $year , $month );
	 			if( ! $b )
	 				$this->add_subscription( $shop_id , $year , $month , $type  );
	 			else
	 				$this->upgrade_subscription( $shop_id , $year , $month , $type );	

	 			
	 			if( $month+1 > 12 )
	 			{
	 				$month = 0;
	 				$year += 1;
	 			}
	 			$month += 1;
	 		}

	 		return $ids;
	 }

	 /**
	  * Get the shop's current subscription.
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function current_subscription(  $shop_id )
	 {
	 	return $this->get_subscription( $shop_id , date('Y') , date('m') );
	 }

	 /**
	  * Return all valid subscription types
	  *
	  * @return 		Array
	  */
	 public function subscription_types(  )
	 {
	 	$subs = array();
	 	$types = $this->config->item('account_plans');
	 	foreach ($types as $key) 
	 	{
	 		$subs[$key] = $this->config->item($key . '_plan');
	 	}
	 	return $subs;
	 }

	 /**
	  *
	  *
	  * @todo Implement functionality.
	  */
	 public function months_to_expire_subscription(  $shop_id )
	 {
	 
	 }


	 
	 private function full_query_string(  )
	 {
		$parts = array();
		foreach( $this->empty_settings as $key => $val )
			array_push( $parts , $key ); 
		return implode( " , " , $parts );
	 }


	 
 };
 
 