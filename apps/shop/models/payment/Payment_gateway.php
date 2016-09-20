<?php
/**
 * Payment Gateway.
 *
 * Handles payment gateways and handles transactions perfromed
 * by a chosen gateway.
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		23 June 2015
 * @package 	Models/Payment/Gateway
 *
 *
 *
 *
 */


class Payment_gateway extends CI_Model
{
	/** Current config **/
	private $_config;

	/** Gateway **/
	private $_gateway;

	/** gateway name in use **/
	private $_gateway_name;

	private $_payment_callback_endpoint_url = 'https://'. OS_SECURE_DOMAIN . '/api/endpoint/';



	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('payment_gateways');
	}

	/**
	 * Get gateway used for all shops using free account.
	 *
	 * 
	 */
	public function default_shop_gateway( )
	{
		$g_name = $this->config->item('free_account_payment_gateway');
		return $this->get( $g_name );
	}

	/**
	 * Default config for free account payment gateway
	 *
	 *
	 */
	public function free_account_gateway_config()
	{
		return $this->config->item('free_account_gateway_config');
	}


	/**
	 * Get all payment gateways available 
	 * in the platform
	 *
	 * @return 		Array
	 */
	public function get_all(  )
	{
		return $this->config->item('payment_gateways');
	}

	/**
	 * Get a particular gateway config
	 *
	 * @param 		String 		$	Gateway name
	 *
	 * @return 		Array 		$	Returns false on fail
	 */
	public function get( $gateway_name )
	{
		$gateways = $this->get_all();
		if( ! is_string($gateway_name))
			return False;
		if( ! isset($gateways[$gateway_name]) )	
			return False;
		return $gateways[$gateway_name];
	}

	/**
	 * Select a gateway to use
	 *
	 * @param 		String 		$	Gateway name
	 *
	 * @return 		Bool
	 */
	public function select( $gateway_name )
	{
		$gateway = $this->get( $gateway_name  );
		if( ! $gateway )
			return False;
		$this->_gateway = $gateway;
		$this->_gateway_name = $gateway_name;
		$this->_config = array();
		
		//load the gateway
		$this->_load_gateway( $gateway );
	}

	private function _load_gateway( $gateway )
	{
		//e.g gateway model is stored as payment_gateway_paynow
		//model name is payment_gateway_paynow, loaded as a scope
		//model i.e $this->$gateway['gateway'] = model . 
		//can be accessed as $this->g_paynow-> 
		$this->load->scope_model($this, 'payment/gateway/Payment_gateway_' . $gateway['gateway'] ,  'gateway'  );
		//should never fail		
	}

	/**
	 * Set a config key:value pair item
	 *
	 * @param 		String 		$	Key
	 * @param 		String 		$	Value
	 *
	 * @return 		Null
	 */
	public function set( $key , $value )
	{
		$this->_config[ $key ] = $value;
	}

	/**
	 * Initiate a transaction.
	 *
	 * Depending on the payment gateway used. This may vary from requesting a transaction
	 * id from a remote server and saving it, or creating a custom transaction id.
	 *
	 * In either case however, the transaction should be updated and stored in the database before the
	 * action is processed.
	 *
	 * Valid actions include: redirect -> which redirects the user to a specified url
	 *						  form     -> a form map was generated and it should be sent as html and submitted using javascript.
	 * If the gateway initiate transaction step fails, function returns false
	 *
	 * @param 		Float 		$		Amount to pay.
	 * @param 		Array 		$		Transaction
	 * @param 		Array 		$		Shopper info fulname,email,phone...etc
	 * 
	 * @return 		Array 		$ 		Action
	 */
	public function initiate_transaction( $amount , $transaction , $shopper  )
	{
		
		$this->gateway->set_config($this->_config);

		$result = $this->gateway->initiate_transaction( $amount , $transaction , $shopper );
		//expects array('action' , 'data') on success

		return $result;
	}

	/**
	 * Get a transaction given the gateway id.
	 *
	 * @param 		String 		$	Gateway ID
	 *
	 * @return 		Array 		$ 	empty on fail
	 */
	public function get_transaction( $gateway_id  )
	{
		
		$this->gateway->set_config(  $this->_config );

		$result = $gateway->get_transaction( $gateway_id );

		return $result;
	}

	/**
	 * Request that the transaction be refunded
	 * This functionality might not be implemented in all gateways.
	 *
	 * @param 		String 		$	Gateway ID
	 * @param 		Float 		$	Amount to refund by, if <= 0.00 refunds total
	 *
	 * @return 		Array
	 */
	public function refund_transaction(  $gateway_id , $amount = 0.00 )
	{
		$this->gateway->set_config(  $this->_config );

		$result = $gateway->refund_transaction( $gateway_id , $amount );

		return $result;
	}

	/**
	 * Request that a transaction be cancelled.
	 *
	 * This will only work if the gateway supports it.
	 * You cannot cancel a transaction which has been paid for. 
	 *
	 * @param 		String 		$	Gateway ID
	 *
	 * @return 		Bool
	 */
	public function cancel_transaction( $gateway_id )
	{
		$this->gateway->set_config(  $this->_config );

		$result = $this->gateway->cancel_transaction( $gateway_id );

		return $result;
	}

	/**
	 * Run validation function
	 *
	 * Valid types : 'init' => 'initiate transaction ','poll'=>'poll response','status_update' =>self explanatory
	 *
	 * @param 		String 	$	Validation message type
	 *
	 * @return 		Bool 
	 */
	public function validate( $type = 'init' , $data )
	{
		$this->gateway->set_config(  $this->_config );

		$result = $this->gateway->validate( $type , $data );

		return $result;	
	}

	public function make_callback_url(  $transaction , $type = 'order' )
	{
		//i.e https://{{OS_SECURE_DOMAIN}}/api/endpoint/paynow?transaction_id=20&type=order&challenge=12345678901234567890123456789012
		return $this->_payment_callback_endpoint_url . $this->_gateway_name . '?transaction_id=' . $transaction['transaction_id'] . '&type=' . urlencode( $type ) . '&challenge=' . urlencode($transaction['challenge']) ;
	}

}
