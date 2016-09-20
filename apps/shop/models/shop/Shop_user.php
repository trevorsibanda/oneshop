<?php
/**
 * Shop User CRUD operations.
 *
 * Each shop can have users which would represent an employer 
 * and employees in real life. Each user has different permissions.
 * For example, the shop admin/owner has all rights but a till operator 
 * needs far less privileges.
 *
 * Make sure you load the shop_user helper for permission checks and operations
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 * @module		Model/Shop/Shop_user
 * 
 * @date		6 May 2015
 */
 
 class Shop_user extends CI_Model
 {
	/** Ctor */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get( $user_id )
	{
		$query = $this->db->get_where('shop_user' , array('user_id' => $user_id ) );
		return $query->row_array();
	}
	
	/**
	 * Get a shop user given its email
	 *
	 * @param		String		$	Email
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_by_email( $email )
	{
		$query = $this->db->get_where('shop_user' , array('email' => $email ) );
		return $query->row_array();		
	}

	/**
	 * Get all shop user accounts given the email
	 *
	 * @param		String	$	Shop Email 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_all_accounts_by_email( $email )
	{
		$query = $this->db->get_where('shop_user' , array('email' => $email ) );
		return $query->result_array();		
	}
	
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User phone number 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_by_phone( $phone )
	{
		$query = $this->db->get_where('shop_user' , array('phone_number' => $phone ) );
		return $query->row_array();		
	}
	
	/**
	 * Get a shop user given its national ID
	 *
	 * @param		Int		$	Shop User  national ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_by_id( $national_id )
	{
		$query = $this->db->get_where('shop_user' , array('national_id' => $national_id ) );
		return $query->row_array();		
	}
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function count_shop_users( $shop_id  )
	{
		$this->db->where('shop_id' , $shop_id)->from('shop_user');
		return $this->db->count_all_results();
	}
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function get_shop_users( $shop_id )
	{
		$query = $this->db->get_where('shop_user' , array('shop_id' => $shop_id ) );
		return $query->result_array();		
	}
	
	/**
	 * Add a user to the shop.
	 *
	 * @param		Int		$	Shop ID
	 * @param 		String 	$	Fullname
	 * @param 		String 	$	Valid Email
	 * @param 		String 	$	Phone number
	 * @param 		String 	$	National ID number 
	 *
	 * @return		Int		$	User ID, false on fail
	 */
	public function add( $shop_id , $fullname , $email , $phone_number , $national_id = '*'  )
	{
		$user = array();
		$user['shop_id'] = $shop_id;
		$user['fullname'] = $fullname;
		$user['email'] = $email;
		$user['phone_number'] = $phone_number;
		$user['password'] = $this->_passwd( time() );
		$user['national_id'] = $national_id;
		$user['date_joined'] = date('Y-m-d');
		$user['is_suspended'] = False;
		$user['is_verified'] = False;
		$user['login_token'] = $this->make_login_token($user);
		//default permissions are all off
		$user['gender'] = "Female"; //men will definitely change this :)
		$this->db->insert('shop_user' , $user);
		$id = $this->db->insert_id();
		return ( $id <= 0 ) ? False : $id;
	}

	/**
	 * Generate a new login token for a user
	 *
	 * @param 		Array 		$	User
	 *
	 * @return 		String
	 */
	public function make_login_token( $user = null )
	{
		if( is_null($user))
			$user = rand();
		$string = json_encode( $user );
		return md5( time() . $string );

	}

	/**
	 * Generate a password of a given length
	 *
	 * @param 		Int 	$	Password length
	 *
	 * @return 		String
	 */
	public function generate_password( $length = 6 )
	{
		$charset = 'abcdefghijklmnoprstuvwxyz01234567890#!@';
		$len = strlen($charset);
		$password = '';
		for( $i = 0; $i < $length ;++$i)
		{
			$password = $password . $charset[  rand()%$len ];
		}
		return $password;
	}
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function delete( $user_id )
	{
		$this->db->where('user_id' , $user_id)
				 ->delete('shop_user');
		return True;		 	
	}
	
	/**
	 * Get a shop user given its ID
	 *
	 * @param		Int		$	Shop User ID 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function update( $user_id , $shop_user )
	{
		unset( $shop_user['user_id'] );
		$this->db->where('user_id' , $user_id)
				 ->update('shop_user' , $shop_user );
		return True;		 
	}
	
	/**
	 * Change a shop user's password
	 *
	 * @param		Int		$	Shop User ID
	 * @param 		String 	$	New password 
	 *
	 * @return		Array	$	Empty array on fail
	 */
	public function change_password( $user_id , $new_password )
	{
		$passwd = $this->_passwd(  $new_password );
		$this->db->where('user_id' , $user_id)
				 ->update('shop_user' , array('password'=> $passwd , 'login_token' => $this->make_login_token() ));
		return True;		 
	}
	
	/**
	 * Check if a provided password is correct
	 *
	 * @param		Array		$	Shop User 
	 * @param 		String 		$	Plain text password
	 *
	 * @return		Bool		$	Empty array on fail
	 */
	public function is_password( $shop_user , $password )
	{
		$passwd = $this->_passwd(  $password );
		
		if( $passwd == $shop_user['password'] )
			return True;
		return False;		
	}
	
	/**
	 * Generate a password
	 *
	 * @param		String		$	Plain text 
	 *
	 * @return		String 		$	Empty array on fail
	 */
	private function _passwd( $plain_text )
	{
		return hash('sha512' , md5($plain_text) );
	}
	
 };
 
 