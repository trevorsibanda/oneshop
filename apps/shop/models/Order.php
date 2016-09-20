<?php
/**
 * Order.
 *
 * CRUD operations for orders
 *
 * @author 		Trevor Sibanda<trevorsibb@gmil.com>
 * @package 	Models/Order/Order
 * @date 		5 June 2015
 *
 *
 *
 *
 */

class Order extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function get_order( $order_id )
	{
		$query = $this->db->select('*')
				    	  ->from('orders')
				 		  ->where('order_id' , $order_id)
				 		  ->get();
		return $query->row_array();		 		  
	}

	public function add_order( $shop_id , $shopper_id , $cart_items )
	{
		$order = array();
		$order['shop_id'] = $shop_id;
		$order['shopper_id'] = $shopper_id;
		
	}

	public function update_order(  $order_id , $order )
	{

	}

	public function delete_order( $order_id )
	{

	}

	public function add_order_item( $shop_id , $product_id , $quantity , $price_per_item , )
}