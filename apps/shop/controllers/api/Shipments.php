<?php
/**
 * OneSHOP Admin Shipping Api
 *
 * Handle all shipping related requests
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Currently the API only works on 
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Shipments extends OS_AdminController
{
	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

		$this->system->load_shipping();
 		
 	}


 	/** Get all shipments that fit a specified criteria **/
 	public function all( $filter )
 	{

 		$deliveries = $this->system->shipping->get_pending_deliveries( $this->data['shop']['shop_id'] , $filter );

 		$this->render('deliveries' , $deliveries );
 	}

 	/** Given a shipping tracker code, get information from the provider **/
 	public function get(  $tracker_code )
 	{

 	}
 	
 	/** Get summary of all pending deliveries **/
 	public function pending_summary(  )
 	{
 		$this->system->load_shipping();

 		$result = array('deliver' => 0,'booking' => 0 , 'collect_instore' => 0 , 'cash_on_delivery' => 0);

 		$pending = $this->system->shipping->get_pending_deliveries( $this->data['shop']['shop_id'] );
 		foreach( $pending as $shipping )
 		{
 			$result[ $shipping['type'] ] += 1;
 		}

 		$this->render('summary' , $result);
 	}

 	/** Get a quotation for an order given the order and the consignment **/
 	public function quotations( $order_id )
 	{
 		$this->system->load_shipping();

 		$consignment = array();

 		if( isset($_POST['consignment']) )
 		{
 			$consignment = json_decode($_POST['consignment'] , True);
 			if( empty($consignment) or $consignment == false)
 				$this->forbidden();
 		}
 		else
 		{
 			$consignment = $this->read_input();
 			if( empty($consignment) or $consignment == false)
 				$this->forbidden();
 		}


 		//get order
 		if( is_null($order_id ) )
 			$this->forbidden();

 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order))
 		{
 			$this->error('Order does not exist!');
 			return;
 		}
 		if(  $this->data['shop']['shop_id'] !== $order['shop_id'] )
 		{
 			$this->forbidden();
 		}
 		$order['items'] = $this->system->cart->get_order_items( $order_id );

 		//items (product names) to send to prvider
 		$items = array();

 		//change attribute options from string
 		foreach ($order['items'] as $key => $item) 
 		{
 			$product = $this->product->get( $item['product_id'] );
 			array_push($items, $product['name']);
 		}

 		$order['shipping'] = $this->system->cart->get_order_shipping( $order_id );
 		//$order['transaction'] = $this->payment->transaction->get(  $order['transaction_id'] );

 		//@todo check if proper consignment object.
 		//best way to do this is to create a consignment using these details
 		$expected = array('security','package','route','contacts');
 		foreach ($expected as $key )
 		{
 			if(! isset( $consignment[$key] ))
 			{
 				$this->error('Incomplete input');
 				return;
 			}	
 		}

 		$json = $consignment;

 		$packg = $this->system->shipping->create_package( $json['package']['weight'] ,
 		 												 $json['package']['dimensions']['l'] , 
 		 												 $json['package']['dimensions']['w'] , 
 		 												 $json['package']['dimensions']['h'] , 
 		 												 $json['package']['contents']['type'] , 
 		 												 $json['package']['contents']['fragile'] , 
 		 												 $json['package']['contents']['medical'] , 
 		 												 $json['package']['contents']['liquid'] , 
 		 												 $json['package']['packaging']['type'] );
 		$route = $this->system->shipping->create_route( $json['route']['source']['country'] ,
 		 												$json['route']['source']['city'] ,
 		 												$json['route']['source']['suburb'] ,
 		 												$json['route']['source']['address'],
 		 												$json['route']['destination']['country'],
 		 												$json['route']['destination']['city'] ,
 		 												$json['route']['destination']['suburb'] ,
 		 												$json['route']['destination']['address'] );

 		//@todo generate contact info from order
 		$cont = $this->system->shipping->create_contacts( $json['contacts']['sender']['fullname'],
 														  $json['contacts']['sender']['email'],
 														  $json['contacts']['sender']['phone'],
 														  $json['contacts']['sender']['address'],
 														  $json['contacts']['receiver']['fullname'],
 														  $json['contacts']['receiver']['email'],
 														  $json['contacts']['receiver']['phone'] ,
 														  $json['contacts']['receiver']['address']);
 		
 		$secu = $this->system->shipping->create_security($json['security']['strict_delivery'],
 														 $json['security']['collection_code'] ,
 														 $json['security']['alt_rcvr_phone_number']);

 		
 		//add items to consignment
 		
 		//cycle through providers and get responses
 		$responses = array();
 		foreach( $this->system->shipping->get_providers() as $provider_name )
 		{
 			$this->system->shipping->set_provider( $provider_name );
 			$consignment = $this->system->shipping->create_consignment($packg , $route , $cont , $secu );
 			$consignment['items'] = $items;
 			$response = $this->system->shipping->get_quotation( $consignment );
 			
 			if( $response['status'] == 'fail' )
 			{
 				//does not handle this path ?
 				continue;
 			}
 			if( ! isset($response['error']))
 			{
 				array_push($responses, $response );	
 			}
 			
 		}

 		$this->render('responses' , $responses );

 	}

 	/** Dispatch an order for delivery given the consignment **/
 	public function dispatch( $order_id = Null )
 	{

 		$this->system->load_shipping();

 		$consignment = array();

 		if( isset($_POST['consignment']) )
 		{
 			$consignment = json_decode($_POST['consignment'] , True);
 			if( empty($consignment) or $consignment == false)
 				$this->forbidden();
 		}
 		else
 		{
 			$consignment = $this->read_input();
 			if( empty($consignment) or $consignment == false)
 				$this->forbidden();
 		}


 		//get order
 		if( is_null($order_id ) )
 			$this->forbidden();

 		$order = $this->system->cart->get_order( $order_id );
 		if( empty($order))
 		{
 			$this->error('Order does not exist!');
 			return;
 		}
 		if(  $this->data['shop']['shop_id'] !== $order['shop_id'] )
 		{
 			$this->forbidden();
 		}
 		$order['items'] = $this->system->cart->get_order_items( $order_id );

 		//items (product names) to send to prvider
 		$items = array();

 		//change attribute options from string
 		foreach ($order['items'] as $key => $item) 
 		{
 			$product = $this->product->get( $item['product_id'] );
 			array_push($items, $product['name']);
 		}

 		$order['shipping'] = $this->system->cart->get_order_shipping( $order_id );
 		//$order['transaction'] = $this->payment->transaction->get(  $order['transaction_id'] );



 		$provider = isset( $consignment['provider'] ) ? $consignment['provider'] : Null;

 		if( is_null($provider) or ! is_string($provider))
 		{
 			$this->error('Provider not specified');
 			return;
 		}

 		//is valid provider
 		if( ! in_array($provider, $this->system->shipping->get_providers()))
 		{
 			
 			$this->error('Provider does not exist');
 			return;
 		}

 		$this->system->shipping->set_provider($provider);

 		//@todo check if proper consignment object.
 		//best way to do this is to create a consignment using these details
 		$expected = array('security','package','route','contacts');
 		foreach ($expected as $key )
 		{
 			if(! isset( $consignment[$key] ))
 			{
 				$this->error('Incomplete input');
 				return;
 			}	
 		}

 		$json = $consignment;

 		$packg = $this->system->shipping->create_package( $json['package']['weight'] ,
 		 												 $json['package']['dimensions']['l'] , 
 		 												 $json['package']['dimensions']['w'] , 
 		 												 $json['package']['dimensions']['h'] , 
 		 												 $json['package']['contents']['type'] , 
 		 												 $json['package']['contents']['fragile'] , 
 		 												 $json['package']['contents']['medical'] , 
 		 												 $json['package']['contents']['liquid'] , 
 		 												 $json['package']['packaging']['type'] );
 		$route = $this->system->shipping->create_route( $json['route']['source']['country'] ,
 		 												$json['route']['source']['city'] ,
 		 												$json['route']['source']['suburb'] ,
 		 												$json['route']['source']['address'],
 		 												$json['route']['destination']['country'],
 		 												$json['route']['destination']['city'] ,
 		 												$json['route']['destination']['suburb'] ,
 		 												$json['route']['destination']['address'] );

 		//@todo generate contact info from order
 		$cont = $this->system->shipping->create_contacts( $json['contacts']['sender']['fullname'],
 														  $json['contacts']['sender']['email'],
 														  $json['contacts']['sender']['phone'],
 														  $json['contacts']['sender']['address'],
 														  $json['contacts']['receiver']['fullname'],
 														  $json['contacts']['receiver']['email'],
 														  $json['contacts']['receiver']['phone'] ,
 														  $json['contacts']['receiver']['address']);
 		
 		$secu = $this->system->shipping->create_security($json['security']['strict_delivery'],
 														 $json['security']['collection_code'] ,
 														 $json['security']['alt_rcvr_phone_number']);

 		$consignment = $this->system->shipping->create_consignment($packg , $route , $cont , $secu );

 		//add items to consignment
 		$consignment['items'] = $items;

 		//dispatch http request
 		$response = $this->system->shipping->dispatch_consignment( $consignment , $order['shipping']['challenge'] );
 		
 		//check for any errors
 		if( ! isset($response['error']))
 		{
 			//we redirect user to the providers website
 			if( isset($response['html']))
 				echo $response['html'];
 			else
 				header('Location:  ' . $response['url'] );
 			return;
 		}
 		else
 		{
 			//@todo we must render the html showing the error
 		}
 	}

 	public function demo( )
 	{
 		$shop = $this->data['shop'];
 		$shipping = $this->system->cart->get_order_shipping(6);
 		$shipping['country'] = $shop['country'] = 'ZW';
 		$settings = array('handling_fee' => 0.00 , 'rules' => array());
 		$settings['rules'] = array( 
 			array('type'=>'price', 'low'=>10.00, 'high'=> 50.00 ,'price'=>2.00, 'conditions'=>array('same_country')) ,
 			array('type'=>'weight', 'low'=>0.00, 'high'=> 5.00 ,'price'=>0.00, 'conditions'=>array()) ,
 			array('type'=>'price', 'low'=>50.00, 'high'=> 100.00 ,'price'=>2.00, 'conditions'=>array()));
 		
 		$total_weight  = 100.00;
 		$total_price = 15.00;
 		echo "You are supposed to pay $" . $this->system->shipping->calculate($settings, $shipping , $shop,$total_weight , $total_price );
 	}

 	/** Set an order as shipped **/
 	public function set_shipped( $order_id = Null )
 	{
 		if( is_null($order_id))
 			$this->forbidden();
 		$order = $this->system->cart->get_order( $order_id );


 		if( empty($order))
 		{
 			$this->error('Order does not exist!');
 			return;
 		}
 		if(  $this->data['shop']['shop_id'] !== $order['shop_id'] )
 		{
 			$this->forbidden();
 		}

 		if( ! in_array($order['status'], array('paid','archived') ) )
 		{
 			$this->error('This order has not been paid for.');
 			return;
 		}

 		$shipping = $this->system->cart->get_order_shipping(  $order_id );
 		$shipping['is_ready'] = $shipping['is_dispatched'] = True;

 		$this->system->cart->update_order_shipping($order_id , $shipping );
 	}



}
