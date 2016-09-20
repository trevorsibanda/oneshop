<?php
/**
 * OneSHOP Admin API. Orders
 *
 * This is the api used for the admin interface orders
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Orders extends OS_AdminController
{
	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

		$this->load->model('payment'); 		
 	}

 	/** Get an order given the order id **/
 	public function get( $order_id )
 	{
 		if( empty($order_id) or is_null($order_id))
 			$this->forbidden();
 		//load payments
 		$this->load->model('payment');

 		$order_id =intval( $order_id );
 		
 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order))
 		{
 			$this->not_found();
 			return;
 		}
 		if(  $this->data['shop']['shop_id'] !== $order['shop_id'] )
 		{
 			$this->forbidden();
 		}
 		
 		$order['items'] = $this->system->cart->get_order_items( $order_id );
 		//change attribute options from string
 		foreach ($order['items'] as $key => $item) {
 			$order['items'][ $key ]['attributes'] = json_decode(  $item['attributes_json'] );
 			
 			//if the item has product_json, it means that the product has been deleted and we should
 			//use this instead
 			if( ! empty($item['product_json']) )
 			{
 				$order['items'][$key]['product'] = json_decode($item['product_json'] , True );
 				//unset description to reduce size
 				unset($order['items'][$key]['product']['description']);

 				$order['items'][$key]['is_deleted'] = True; //tell us that its deleted

 				$order['items'][$key]['product']['images'] = $this->product->image->get_images( $order['items'][$key]['product']['images'] );

 			}
 			else
 			{
 				//product still exists so get from database.
 				//get product
	 			$order['items'][$key]['product'] = $this->product->get( $item['product_id'] );
	 			if( empty( $order['items'][$key]['product'] ))
	 				continue; //this should never happen
	 			unset($order['items'][$key]['product']['description']);

	 			//get all product images
	 			if( ! isset($order['items'][$key]['product']['images']))
	 			{
	 				print_r( $item['product_id'] );
	 				return;
 						
	 			}
	 			
 				$order['items'][$key]['product']['images'] = $this->product->image->get_images( $order['items'][$key]['product']['images'] );
 			}
 			

 			

 			//hide from public
 			unset( $order['items'][$key]['attributes_json']); 
 			unset( $order['items'][$key]['product_json'] );
 		}
 		$order['shipping'] = $this->system->cart->get_order_shipping( $order_id );
 		unset( $order['shipping']['log'] );

 		$order['transaction'] = $this->payment->transaction->get(  $order['transaction_id'] );
 		
 		//hide sensitive info from end user
 		unset( $order['transaction']['gateway_data']);
 		unset( $order['transaction']['challenge'] ); 
 		unset( $order['shipping']['challenge']);
 		unset( $order['transaction']['log']);
 		$this->render('order' , $order );
 	}

 	/** Get all orders with an applied filter, maximum number of items , sorting and offset **/
 	//Alot of info is not returned to reduce size
 	public function all( $filter = 'all' )
 	{
 		$this->load->model('payment');

 		$max = $this->input->get('max');
 		$offset = $this->input->get('offset');
 		$order_by = $this->input->get('order_by');

 		if(is_null($max) )
 			$max = 0;
 		if( is_null($offset))
 			$offset = 0;
 		if( is_null($order_by))
 			$order_by = 'DESC';

 		
 		
 		$orders = $this->system->cart->get_shop_orders( $this->data['shop']['shop_id'] , $filter , $max , $offset , $order_by );
 		$the_orders = array();
 		foreach( $orders as  $order )
 		{
 			$order['items'] = $this->system->cart->get_order_items( $order['order_id'] );
	 		//change attribute options from string
	 		foreach ($order['items'] as $key => $item) 
	 		{
	 			//$order['items'][ $key ]['attributes'] = json_decode(  $item['attributes_json'] );
	 			//get product
	 			//$order['items'][$key]['product'] = $this->product->get( $item['product_id'] );
	 			//get all product images
	 			//$order['items'][$key]['product']['images'] = $this->product->image->get_images( $order['items'][$key]['product']['images'] );
	 			unset( $order['items'][$key]['attributes_json']);
	 			unset( $order['items'][$key]['product_json']); 
	 		}
	 		$order['shipping'] = $this->system->cart->get_order_shipping( $order['order_id']);

	 		unset( $order['shipping']['log']);
	 		unset( $order['shipping']['challenge']);

	 		$order['transaction'] = $this->payment->transaction->get(  $order['transaction_id']  );
	 		
	 		//remove transaction info we dont want the user to see
	 		unset( $order['transaction']['challenge'] );
	 		unset( $order['transaction']['gateway_data'] );
	 		unset( $order['transaction']['log'] );
	 		unset( $order['log'] );

	 		array_push($the_orders, $order);
 		}
 		
 		$this->render('orders' , $the_orders );
 	}

 	/** Get an order given the collection code **/
 	public function collection_code( $collection_code )
 	{
 		if( empty($collection_code))
 			$this->forbidden();
 		$result = array('order_id' => 0);
 		$result['order_id'] = $this->system->cart->get_order_by_collection_code(  $collection_code );
 		if( $result['order_id'] == False)
 			unset($result['order_id']);
 		else
 		{
 			$order = $this->system->cart->get_order( $result['order_id'] , $this->data['shop']['shop_id'] );
 			if( empty($order))
 				$this->forbidden();
 		}
 		$this->render('collection_order' , $result );
 	}

 	/** Verify authenticity of an order given a verification code **/
 	public function verify_code( $v_code  )
 	{
 		if(empty($v_code))
 			$this->forbidden();
 		$result = array('order_id' => 0 );
 		$order_id = $this->system->cart->verify_order_code( $this->data['shop']['shop_id'] , $v_code );
 		if( $order_id == False)
 			unset($result['order_id']);
 		else
 		{

 			$result['order_id'] = $order_id;
 		}
 		$this->render('verified_order' , $result );
 	}

 	/** Get orders summary, number of paid, pending ,cancelled orders...etc **/
 	public function summary()
 	{
 		
 		$orders = $this->system->cart->get_shop_orders( $this->data['shop']['shop_id']  , 'all' , 999 );

 		$info = array('paid' => 0 , 'pending' => 0 , 'cancelled' => 0 , 'delivered' => 0 , 'archived' => 0);

 		foreach($orders as $order )
 		{
 			$info[ $order['status'] ] += 1;
 		}



 		$this->render('order_summary' , $info);
 	}

 	/** Updates an order. Only shipping info and items in order can be updated, as long as it has not been paid for **/
 	public function update( )
 	{
 		//@todo
 		$this->error('Not valid');
 	}

 	/** Cancel a pending order **/
 	public function cancel( $order_id  , $api_called = False )
 	{
 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order) or ( $order['shop_id'] != $this->data['shop']['shop_id'] ) )
 		{
 			$this->forbidden();
 		}

 		if( ! has_permission('manage_orders') )
 		{
 			$this->forbidden('You do not have permission to cancel orders');
 		}

 		if( $order['status'] == 'cancelled')
 		{
 			$this->error('Already cancelled');
 			return;
 		}

 		//if paid, we cannot delete this order
 		if( $order['status'] == 'paid'  || $order['status'] == 'archived' )
 		{
 			$this->error('This order has been paid for and cannot be deleted !');
 			return;
 		}
 		//if transaction paid but not reflected in order, dont delete
 		//@todo query gateway for updated transction details
 		if( (! empty($transaction))  and ( $transaction['amount'] > 0.00 ) )
 		{
 			//transaction states that this order has been paid for
 			$this->error('The customer is currently paying for this order. ');
 			return;
 		}

 		$shipping = $this->system->cart->get_order_shipping( $order_id );
 		$transaction = $this->payment->transaction->get( $order['transaction_id'] );
 		$items = $this->system->cart->get_order_items( $order_id );

 		//restore stock for each item which was ordered
 		foreach(  $items as $item )
 		{
 			if( ! empty( $item['product_json']))
 				continue; //deleted product, this should never happen
 			//add stock back
 			$product = $this->product->get( $item['product_id'] );
 			if( ! empty($product) )
 			{
 				//increment stock
 				$product['stock_left'] += $item['qty'];
 				//decrement ordered by qty
 				$product['stock_ordered'] -= $item['qty'];
 				$this->product->update( $product['product_id'] , $product );
 			}
 		}

 		//alert customer that the order has been deleted
 		//send the user a notification
		$email = array();
		$email['type'] = 'order';
		$email['header'] = 'Your order has been cancelled';

		$email['subheader'] = '';
		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/> Your order has been cancelled.<br/>If you wish to continue this order, follow the link below. You can then edit the order and continue to checkout. <br/><br/>We hope to see you soon.<br/>';
		$email['action_link'] = $this->data['shop']['url'];
		$email['action_message'] = 'Start shopping!';

		//url to continue order
		$url = $this->data['shop']['url']. 'continue/' . $order['order_id'];

		$sms =  $this->data['shop']['name'] . "\nIMPORTANT: Your order has been cancelled.\nFollow " . $url . ' to continue your order. An email with more info was also sent to you.' . "\n This message is intended for {$shipping['fullname']} if this is not you, ignore it";
		$cart = array();
		
		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , $cart );
		//push email
		$this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'], 'IMPORTANT: Your order has been cancelled', $html );
		//push sms
		//critical but not as much :)
		if( account() != 'free' )
			$this->system->pushnotification->push_sms( $order['shop_id'] ,  $shipping['phone_number'] , $sms ,  8 );

		//set as cancelled
		$order['status'] = 'cancelled';
		$order['expire_date'] = date('Y-m-d');

		$order['log'] = $order['log'] . "\n Updated order set it to cancelled at " . date('r');
		$this->system->cart->update_order($order['order_id'], $order );

		//show the order
		$this->get( $order['order_id'] );
		return;

 	}

 	/** Cancel all orders, where a particular product is ordered **/
 	public function cancel_where_ordered( $product_id )
 	{
 		if( is_null($product_id))
 			$this->forbidden();

 		if( ! has_permission('orders') )
 		{
 			$this->forbidden('You do not have permission to cancel orders');
 		}

 		$product = $this->product->get( $product_id );
 		if(  empty($product) or $product['shop_id'] != $this->data['shop']['shop_id'] )
 			$this->forbidden();

 		//number of items ordered should be equal to the number of items we get here
 		$where_ordered = $this->system->cart->get_where_ordered($this->data['shop']['shop_id'] , $product['product_id']);

 		if(  count($where_ordered) != $product['stock_ordered'] )
 		{
 			//this should never happen
 			//@todo log it
 		}

 		foreach( $where_ordered as $order )
 		{
 			//cancel these orders.
 			if( $order['status'] == 'pending' )
 			{
 				//nb: called function may exit if a transaction is currently happening
 				//nb: no need to update product stock because its done in $this->cancel
 				$this->cancel( $order['order_id'] );	
 			}
 			else
 			{
 				//if not pending. nothing to worry abuot ?
 			}
 			
 		}

 		//just to be safe, lets reset stock ordered count to 0
 		$product['stock_ordered'] = 0;
 		$this->product->update( $product['product_id'] , $product );

 		$this->render('result' , array('status'=>'ok'));

 	}

 	/** Delete a pending order. Fails if order has been paid for or refunded **/
 	public function delete(  $order_id )
 	{
 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order) or ( $order['shop_id'] != $this->data['shop']['shop_id'] ) )
 		{
 			$this->forbidden('The order does not exist');
 		}

 		if( ! has_permission('manage_orders') )
 		{
 			$this->error('You do not have permission to cancel orders');
 		}

 		if( $order['status'] != 'cancelled')
 		{
 			$this->error('You can only delete an order once you have cancelled it.');
 			return;
 		}	

 		$transaction = $this->payment->transaction->get( $order['transaction_id'] );
 		$shipping = $this->system->cart->get_order_shipping( $order_id );

 		if( !empty($transaction) && $transaction['amount'] != 0.00 )
 		{
 			$this->error('Cannot delete an order which has been paid for. Transaction ' . $transaction['transaction_id'] . ' paid - ' . money($transaction['amount']));
 			return;
 		}

 		$this->system->cart->remove_order(  $order['order_id'] );

 		//send email to customer ???
 		//@todo send out email and/or sms to customer
		$email = array();
 		$email['type'] = 'order';
 		$email['header'] = 'Your order has been deleted';
 		$email['subheader'] = '';
 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>We were doing some house keeping and had to delete your order to keep things running quickly and smoothly. We had already cancelled your order. We apologize for any inconvience caused. ';
 		$email['action_link'] = $this->data['shop']['url'];
 		$email['action_message'] = 'Start shopping ';

 		$html= $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
 	
		$this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'] , $email['header'] , $html );
		
		$this->shop->logger->action('Deleted order #' . $order['order_id'] .  ' by ' . $shipping['fullname'] . ' - ' . $shipping['email'] );
		$this->render('status' , array('status'=>'ok'));	
 	}

 	/** Create a order using the point of sale **/
 	public function create_pos_order(  )
 	{
 		//feature not active
 		die('not allowed');

 		$order = $this->read_input();//actually an invoice
 		
 		if( $order == False || empty($order) )
 		{
 			$this->forbidden();
 		}	

 		$expected = array('order' , 'customer' , 'company' );
 		foreach ($expected as $key ) {
 			if(   ! isset($order[$key]) or ( ! is_array($order[$key])))
 			{
 				
 				$this->forbidden();
 			}	
 				
 		}

 		//check order
 		$expected = array('order_id' , 'items' , 'shipping_amount' , 'status' );
 		foreach ($expected as $key ) {
 			if(   ! isset($order['order'][$key])  )
 				{   $this->forbidden(); }
 		}

 		$this->system->cart->clear();
 		$items = $order['order']['items'];
 		$customer = $order['customer'];

 		foreach(  $items as $item)
 		{
 			$product = $this->product->get(  $item['product_id'] );
 			if( empty($product))
 				$this->error('Non existant product #' . $item['product_id']);
 			//add to cart
 			$this->system->cart->add( $product , $item['qty'] , array() ); //no options
 		}
 		//shopper

 		$shopper = array();
		$shopper['fullname'] = $customer['name'];
		$shopper['email'] = $customer['email'];
		$shopper['phone_number'] = $customer['phone'];
		$shopper['city'] = $this->data['shop']['city'];
		$shopper['country'] = $this->data['shop']['country'];
		$shopper['address'] = $customer['address'];
		//@todo check if user wants to subscribe to this shop's feed
		$this->system->cart->shopper( $shopper );

		$shipping = array();
		$shipping['type'] = 'collect_instore';//all pos orders are collect instore by default
		$shipping['fullname'] = $customer['name'];
		$shipping['phone_number'] = $customer['phone'];
		$shipping['email'] = $customer['email'];
		$shipping['address'] = $customer['address'];
		$shipping['city'] = $this->data['shop']['city'];
		$shipping['country'] = $this->data['shop']['country'];
		//@todo allow shop to specify diferent collection address
		$shipping['collection_address'] = $this->data['shop']['address'];
		//generate random collection code
		$shipping['collection_code'] = substr(  md5(json_encode($shipping) ) , 0 , 7); //6 character collection code
		$this->system->cart->shipping( $shipping );

		//place order
		$order_id = $this->system->cart->place_order($this->data['shop']['shop_id']); 
		if( $order_id == False )
		{
			
			$this->forbidden();
		}
		//@todo send out email and/or sms to customer
		$email = array();
 		$email['type'] = 'order';
 		$email['header'] = 'Your order is waiting';
 		$email['subheader'] = '';
 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $shop['name'] .'<br/>You can complete and change you order at any time and still enjoy the convenience of paying using EcoCash, TeleCash, Visa, ZimSwitch or MasterCard!';
 		$email['action_link'] = OS_BASE_SITE;
 		$email['action_message'] = 'Pay ' .  money($this->system->cart->get_total() );

 		echo $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , array() );
 	
		//@todo add extra info on order specifying that it was placed on the point of sale
		$this->get_order( $order_id );
 	}

 	/** Remove several items from a pending order and notify the customer **/
 	public function remove_items( $order_id )
 	{
 		die('Not allowed');
 	}

 	/** Refund a customer for an order **/
 	public function refund( $order_id )
 	{
 		die('Not allowed');
 	}

 	/** Move an order to archive **/
 	public function move_to_archive( $order_id )
 	{
 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order) or ( $order['shop_id'] != $this->data['shop']['shop_id'] ) )
 		{
 			$this->forbidden();
 		}

 		if( $order['status'] != 'paid' )
 		{
 			return $this->error('You can only archive a paid order, cancelled orders are automatically archived');
 		}

 		$order['status'] = 'archived';
 		$this->system->cart->update_order( $order['order_id'] , $order );

 		$this->render('move_archive' , array('status'=>'ok'));
 	}










}