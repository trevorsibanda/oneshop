<?php
/**
 * Log all activities that occur in the shop.
 * 
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		23 June 2015
 * @package 	Models/Shop/Shop_log
 *
 */

class Shop_log extends CI_model
{

	private $_shop_id;
	private $_user_id;

	private $_table_name = 'shop_log';


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function shop_id( $shop_id = Null )
	{
		if( ! is_null($shop_id))
			$this->_shop_id = $shop_id;
		return $this->_shop_id;
	}

	public function user_id( $user_id = Null)
	{
		if( ! is_null($user_id))
			$this->_user_id = $user_id;
		return $this->_user_id;
	}


	public function get_auth( $count = 50 )
	{
		return $this->get_log( 'auth' , $count );
	}

	public function get_wallet(  $count = 100 )
	{
		return $this->get_log( 'wallet' , $count );
	}

	public function get_product(  $count = 50 )
	{
		return $this->get_log( 'product' , $count );
	}

	public function get_settings(  $count = 20 )
	{
		return $this->get_log( 'settings' , $count );
	}

	public function get_action(  $count = 100 )
	{
		return $this->get_log( 'action' , $count );
	}

	public function get_log( $type , $count = 50 )
	{

		if( $this->_user_id != Null )
			$this->db->where('user_id' , $this->_user_id );
		$query = $this->db->where('action' , $type )
						  ->limit( $count )
						  ->order_by('log_id' , "DESC" )
						  ->where('shop_id' , $this->_shop_id )
						  ->get( $this->_table_name );
		return $query->result_array();				  
	}

	public function get_all( $count = 100 , $ignore_user = False )
	{
		if( $this->_user_id != Null && ! $ignore_user )
			$this->db->where('user_id' , $this->_user_id );
		$query = $this->db->where('shop_id' , $this->_shop_id )
						  ->limit( $count )
						  ->order_by('log_id' , "DESC" )	
						  ->get( $this->_table_name );
		return $query->result_array();	
	}


	public function log( $type , $message  )
	{
		$log = array();
		$log['shop_id'] = $this->_shop_id;
		$log['action'] = $type;
		$log['ip_address'] = $this->input->ip_address();
		$log['user_agent'] = $this->input->user_agent();
		$log['log'] = $message;
		$log['user_id'] = ( is_null($this->_user_id)  ? -1 : $this->_user_id );
		$this->db->insert( $this->_table_name , $log );
		return $this->db->insert_id();
	}

	public function auth( $message )
	{
		return $this->log( 'auth' , $message );
	}

	public function wallet( $message )
	{
		return $this->log( 'wallet' , $message );
	}

	public function product( $message )
	{
		return $this->log( 'product' , $message );
	}

	public function settings(  $message )
	{
		return $this->log( 'settings' , $message );
	}

	public function action( $message )
	{
		return $this->log( 'action' , $message );
	}


};

