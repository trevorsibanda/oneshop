<?php
/**
 * PayNow payment gateway model
 *
 * Model to interact with PayNow library in a standard
 * way.
 *
 * @author 		Trevor Sibanda
 * @date 		15 August 2015
 * @package 	Models/Payment/Gateway/Pay4App
 */

class Payment_gateway_paynow extends CI_Model
{

	/** Config **/
	private $_config;

	/** Has the gateway bootstrapped **/
	private $_is_bootstrapped = false;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('payment_gateways');
		
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
		$config=  array('id' => $this->config_item('api_key') , 'key' => $this->config_item('api_secret')   );
		$this->load->library('payment/Paynow' , $config );
		//server response
		$this->paynow->set_result_url(   $this->config_item('callback_url')  );
		//user redirect on success
		$this->paynow->set_return_url( $this->config_item('checkout_url') );
		$this->_is_bootstrapped = True;	
	}

	public function initiate_transaction( $amount , $transaction , $shopper , $additional_info = '')
	{
		
		$this->bootstrap();
		//local transaction
		$local_trans = $transaction;
		
		$transaction = $this->paynow->make_transaction( $local_trans['transaction_id'] , $amount , $additional_info  );

		$transaction['authemail'] = $shopper['email'];
		//init
		$response = $this->paynow->init_transaction( $transaction );
		
		$result = array('action'=>'url','data' => $response );
		//paynow fix

		if( isset($response['status']) )
		if( $response['status'] == PayNow::ps_ok )
		{
			$result['data']['url'] = $result['data']['browserurl'];
		}
		
		return $result;
	}

	/**
	 * Querying a checkout is done the PayNow way.
	 *
	 * Instead of querying for using a gateay id the poll url is used instead
	 *
	 * @param 		Url 		$	Poll Url
	 */
	public function get_transaction(  $gateway_id )
	{
		$this->bootstrap();
		$result = $this->paynow->poll_transaction( $gateway_id );

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
		$b = False;
		$this->bootstrap();
		switch( $type )
		{
			case 'init':
			{
				$b = $this->paynow->is_valid_init_response( $data );
			}
			break;
			case 'poll':
			{
				$b = $this->paynow->is_valid_poll_response( $data );
			}
			break;
			case 'status_update':
			{
				$b = $this->paynow->is_valid_status_update( $data );
			}
			break;
			default:
				$b = False;
			break;	
		}
		return $b;
	}

	/**
	 * Initiate a cancel transaction request.
	 *
	 * @todo 	if  Paynow implements 
	 */
	public function cancel_transaction(  $gateway_id )
	{

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