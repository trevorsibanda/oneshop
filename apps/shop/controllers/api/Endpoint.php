<?php
/**
 * OneSHOP Callback Endpoints
 *
 * Endpoin are only accessible if accessed as secure.{OS_BASE_DOMAIN}.co.zw
 * 
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 */

class Endpoint extends CI_Controller
{
	public function __construct()
	{
		
		if( ! defined('OS_SECURE') )
		{
			//we were not called securely. exit
			die('Not allowed');
		}	

		parent::__construct();
		
		//only load what we need
		$this->load->model('shop');
		$this->load->model('product');
		$this->load->model('system');
		$this->load->model('payment');

		$this->payment->load_gateway();
		

		//@todo log all request coming here
		

		/*
		Test account subscription
		
		$salt = rand() . time();
		$chal = md5( $salt . 1 . 'premium' . 1 . 3 . $salt );
		//test 
		$_POST = json_decode('{"reference":"32","paynowreference":"49126","amount":"1.00","status":"Paid","pollurl":"https:\/\/www.paynow.co.zw\/Interface\/CheckPayment\/?guid=ecb27fa8-b561-4a13-b46c-8cb95223d120","hash":"42841A2A2C986F3913BEF336F29EC5FDA04AB24902E7F22D9607FEC8EA83FD7570448B09EFB01DC9D94475CC2268ADC9B0EA49EC8F9FBE663AE174D0976DC3F3"}' , true);
		$_GET = array('shop_id'=>1,'is_upgrade'=>1,'period_months'=>3,'challenge'=>$chal,'salt'=>$salt,'account_type'=>'premium');

		/**/
	}
	
	public function index()
	{
		exit;
	}


	/**
	 * Pay4App endpoint communication.
	 *
	 * Handles all updates received from the Pay4App server.
	 *
	 * @todo Get updated details from Pay4App
	 */
	public function pay4app(  )
	{
		$gateway = 'pay4app';
		$this->payment->gateway->select($gateway);
		
		$result = array();

		$merchant = $this->input->get_post('merchant');
		$transaction_id = $this->input->get_post('order');
		$amount = $this->input->get_post('amount');
		$digest = $this->input->get_post('digest');
		$gateway_id = $this->input->get_post('checkout');
		$email = $this->input->get_post('email');
		$phone = $this->input->get_post('phone');


		//verify signature
		$_get_ =  $this->input->get();

		//oneshop signature, used to validate that customer is not tampering with request
		$transaction_challenge = $this->input->get('challenge');

		//get local transaction

		$transaction = $this->payment->transaction->get($transaction_id);

		if( empty($transaction) )
		{
			//@todo log in oneshop error log
			$result['status'] = 0;
			$result['msg'] = 'Transaction does not exist.';
			echo json_encode($result);
			return;
		} 

		$shop = $this->shop->get_by_id( $transaction['shop_id'] , false );
		$shop['logo'] = $this->shop->image->get( $shop['shop_id'] );
		$shop['contact'] = $this->shop->settings->get_contact_settings( $shop['shop_id'] );


		//pay4app does not have this feature
		//it has static callback url
		/**
		if( $transaction['challenge'] != $transaction_challenge )
		{
			$transaction['log'] .= "Failed transaction challenge. Request provided challenge: " . $transaction_challenge . " at " . date('r') . " request received from ip address ". $this->input->ip_address();
			$this->payment->transaction->update($transaction['transaction_id'] , $transaction );
			$result = array('status' => 0);
			die( json_encode($result) );	
		}
		**/

		//payment settings
		$p_settings = $this->shop->settings->get_payment_settings( $shop['shop_id'] );
		
		//order settings
		$o_settings = $this->shop->settings->get_order_settings( $shop['shop_id'] );

		$config = array();

		//is user supported payment gateway	
		switch( $gateway )
		{
			case $p_settings['secondary_gateway']:
			{
				$api_key = $p_settings['secondary_api_key'];
				$api_secret = $p_settings['secondary_api_secret'];
				$api_data = $p_settings['secondary_data'];
				
			}
			break;
			case $p_settings['primary_gateway']:
			{
				$api_key = $p_settings['primary_api_key'];
				$api_secret = $p_settings['primary_api_secret'];
				$api_data = $p_settings['primary_data'];
				
			}
			break;
			default:
			{
				//@todo, log error, user changed settings whilst order was been processed... bad 
				$result = array('status' => 0, 'msg'=>'Error, most likely user changed gateway settings. ');
				echo json_encode($result);
				return;
			}
		}

		$config = array('api_key'=>$api_key, 'api_secret'=>$api_secret ,'api_data' => $api_data );
			
		$this->payment->gateway->set('checkout_url' , '' );
		//url shown when payment is pending
		$this->payment->gateway->set('pending_url' , '');
		//callback url
		$this->payment->gateway->set('callback_url' , '' );

		$this->payment->gateway->set('api_key' , $api_key );
		$this->payment->gateway->set('api_secret' , $api_secret );
		$this->payment->gateway->set('api_data' , $api_data );
		
		if( $this->payment->gateway->validate('notification' , $_get_ )  )
		{
			
			if( $gateway_id == 0 )
			{
				//funds havent been secured.
				$result['status'] = 1; //dummy call
			}
			else
			{

				//@todo validate payment request from server
				//$remote_transaction = $this->payment->gateway->gateway->get_transaction(  $gateway_id );	
				
				$result['status'] = (int)$this->_update_order_on_transaction('pay4app' , $amount , $shop , $transaction , $o_settings , $gateway_id , 'paid' );

			}
		}	
		else
		{
			//if shop admin changed gateway settings - it has nothing to do with us ;)
			$result['status'] = 0;
			$result['msg'] = 'Failed to verify authenticity of message';
		}



		//verify hash.
		$this->output->set_content_type('application/json');
		die( json_encode($result) );
		return;
	}



