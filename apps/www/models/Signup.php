<?php

class Signup extends CI_Model
{

	
	public function __construct( )
	{
		parent::__construct();
		$this->load->database('oneshop');
		
		$this->load->model( 'shop');
		
		
	}
	
	public function is_subdomain_available($subdomain )
	{
		$query = $this->db->where('subdomain' , $subdomain )
			 	  ->from('shop')
			 	  ->get();
		$row = $query->row_array();
		if( empty($row) )
		{
			//check redirect pool
			$query = $this->db->get_where('shop_used_subdomains' , array('subdomain' , $subdomain ) );
			$row = $query->row_array();
		}
		return empty( $row );	 
	}
	
	public function build_shop( $shop , $user  , $payment  )
	{
		
	}

}
