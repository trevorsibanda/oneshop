<?php
/**
 * Pay4app payment gateway model
 *
 * Model to interact with Pay4App library in a standard
 * way.
 *
 * @author 		Trevor Sibanda
 * @date 		15 August 2015
 * @package 	Models/Payment/Gateway/Pay4App
 */

class Payment_gateway_pay4app extends CI_Model
{

	/** Config **/
	private $_config;

	/** Has the gateway bootstrapped **/
	private $_is_bootstrapped = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('payment_gateways');
		$this->load->library('payment/Pay4app');
	}

	public function set_config(  $config )
	{
		
		$this->_config = $config;
		
	}

	public function config_item( $key  )
	{
		return isset( $this->_config[$key] ) ? $this->_config[$key] : '';
	}

	public function bootstrap( )
	{

		$this->pay4app->api_secret( $this->config_item('api_secret')  );
		$this->pay4app->merchant_id( $this->config_item('api_key') );
		$this->pay4app->checkout_url(  $this->config_item('checkout_url') );
		$this->pay4app->transfer_pending_url(  $this->config_item('pending_url') );
		$this->pay4app->set_json_format('array');
		$this->pay4app->set_test_mode(False);
		$this->_is_bootstrapped = True;	
	}

	public function initiate_transaction( $amount , $transaction , $shopper , $additional_info = '' )
	{
		$this->bootstrap();
		$form = $this->pay4app->make_form( $transaction['transaction_id'] , $amount );
		$result = array('action'=>'form','data' => $form );
		//if( $this->config->item('payment_gateway_debug_mode'))
		//	$result['data']['form_action'] = 'http://127.0.0.1:9091/checkout.php';
		return $result;
	}

	public function get_transaction(  $gateway_id )
	{
		$this->bootstrap();
		$result = $this->pay4app->query_checkout( $gateway_id  );
		if( ! isset($result['status']))
			return array(); //failed somewhere, most proobably http request
		if( $result['status'] != '1')
			return $result ; //error occured, lets pass it on
		return $result;
		//@todo, convert to conformant data struct b4 returning
	}

	public function cancel_transaction(  $gateway_id )
	{

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
	public function validate( $type = 'notification' , $data )
	{
		$b = False;
		$this->bootstrap();
		switch( $type )
		{
			case 'notification':
			{
				
				$b = $this->is_notification_request( $data );
			}
			break;
			default:
				$b = False;
			break;	
		}
		return $b;
	}

	/**
	 * Check if data provided is a valid
	 * http notification request.
	 *
	 * @param 		Array 		$	$_GET array
	 *
	 * @return 		Bool
	 */
	public function is_notification_request( $_get_ )
	{
		return $this->pay4app->is_notify_request( $_get_ );
	}




}