	/**
	 * PayNow endpoint communication
	 *
	 * Handles all callback routines from the PayNow server.
	 *
	 */
	public function paynow( )
	{
		if( $this->input->get('action') == 'subs' )
		{
			return $this->account_subscription();
		}

		//https://secure.oneshop.co.zw/api/endpoint/paynow?transaction_id=20&type=order&challenge=12345678901234567890123456789012
		$gateway = 'paynow';
		$this->payment->gateway->select($gateway);

		$_get_ = $this->input->get();

		$expected = array('transaction_id' , 'challenge');

		//check get params
		foreach( $expected as $key )
		{
			if( ! isset($_get_[$key]))
				$this->fail('Expected key ' . $key . ' not set');
		}
		

		//check post params
		$_post_  =  $this->input->post();
		$expected = array('reference','amount','paynowreference','pollurl','status','hash');

		foreach( $expected as $key )
		{
			if( ! isset($_post_[$key]))
				return $this->fail('Expected key from PayNow ' . $key . ' not set');
		}

		$transaction = $this->payment->transaction->get(  $_get_['transaction_id']  );
		if( empty($transaction))
		{
			$this->fail('Transaction does not exist');
			return;
		}
		
		if( $transaction['challenge'] != urldecode( $_get_['challenge'] ) )
		{
			//@todo log this
			$this->fail('Transaction challenge mismatch. Logged for viewing by admin.');
			return;
		}

		//shop and relevant settings
		$shop = $this->shop->get_by_id( $transaction['shop_id'] , false );
		$shop['logo'] = $this->shop->image->get( $shop['shop_id'] );
		$shop['contact'] = $this->shop->settings->get_contact_settings( $shop['shop_id'] );

		//payment settings
		$p_settings = $this->shop->settings->get_payment_settings( $shop['shop_id'] );
		
		//order settings
		$o_settings = $this->shop->settings->get_order_settings( $shop['shop_id'] );



		$transaction_id = $this->input->post('reference');
		$amount = floatval( $this->input->post('amount') );
		$remote_transaction_id = $this->input->post('paynowreference');
		$poll_url = $this->input->post('pollurl');

		$gateway_id = $poll_url;

		$status = $this->input->post('status');
		$hash = $this->input->post('hash');


		$config = array();
		$api_key = '';
		$api_secret = '';
		$api_data = '';

		//is user supported payment gateway	
		switch( $gateway )
		{
			case $p_settings['secondary_gateway']:
			{
				$api_key = $p_settings['secondary_api_key'];
				$api_secret = $p_settings['secondary_api_secret'];
				$api_data = $p_settings['secondary_data'];
				
			}
			break;
			case $p_settings['primary_gateway']:
			{
				$api_key = $p_settings['primary_api_key'];
				$api_secret = $p_settings['primary_api_secret'];
				$api_data = $p_settings['primary_data'];
				
			}
			break;
			default:
			{
				//@todo, log error, user changed settings whilst order was been processed... bad 
				$result = array('status' => 0, 'msg'=>'Error, most likely user changed gateway settings. ');
				die( json_encode($result) );
			}
		}

		//values not necessary as we will be simply querying backend	
		$this->payment->gateway->set('checkout_url' , '' );
		$this->payment->gateway->set('pending_url' , '');
		$this->payment->gateway->set('callback_url' , '' );
		$this->payment->gateway->set('api_key' , $api_key );
		$this->payment->gateway->set('api_secret' , $api_secret );
		$this->payment->gateway->set('api_data' , $api_data );

		//is status update valid ?
		if( !  $this->payment->gateway->validate('status_update' , $_post_ ) )
		{
			//@todo log this error
			return $this->fail('Authenticity of message failed. Request not from Paynow server. Logged');
		}

		//what type of status update is it ?
		switch(  $status )
		{
			
			
			//transaction was remotely cancelled by Paynow
			case PayNow::ps_cancelled:
			{
				//order remotely cancelled
				//get order given id
				$result = $this->_update_order_on_transaction('paynow' ,  $amount , $shop , $transaction , $o_settings , $gateway_id , 'cancelled' );
				die(json_encode(array('response'=> $result)));

			}
			break;
			//transaction created on paynow, but not yet paid by customer
			case PayNow::ps_created :
			{
				$transaction['log'] .= "Received PayNow transaction created message. Update transaction details \n Received HTTP REQUEST: " . json_encode($_post_);
				$transaction['gateway_data'] = $pollurl;
				$this->payment->transaction->update( $transaction['id'] , $transaction );
			}
			break;
			//customer has confirmed receiving goods
			case PayNow::ps_delivered:
			{
				//goods delivered, just set paid
				$result = $this->_update_order_on_transaction('paynow' ,  $amount , $shop , $transaction , $o_settings , $gateway_id , 'delivered' );
				die(json_encode(array('response'=> $result)));
			}
			break;
			//customer is saying he/she did not receive goods, opened dispute
			case PayNow::ps_disputed:
			{
				//send admin an email, send customer an email
				$result = $this->_update_order_on_transaction('paynow' ,  $amount , $shop , $transaction , $o_settings , $gateway_id , 'disputed' );
				die(json_encode(array('response'=> $result)));
			}
			break;
			//payment made, customer awaiting delivery of goods
			case PayNow::ps_awaiting_delivery:
			
			//Transaction paid successfully, the merchant will receive the funds at next settlement
			case PayNow::ps_paid:
			{
				$gateway_id = $poll_url;
				$result = $this->_update_order_on_transaction('paynow' ,  $amount , $shop , $transaction , $o_settings , $gateway_id , 'paid' );
				die(json_encode(array('response'=> $result)));
			}
			break;
			//refunded.
			case PayNow::ps_refunded:
			{
				//notify admin, via email and sms, and move order to cancelled
				//notify customer via sms and email and move to cancelled
				$result = $this->_update_order_on_transaction('paynow' ,  $amount , $shop , $transaction , $o_settings , $gateway_id , 'refunded' );
				die(json_encode(array('response'=> $result)));
			}
			break;
			//sent to upstream payment provider
			case PayNow::ps_sent:
			{
				//@todo lock order ?
				die('Nothing to do with this.');
			}
			break;
			default:
			{
				//error occured
				die('Unexpected, unknown message from Paynow, WTF PayNow?');
			}
			break;

		}

		return;

	}

