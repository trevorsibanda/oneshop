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
 
 class Shop_settings extends CI_Model
 {
	 
	
	 
	 /** Ctor */
	 public function __construct()
	 {
		 parent::__construct();
		 $this->load->database();
	 }
	 
	 /**
	  * Get all the shop's settings given the shop_id
	  *
	  * @param		Int		$	Shop ID
	  *
	  *	@return		Array	$	Settings, empty array on fail
	  */
	 public function get( $shop_id )
	 {
	 	$settings = array();
		$settings['contact'] = $this->get_contact_settings($shop_id);
		$settings['order'] = $this->get_order_settings($shop_id);
		$settings['payment'] = $this->get_payment_settings($shop_id);
		$settings['shipping'] = $this->get_shipping_settings($shop_id);
		$settings['theme'] = $this->get_theme_settings($shop_id);
		$settings['analytics'] = $this->get_analytics_settings($shop_id);
		return $settings;
	 }


	 /**
	  * Get all shop analytics settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_analytics_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_analytics' , array('shop_id' => $shop_id));
	 	return $query->row_array();
	 }

	 /**
	  * Get all shop contact settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_contact_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_contact' , array('shop_id' => $shop_id));
	 	return $query->row_array();
	 }

	 /**
	  * Get all shop order settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_order_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_order' , array('shop_id' => $shop_id));
	 	return $query->row_array();
	 }

	 /**
	  * Get all shop payment settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_payment_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_payment' , array('shop_id' => $shop_id));
	 	return $query->row_array();
	 }

	 /**
	  * Get all shop shipping settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_shipping_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_shipping' , array('shop_id' => $shop_id));
	 	return $query->row_array();
	 }
	 
	 /**
	  * Get *raw/unparsed* shop theme settings
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_all_theme_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_theme' , array('shop_id' => $shop_id));
	 	return $query->result_array();
	 }

	/**
	  * Get *raw/unparsed* shop theme settings of the ACTIVE theme
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Array
	  */
	 public function get_theme_settings( $shop_id )
	 {
	 	$query = $this->db->get_where('shop_settings_theme' , array('shop_id' => $shop_id , 'is_active' => True));
	 	return $query->row_array();
	 }	 


	 /**
	  * Update a shop's settings. updates payment, contact, order and shipping settings.
	  * 
	  * Does not update shop theme settings. use the UI Model for that.
	  *
	  * @param		Int		$	Shop ID
	  * @param		Array	$	Shop settings
	  *
	  * @return		Bool
	  */
	 public function update( $shop_id , $settings , $type = 'all' )
	 {
		//dont edit theme
		unset($settings['theme']);

		//unset dangerous keys
		if( isset($shop['shop_id'] ) )
			unset( $shop['shop_id'] );
		
		$sections = array('contact' , 'payment' ,  'order' , 'shipping' , 'analytics' );
		if( ! in_array($type, $sections) )
		{
			foreach ($sections as $key) 
			{
				if( isset($settings[$key]) and is_array($settings[$key]))
				{
					//update 
					$this->update( $shop_id , $settings[$key] , $key );
				}
			}
		}
		else
		{
			$table = 'shop_settings_' . strtolower($type);
			$this->db->where ('shop_id' , $shop_id )
				 ->update( $table  , $settings );
		

		}
		
		return True;
	 }
	 
	 /**
	  * Add shop settings for a particular shop
	  *
	  * @param		Int		$	Shop ID	
	  * @param		Array	$	Settings
	  *
	  * @return		Bool	$	True on success, False in fail
	  */
	 public function add( $shop_id , $settings = array()  , $type  = 'all' )
	 {
		if( ! is_array($settings))
		{
			return False;
		}
		$expected = array( 'contact' => array('shop_id', 'notify_sms') ,
						   'order' => array('shop_id' ) ,
						   'shipping' => array('shop_id' ),
						   'payment' => array('shop_id' ) ,
						   'theme' =>  array('shop_id' ) );
			
		if( $type == 'all' )
		{
			//@too fix buggy check
			foreach(  $expected as $key => $checks )
			{
				//check if all required columns have values
				foreach ($checks as $col ) 
				{
					if( ! isset($settings[$key][$col]))
					{
						continue; //a required key s not set
					}
				}
				//now add
				$this->add( $shop_id , $settings[ $key ] , $key );
			}
			return True;
		}	
		else
		{
			$table = 'shop_settings_' . strtolower( $type );
			$this->db->insert($table , $settings );
			return True;	
		}
			
	 }
	 
	 /**
	  * Delete a shop's settings. Only called when a user wants to deactivate their account
	  *
	  * @param		Int		$	Shop ID
	  *
	  * @return		Bool
	  */
	 public function delete( $shop_id )
	 {
		 $tables = array('payment' , 'order' , 'shipping' , 'theme' , 'contact');
		 foreach( $tables as $table_postfix )
		 {
		 	$table = 'shop_settings_' . $table_postfix;
		 	$this->db->where('shop_id' , $shop_id )
		 			 ->delete($table);
		 }
		 return True;
	 }
	 
	 
	 /**
	  * Check if a particular Shop has all proper setings
	  * 
	  * @param		Int		$	Shop ID
	  *
	  * @return		Bool
	  */
	 public function has_settings(  $shop_id )
	 {
		$tables = array('payment' , 'order' , 'shipping' , 'theme' , 'contact');
		$return = false;
		foreach( $tables as $postfix )
		{
			$table = 'shop_settings_' . strtolower($postfix);
			$this->db->select('shop_id')
				 ->from($table)
				 ->where('shop_id' , $shop_id ); 
			$return = ( $return &&  $this->db->count_all_results()  );
		}
		return $return;
	 }
	 

	 
 };
 
 