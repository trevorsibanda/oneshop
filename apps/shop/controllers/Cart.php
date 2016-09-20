<?php
/**
 * Shopping cart.
 *
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @package 	Models/Cart
 * @date 		28 May 2015
 *
 */

class Cart extends OS_Controller
{
	public function __construct()
	{
		parent::__construct();
		//Load payment transactions
		$this->load->model('payment');
		$this->payment->load_gateway();

		$this->data = $this->load_shop();
	}

	public function add()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('product_id' , '' , 'required|numeric');
		$this->form_validation->set_rules('items' , '' , 'required|numeric' );
		//$this->form_validation->set_rules('shop_id' , '' , 'required|numeric');
		//@todo prevent csrf
		//$this->form_validation->set_rules('token_id' , '' , 'required|xss_clean');
		//$this->form_validation->set_rules('challenge' , '' , 'required|xss_clean|length[32]');

		if( $this->form_validation->run() )
		{
			$post_data = $this->input->post();
			$item_options = array();
			$product = $this->product->get( $post_data['product_id']  , True );
			if( empty( $product) or $product['shop_id'] !== $this->data['shop']['shop_id'] )
			{
				$this->render( 'cart' , array('error' => 'Invalid Product ID') );
			}

			if( $product['stock_left'] == 0 )
				die('<script>alert("Sorry but this product is out of stock"); history.back();</script> ');
			//get product images
			$product['images'] = $this->product->image->get_images( $product['images'] );
			unset( $post_data['product_id'] );
			unset( $post_data['items'] );
			foreach( $post_data as $key => $value )
			{
				//only accept those prefixed with custom_
				if( substr($key, 0 , 7) == 'custom_' )
				{
					
					//check if not containminated data
					$key = (int)str_replace('custom_', '', $key );
					$value  = $this->security->xss_clean( $value );
					//validdate if opton exists
					foreach( $product['attributes'] as $attr )
					{
						if( $attr['attribute_id'] == $key)
						{
							//now verify if valid option. Best way would be to check within options
							if(  $value == $attr['attribute_value'] )
							{
								array_push( $item_options , array('option' => $attr['attribute_name'] , 'value' => $value ) );
								continue;	
							}
							foreach(  $attr['options']  as $attr_opt )
							{
								//valid option
								if( $attr_opt['value'] == $value )
								{
									$option_text = $attr['attribute_name'];
									array_push( $item_options , array('option' => $option_text , 'value' => $value ) );	
								}
								
							}
						}
					}
				}
			}

			$items = intval($this->input->post('items') );
			$items = (  $items < $product['min_orders']  ) ? $product['min_orders'] : $items;
			$items = (  $items > $product['max_orders']  ) ? $product['max_orders'] : $items ;
			
			//$this->system->cart->insert( $item );
			$item = $this->system->cart->add(  $product , $items , $item_options );
			//if not ajax request redirect to previous page
			if(  $this->input->is_ajax_request() == False )
			{
				//open redirect here
				header('Location: ' . $this->input->server('HTTP_REFERER') );
				return;
			}
			$this->render( 'cart' , $data );
		}
		else
		{
			die('<script>alert("Failed to add product to cart. Please try again");history.back();</script>');
			//$this->render('cart' , array('error' , 'Form validation failed'));
		}
	}

	public function update()
	{
		$_post_ = $this->input->post();

		$cart_items = $this->system->cart->items();

		$max_items = 300;
		$x = 0;
		foreach( $_post_ as $key => $val )
		{
			$x += 1;
			if( $x > $max_items )
				die('Too many items in cart');
			if( substr( $key , 0 , 14 ) == 'cart_item_qty_' && strlen($key) > 14 && is_numeric(  substr($key, 14 ) ) )
			{
				$product_id = substr($key, 14 );
				$val = intval( $val );
				if( $val == 0 )
				{
					//remove from cart

					$this->system->cart->remove( $product_id );
					//if ordered, remove from cart and update cart @todo
					if( $this->system->cart->is_cart_ordered( ) )
					{
						//@todo remove item from database and update order.
					}

				}
				else
				{
					$item = array();
					foreach( $cart_items as $_item )
					{
						if( $_item['product_id'] == $product_id )
						{
							$item = $_item;
							break;
						}
					}
					if( empty($item) )
						continue; //not in cart

					$val = (  $val > $item['product']['max_orders'] ) ? $item['product']['max_orders'] : $val;
					$val = ( $val < $item['product']['min_orders'] ) ? $item['product']['min_orders'] : $val;

					$item['qty'] = $val;
					//will update self by adding
					$this->system->cart->add(  $item['product'] ,  $val , $item['options'] );
				}
				

			}
		}

		//back to previous page
		if( ! empty($_post_))
			header('Location: ' . $this->input->server('HTTP_REFERER'));
	}

	public function remove(  )
	{
		
		$product_id =  $this->input->get_post('product_id');
		if( is_null($product_id) )
		{
			return;
		}
		$cart = $this->system->cart->items();
		$b = $this->system->cart->remove( $product_id );
		if( ! $this->input->is_ajax_request() )
		{
			//open redirect here
			//@todo fix bug
			header('Location: ' . $this->input->server('HTTP_REFERER') );
			return;
		}
		$this->render('remove_cart' , array('status' => ($b ? 'Success' : 'Failed') ) );
		return;
	}

	public function checkout( )
	{
		$data = array();
		$this->system->load_shipping();
		$this->load->library('form_validation');
		$this->load->helper('captcha');

		$data = $this->load_shop(  );
        //payment settings
		$p_settings = $this->shop->settings->get_payment_settings( $this->data['shop']['shop_id'] );
		//order settings
		$o_settings = $this->shop->settings->get_order_settings( $this->data['shop']['shop_id'] );
        
		$data['page']['title'] = 'Checkout'; 
		$data['page']['description'] = 'Pay for the goods you have in your shopping cart and set delivery options';
		
		$this->form_validation->set_rules( 'fullname' , 'Fullname' , 'required|min_length[3]|max_length[1024]' );
		$this->form_validation->set_rules( 'phone_number' , 'Phone Number' , 'required|min_length[7]|max_length[24]');
		$this->form_validation->set_rules( 'email' , 'Email address' , 'required|valid_email');
		$this->form_validation->set_rules( 'delivery' , 'Delivery' , 'required|in_list[collect_instore,deliver]' );
		//@todo add support for cash on delivery
		if( $this->input->post('delivery') == 'deliver')
		{

			$this->form_validation->set_rules( 'country' , 'Country' , 'required|exact_length[2]|alpha' );
			$this->form_validation->set_rules( 'city' , 'City' , 'required');
			$this->form_validation->set_rules( 'address' , 'Address' , 'required');	
		}


        if( $o_settings['use_captcha'] )
        {
            $this->form_validation->set_rules( 'captcha' , 'Captcha code' , 'required|min_length[4]|max_length[8]');
        }
		$this->form_validation->set_rules( 'gateway' , 'Payment gateway' , 'required');

		
		
		//if an order has already been placed, redirect to order page 
		if( $this->system->cart->is_cart_ordered()  )
		{
			$order_id = $this->system->cart->order_id();
			$order = $this->system->cart->get_order( $order_id );
			if( empty($order) )
				die('Order no longer exists');
			if( $order['transaction_id'] == 0 )
			{
				die('Placed order does not have valid transaction info.');
			}
			$transaction = $this->payment->transaction->get( $order['transaction_id'] );
			if( empty($transaction) )
				die('Transaction no longer exists!');
			$shipping = $this->system->cart->get_order_shipping( $order_id );
			if( empty($shipping) )
				die('Shipping data not valid');

			//url shown when payment is successfully made
			$order_url = $this->data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) . '?action=view' ;
			
			return header('Location: ' . $order_url );

		}

		$data['shipping'] = $this->shop->settings->get_shipping_settings( $data['shop']['shop_id'] ); 
		$data['shipping']['rules'] = json_decode($data['shipping']['rules_json'] , true );
        //calcuate shipping costs
        $data['shipping_cost'] = 0.00;

        $shipping = array('country' =>$data['shop']['country'] , 'city' => $data['shop']['city'] );
        $total_weight = 0.00;
        $total_price = 0.00;
        $cart = $this->system->cart->items();
        foreach ($cart as $item) 
        {
        	$product = $item['product'];
        	if( $product['type'] == 'virtual' )
        		continue;
        	$total_price += $product['price'];
        	$total_weight += $product['weight_kg'];
        }

        $data['shipping_cost'] = $this->system->shipping->calculate( $data['shipping'] , $shipping , $data['shop'] , $total_weight , $total_price );
        
        if( $this->form_validation->run() && ! empty( $cart ) )
		{
			//some basic tests
			$delivery_option = $this->input->post('delivery');
			if( ! in_array($delivery_option, array('collect_instore' , 'deliver','cash_on_delivery')))
				die('Invalid delivery option');

			$gateway = $this->input->post('gateway');
			$gateways = $this->payment->gateway->get_all();
			if( ! isset( $gateways[$gateway]))
			{
				die( 'Unknown payment gateway - ' . $gateway );
				return;
			}

			$fullname = ucwords( clean( $this->input->post('fullname') ) );
			$phone_number = $this->input->post('phone_number');
			//@todo check if valid phone number

			$email = $this->input->post('email');
			$country = clean( $this->input->post('country') );
			$city = ucwords( clean( $this->input->post('city') ) );
			$address = clean( $this->input->post('address') );
			$subscribe = $this->input->post('subscribe');
			//@todo check if shopper with details exists if not create shopper
			$shopper = array();
			$shopper['fullname'] = $fullname;
			$shopper['email'] = $email;
			$shopper['phone_number'] = $phone_number;
			$shopper['city'] = $city;
			$shopper['country'] = $country;
			$shopper['address'] = $address;
			//@todo check if user wants to subscribe to this shop's feed
			$this->system->cart->shopper( $shopper );

			$shipping = array();
			$shipping['type'] = $delivery_option;
			$shipping['fullname'] = $fullname;
			$shipping['phone_number'] = $phone_number;
			$shipping['email'] = $email;
			$shipping['address'] = $address;
			$shipping['city'] = $city;
			$shipping['country'] = $country;
			//@todo allow shop to specify diferent collection address
			$shipping['collection_address'] = $data['shop']['address'];
			//generate random collection code
			$shipping['collection_code'] = substr(  md5(json_encode($shipping) ) , 0 , 7); //6 character collection code
			$this->system->cart->shipping( $shipping );
			$order_id = $this->system->cart->place_order($data['shop']['shop_id']);

			if( $order_id  == False )
			{
				die('Failed to place an order');
			}

			$order = $this->system->cart->get_order( $order_id );
			//expire after n days
			$order['expire_date'] = date('Y-m-d', strtotime("+{$o_settings['order_expire_days']} days"));
			

			//initiate transaction
			$order['transaction_id'] = $this->payment->transaction->create( $order['shop_id'] , 0 /*not yet active*/ , 'order' , $gateway , ''  );
			$transaction = $this->payment->transaction->get( $order['transaction_id'] );
			//@todo create transaction might fail ?
			if( empty($transaction) )
			{
				//@todo handle create transaction fail
				//delete the order and fail
				die('Failed to create transaction');
			}
			$this->system->cart->update_order( $order['order_id'] , $order );


			//url shown when payment is successfully made
			$order_url = $this->data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) . '?action=view' ;
			//url to cancel order
			$order_cancel_url = $this->data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) . '?action=cancel';


			$email = array();

	 		//@todo add support for cash on delivery
			if(  $shipping['type'] == 'cash_on_delivery')
			{
				$email['type'] = 'order';
		 		$email['header'] = 'Your order is waiting approval';

		 		$email['subheader'] = '';
		 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>You can complete and change you order at any time and still enjoy the convenience of using your prefered payment platform';
		 		$email['action_link'] = $order_url;
		 		$email['action_message'] = 'I will pay ' .  money($this->system->cart->get_total() ) . ' on delivery of order';
		 		$email['footer_msg'] = 'A member of the shop will contact you to verify that you still want to have the order delivered to you. After which you will receive the goods and be required to pay on delivery';
		 		$email['footer_action'] = 'Cancel this order';
				$email['footer_action_url'] = $order_cancel_url;
			}
			else if( $shipping['type'] == 'collect_instore')
			{
				
		 		$email['type'] = 'order';
		 		$email['header'] = 'Your order is waiting';

		 		$email['subheader'] = '';
		 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>You can complete and change you order at any time and still enjoy the convenience of using your prefered payment platform';
		 		$email['action_link'] = $order_url;
		 		$email['action_message'] = 'Pay ' .  money($this->system->cart->get_total() );
		 		$email['footer_msg'] = 'After making the payment you will receive an SMS with a collection code you will be required to produce at our stores, you will then be able to collect your order.';
		 		$email['footer_action'] = 'Cancel this order';
				$email['footer_action_url'] = $order_cancel_url; 		
 		
		 		
			}
			else if(  $shipping['type'] == 'deliver')
			{

		 		$email['type'] = 'order';
		 		$email['header'] = 'Your order is waiting';

		 		$email['subheader'] = '';
		 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>You can complete and change you order at any time and still enjoy the convenience of using your prefered payment platform';
		 		$email['action_link'] = $order_url;
		 		$email['action_message'] = 'Pay ' .  money($this->system->cart->get_total() );
		 		$email['footer_msg'] = 'After making the payment our staff will dispatch the delivery to the address you specified . ' . $shipping['country'] . '/' . $shipping['city'] . '/' . $shipping['suburb'] . '/' . $shipping['address'] . '. You will receive an SMS and email with your delivery details once its dispatched';
		 		$email['footer_action'] = 'Cancel this order';
				$email['footer_action_url'] = $order_cancel_url; 		
 		
		 		
			}
			$cart = $this->system->cart->items();
		 		
		 	$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , $cart );
		 	$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $shipping['fullname'] , $shipping['email'], 'Your order is waiting', $html );
		 		

			//send another email to user with receipt
			//get html generated by view_order?Action=receipt
			//@see $this->view_order
	 		$html =  file_get_contents( str_replace('action=view', 'action=receipt',$order_url ) . '&no_buttons' ) ;
	 		$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $shipping['fullname'] , $shipping['email'], 'Your order receipt.', $html );
	 		
	 		

			//notify on order
			if( $o_settings['email_notify_on_order'] )
			{
				$email['type'] = 'order';
		 		$email['header'] = 'An order was placed by ' . $shipping['fullname'] . '(' . $shipping['email'] . ' )';

		 		$email['subheader'] = '';
		 		$email['message'] = $shipping['fullname'] . '(' . $shipping['email'] . ' ) placed an order<br/>The order has not been paid for yet.<br/> You can view the order by following the link below. <br/> <b>We will send you an email once the customer pays for the order.';
		 		$email['action_link'] = $this->data['shop']['url'] . 'admin/#orders/view/' . $order['order_id'];
		 		$email['action_message'] = 'View order of ' .  money($this->system->cart->get_total() );
		 		$email['footer_msg'] = 'If you dont want to continue receiving email messages for placed orders, login to your shop and change the settings under Order Settings.';
		 		$email['footer_action'] = '';
				$email['footer_action_url'] = ''; 
			}
			//sms notify on order placed
			if( $o_settings['sms_notify_on_order'] && account() != 'free' )
			{
				$sms = 'Order placed by ' . $shipping['fullname'] . ' - ' . $shipping['phone_number'] . ' - ' . $shipping['email'] . ".\n " ;
				$sms .= 'Totalling ' . money($this->system->cart->get_total() ) . '. This order has not been paid for. Login to your shop to view order.';
				//low priority message
				$this->system->pushnotification->push_sms( $this->data['shop']['shop_id'] , '*' , $sms  , 4 );
		 		
			}

	 		
			return $this->_checkout_transaction($order , $transaction , $shopper , $p_settings , $order_url );		
			
			
		}

		$data['gateways'] = array();
		$data['gateways'][0] = $this->payment->gateway->get( $p_settings['primary_gateway']);
		if( $p_settings['secondary_gateway'] != '*' and ! empty($p_settings['secondary_gateway']) )
			$data['gateways'][1] = $this->payment->gateway->get( $p_settings['secondary_gateway'] );

		

		if( empty( $data['gateways'][0] ) and empty($data['gateways'][1]))
			$data['gateways'] = array();
	
		if( $o_settings['use_captcha'] )
		{
			//enable captcha on login form
            $vals = array(
            'img_path'      => ASSETS_DIR .'captcha/',
            'img_url'       => ASSETS_BASE .'captcha/',
            'font_path'     => APPPATH . 'third_party/fonts/shift_bold.ttf',
            'img_width'     => '250',
            'img_height'    => 50,
            'expiration'    => 7200,
            'word_length'   => 6,
            'font_size'     => 24,
            'img_id'        => 'Imageid',
            'pool'          => '0123456789abcdefghijklmnopqrstuvwxyz',

            // White background and border, black text and red grid
            'colors'        => array(
                    'background' => array(255, 255, 255),
                    'border' => array(255, 255, 255),
                    'text' => array(0, 0, 0),
                    'grid' => array(255, 0, 255)
            )
            );
			$data['captcha'] = create_captcha($vals);
			
		}
        

		
		$this->render('checkout' ,  $data  );

	}


	public function clear($redir = True)
	{
		$this->system->cart->clear();
		if( ! $redir)
			return;
		if( ! $this->input->is_ajax_request() )
		{
			//@todo fix open redirect vulnerability
			header('Location: ' . $this->input->server('HTTP_REFERER') );
			return;
		}
		$this->render('clear_cart' , array('status' => 'Success'  ) );
		return;
	}

	public function edit_item(  $product_id )
	{

	}

	/** COntinue an order given the order id **/
	public function view_order( $order_id = Null, $challenge = Null)
	{
		//first validate hash
		if( is_null($order_id) )
			return header('Location: /cart/checkout');
		$order = $this->system->cart->get_order( $order_id , $this->data['shop']['shop_id'] );

		if( empty($order) )
		{
			return header('Location: /');
		}

		//validate challenge
		$local_challenge = md5( $order['date_created'] .OS_BASE_DOMAIN . $order_id );
		if(  $local_challenge != $challenge )
		{
			$data  = array();
			$data['shop'] = $this->data['shop'];
			$this->sys_render('orders/not_allowed' , $data );
			return;
		}

		$this->system->cart->load_order_into_cart(  $order_id );

		//action
		$data = array( 'shop' => $this->data['shop']);

		//add images to products
		$data['order_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=view';
		$data['checkout_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=checkout';
		$data['cancel_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=cancel';
		$data['print_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=receipt';
		$data['download_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=download';
		$data['support_url'] = $data['shop']['url'] . 'cart/view_order/' . $order['order_id'] . '/' . $local_challenge . '?action=support';
		

		$data['items'] = $this->system->cart->items();
		$data['order'] = $order;
		$data['order']['transaction'] = $this->payment->transaction->get( $order['transaction_id'] );
		$data['order']['shipping'] = $this->system->cart->get_order_shipping( $order['order_id'] );
		$data['shipping'] = $data['order']['shipping'];
		$data['transaction'] = $data['order']['transaction'];
		$data['cart_total'] = $this->system->cart->get_total();
		$data['cart_items'] = $this->system->cart->count_items();
		$data['msg'] = '';

		//a virtual order is an order which contains files
		//i.e if the order included a piece of software it becomes a virtual order
		$data['is_virtual_order'] = False;
		foreach( $data['items'] as $item )
		{
			if($item['product']['type'] != 'physical')
			{
				$data['is_virtual_order'] = True;
				break;
			}
		}
		

		

		

		$page = 'view';

		$action = $this->input->get('action');
		$action = empty($action) ? 'view' : $action;

		$_get_ = $this->input->get();

		if( isset($_get_['no_buttons']))
			$data['no_buttons'] = True;

		if( isset($_get_['just_paid']) )
		{
			$action = 'receipt';
		}

		if( $order['status'] == 'cancelled' )
		{
			$action = 'cancel';

		}
			

		switch( $action )
		{
			case 'view':
			{

			}
			break;
			case 'cancel':
			{
				//can only cancel pending orders
				if( $order['status'] != 'pending' &&$order['status'] != 'cancelled' )
					break;


				$page = 'cancel_order';

				$data['is_order_cancelled'] = ($order['status'] === 'cancelled');


				if( !is_null($this->input->post('confirm_cancel')) && !$data['is_order_cancelled'] )
				{

					$reason = $this->input->post('cancel_reason');
					if( ! is_null($reason) )
						$reason = $this->system->safe_html(  $reason );
					else
						$reason = 'The customer did not specify a reason for cancelling their order';

					//cancel order
					$order['status'] = 'cancelled';
					$order['log'] .= "\n Customer cancelled their own order \n Noted reason: \n " . $reason;
					//set today as expiration date
					$order['expire_date'] = date('Y-m-d');

					$this->system->cart->update_order($order['order_id']  , $order );


					//notify user that order has been cancelled
					$email = array();
					$email['type'] = 'order';
			 		$email['header'] = 'You cancelled your order.';

			 		$email['subheader'] = '';
			 		$email['message'] = 'You order has been successfully cancelled.<br/>You will not be able to access the order anymore and will have to start again.<br/><br/>Thank you for choosing us.<br/>';
			 		$email['action_link'] = $this->data['shop']['url'];
			 		$email['action_message'] = 'Start shopping again ';
			 		$email['footer_msg'] = '';
			 		$email['footer_action'] = '';
					$email['footer_action_url'] = '';

					$cart = $this->system->cart->items();
		 		
		 			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $cart , array() );
		 			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $data['order']['shipping']['fullname'] , $data['order']['shipping']['email'], $email['header'] , $html );
	 		
		 			//send shop admin an email 
		 			//@todo 
		 			$data['is_order_cancelled'] = True;

		 			


				}
				$this->clear(False);
			}
			break;
			case 'download':
			{
				//@todo calculate max download date
				//max of 14 days to download
				$max_download_date = strtotime( $data['transaction']['time_paid'] ) + ( 60 * 60 * 24 * 14 );

				$data['max_download_date'] = date('Y-m-d' , $max_download_date );
				$data['download_period_expired'] = ( time() > $max_download_date );


				//if product id and challenge set 
				$product_id = $this->input->get('product_id');
				$challenge = $this->input->get('challenge');

				if( ! is_null($product_id) && ! is_null($challenge) )
				{
					//check challenge
					if( md5($product_id . date('Y-m-d')) !== $challenge )
						die('File download token has expired');

					//ok create down
					//use foreach incase the product was deleted
					foreach( $data['items'] as $item )
					{
						$p = $item['product'];
						if( $p['product_id'] == $product_id )
						{
							//get file
							if( $p['type'] != 'virtual')
								die('Cannot download physical product !');
							$file = $this->product->file->get( $p['file_id'] );
							if( empty($file))
								die('Sorry this file has been deleted. Please contact the shop admin to help you');
							//dispatch download
							//@todo move this to controller
							$this->load->helper('download');
							$file_path = ASSETS_FILES . 'product_files/' . $file['hash'] . '.zip';
							if( ! is_file($file_path))
							{
								die('Sorry the file no longer exists!');
							}
							$data = @file_get_contents( $file_path );
							force_download( $file['filename'] . '.zip' , $data );
							return;
						}
					}
					die('Product does not exist in your order');
				}

				
				
				//add download url to all products
				for($x=0; $x < count($data['items']); ++$x )
				{
					//each download url is only valid for 24 hours
					$p = $data['items'][$x]['product'];
					$challenge = md5( $p['product_id'] . date('Y-m-d') );
					$data['items'][$x]['product']['download_url'] = $data['download_url'] . '&product_id=' . $p['product_id'] . '&challenge=' . $challenge; 
				}
				$page = 'download';
			}
			break;
			case 'just_paid':
			case 'receipt':
			{
				//cant print a receipt 
				$page = 'receipt';

			}
			break;
			case 'checkout':
			{
				return $this->_checkout_transaction($order , $data['transaction'] , $data['shipping'] , Null , $data['order_url'] );		
			}
			break;
			case 'support':
			{
				$message = $this->input->post('message' );
				$type = $this->input->post('type');

				if( is_null($message) or is_null($message) or empty($message) )
				{

				}
				else
				{
					//sanitize
					$message = htmlspecialchars($message);
					if( ! in_array($type, array('complaint','enquiry','suggestion','compliment')))
						die('Unknown message type');

					//generate email to send
					$email = array();
					$email['type'] = 'order';
			 		$email['header'] = ucwords($type) . ' message from customer on Order #' . $data['order']['order_id'];

			 		$email['subheader'] = '';
			 		$email['message'] = 'You have received a  ' .ucwords($type) . ' customer support message from ' . $data['shipping']['fullname'] . ' ( ' . $data['shipping']['email'] . ' )<br/>The message was sent from  ' . $this->input->ip_address() . ' on ' . xss_clean( $this->input->user_agent() ) .  '<br/>Message:<br/><br/>' . $message;
			 		$email['action_link'] = $this->data['shop']['url'] . 'admin#/orders/view/' . $data['order']['order_id'] ;
			 		$email['action_message'] = 'View order and reply';
			 		$email['footer_msg'] = 'This message was generated and queued for sending at ' . date('r');
			 		$email['footer_action'] = '';
					$email['footer_action_url'] = '';

					$cart = $this->system->cart->items();
		 		
		 			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
		 			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , OS_SITE_NAME .' Customer Support' , '*', $email['header'] , $html );
		 			
		 			$data['msg'] = 'Thank you, your message has been submitted and someone will get back to you as soon as possible.';
				}
				$page = 'support';
			}
			break;
		}


		
		$this->sys_render('orders/' . $page , $data );
	}

	public function _checkout_transaction($order , $transaction , $shopper , $p_settings = Null  , $order_url = '' )
	{
		if( empty($order_url) )
			die('Internal programming error. in module Cart line ' . __LINE__ );

		if( is_null($p_settings) )
		{
			$p_settings = $this->shop->settings->get_payment_settings( $this->data['shop']['shop_id'] );
		}


		
		
		//using custom payment gateway
		$api_key = '';
		$api_secret = '';
		$api_data = '';

		switch( $transaction['payment_gateway'] )
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
				//unsupported gateway ?
				$data['msg'] = 'Sorry, this shop does not support the specified payment gateway, this might be because the shop stopped accepting payments through the gateway or a technical error occured. Please go back to your order and choose a different payment gateway.';
				return $this->sys_render('payment/unsupported_gateway' , $data );
			}
			break;
		}
		$this->payment->gateway->select( $transaction['payment_gateway'] );
			
		$this->payment->gateway->set('checkout_url' , $order_url . '?just_paid' );
		//url shown when payment is pending
		$this->payment->gateway->set('pending_url' , $order_url . '?pending');
		//callback url
		$this->payment->gateway->set('callback_url' , $this->payment->gateway->make_callback_url($transaction) );

		$this->payment->gateway->set('api_key' , $api_key );
		$this->payment->gateway->set('api_secret' , $api_secret );
		$this->payment->gateway->set('api_data' , $api_data );
		//init transaction
		$response = $this->payment->gateway->initiate_transaction( $order['total'] , $transaction , $shopper  );

		//@todo if failed to init transaction - notify user.
		switch(  $response['action'] )
		{
			case 'redirect':
			case 'url':
			{
				//redirect to specified url

				header('Location: ' . $response['data']['url'] );
				return;
			}
			break;
			case 'form':
			{

				$data = '<script>function redirect(){ document.forms["redirect"].submit(); }</script>';
				$data .= '<form method="POST" action="'. $response['data']['form_action'] .'" name="redirect" > ';
				unset( $response['data']['form_action'] );
				foreach(  $response['data'] as $key=>$value )
				{
					$data .= '<input type="hidden" name="' . $key .'" value="' . $value  .'" />';	
				}
				$data .= 'Taking too long ? ...<a type="submit" href="javascript:;" > Try again</a> ';
				$data .= '</form>';

				$view_data = array('code' => $data , 'title' =>'Redirect to payment gateway', 'onload'=>'redirect()');
				$this->sys_render('redirect' , $view_data );
				return;
			}
			break;
			default:
			{
				//assume error
			}
			break;
		}

		$this->render('checkout_pay' , $data );
		return;
	}


}