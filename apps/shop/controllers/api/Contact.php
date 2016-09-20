<?php
/**
 * Contact 
 * 
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		7 September 2015
 * @package 	Controllers/Api/Contact
 *
 */

require_once( APPPATH . 'core/OS_AdminController.php');

class Contact extends OS_AdminController
{

	/** Constructor **/
	public function __construct()
	{
		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

		$this->load->model('ui');
	}

	/**
	 * Contact customer
	 *
	 * @param 		Int 		$	Order id
	 *
	 * array(message=>array('email','sms','app'),type=>array('info','warning','error'),title=>'',text=>'hello') 
	 */
	public function customer( $order_id = Null )
	{
		$json = $this->read_input();
		if( is_null($order_id) )
		{
			$this->error('Specify order id');
		}
		if( empty($json) )
		{
			$this->error('Invalid request');
		}

		//expected keys
		$expected = array('message', 'type' , 'title' , 'text');
		foreach(  $expected as $key )
		{
			if( ! isset($json[$key]))
				$this->error('Expected key not set.');
		}
		if( ! in_array($json['message'], array('email','sms','app')))
		{
			$this->error('Unknown message type');
		}
		if( ! in_array($json['type'] , array('info','warning','success','error')) )
		{
			$json['type'] = 'info';
		}
		if( $json['type'] == 'error')
		{
			//change to bootstrap style
			$json['type'] = 'danger';
		}

		$this->_send_message( $order_id , 'customer' , $this->data['shop'] , $this->data['theme'] , $json);

		
	}

	/**
	 * Contact user
	 *
	 * @param 		Int 		$	User id
	 *
	 * array(message=>array('email','sms','app'),type=>array('info','warning','error'),text=>'hello')
	 */
	public function user( $user_id = Null )
	{
		$json = $this->read_input();
		if( is_null($order_id) )
		{
			$this->error('Specify order id');
		}
		if( empty($json) )
		{
			$this->error('Invalid request');
		}

		//expected keys
		$expected = array('message', 'type' , 'title' , 'text');
		foreach(  $expected as $key )
		{
			if( ! isset($json[$key]))
				$this->error('Expected key not set.');
		}
		if( ! in_array($json['message'], array('email','sms','app')))
		{
			$this->error('Unknown message type');
		}
		if( ! in_array($json['type'] , array('info','warning','success','error')) )
		{
			$json['type'] = 'info';
		}
		if( $json['type'] == 'error')
		{
			//change to bootstrap style
			$json['type'] = 'danger';
		}

		$this->_send_message( $user_id , 'user' , $this->data['shop'] , $this->data['theme'] , $json);

	}

	private function _send_message(  $id , $type = 'customer' , $shop , $theme , $json )
	{
		

		//target user
		$user = array('fullname'=>'','phone_number'=>'','email'=>'');
		$order = array();

		if(  $type == 'customer' )
		{
			$order = $this->system->cart->get_order(  $id );
			if( empty($order) or $order['shop_id'] != $shop['shop_id'] )
			{
				$this->error('Order does not exist');
			}

			$shipping = $this->system->cart->get_order_shipping( $id );
			if( empty($shipping) )
			{
				$this->error('Failed to get customer info');
			}	
			$user['fullname'] =  $shipping['fullname'];
			$user['phone_number'] = $shipping['phone_number'];
			$user['email'] = $shipping['email'];
		}
		elseif( $type == 'user' )
		{
			$user =  $this->shop->user->get( $id );
			if( empty($user) or $user['shop_id'] != $shop['shop_id'] )
			{
				$this->error('User does not exist');
			}

		}

		switch( $json['message'] )
		{
			case 'email':
			{
				$email = array();
		 		$email['type'] = $json['type'];
		 		$email['header'] = clean( $json['title'] ); //512 chars max
		 		//@todo proper sanitize
		 		$email['message'] =  $this->system->safe_html( $json['text'] );
		 		if( $type == 'customer')
		 		{
		 			$email['action_link'] = $this->system->order_url( $shop , $order );
		 			$email['action_message'] = ' View my order ';
		 		}
		 		elseif( $type == 'user')
		 		{
		 			$email['action_link'] = $shop['url'] . 'admin#/dashboard';
		 			$email['action_message'] = ' View Admin Dashboard ';
		 		}
		 		
		 		$email['footer_msg'] = 'This message was sent by ' . $this->user['fullname'] .' ,a verified user of ' . $this->data['shop']['name'] . ' at ' . date('r');
		 		$email['footer_action'] = 'Create your own online shop';
				$email['footer_action_url'] = OS_BASE_SITE;
				//@todo get products
				$products = array();
				$cart_items = array();

				$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $products , $cart_items );
			
	 			$this->system->pushnotification->push_email( $shop['shop_id'] , $shipping['fullname'] , $shipping['email'], $email['header'], $html );
	 		
			}
			break;
			case 'sms':
			{
				//max length is 3 pages
				$sms = $shop['name'] . ":\n" . strip_tags( $json['text'] ) . "\n" . $shop['url'];

				$this->system->pushnotification->push_sms( $shop['shop_id'] ,  $shipping['phone_number'] , $sms , 5);
				
			}
			break;
			case 'app':
			{
				$this->error('App messages not yet implemented');
			}
			break;
		}

		$this->render('send_message' , array('status'=>'ok'));
	}


}