	/**
	 * Handle subscription payment callbacks.
	 *
	 * We use paynow.
	 *
	 */
	public function account_subscription()
	{
		$gateway = 'paynow';
		$this->payment->gateway->select($gateway);

		//load oneshop accounts payment details
		$this->load->config('oneshop/account_payment_gateway');

		$_get_ = $this->input->get();
		$expected = array('shop_id' , 'account_type' , 'period_months' , 'challenge' , 'is_upgrade' , 'salt' );

		//check get params
		foreach ($expected as $key) 
		{
			if( ! isset($_get_[$key]))
				$this->fail('Expected key ' .$key  . ' not set');
		}

		//vars
		$acc_type 		= $_get_['account_type'];
		$sub_period		= $_get_['period_months'];
		$challenge  	= $_get_['challenge'];
		$is_upgrade		= (int)$_get_['is_upgrade'];

		//bullshit filter
		if( $acc_type == 'free' )
		{
			$this->fail('You cannot pay for free account!');
		}

		//check post params
		$_post_  =  $this->input->post();
		$expected = array('reference','amount','paynowreference','pollurl','status','hash');
		
		foreach( $expected as $key )
		{
			if( ! isset($_post_[$key]))
				return $this->fail('Expected key from PayNow ' . $key . ' not set');
		}

		//paynow vars
		$pn_amount = floatval( $_post_['amount'] );
		$gateway_id = $_post_['pollurl'];
		$pn_status  = $_post_['status'];
		$pn_hash    = $_post_['hash'];

		//get shop even if suspended or inactive
		$shop = $this->shop->get_by_id( $_get_['shop_id'] , false );
		if( empty($shop) )
		{
			$this->fail('Shop does not exist');
		}
		$shop['logo'] = $this->shop->image->get( $shop['shop_id'] );
		$shop['contact'] = $this->shop->settings->get_contact_settings( $shop['shop_id'] );


		$user =  $this->shop->user->get( $shop['admin_id'] );

		//validate challenge
		$local_challenge = md5( $_get_['salt'] . $shop['shop_id'] . $acc_type . $is_upgrade . $sub_period . $_get_['salt'] );
		if( $local_challenge !== $_get_['challenge'] )
		{
			$this->fail('Challenge failed, could not verify the source of the message.');
		}

		//get oneshop subscriptions paynow api key and secret
		$os_psa = $this->config->item('os_paynow_subs_account');
		$this->payment->gateway->set('checkout_url' , '' );
		$this->payment->gateway->set('pending_url' , '');
		$this->payment->gateway->set('callback_url' , '' );
		$this->payment->gateway->set('api_key' , $os_psa['api_key'] );
		$this->payment->gateway->set('api_secret' , $os_psa['api_secret'] );
		$this->payment->gateway->set('api_data' , $os_psa['api_data'] );

		//validate paynow message
		if( ! $this->payment->gateway->validate('status_update' , $_post_) )
		{
			$this->fail('Could not verify authenticity of Paynow message, not from Paynow!');
		}

		//get account
		$year = date('Y');
		$month = date('m');
		$account_types  = $this->shop->account->subscription_types();
		$all_subs 		= $this->shop->account->get_all_subscriptions( $shop['shop_id']);
		$current_sub 	= $this->shop->account->current_subscription( $shop['shop_id'] );
		if( ! isset( $account_types[  strtolower( $acc_type  ) ] ) )
		{

			$this->fail('Unknown account type');
		}
		$account 		= $account_types[ $acc_type ];
		$plan_name 		= $account['name'];

		//@todo validate amounts paid

		switch(  $pn_status )
		{
			//subscription paid for
			case PayNow::ps_awaiting_delivery:
			case PayNow::ps_paid:
			{
				$this->load->model('ui');
				//load shop theme
				$t_settings = $this->shop->settings->get_theme_settings( $shop['shop_id'] );
				if( ! $this->ui->theme->load_theme( $t_settings['theme'] ) )
				{
					//load fallback theme..
					$this->ui->theme->load_theme( 'fallback' );		
				}	
				$theme = $this->ui->theme->theme_options();

				if(  $is_upgrade )
				{
					//upgrade for all motnhs
					$this->shop->account->upgrade_period(  $shop['shop_id'] , $sub_period , $acc_type );
				}else
				{
					//subscribe for all months
					$this->shop->account->subscribe_period(  $shop['shop_id'] , $sub_period , $acc_type );
				}

				//send 
				$email = array();
		 		$email['type'] = 'success';
		 		$word = ( $is_upgrade ? ' upgraded ' : ' subscribed ');
		 		$email['header'] = 'You have ' . $word . ' to ' . $plan_name . ' for ' . $sub_period . ' months';
		 		$email['subheader'] = '';
		 		$email['message'] = 'Hello ' . $user['fullname'] . '<br/>Thank you for choosing OneShop<br/>We have received your payment and have  ' . $word .' your account to the ' . $plan_name .' for ' . $sub_period . ' months. We hope you enjoy using the features we have packed for you into the plan you chose.<br/>Remember you can always contact us at anytime if you need any help.<br/>Thank you.' ;
		 		$email['action_link'] = $shop['url'];
		 		$email['action_message'] = ' Take me to my shop ';
		 		$email['footer_action'] = '';
				$email['footer_action_url'] = '';
				$products = array();
				$cart_items = array();
				$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );				
		 		$this->system->pushnotification->push_email( $shop['shop_id'] , $user['fullname'] , '*', $email['header'] , $html );
		 		
		 		//send sms to shop admin
				$sms = 'Your subscription to OneShop ' . ucwords( $acc_type ) . ' for ' . $sub_period . ' months has been received. Thank you for choosing us.';
		 		$this->system->pushnotification->push_sms( $shop['shop_id'] ,  '*' , $sms , 9);
	 		

		 		//send email to oneshop admins letting them know of subscription
		 		$email['header'] = $shop['name'] .' ' . $word . ' to ' . $plan_name . ' for ' . $sub_period . ' months';
		 		$email['message'] = 'Post data: ' . json_encode($_post_) . "\n\n Get data: " . json_encode($_get_) ;
		 		$email['action_message'] = ' View their shop ';
		 		$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );				
		 		//setting shop_id to -1 means its a message to oneshop admins
		 		$this->system->pushnotification->push_email( -1 , '*' , '*', $email['header'] , $html );
		 		
		 		//@todo send receipt

				die('Success');



			}
			break;
			//user was refunded for subscription
			case PayNow::ps_refunded:
			{
				//send email to oneshop 
				//setting shop_id to -1 means its a message to oneshop admins
				$email = array();
				$email['header'] = $shop['name'] .' has been refunded for their subscription payment. ';
		 		$html = 'Post data: ' . json_encode($_post_) . "\n\n Get data: " . json_encode($_get_) ;
		 		
		 		$this->system->pushnotification->push_email( -1 , '*' , '*', $email['header'] , $html );

		 		die('Ok someone is onto it');
		 		
			}
			break;
			//the customer opened a dispute
			case PayNow::ps_disputed:
			{
				//send 
				//load shop theme
				$t_settings = $this->shop->settings->get_theme_settings( $shop['shop_id'] );
				if( ! $this->ui->theme->load_theme( $t_settings['theme'] ) )
				{
					//load fallback theme..
					$this->ui->theme->load_theme( 'fallback' );		
				}	
				$theme = $this->ui->theme->theme_options();

				//send 
				$email = array();
		 		$email['type'] = 'warning';
		 		$word = ( $is_upgrade ? ' upgraded ' : ' subscribed ');
		 		$email['header'] = 'Dispute opened over account subscription.';
		 		$email['subheader'] = '';
		 		$email['message'] = $shop['name'] . ' opened an account dispute.<br/> Contact the customer, view the logs.. etc for more info' ;
		 		$email['action_link'] = $shop['url'];
		 		$email['action_message'] = ' View shop ';
		 		$email['footer_action'] = '';
				$email['footer_action_url'] = '';
				$products = array();
				$cart_items = array();
				$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );				
		 		$this->system->pushnotification->push_email( -1 , '*' , '*', $email['header'] , $html );
		 		
