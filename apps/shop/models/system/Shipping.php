<?php
/**
 * OneShop Shipping Manager.
 *
 * Creates, dispatches and handles responses from different 
 * delivery and logistics providers.
 *
 * The logistics and delivery providers then communicate with 
 * the public gateway.
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @date 		25 July 2015
 * @package 	Models/system/Shipping
 *
 * @todo 		Log all http requests
 */

 class Shipping extends CI_Model
 {

 	/** shipping provider **/
 	private $provider; 


 	public function __construct()
 	{
 		parent::__construct();
 		$this->load->database();
 		$this->load->config('shipping_providers');
 	}

 	/**
 	 * Get pending shop deliveries
 	 *
 	 * @param  		Int 	$ 		Shop ID	
 	 * @param 		String 	$		Filter 
 	 *
 	 * @return 		Array
 	 */
 	public function get_pending_deliveries( $shop_id , $filter = 'all' )
 	{
 		$shop_id = intval($shop_id);
 		$append = in_array($filter ,array('collect_instore' , 'deliver' , 'booking' , 'cash_on_delivery') ) ? " and type = '{$filter}' " : "";
 		$sql = "select * from orders_shipping where order_id in( select order_id from orders where shop_id = {$shop_id} and status = 'paid') {$append} and is_dispatched = false  ORDER BY order_id DESC";

 		$query = $this->db->query( $sql );
 		return $query->result_array();
 	}

 	/**
 	 * Get dispatched shop deliveries
 	 *
 	 * @param  		Int 	$ 		Shop ID	
 	 *
 	 * @return 		Array
 	 */
 	public function get_dispatched_deliveries(  $shop_id )
 	{
 		$shop_id = intval($shop_id);
 		$sql = "select * from orders_shipping where order_id in( select order_id from orders where shop_id = {$shop_id} and status = 'paid') and is_dispatched = true ORDER BY order_id DESC";
 		$query = $this->db->query( $sql );
 		return $query->result_array();
 	}

 	/**
 	 * Get collect instore deliveries that have been collected.
 	 *
 	 * @param 		Int 		$		Shop ID
 	 *
 	 * @return 		Array
 	 */
 	public function get_instore_deliveries(  $shop_id )
 	{
 		$shop_id = intval($shop_id);
 		$sql = "select * from orders_shipping where order_id in( select order_id from orders where shop_id = {$shop_id} and status = 'paid') and is_collected = true and type = 'collect_instore' ORDER BY order_id DESC";
 		$query = $this->db->query( $sql );
 		return $query->result_array();
 	}

 	public function get_instore_pending( $shop_id )
 	{

 	}


 	/**
 	 * Set the provider to perform operations on. Must be valid
 	 *
 	 * You can call  get_providers to get a list of all supporte
 	 * 
 	 * @param 		String 		$provider 		Valid Provider i.e SWIFT-ZW		
 	 *
 	 * @return 		BOOL 		False on invalid provider
 	 */
 	public function set_provider(  $provider  )
 	{
 		if( ! in_array($provider, $this->get_providers()))
 			return False; //invalid provider

 		$this->provider = $provider;
 		return True; 
 	}

 	/**
 	 * Get all shipping delivery providers supported by OneShop
 	 *
 	 * @return 		Array
 	 */
 	public function get_providers( )
 	{
 		return $this->config->item('shipping_providers');
 	}

 	/**
 	 * A package is a contained casing which contains goods to be delivered.
 	 * For example a user orders 3 products. OneShop expects these to be packed into a 
 	 * package (cardboard box ..etc) and the dimensions and weight recorded and other flags set
 	 *
 	 * @param 		Float 		$weight_kg 		Weight KG
 	 * @param 		Int 		$length_cm 		Length Cm 
 	 * @param 		Int 		$width_cm 		Width Cm
 	 * @param 		Int 		$height_cm 		Height Cm
 	 * @param 		String 		$contents_cat 	Contents type ( electronics, stationary ...etc)
 	 * @param 		Bool 		$is_fragile 	Is the contents of the package fragile
 	 * @param 		Bool 		$is_medical 	Is the contents medical instruments or items
 	 * @param 		Bool 		$is_liquid 		Does the packaging contain items liquid in nature
 	 * @param 		String 		$package_type 	Packaging type (cardboard box, plastic wrap...etc)
 	 * @param 		String 		$comments 		Comments regarding the packaging
 	 *
 	 * @return 		Array 	 	Package
 	 */
 	public function create_package( $weight_kg , $length_cm , $width_cm , $height_cm , $contents_category , $is_fragile , $is_medical ,  $is_liquid , $package_type ='cardboard' , $comments = '' )
 	{
 		$package = array();
 		$package['weight'] = floatval($weight_kg);
 		$package['dimensions'] = array('l' => $length_cm , 'w' => $width_cm , 'h' => $height_cm );
 		$package['contents'] = array('fragile' => $is_fragile , 'medical' => $is_medical , 'liquid' => $is_liquid , 'type' => $contents_category );
 		$package['packaging'] = array('type' => $package_type );
 		$package['comments'] = $comments;
 		return $package;
 	}

 	/**
	 * The route is the path (source->destination) which your package will take.
	 * I.e delivering from Bulawayo to Harare. 
	 *
	 * @param 		String 		$src_country 			Source Country default ZW
	 * @param 		String 		$src_city				Source City/Town
	 * @param 		String 		$src_suburb 			Source suburb
	 * @param 		String 		$scr_address			Source address
	 * @param 		String 		$dst_country 			Destination country
	 * @param 		String 		$dst_city 				Destination city or town
	 * @param 		String 		$dst_suburb 			Destination suburb
	 * @param 		Bool 		$dst_address 			Destination address
	 * @param 		String 		$extra_info 			Extra information
	 *
	 * @return 		Array 		Route 
	 */
 	public function create_route( $src_country , $src_city , $src_suburb , $src_address , $dst_country , $dst_city , $dst_suburb , $dst_address , $extra_info = '' )
 	{
 		$route = array();
 		$route['source'] = array('country' => $src_country , 'city'=> $src_city , 'address' => $src_address , 'suburb' => $src_suburb );
 		$route['destination'] = array('country' => $dst_country , 'city' => $dst_city , 'address' => $dst_address , 'suburb' => $dst_suburb );
 		$route['comments'] = $extra_info;
 		return $route;
 	}

 	/**
 	 * Contact information is needed to contact both recpt and sender in several situations.
 	 *
 	 * @param 		String 		$sender_fullname 		Sender Fullname
 	 * @param 		String 		$sender_email 			Sender Email 
 	 * @param 		String 		$sender_phone 			Sender phone number
 	 * @param 		String 		$sender_address 		Sender home.business address 
 	 * @param 		String 		$receiver_fullname 		Rcvr Fullname
 	 * @param 		String 		$receiver_email 		Rcvr email
 	 * @param 		String 		$receiver_phone 		Rcvr phone
 	 * @param 		String 		$receiver_address 		Rcvr address
 	 * @param 		String 		$extra_info 			Extra contact information
 	 *
 	 * @return 		Array 		ContactInfo
 	 */
 	public function create_contacts( $sender_fullname , $sender_email , $sender_phone , $sender_address , $receiver_fullname , $receiver_email , $receiver_phone , $receiver_address , $extra_info = ''  )
 	{
 		$contact = array();
 		$contact['sender'] =  array('fullname' => $sender_fullname , 'email' => $sender_email , 'phone' => $sender_phone , 'address' => $sender_address );
 		$contact['receiver'] = array('fullname' => $receiver_fullname , 'email' => $receiver_email , 'phone' => $receiver_phone , 'address' => $receiver_address );
 		$contact['comments'] = $extra_info;
 		return $contact;
 	}

 	/**
 	 * Security info
 	 *
 	 * @param 		Bool 		$is_strict_delivery 		Is the delivery strict (to be delivered to that person only !)
 	 * @param 		String 		$collection_code 			Collection code 
 	 * @param 		String 		$alt_receiver_phone_number 	Alternative phone number in case of emergency
 	 *
 	 * @return 		Array 		Security
 	 */
 	public function create_security( $is_strict_delivery , $collection_code , $alt_receiver_phone_number  )
 	{
 		$security  = array();
 		$security['strict_delivery'] = $is_strict_delivery;
 		$security['collection_code'] = $collection_code;
 		$security['alt_rcvr_phone_number'] = $alt_receiver_phone_number;
 		
 		return $security;
 	}

 	/**
 	 * The consignment is the actual shipment, containing info about the contents of the
 	 * package, the sender, the receiver , their contact details and security details.
 	 *
 	 * NB: ONCE A CONSIGNMENT IS CREATED IT SHOULD NOT BE EDITTED !
 	 *
 	 * @param 		Array 		$package 		Package Info
 	 * @param 		Array 		$route 			Route Info
 	 * @param 		Array 		$contacts 		Contact Information
 	 * @param 		Array 		$security 		Security details
 	 *
 	 * @return 		Array
 	 */
 	public function create_consignment( $package , $route , $contacts , $security )
 	{
 		$provider = $this->config->item( $this->provider );
 		if(empty($provider))
 		{
 			die('Fatal error occured !' . $this->provider . ' => '. __LINE__ . '==> ' . __FILE__ );
 		}
 		$con = array(); //consignment
 		$con['package'] = $package;
 		$con['route'] = $route;
 		$con['contacts'] = $contacts;
 		$con['security'] = $security;
 		$con['provider'] = $this->provider;
 		//Append security data
 		//For authenticity
 		$con['salt'] = md5( time() );
 		$con['challenge'] = $this->api_hash( $con['salt'] , $provider );
 		

 		return $con;
 	}  

 	/**
 	 * Communicates with the shipping provider and gets a price quotation
 	 *
 	 * @param 		Array 		$consignment 		Consignment
 	 *
 	 * @return 		Array 		Array('amount' => Float , 'info' => String )
 	 */
 	public function get_quotation( $consignment )
 	{
 		$provider = $this->config->item( $this->provider );
 		if(empty($provider))
 		{
 			die('Fatal error occured !' . __LINE__ . '==> ' . __FILE__ );
 		}

 		$api = $provider['api'];
 		$json_text = json_encode($consignment);

 		$data = $this->http_request( $api['quote'] , 'POST' , $json_text );

 		if( empty($data) )
 			return array('status' =>'fail', 'error' => 'Http request failed');

 		$json = json_decode($data , True);
 		if( ! is_array($json) or isset($json['error']) )
 		{
 			if(isset($json['error']))
 				return $json;
 			return array('status' =>'fail', 'error' =>'Provider api is currently down');
 		}

 		return $json;
 	}


 	/**
 	 * Dispatch the consignment. This sends the details to the provider
 	 * and expects a response containing a url to redirect the user to so he can
 	 * preview and pay for the consignment or ask for more information.
 	 *
 	 * After making the payment, the provider has to make an http request to OneShop
 	 * notifying that the user has accepted the provider and paid for the consignment.
 	 * The url is generated internally by oneshop 
 	 *
 	 * @param 		Array 		$consignment 		Consignment
 	 * @param 		Int 		$order_id 			Order id . 
 	 *
 	 * @return 		Array 		Expects array('url' =>'' , 'status' => 'ok')
 	 */
 	public function dispatch_consignment(  $consignment , $order_challenge )
 	{
 		$provider = $this->config->item( $this->provider );
 		if(empty($provider))
 		{
 			die('Fatal error occured !' . __LINE__ . '==> ' . __FILE__ );
 		}

 		$api = $provider['api'];
 		$consignment['order_challenge'] = $order_challenge;

 		$json_text = json_encode($consignment);

 		$data = $this->http_request( $api['dispatch'] , 'POST' , $json_text );

 		if( empty($data) )
 			return array('error' => 'Http request failed');

 		$json = json_decode($data , True);
 		if( ! is_array($json) or isset($json['error']) )
 		{
 			if(isset($json['error']))
 				return $json;
 			return array('error' => 'Provider API is down ');
 		}

 		return $json;
 	}

 	/**
 	 * Generate order hash used to verify that provider callback is valid for the specified order
 	 *
 	 * @param 		Int		$order_id 		Order id
	 *
 	 * @return 		String
  	 */
 	public function hash_order_id($order_id )
 	{
 		return md5(  $order_id . $this->order_hash_salt );
 	}

 	/**
 	 * Generate the api hash to be appended to all requests
 	 *
 	 * @param 		String 		$salt 		Salt
 	 * @param 		Mixed 		$provider 	Provider name or provider
 	 *
 	 * @return 		String 		Returns false on provider not valid
 	 */
 	public function api_hash( $salt , $provider_name )
 	{
 		$provider = is_array($provider_name) ? $provider_name : $this->config->item( $provider_name );
 		if(empty($provider))
 		{
 			return False;
 		}
 		return md5( $salt . $provider['secret']   );

 	}



 	/**
 	 * Handles responses from the provider.
 	 *
 	 * i.e Delivery dispatched, 
 	 *
 	 */
 	public function handle_provider_response( $response )
 	{

 	}

 	/**
 	 * Check if a specified prvider is online.
 	 *
 	 * @param 		String 		$provider 	Provider ,etc SWIFT-ZW 	
 	 *
 	 * @return 		Array 		Expects array('status' => 'ok|down')
 	 */
 	public function check_online( $provider )
 	{
 		$provider = $this->config->item( $provider );
 		if(empty($provider))
 		{
 			die('Fatal error occured !' . __LINE__ . '==> ' . __FILE__ );
 		}

 		$api = $provider['api'];
 		$json_text = json_encode($consignment);

 		$data = $this->http_request( $api['check'] , 'POST' , $json_text );

 		if( empty($data) )
 			return array();

 		$json = json_decode($data , True);
 		if( ! is_array($json) or isset($json['error']) )
 		{
 			if(isset($json['error']))
 				return $json;
 			return array();
 		}

 		return $json;
 	}

 	/**
 	 * Get informationa about a particluar provider.
 	 *
 	 * This information is entirely at the discretion of
 	 * the provider.
 	 *
 	 * @param 		Array 		$provider 		Provider
 	 *
 	 * @return 		Array
 	 */
 	public function get_information(  $provider )
 	{
 		$provider = $this->config->item( $provider );
 		if(empty($provider))
 		{
 			die('Fatal error occured !' . __LINE__ . '==> ' . __FILE__ );
 		}

 		$api = $provider['api'];
 		$json_text = json_encode($consignment);

 		$data = $this->http_request( $api['info'] , 'POST' , $json_text );

 		if( empty($data) )
 			return array();

 		$json = json_decode($data , True);
 		if( ! is_array($json) or isset($json['error']) )
 		{
 			if(isset($json['error']))
 				return $json;
 			return array();
 		}

 		return $json;
 	}

 	/**
 	 * @todo Implement functionality.
 	 *
 	 */
 	public function get_closest_depot( $provider , $lat , $long )
 	{

 	}


 	 /**
	 * Perform HTTP request.
	 * On fail sets an error
	 *
	 * Taken from PayNow Api by Trevor Sibanda
	 *
	 * @param 		String 		$	Url to request
	 * @param 		String 		$ 	Request type ( get , post )
	 * @param 		String 		$	Data to Post. Ignored if HTTP request
	 *
	 * @return 		Mixed 		$	Http Response data 
	 */
	protected function http_request( $url , $method = 'POST' ,   $post_data = Null )
	{
		
		if( function_exists('curl_init') )
		{

			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url );
			if( $method == 'POST')
			{
				$post_data = $this->urlify($post_data);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
			}
			if( $method == 'GET')
				curl_setopt($ch, CURLOPT_POST, false);

			if( $this->_use_proxy )
			{
				//socks proxy
				curl_setopt($ch, CURLOPT_PROXY, $this->_proxy);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				

			}
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			//execute post
			$result = curl_exec($ch);

			curl_close($ch);

			return $result;
		}
		else
		{
			$data = $this->rest_helper($url, $post_data , $method , '' );	
		}
		
		return $data;
	}


	/**
	 * Process the shipping rules to find conflicts
	 * and resolve them.
	 *
	 * The rules are collected from the shop shipping 
	 * settings.
	 *
	 * @param 		Mixed 		$json		Rules array 		
	 *
	 * @return 		Array 		New shipping rules
	 */
	public function process_rules(  $rules   )
	{
		$new_rules = array();
		if( ! is_array($rules))
			return $new_rules;

		foreach( $rules as $rule )
		{
			$expected = array('type' , 'high' , 'low' , 'price' , 'conditions' );
			$b = true;
			foreach($expected as $key )
				if( ! isset($rule[$key]))
					$b = false;
			if( ! $b )
				continue;

			//check 
			if( $rule['low'] > $rule['high']  )
				continue;
			//check rule type
			if( ! in_array($rule['type'] , array('price' , 'weight') ) )
				continue;
			//@todo check conditions

			array_push( $new_rules , $rule );	
		}
		return $new_rules;
	}

	/**
	 * Calculate the price of an order given the 
	 * shipping settings. i.e Shipping rules, handling fee
	 * and combined weight /price of order.
	 *
	 * @param 		Array 		$shipping_settings 		Shipping settings
	 * @param 		Float 		$total_weight 			Total weight, optional if using price based
	 * @param 		Float 		$total_price 			Total price, optional if using weight based
	 *
	 *
	 * @note 		A rule is structured as array('type' => 'weight|price' , 'low' =>Float , 'high' => Float , price => Float , 'condition' => array() )
	 *				For example to charge $1,00 for orders below $10
	 *					array('type'=>'price','low' => 0,'high'=>10,price=>1.00,'condition'=>array())
	 *				To give free shipping for orders above 50Kg
	 *					array('type'=>'weight', 'low'=>50,'high'=>99999,price=>0.00,'condition'=>array())
	 *				To charge $5 on all orders below $100 and in the same city
	 *					array(type=>price,low=>0.00,high=>100,price=>5.00,'condition'=>'same_city')
	 *
	 * 				Other valid conditions are  'same_city'=>to be delivered in same city,
	 *										    'same_country'=>delivered to different country
	 *				The handling fee will be added to the total cost.	
	 *												 
	 *
	 * @return 		Float 		Price 
	 */
	public function calculate( $shipping_settings , $shipping , $shop , $total_weight = 0.00 , $total_price = 0.00 )
	{
		$cost = 0;
		if( ! is_array($shipping_settings))
			return False;
		$cost += $shipping_settings['handling_fee']; 
		if( $shipping_settings['use_shipping_rules'] == False )
		{
			if(  $shipping['city'] == $shop['city'] && $shipping['country'] == $shop['country'] )
				$cost += $shipping_settings['intracity_fee'];
			else
				$cost += $shipping_settings['intercity_fee'];

			return $cost;
		}

		if(  is_array($shipping_settings['rules']) )
		foreach(  $shipping_settings['rules'] as $rule )
		{
			//check if in range
			$value = ($rule['type'] == 'weight') ? $total_weight : $total_price;
			//rule must be in range
			if( $value > $rule['high'] or $value < $rule['low'] )
				continue;

			$conditions = array();
			//extra conditions
			if( is_array($rule['conditions']))
			
			foreach(  $rule['conditions'] as $condition )
			{
				switch($condition )
				{
					case 'same_city':
					{
						$b = ( $shipping['city'] == $shop['city']);
						array_push($conditions , $b );	
					}
					break;
					case 'same_country':
					{
						$b = ( $shipping['country'] == $shop['country']);
						array_push($conditions , $b );
					}
					break;
					//@todo add more conditions
				}
			}
			//if all conditions are true add to array
			$do_add = True;
			foreach($conditions as $b)
			{
				if(!$b)
					$do_add = False;
			}
			//all conditions passed
			if( $do_add )
				$cost += $rule['price'];

		}	

		return $cost;
	}

	/**
	 * Rest helper
	 *
	 * @param 		String 	$url 		Url
	 * @param 		Mixed 	$params 	Parameters, string or array
	 * @param 		String 	$verb 		HTTP VERB
	 * @param 		String 	$format 	Format
	 *
	 * @return 		Mixed 	$			Depending on format, returns array, object or string. returns False on Fail
	 */
	function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
	{
	  $cparams = array(
	    'http' => array(
	      'method' => $verb,
	      'ignore_errors' => true
	    )
	  );
	  if ($params !== null) {
	    if( ! is_string($params))
	    	$params = http_build_query($params);
	    if ($verb == 'POST') {
	      $cparams['http']['content'] = $params;
	    } else {
	      $url .= '?' . $params;
	    }
	  }

	  $context = stream_context_create($cparams);
	  $fp = @fopen($url, 'rb', false, $context);
	  if (!$fp) {
	    $res = false;
	  } else {
	    // If you're trying to troubleshoot problems, try uncommenting the
	    // next two lines; it will show you the HTTP response headers across
	    // all the redirects:
	    // $meta = stream_get_meta_data($fp);
	    // var_dump($meta['wrapper_data']);
	    $res = stream_get_contents($fp);
	  }

	  if ($res === false) {
	    return False;
	  }

	  switch ($format) {
	    case 'json':
	      $r = json_decode($res);
	      if ($r === null) {
	        return $r;
	      }
	      return $r;

	    case 'xml':
	      $r = simplexml_load_string($res);
	      if ($r === null) {
	        return $r;
	      }
	      return $r;
	  }
	  return $res;
	}

 	


 } 
