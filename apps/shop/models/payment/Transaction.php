<?php
/**
 * Transaction
 *
 * Record transactions between customers and payment gateways
 * *None of theses transactions can/should be altered or deleted*
 *
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @package 	Models/Payment/Transaction
 * @date 		7 June 2015
 *
 *
 */

class Transaction extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Get a transaction.
	 *
	 * @param 		Int 		$	Transaction ID
	 *
	 * @return 		Array 		$ 	Empty on fail
	 */
	public function get( $transaction_id  	)
	{
		$this->db->select( '*' )
				  ->from('transaction')
				  ->where('transaction_id' , $transaction_id );
		$query = $this->db->get();
		//@hack Something went wrong and I do not know WTF it is !
		return ( $query->row_array() == Null ) ? array() : $query->row_array();
	}

	/**
	 * Get all transactions for a shop
	 *
	 * @param 		Int 		$	Shop ID
	 *
	 * @return 		Array 		$	Empty on fail
	 */
	public function get_shop_transactions( $shop_id )
	{
		$query = $this->db->get_where('transaction' ,array('shop_id' => $shop_id ) );
		return $query->result_array();
	}

	/**
	 * Get all transactions for a shopper
	 *
	 * @param 		Int 		$	Shopper ID
	 *
	 * @return 		Array 		$	Empty on fail
	 */	
	public function get_shopper_transactions( $shopper_id  )
	{
		$query = $this->db->get_where('transaction' ,array('shopper_id' => $shopper_id ) );
		return $query->result_array();
	}

	/**
	 * Get all transactions for a particular gateway
	 *
	 * @param 		Int 		$	Shopper ID
	 *
	 * @return 		Array 		$	Empty on fail
	 */	
	public function get_gateway_transactions( $gateway_name )
	{
		$query = $this->db->get_where('transaction' ,array('payment_gateway' => $gateway_name ) );
		return $query->result_array();
	}

	/**
	 * Get all transactions which have not yet being processed
	 *
	 * @return 		Array 		$	
	 */	
	public function get_empty_shop_transactions( $shop_id  )
	{
		$query = $this->db->get_where('transaction' ,array('amount' => 0.00 , 'shop_id' => $shop_id ) );
		return $query->result_array();
	}

	/**
	 * Get all transactions which have not yet being processed
	 *
	 * @return 		Array 		$	
	 */	
	public function get_empty_transactions(  )
	{
		$query = $this->db->get_where('transaction' ,array('amount' => 0.00 ) );
		return $query->result_array();
	}


	public function count_all()
	{
		return $this->db->count_all_results('transaction');
	}

	public function update( $transaction_id , $transaction )
	{
		unset( $transaction['transaction_id'] );
		unset( $transaction['challenge'] );
		unset( $transaction['shop_id']);
		$this->db->where('transaction_id' , $transaction_id )
				 ->update('transaction' , $transaction);
		return True;		 
	}

	public function create( $shop_id , $shopper_id , $type , $gateway_used , $gateway_data  )
	{
		$transaction = array();
		$transaction['shop_id'] = $shop_id;
		$transaction['shopper_id'] = $shopper_id;
		$transaction['payment_type'] = $type;
		$transaction['payment_gateway'] = $gateway_used;
		$transaction['gateway_data'] = $gateway_data;
		//challenge
		$transaction['challenge'] = md5( json_encode($transaction) .time() );
		$this->db->insert('transaction' , $transaction );
		$id = $this->db->insert_id();
		return ( $id > 0 ) ? $id : False;
	}


	public function delete_expired_transactions()
	{

	}

	/**
	 * Delete a transaction. Use with care, in fact never use
	 * 
	 * @param 		Int 	$	Transaction ID
	 *
	 * @return 		Bool
	 */
	public function delete( $transaction_id )
	{
		$this->db->where('transaction_id' , $transaction_id )->delete('transaction');
		return True;
	}


}

