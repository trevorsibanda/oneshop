<?php
/**
 * Product Notification.
 *
 * This feature allows a customer interested in buying a particular
 * product to be added to a "waiting" list to be sent out as soon as
 * the product is in stock again.
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com> 
 * @package		Models/Product/Product_Notification
 * @date		June 8 2015
 *
 */

 class Product_Notification extends CI_Model
 {
 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->database();
 	}

 	/**
 	 * Get all people subscribed to a particular prodcut's stock.
 	 *
 	 * @param 		Int 	$	Product ID
 	 *
 	 * @return  	Array 	
 	 */
 	public function get_all_product( $product_id )
 	{
 		$query = $this->db->get_where('product_notification' , array('product_id' =>  $product_id ) );
 		return $query->result_array();
 	}

 	/**
 	 * Check if already subscribed to a product launch
 	 *
 	 * @param 		Int 	$	Product ID
 	 * @param 		String 	$	Email
 	 * @param 		String 	$	Phone number
 	 *
 	 * @todo		Fix this function
 	 *
 	 * @return 		Bool
 	 */
 	public function is_subscribed( $product_id , $email , $phone_number )
 	{
 		$query = $this->db->where('product_id' , $product_id )
 				 ->and_group_start()
 				 ->where('email' , $email )
 				 ->or_where('phone_number' , $phone_number)
 				 ->group_end()
 				 ->from('product_notification')
 				 ->get();
 		return $query->num_rows();		 
 	}

 	/**
 	 * Get all people subscribed to a shop's products
 	 *
 	 * @param 		Int 	$	Shop  ID
 	 *
 	 * @return 		Array
 	 */
 	public function get_all_shop( $shop_id )
 	{
 		$query = $this->db->get_where('product_notification' , array('shop_id' =>  $shop_id ) );
 		return $query->result_array();
 	}

 	/**
 	 * Update the state of all subscribed notification for a shop to sent
 	 *
 	 * @param 		Int 	$	Shop ID
 	 *
 	 * @return 		Bool
 	 */
 	public function clear_all_shop( $shop_id )
 	{
 		$this->db->where('shop_id' , $shop_id)
 				 ->update('product_notification' , array('is_sent' => True) );
 		return True;		 
 	}

 	/**
 	 * Update the state of all subscribed notification for a product to sent
 	 *
 	 * @param 		Int 	$	Shop ID
 	 *
 	 * @return 		Bool
 	 */
 	public function clear_all_product( $product_id )
 	{
 		$this->db->where('product_id' , $product_id)
 				 ->update('product_notification' , array('is_sent' => True) );
 		return True;
 	}

 	/**
 	 * Delete all notifications for a particular shop
 	 *
 	 * @param 		Int 	$	Shop ID
 	 *
 	 * @return 		Bool
 	 */
 	public function remove_all_shop( $shop_id )
 	{
 		$this->db->where('shop_id' , $shop_id)->delete('product_notification');
 		return True;
 	}

 	/**
 	 * Delete all notifications for a particular shop
 	 *
 	 * @param 		Int 	$	Shop ID
 	 *
 	 * @return 		Bool
 	 */
 	public function remove_all_product( $product_id )
 	{
 		$this->db->where('product_id' , $product_id)->delete('product_notification');
 		return True;
 	}

 	/**
 	 * Add a notification for a product. /subscribe 
 	 *
 	 * @param 		Int 	$	Product ID
 	 * @param 		Int 	$	Shop ID
 	 * @param 		String 	$	Fullname
 	 * @param 		String 	$	Phone number
 	 * @param 		String 	$	Email address
 	 *
 	 * @return 		Bool
 	 */
 	public function add( $product_id , $shop_id ,  $fullname , $phone_number , $email  )
 	{
 		$ntfy = array();
 		$ntfy['product_id'] = $product_id;
 		$ntfy['shop_id'] = $shop_id;
 		$ntfy['fullname'] = $fullname;
 		$ntfy['phone_number'] = $phone_number;
 		$ntfy['email'] = $email;
 		$ntfy['is_sent'] = False;
 		$query = $this->db->insert('product_notification' , $ntfy );
 		$id = $this->db->insert_id();
 		return ( $id > 0 )? $id : False;
 	}


 }

