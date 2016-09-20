<?php
/**
 * Shop CRUD operations
 *
 * @author	Trevor Sibanda <trevorsibb@gmail.com>
 * @module	Models/Shop/Shop_model
 *
 * @date	6 May 2015
 */
 
 
 class Shop extends CI_Model
 {
	 
	//@TODO Fill empty array to make valid shop testing more robust
	 private $empty_shop = array( 
	 'shop_id' => array('type' => 'Int' , 'required' => True , 'min_value' => 1 ) ,
	 'subdomain' => array('type' => 'String' , 'required' => True , 'max_len' =>100 , 'min_len' => 3 ) ,
	 'alias' => array() ,
	 'name' => array() , 
	 'keywords' => array() ,
	 'address' => array('type' => 'String' , 'required' => True , 'max_len' => 4096 ) ,
	 'country' => array('type' => 'String' , 'required' => True , 'max_len' => 2 ) ,
	 'telephone' => array() ,
	 'city' => array() ,
	 'trading_name' => array() ,
	 'description' => array() ,
	 'short_description' => array() ,
	 'type' => array() ,
	 'tagline' => array() ,
	 'open_time' => array() ,
	 'close_time' => array() ,
	 'operate_days' => array() ,
	 'is_active' => array() ,
	 'is_suspended' => array() ,
	 'admin_id' => array()
	 );
	 /**
	  * Ctor
	  */
	 public function __construct()
	 {
		parent::__construct();
		$this->load->database();
		
		//load models
		$this->load->scope_model($this ,'shop/Shop_settings' , 'settings' );
		
		$this->load->scope_model($this ,'shop/Shop_image' , 'image' );
		$this->load->scope_model($this ,'shop/Shop_log' , 'logger' );
		$this->load->scope_model($this ,'shop/Shop_user' , 'user' );
		$this->load->scope_model($this ,'shop/Shop_account' , 'account' );
	 }

	 public function load_follower()
	 {
	 	$this->load->scope_model($this ,'shop/Shop_follower' , 'follower' );
	 }

	 public function load_wallet()
	 {
	 	$this->load->scope_model($this , 'shop/shop_wallet' , 'wallet');	
	 }

	
	 /**
	  * Get a shop using the shop ID
	  *
	  * @param		Int		$	Shop ID
	  * @param		Bool	$	Must the shop be active
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_by_id( $shop_id , $active = True )
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'shop_id' , $shop_id );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() <= 0 )
			return array();
		$row = $query->row_array();
		$row['url'] = $this->shop_url( $row );
		return $row;
	 }
	 
	 /**
	  * Get a shop provided the shops subdomain name
	  *
	  * @param		String	$	Shop Subdomain. Must contain alphanumeric characters and dot
	  * @param		Bool	$	Must the shop be active/not suspended
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_by_alias( $alias , $active = True)
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'alias' , $alias );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() <= 0 )
			return array();
		$row = $query->row_array(); 
		$row['url'] = $this->shop_url( $row );
		return $row; 
	 }

	 /**
	  * Get a shop provided the shops subdomain name
	  *
	  * @param		String	$	Subdomain. Must contain alphanumeric characters and dot
	  * @param		Bool	$	Must the shop be active/not suspended
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_by_subdomain( $subdomain , $active = True)
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'subdomain' , $subdomain );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() <= 0 )
			return array();
		$row = $query->row_array(); 
		$row['url'] = $this->shop_url( $row );
		return $row;
	 }
	 
	 /**
	  * Get a shop using the shop's trading name
	  *
	  * @param		String	$	Shop trading name
	  * @param		Bool	$	Must the shop be active/not suspended
	  *
	  *	@return		Array	$	Empty array on fail
	  */
	 public function get_by_name( $shop_name , $active = True )
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'name ' , $shop_name )
				 ->or_like( 'trading_name' , $shop_name )
				 ->limit ( 1 );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )
			return array();
		$row = $query->row_array(); 
		$row['url'] = $this->shop_url( $row );
		return $row;
	 }
	 
	 /**
	  * Return a shop given its address
	  *
	  * @param		String	$	Country code i.e ZW
	  * @param		String	$	Shop address
	  * @param		Bool	$	Only show active/not suspended shop
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_by_address( $shop_country , $shop_address  , $active = True)
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'country' , $shop_country )
				 ->like ( 'address' , $shop_address )
				 ->limit ( 1 );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )
			return array();
		$row = $query->row_array(); 
		$row['url'] = $this->shop_url( $row );
		return $row; 
	 }
	 
	 /**
	  * Get a shop from its shop admin ID 
	  *
	  * @param		Int		$	Shop Admin ID
	  *
	  * @note		If multiple shops are registered, only one shop is returned, ordered by shop ID
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_by_admin(  $shop_admin_id  )
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop' )
				 ->where ( 'admin_id' , $shop_admin_id )
				 ->order_by('shop_id' , 'desc' );
		if( $active )
			$this->db->where( 'is_active' , True );
		$query = $this->db->get();
		
		if( $query->num_rows() == 0 )
			return array();
		$row = $query->row_array(); 
		$row['url'] = $this->shop_url( $row );
		return $row;
	 }

	 /**
	  * Get the url a shop should use.
	  *
	  * @param 		Array 	$	Shop
	  *
	  * @return 	String
	  *
	  * @todo 		Use http or https for local subdomain ?
	  */
	 public function shop_url( $shop )
	 {
	 	if( isset($shop['alias']) and isset($shop['subdomain']) and isset($shop['use_alias']) )
	 	{
	 		$url = 'http://';
	 		if( $shop['use_alias'] )
	 			$url = 'http://' . $shop['alias'] . '/';
	 		else
	 			$url = 'http://' . $shop['subdomain'] . '.' . OS_DOMAIN . '/';
	 		return $url;
	 	}
	 	return '';
	 }
	 
	 /**
	  * Update a shop's details
	  *
	  * @param		Int		$	Shop ID. To prevent Stray Queries
	  * @param		Array	$	Shop
	  *
	  * @return		Bool 
	  */
	 public function update( $shop_id , $shop )
	 {
		//unset dangerous keys
		if( isset($shop['shop_id'] ) )
			unset( $shop['shop_id'] );

		unset($shop['logo']);
		unset($shop['url']);
		unset( $shop['date_joined']);
		
		$this->db->where ('shop_id' , $shop_id )
				 ->update( 'shop'  , $shop );
		
		return True;
	 }
	 
	 /**
	  * Suggest a shop url given a name, address and city
	  *
	  * @param 		String	$	Shop trading name
	  * @param		String	$	Shop City
	  *
	  *	@return		Array	$	String suggestions of up to 10 possible names.
	  */
	 public function suggest_subdomain( $shop_name , $shop_city = null )
	 {
		 return str_replace(array('"' ,"'",' ') , '' , strtolower($shop_name . '_' . rand()%10 )  );
	 }
	 
	 /**
	  * Check if a shop object is valid
	  *
	  * @param		Array	$	Shop
	  * @todo		Add more checking
	  *
	  * @return		Bool
	  */
	 public function is_valid( $shop )
	 {
		foreach( $this->empty_shop as $key => $value )
			if( ! isset( $shop[ $key ] ) )
				return False;
		return True; 
	 }
	 
	 
	 /**
	  * Check if a shop with a particular url exists
	  *
	  * @param		String	$	Shop URL
	  *
	  *	@return		Bool	$	True if exists
	  */
	 public function subdomain_exists( $shop_url )
	 {
		$this->db->select('shop_id')
				 ->from('shop')
				 ->where('subdomain' , $shop_url ); 
		return (  $this->db->count_all_results()  ) ? True : False;
	 }
	 
	 /**
	  * Get all shops owned by a particular admin ID
	  *
	  * @param		Int		$	Shop admin ID
	  *
	  * @return		Array	$	Array of shops, empty array on fail
	  */
	 public function get_admin_shops( $shop_admin_id )
	 {
		$this->db->select(  '*' )
				 ->from  ( 'shop')
				 ->where ( 'admin_id' , $shop_admin_id );
		$query = $this->db->get();
		return $query->result_array();
	 }
	 
	 public function add( $shop_admin_id , $shop_name , $shop_city , $shop_country , $shop_address , $shop_type  , $shop_description )
	 {
		//create empty array
		$keys = array();
		foreach( $this->empty_shop as $key => $val )
			$keys[ $key ] = '';

		unset( $keys['shop_id'] );
		
		//set shop url to something unique
		$keys['admin_id'] = $shop_admin_id;
		$keys['name'] = $shop_name;
		$keys['city'] = $shop_city;
		$keys['country'] = $shop_country;
		$keys['address'] = $shop_address;
		$keys['type'] = $shop_type;
		$keys['description'] = $shop_description;
		$keys['subdomain'] =  $keys['alias'] =  time();
		$keys['date_joined'] = date('Y-m-d');

		
		$this->db->insert('shop' , $keys );
		return $this->db->insert_id();
	 }


	 public function is_valid_subdomain( $subdomain )
	 {
	 	if( strlen($subdomain) > 50  or strlen( $subdomain) < 3 )
	 		return False;
	 	$matches = array();
	 	if( $subdomain[0] == '_' or $subdomain[0] == '-' or $subdomain[ strlen($subdomain)-1] == '-' or $subdomain[ strlen($subdomain)-1] == '_' )
	 		return False;
	 	$pattern = '/^[A-Z0-9_-]+$/i';
	 	$r = preg_match($pattern, $subdomain , $matches );
	 	if( empty($matches))
	 		return False;

	 	//check in list ofreserved or dirty words
 		$reserved = array('shop','test','admin','internal','dev','developer','login','logout','oneshop','hosted','263','263shop','blog');
 		$dirty = explode("\n" , file_get_contents(OS_SHARED_PATH . 'libraries/security/dirtywords.txt'));

 		if(  in_array($subdomain , $reserved) or in_array($subdomain, $dirty) )
 		{
 			return False;
 		}
	 	return True;
	 }
	 
	 public function remove( $shop_id )
	 {
		$this->db->where('shop_id' , $shop_id)
				 ->delete('shop' );
		return True;		  
	 }
	 
	 private function full_query_string(  )
	 {
		$parts = array();
		foreach( $this->empty_shop as $key => $val )
			array_push( $parts , $key ); 
		return implode( " , " , $parts );
	 }

 };
	 
	 