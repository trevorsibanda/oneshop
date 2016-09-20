<?php
/**
 * Shop Follower.
 *
 * A shop follower is someone
 * who has interacted( shopped or added ) the shop to their list of "My Stores"
 *
 * These followers can then be targeted through campaigns
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 * @module		Model/Shop/Shop_Follower
 * 
 * @date		6 May 2015
 */
 
 class Shop_follower extends CI_Model
 {
	/** Ctor */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Count the number of shoppers following this shop
	 *
	 * @param		Int		$	Shopper ID	
	 *
	 * @return		Int		$	0 on fail
	 */
	public function count_followers( $shop_id )
	{
		$this->db->select('shopper_id')
				 ->from  ('shop_follower')
				 ->where ( 'shop_id' , $shop_id );
		return $this->db->count_all_results( ); 
	}
	
	/**
	 * Check if a shopper follows a particular shop
	 *
	 * @param		Int		$	Shopper ID
	 * @param		Int		$	Shop ID
	 *
	 * @return		Bool
	 */
	public function is_follower(  $shopper_id , $shop_id )
	{
		$this->db->select('shopper_id')
				 ->from  ('shop_follower')
				 ->where ( 'shop_id' , $shop_id )
				 ->where ( 'shopper_id' , $shopper_id);
		return  ( $this->db->count_all_results( ) ) ? True : False;
	}
	
	/**
	 * Add a shopper to a shop's following list
	 *
	 * @param		Int		$	Shopper ID
	 * @param		Int		$	Shop ID
	 * @param		Bool	$	Allow promotions, Default True
	 *
	 * @return		Bool
	 */
	public function follow(  $shopper_id , $shop_id , $allow_promotions = True )
	{
		if( $this->is_follower($shopper_id , $shop_id) )
			return False;
		$data = array( 'shop_id' => $shop_id , 'shopper_id' => $shopper_id , 'allow_promotions' => $allow_promotions  );
		
		$this->db->insert( 'shop_follower' , $data );
		return True;
	}
	
	/**
	 * List all shops the shopper is following
	 *
	 * @param		Int		$	Shopper ID
	 *
	 * @return		Array	$	Can be empty array
	 */
	public function list_following( $shopper_id )
	{
		$this->db->select( 'shop_id , allow_promotions, shopper_id' )
				 ->from  ( 'shop_follower' )
				 ->where ( 'shopper_id' , $shopper_id );
		 
		$query = $this->db->get();
		
		return $query->results_array();
	}
	
	/**
	 * List all shoppers following a shop
	 *
	 * @param		Int		$	Shop ID
	 *
	 * @return		Array
	 */
	public function list_followers( $shop_id )
	{
		$this->db->select( 'shop_id , allow_promotions, shopper_id' )
				 ->from  ( 'shop_follower' )
				 ->where ( 'shop_id' , $shop_id );
		 
		$query = $this->db->get();
		
		return $query->results_array();
	}
	
	/**
	 * Remove a shopper from a list of shops that follow the shop
	 *
	 * @param		Int		$	Shopper ID
	 * @param		Int		$	Shop ID
	 *
	 * @return		Bool
	 */
	public function unfollow( $shopper_id , $shop_id )
	{
		$this->db->where('shopper_id' , $shopper_id )
				 ->where('shop_id' , $shop_id );
		 
		$this->db->delete( 'shop_follower' );
		return True;
	}
	
	/**
	 * Update a shopper's following settings
	 *
	 * @param		Int		$	Shopper ID
	 * @param		Int		$	Shop ID
	 * @param		Bool	$	Allow promotions
	 *
	 * @return		Bool
	 */
	public function update( $shopper_id , $shop_id , $allow_promotions = True )
	{
		$data = array('shopper_id' => $shopper_id , 'shop_id' => $shop_id ,'allow_promotions'=> $allow_promotions);
		$this->db->where ('shopper_id' , $shopper_id )
				 ->where ('shop_id'   , $shop_id )
				 ->update( 'shop'  , $data );
		
		return True;
	}
	
	
 };
 