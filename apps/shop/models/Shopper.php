<?php
/**
 * Shopper
 *
 * @author 		Trevor Sibanda <trevrosibb@gmail.com>
 * @date 		23 June 2015
 * @package 	Models/Shopper/Shopper
 *
 *
 */

class Shopper extends CI_Model
{

	private $_table = 'shopper';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->scope_model( $this , 'shopper/app' , 'app');
		$this->load->scope_model( $this , 'shopper/log' , 'log');
		$this->load->scope_model( $this , 'shopper/settings' , 'settings' );
	}

	public function get( $shopper_id )
	{
		return $this->get_by('shopper_id' , $shopper_id );
	}

	public function get_by_email( $email_address  )
	{
		return $this->get_by( 'email' , $email_address );
	}

	public function get_by_phone(  $phone_number )
	{
		return $this->get_by('phone_number' , $phone_number );
	}

	public function get_by(  $key , $value )
	{
		$query = $this->db->where($key , $value)
				 		  ->get($this->_table);
		return $query->row_array();
	}

	public function create( $fullname , $email , $phone_number , $city , $country , $address )
	{

	}

	public function is_password( $shopper , $plain_text )
	{

	}

	public function change_password(  $shopper , $plain_text )
	{

	}

	public function update( $shopper_id , $shopper )
	{

	}

	public function  remove(  $shopper_id  )
	{
		
	}	
}