				die('Ok someone is onto it');
			}
			break;
			//we really dont care about the rest
		}

		

		switch(  $_get_['account_type'] )
		{
			case 'free':
			{

			}
			break;
			case 'basic':
			{

			}
			break;
			case 'premium':
			{

			}
			break;
			default:
			{
				$this->fail('wtf?');
			}
			break;
		}

	}

	/** Update transaction **/
	private function _update_order_on_transaction( $gateway ,  $amount , $shop , $transaction , $o_settings , $gateway_id , $status = 'paid' )
	{
		$this->load->model('ui');

		$_get_ = $this->input->get();
		$_post_ = $this->input->post();

		//get order given id
		$order = $this->system->cart->get_order_by_transaction_id( $transaction['transaction_id'] );

		if( empty($order) )
		{
			//@error transaction processed but order no longer exists.
			//well this is shitty,
			//notify oneshop of problem
			//notify shop administrator of problem
			return False;
		}

		//theme settings
		$t_settings = $this->shop->settings->get_theme_settings( $shop['shop_id'] );

		if( ! $this->ui->theme->load_theme( $t_settings['theme'] ) )
		{
			//load fallback theme..
			$this->ui->theme->load_theme( 'fallback' );	
			
		}
		
		//theme
		$theme = $this->ui->theme->theme_options();
		
		//@todo get featured products
		$products = array();
		
		//cart
		$this->system->cart->load_order_into_cart(  $order['order_id'] );
		$cart_items = $this->system->cart->items();

		//get order shipping
		$shipping = $this->system->cart->get_order_shipping( $order['order_id'] );


		//ordered items
		$items = $this->system->cart->get_order_items( $order['order_id'] );

		//if order contains virtual products, add text telling user where to download
		$is_virtual_order = False;

		//order url
		$order_url = $this->system->order_url( $shop , $order );

		



		if( $status == 'cancelled' )
		{

			if( empty($order) )
			{
				//order was also delete, nothing to do, this should never happen
				//when deleteing an order, the transaction must be deleted as well
				$this->payment->transaction->delete( $transaction['transaction_id'] );
				die('Transaction cancelled, order was already deleted.');
			}
			//cancelled transaction for a valid order ?
			if( $order['status'] == 'paid' || $order['status'] == 'pending' )
			{
				//not good
				//@todo report anomaly
				die('anomaly');
			}
			else
			{
				//transaction cannot cancel order, it might break too many things
				//create a new id on checkout
				$transaction['log'] .= "\n Order has been cancelled by Paynow. New order will be created. \n Received HTTP DATA \n : " . json_encode($_post_);
				$transaction['gateway_data'] = ''; 
				$this->payment->transaction->update( $transaction['transaction_id'] , $transaction );
				die('Deleted transaction. If order continue, a new one will be created.');
			}

		}
		else if(  $status == 'disputed' )
		{
			$email = array();

			//send message to customer - 
	 		$email['type'] = 'warning';
	 		$email['header'] = 'Your opened an order dispute with ' . $gateway;

	 		$email['subheader'] = '';
	 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $shop['name'] .'<br/>We have received your order dispute for Order # ' . $order['order_id'] .' and will have someone look at it as soon as possible and get back to you as soon as possible. We sincerely apologize for any inconvience caused. ';
	 		$email['action_link'] = $order_url;
	 		$email['action_message'] = ' View your order ';

	 		$email['footer_action'] = '';
			$email['footer_action_url'] = '';
			$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );
			
	 		$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , $shipping['email'], $email['header'] , $html );
	 		

	 		//send customer sms
			$sms = 'Hello ' . $shipping['fullname'] . ' we have received your order dispute and will be getting in touch with you shortly. ';
			$this->system->pushnotification->push_sms( $shop['shop_id'] ,  $shipping['phone_number'] , $sms , 9);
	 		

			//notify administrator
			$email['header'] = 'Payment dispute opened by ' . ucwords( $gateway ) . ' on order #' . $order['order_id'];
			$email['message'] = 'Customer ' . $shipping['fullname'] . ' has opened a dispute claim on their order. Order #'. $order['order_id'] . '. If you do not respond the customer will receive a refund and the orderwill be cancelled. If you do not resolve this dispute, the customer might get a refund.';
			$email['action_message'] = ' View order in  ' . OS_SITE_NAME;
			$email['action_link'] = $shop['url'] . 'admin#/orders/view/' . $order['order_id'];
			
			$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );
			
	 		$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , $shipping['email'], $email['header'] , $html );
	 		

			$transaction['log'] .= "\n Customer has disputed the order through PayNow. Email and SMS have been sent to both customer and shop administrator. \n Received HTTP DATA \n : " . json_encode($_post_); 
			$this->payment->transaction->update( $transaction['transaction_id'] , $transaction );
		}
		else if( $status == 'refunded')
		{
			if( $order['status'] == 'cancelled' )
			{
				die( json_encode(array('status'=>1,'msg'=>'Order already cancelled')) );
			}
			$order['status'] = 'cancelled';
			$order['log'] .= "\n Order has been refunded, changed from paid to cancelled. ";

			//update order
			$this->system->cart->update_order( $order['order_id'] , $order );

			$transaction['is_refunded'] = True;

			$transaction['log'] .= "\n Transaction was refunded and order was set to cancelled. ";

			$this->payment->transaction->update( $transaction['transaction_id'] , $transaction );

			//notify admin by email
			$email = array();
	 		$email['type'] = 'danger';
	 		$email['header'] = 'Important: Order #' . $order['order_id'] . ' by ' . $shipping['fullname'] . ' has been refunded. ';
	 		$email['subheader'] = '';
	 		$email['message'] = 'This is to notify you that order #' . $order['order_id'] .' by ' . $shipping['fullname'] . ' has successfully been refunded and it has been moved to cancelled orders.<br/>';
	 		$email['action_link'] = $shop['url'] . 'admin/#orders/browse';
	 		$email['action_message'] = ' Browse all orders ';
	 		$email['footer_action'] = '';
			$email['footer_action_url'] = '';

			$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );	
	 		$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , '*', $email['header'], $html );
	 		

			//notify admin by sms
			$sms = 'Important: Order #' . $order['order_id'] . ' by ' . $shipping['fullname'] . ' has been refunded. Login to your shop for more details.'; 
			$this->system->pushnotification->push_sms( $shop['shop_id'] ,  '*' , $sms , 9);
	 		

			//notify customer
			$email = array();
	 		$email['type'] = 'warning';
	 		$email['header'] = 'Your order has been refunded';
	 		$email['subheader'] = '';
	 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $shop['name'] .'<br/> Your payment for order #' . $order['order_id'] . ' has been refunded, <br/>We sincerely apologize for any inconvience caused and we hope to see you shopping through us soon. ';
	 		$email['action_link'] = $shop['url'];
	 		$email['action_message'] = ' Start shopping. ';
	 		$email['footer_action'] = '';
			$email['footer_action_url'] = '';

			$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );
	 		$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , $shipping['email'], $email['header'], $html );
	 		

			$sms = 'Hello ' . $shipping['fullname'] . ' you have been refunded for your order #' . $order['order_id'] . ' Check your email for more info.';
			$this->system->pushnotification->push_sms( $shop['shop_id'] ,  $shipping['phone_number'] , $sms , 9);
	 		
	 		return True;
		}
		else if( $status == 'delivered' )
		{
			//customer has confirmed receiving goods
			$shipping['is_collected'] = True;

			$order['log'] .= "\n Customer confirmed that goods have been delivered to his/her address.\n";



			$this->system->cart->update_order( $order['order_id'] , $order );
			$this->system->cart->update_order_shipping(  $order['order_id'] , $shipping );

			die('Confirmed customer received goods');
		}
		else if( $status == 'paid' )
		{
			if( floatval($order['total'] ) != floatval($amount)  )
			{
				//@todo log in oneshop error log, amounts do not match
				$result['status'] = 0;
				$result['msg'] = 'Amounts mismatch. Order is ' . money($order['total']) . ' server returned ' . money($amount);
				echo json_encode($result);
				return;
			}

			if(  $transaction['amount'] > 0.00 )
			{
				//already paid ?
				if(  $transaction['payment_gateway'] != $gateway )
				{
					//@todo log anomaly
					//anomaly, customer paying for already paid order ?
					return 0;
				}
				die( json_encode(array('status'=>0,'msg'=> $gateway .' already updated this transaction')));
			}
			//update transaction
			$transaction['gateway_data'] = $gateway_id;
			$transaction['time_paid'] = date('Y-m-d h:m:s');
			$transaction['amount'] = floatval($amount);
			$transaction['payment_gateway'] = $gateway;

			$ip = $this->input->ip_address();
			$useragent = $this->input->user_agent();

			$transaction['log'] .= "Updated transaction status \n Received http request from $ip -> $useragent \n " . json_encode($_get_) . "\n Transaction before was " . json_encode($transaction) . " \n Order before was " . json_encode($order ) . "\n\n";

			$this->payment->transaction->update( $transaction['transaction_id'] , $transaction );

			//update order
			$order['status'] = 'paid';
			$order['log'] .= 'Payment received at '. date('r');

			//update order
			$this->system->cart->update_order( $order['order_id'] , $order );

			
			foreach( $items as $key=> $item )
			{
				$item['product'] = (  empty($item['product_json']) ) ? $this->product->get( $item['product_id'] ) : json_decode( $item['product_json'] , True );
				if( ! empty($item['product_json']))
				{
					unset( $item['product_json']);
					$item['is_deleted'] = True;
				}
				if( $item['product']['type'] == 'virtual' )
				{
					//files must be sent out when order is paid for.
					$is_virtual_order = True;
				}
				$items[ $key ] = $item;

				//update stock
				if( ! isset($item['deleted']))
					$item['is_deleted'] = false;

				if( ! $item['is_deleted'] )
				{
					$product = $item['product'];
					$product['stock_sold'] += $item['qty'];

					$product['stock_ordered'] -= $item['qty'];
					if( $product['stock_sold'] < 0)
					{
						//this should never happen
						$product['stock_sold'] = 0;
					}

					$this->product->update($product['product_id'] , $product );
				}

			}

		
			//send out notification email
			$email = array();
	 		$email['type'] = 'order';
	 		$email['header'] = 'Your order has been paid for.';

	 		$email['subheader'] = '';
	 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $shop['name'] .'<br/>We have received your payment of ' . money($amount) . ' through payment gateway ' .ucwords($gateway) . ' <br/>We will send you the receipt shortly.<br/> Feel free to contact us at any time. ';
	 		$email['action_link'] = $order_url;
	 		$email['action_message'] = 'View your order ';
	 		if( $is_virtual_order )
	 		{
	 			$email['action_message'] = 'View order and download files.';
	 		}
	 		
	 		switch( $shipping['type'])
	 		{
	 			case 'deliver':
	 			{
	 				$msg = 'One of our employees has received a notification and will be dispatching your order to be delivered to your address soon. The order will be delivered to ' . $shipping['address'] . '/' . $shipping['suburb'] . '/' . $shipping['city'] . '/' .$shipping['country'] . '. If this is incorrect, please change it as soon as possible on your order page. Please make sure you do this as soon as possible, before your order is shipped';
	 				$email['footer_action'] = 'Change your shipping address';
					$email['footer_action_url'] = $order_url; 
	 			}
	 			break;
	 			case 'collect_instore':
	 			{
	 				$msg = 'One of our employees has received a notification and will be reviewing your order. We will contact you with any more details and you will be able to collect your order from our shop address ' . $shop['address'] .' - ' . $shop['city'] . '/' . $shop['country'] .'. Thank you. ';
	 				$email['footer_action'] = 'Contact us';
					$email['footer_action_url'] = $order_url; 
	 			}
	 			break;
	 			case 'cash_on_delivery':
	 			{
	 				$msg = 'One of our employees has received a notification and will be reviewing your order. We will contact you to verify your identity and order details before we send someone to deliver the order. Thank you. ';
	 				$email['footer_action'] = 'Contact us';
					$email['footer_action_url'] = $order_url;
	 			}
	 			break;

	 		}

	 		$email['footer_msg'] = $msg;
			$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );
			
	 		$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , $shipping['email'], 'Your order has been paid for', $html );
	 		

			//send out payment received sms
			$sms = 'Hello ' . $shipping['fullname'] . '. Your payment of ' . money( $amount ) . ' through ' . ucwords($gateway) . ' has been received. Check your email for more details';
			$this->system->pushnotification->push_sms( $shop['shop_id'] ,  $shipping['phone_number'] , $sms , 9);
	 		
			//send payment received sms to shop user
			if( $o_settings['sms_notify_on_pay'] )
			{
				$sms = "Order Payment Received \nCustomer " . $shipping['fullname'] . ' paid ' . money( $amount ) . ' through ' . ucwords($gateway) . ' for an order. Please login to your shop dashboard and view this order. ' . ($shipping['type'] == 'deliver'?'This order is supposed to be delivered to ' . $shipping['city'] . '/' . $shipping['suburb'] . '/' . $shipping['address'] : '') ;
				//asterisk for sms receiver is shop contact phone number, will be updated on sending out. 
				$this->system->pushnotification->push_sms( $shop['shop_id'] , '*' , $sms , 9);	
			}	

	 		//send email receipt to user
			$html = file_get_contents( $order_url . '?action=receipt&no_buttons' );
		 	$this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'], 'Your order receipt.', $html );
		 	
		 	//send email notifying shop owner of order paid for
		 	{
			 	$email['header'] = 'Order #' . $order['order_id'] .' has been paid for.';

		 		$email['subheader'] = '';
		 		$email['message'] = 'Customer ' . $shipping['fullname'] .' has paid for an order. <br/>You have received payment of ' . money($amount) . ' into your payment gateway account, the customer paid using ' . ucwords($gateway) .' <br/>The customer also received an SMS and email of the order details. <br/>Please fulfill the customer\'s order as soon as possible, to avoid disputes. <br/> The products bought by the customer are shown below. ';
		 		$email['action_link'] = $shop['url'] . 'admin#/orders/view/' . $order['order_id'];
		 		$email['action_message'] = 'View order in ' . OS_SITE_NAME ;

		 		$email['footer_action'] = 'Login to my account';
				$email['footer_action_url'] =$email['action_link']; 
		 		
		 		switch( $shipping['type'])
		 		{
		 			case 'deliver':
		 			{
		 				$msg = 'The customer has paid for shipping costs as defined in your ' . OS_SITE_NAME .' settings, the order is supposed to be delivered to ' . $shipping['address'] . '/' . $shipping['suburb'] . '/' . $shipping['city'] . '/' .$shipping['country'] . '. If you suspect an error, please first contact the customer to verify the details before shipping.';
		 				
		 			}
		 			break;
		 			case 'collect_instore':
		 			{
		 				$msg = 'The customer hsa chose to collect the order from the store. Contact the customer for more info on this. The customer will collect the goods from  ' . $shop['address'] .' - ' . $shop['city'] . '/' . $shop['country'] .'. If this is incorrect, login to your '. OS_SITE_NAME . ' account and notify the user of the changes. ';
		 				
		 			}
		 			break;
		 			case 'cash_on_delivery':
		 			{
		 				$msg = 'The customer specified wishes to pay cash on delivery. You are responsible for verifying the authenticity of this request and sending the goods to the customer as well as collecting money. The customer specified address  ' . $shipping['address'] . '/' . $shipping['suburb'] . '/' . $shipping['city'] . '/' .$shipping['country'] ;
		 				
		 			}
		 			break;

		 		}
		 		$html = $this->ui->generate_email($theme['info']['dir'] ,$email , $shop , $products , $cart_items );
		 		$this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , '*' , 'Order #' . $order['order_id'] .' has been paid for.' , $html );

		 		return True;
		 	}	


		}

	 	return True;
	}
	
	/**
	 * Received as an http post request with a json
	 * encoded array as post data.
	 *
	 * Called when a delivery provider acknowledges that
	 * they will be handling the delivery of an order.
	 *
	 * Two parameters must first be verified. That is the 
	 * api challenge and the order challenge.
	 *
	 * If successful It will update the databse to reflect that
	 * the delivery has been dispatched and delivered.
	 */
	public function shipment_acknowledge(  $challenge )
	{
		//load shipping
		$this->system->load_shipping();
		
		$json = $this->read_input();
		if( empty($json) or is_null($challenge) )
		{
			$this->fail('');
		}

		$api_secret = '';


		//verify source
		$hash = $this->system->shipping->api_hash( $json['salt'] , $json['provider'] );

		if( $hash != $json['challenge'] )
		{
			$this->fail('Api challenge mismatch');
			return;
		}

		

		//ok we've verified the request is coming from a trusted source
		$response = array();


		
		//get shipping
		$shipping = $this->system->cart->get_order_shipping_by_challenge(  $challenge );
		if( empty($shipping) )
		{
			//@todo log fatal error
			return $this->fail();
		}

		$order = $this->system->cart->get_order(  $shipping['order_id'] );
		if( empty($order) )
		{
			//@todo log this error
			return $this->fail();
		}


		//check if this order has not already been sent out
		if( $shipping['is_dispatched'] )
		{
			//user paid using one handler and then chose another handler
			//dilemna !
			//we will update o reflect the new settngs and log all past information
			$shipping['log'] = $shipping['log'] . "\n\n" . json_encode( $shipping , JSON_PRETTY_PRINT ) . "\n";
		}

		//update
		$shipping['handler'] = $json['provider'];
		$shipping['is_dispatched'] = True;
		$shipping['is_ready'] = True;
		$shipping['is_collected'] = True;
		$shipping['tracker_code'] = $json['tracker_code'];

		//@todo send out notification email and sms to customer
		{

		}

			

		$shipping['is_notification_sent'] = True;

		//add response to log
		$shipping['log'] = $shipping['log'] . "\nGot from provider at " . date('r') . "\n" . json_encode( $json , JSON_PRETTY_PRINT ) . "\n";

		$this->system->cart->update_order_shipping( $order['order_id'] , $shipping );

		//ok all is well now
		$this->output->set_content_type('application/json');
		echo json_encode(array('status' => 'ok') );

	}

	
	/** Internal read POST data **/
	private function read_input()
	{
		return json_decode(file_get_contents('php://input' ) , True );
	}

	
	/** Fail **/
	private function fail($message = '')
	{
		$res = array('status' => 'fail');
		if( ! empty($message))
			$res['message'] = $message;
		$this->output->set_content_type('application/json');
		die ( json_encode($res) );
	}



	
	
}


