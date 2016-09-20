<?php
/**
 * Shopping cart.
 *
 * This is an extended shopping cart which utilises the database and allows
 * for users to continue their shopping experience if the user has already provided their
 * details for shipping,etc. 
 * It also mamages database orders, allowing for continued orders and accessing data during
 * checkouts.
 *
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @date 		28 May 2015
 * @package 	Models/Shopping_cart
 *
 * @todo Implement different storgae systems
 */

class Shopping_cart extends CI_Model
{
	/** Cart **/
	private $_cart_data;

	/** Shopping cart **/
	private $_shop_domain;

	/** Storage to use **/
	private $_storage = 'session';

	/** sHIPPING DETAILS **/
	private $_shipping ;

	/** Shopper details **/
	private $_shopper;

	/** Empty shipping options **/
	private $_empty_shipping_options = array(
			'type' => 'collect_instore', //other valid option is deliver
			'fullname' => '' ,  //fullname of person to receive or collect
			'phone_number' => '' , //phone number (i.e in international mode)
			'address' => '' ,  //address to ship to
			'suburb' => '' ,//suburb
			'city' => '' ,//city to deliver to
			'country' => '' , //country to deliver to
			'collection_address' => '' ,//address goods will be collected from ( by default should be shop's address)
			'collection_ready_time' => '' , //time when the collection of goods will be ready
			'alt_phone_number' => '' , //alternative phone number
			'alt_address' => '' , //alternative address
			'collection_code' => ''//collection code to be presented with order nummber at collection
			/** is_collected , is_notification_sent , is_dispatched added in db **/ 
		);


	/** CTor **/
	public function __construct(  )
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('php_session');
		//requires product
		$this->load->model('product/Product' , 'product');
		$this->_shop_domain = defined('SHOP_DOMAIN') ? SHOP_DOMAIN : SHOP_SUBDOMAIN;
		$this->_cart_data = Null;
		//load
		$this->_load_cart();
	}

	/**
	 * Retrieve an order from db along with products and shipping options
	 *
	 * @param 		Int 		$	Order Number
	 * @param 		Int 		$	[optional] Shop ID
	 *
	 * @return 		Array 		$	array('order' => array() , 'shipping' => array() , 'products' => array() )
	 */
	public function get_order( $order_id , $shop_id = Null )
	{
		$this->db->select('*')
				 ->from('orders')
				 ->where('order_id' , $order_id );
		if( ! is_null($shop_id))
			$this->db->where('shop_id' , $shop_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	

	/**
	 * Retrieve an order from db then load it into the current cart
	 *
	 * @param 		Int 		$	Order Number
	 * @param 		Array 		$	Shopper
	 *
	 * @todo 		Support shopper info
	 *
	 * @return 		Bool
	 */
	public function load_order_into_cart(  $order_id  , $shopper = array() )
	{

		$order = $this->get_order( $order_id );
		if( empty($order) )
			return False;
		$this->clear();
		$items = $this->get_order_items( $order_id );
		$shipping = $this->get_order_shipping( $order_id );

		//load into cart
		foreach( $items as $item)
		{
			
			$options = json_decode( $item['attributes_json'] , True );
			$options = is_array($options) ? $options : False;
			$product = $this->product->get( $item['product_id'] );
			if( empty($product) )
			{
				$product = json_decode($item['product_json']);
			}
			if( empty($item['product_json']) )
			{
				//get images
				$product['images'] = $this->product->image->get_images( $product['images'] );
			}
			unset( $product['description'] );
			$this->add( $product , $item['qty'] , $options  );	
		}

		//set shipping details
		if( empty($shopper) )
		{
			$shopper['fullname'] = $shipping['fullname'];
			$shopper['email'] = $shipping['email'];
			$shopper['phone_number'] = $shipping['phone_number'];
			$shopper['city'] = $shipping['city'];
			$shopper['country'] = $shipping['country'];
			$shopper['address'] = $shipping['address'];	
		}
		$this->system->cart->shopper( $shopper );

		$this->system->cart->shipping( $shipping );
		$this->_cart_data['is_ordered'] = True;
		$this->_cart_data['order_id'] = $order_id;
		$this->_cart_data['order_number'] = '#' . $order_id;
		return True;
	}

	/**
	 * Retrieve an order given the transaction id
	 *
	 * @param 		Int 		$ 	Transaction id
	 *
	 * @return 		Array
	 */
	public function get_order_by_transaction_id(  $transaction_id , $shop_id = Null )
	{
		$this->db->select('*')
				 ->from('orders')
				 ->where('transaction_id' , $transaction_id );
		if( ! is_null($shop_id))
			$this->db->where('shop_id' , $shop_id);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Check if an order is still valid.
	 *
	 * This works by checking the prices of all the goods
	 * It checks the prices of the old goos as defined in the order
	 * and compares them with product details in the database.
	 *
	 * If a product's price has changed and the payment has not being made
	 * the function returns FALSE and the order can be cancelled or a warning shown
	 * depending on the shop's settings.
	 *
	 * @param 		Int 		$	Order ID
	 *
	 * @return 		Bool
	 */
	public function check_valid_order( $order_id )
	{

	}

	/**
	 * Get an order item
	 *
	 * @param 		Int 		$	Order ID
	 * @param 		Int 		$	Product ID
	 *
	 * @return 		Array 		$	Order item, empty on fail
	 */
	public function get_order_item( $order_id , $product_id )
	{
		$query =	$this->db->select('*')
				 		 ->from('order_items')
				 		 ->where('order_id' , $order_id )
				 		 ->where('product_id' , $order_id)
				 		 ->get();
		return $query->row_array();		 		 
	}

	/**
	 * Get shop orders.
	 *
	 * @param 		Int 	$	Shop ID
	 * @param 		String 	$	Filter by status ( pending , paid , delivered , cancelled  , refunded , archived )
	 * @param 		Int 	$	Maximum results, default is 20
	 * @param 		Int 	$	Offset default is 0
	 * @param 		String 	$	Order by. default is 'DESC' ,can be 'ASC'
	 *
	 * @return 		Array
	 */
	public function get_shop_orders( $shop_id , $filter = 'all' ,  $max = 20 , $offset = 0 , $order_by = 'DESC' )
	{
		if(! in_array( $order_by , array('DESC' , 'ASC')) )
			$order_by = 'DESC';
		if( $max <= 0)
			$max = 999;
		if( $offset <= 0 )
			$offset = 0;		
		$this->db->from('orders')->where('shop_id' , $shop_id)->order_by('order_id' , $order_by )->limit( $max )->offset($offset);
		if( $filter != 'all')
		{
			if(  in_array($filter, array('paid' , 'pending' , 'cancelled' , 'archived' )))
				$this->db->where('status' , $filter );
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	

	/**
	 * Check if a product is ordered anywhere in the shop.
	 *
	 * For example, before editting the product the user
	 * must be made aware of this.
	 * 
	 * @param 		Int 	$	Shop ID
	 * @param 		Int 	$	Product ID
	 *
	 * @return 		Int 	$	Number of active orders
	 */
	public function is_ordered(  $shop_id , $product_id  )
	{
		$orders = $this->get_shop_orders( $shop_id , 'pending' );
		$count = 0;
		foreach( $orders as $order )
		{
			$order_items = $this->get_order_items(  $order['order_id'] );
			foreach( $order_items as $item )
			{
				if( $item['product_id'] == $product_id )
				{
					$count++;
					break; //a prodcuct cant exists twice in an order
				}
			} 
		}
		return $count;
	}

	/**
	 * Get all orders where a product is ordered in.
	 *
	 * @param 		Int 	$	Shop ID
	 * @param 		Int 	$	Product ID
	 * @param 		String 	$	Type ( pending, paid, cancelled, delivered ...etc)
	 *
	 * @return 		Array 
	 *
	 */
	public function get_where_ordered( $shop_id , $product_id  )
	{
		$order_items = array();
		$query = $this->db->where('shop_id' , $shop_id )
				 	->where('product_id' , $product_id )
				 	->from('order_items')
				 	->select('order_id')
				 	->get();
		$orders = $query->result_array();
		$this->db->select('*')->from('orders');
		foreach($orders as $order_id )
		{
			$this->db->or_where('order_id' , $order_id['order_id']);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	/**
	 * Get items for a particular order
	 *
	 * @param 		Int 		$	Order ID
	 *
	 * @return 		Array 		$	Order item, empty on fail
	 */
	public function get_order_items( $order_id  )
	{
		$query =	$this->db->select('*')
				 		 ->from('order_items')
				 		 ->where('order_id' , $order_id )
				 		 ->get();
		return $query->result_array();		 		 
	}


	/**
	 * Get order shipping options
	 *
	 * @param 		Int 		$	Order ID
	 *
	 * @return 		Array 		$	Shipping details, empty on fail
	 */
	public function get_order_shipping( $order_id )
	{
		$this->db->select('*')
				 ->from('orders_shipping')
				 ->where( 'order_id' , $order_id );
		$query = $this->db->get();
		return $query->row_array();		 
	}

	/**
	 * Get order shipping options
	 *
	 * @param 		String 		$	Challenge code
	 *
	 * @return 		Array 		$	Shipping details, empty on fail
	 */
	public function get_order_shipping_by_challenge( $challenge )
	{
		$this->db->select('*')
				 ->from('orders_shipping')
				 ->where( 'challenge' , $challenge );
		$query = $this->db->get();
		return $query->row_array();	
	}

	/**
	 * Update an order
	 *
	 * @param 		Int 	$ 	Order ID
	 * @param 		Array 	$	Order 
	 *
	 * @todo		Add more error checking
	 *
	 * @return 		Bool
	 */
	public function update_order( $order_id , $order )
	{
		unset( $order['order_id']);
		unset( $order['shop_id']);
		unset(  $order['v_code'] );

		unset( $order['shipping'] );
		unset( $order['transaction']);
		unset( $order['shopper'] );
		unset( $order['items'] );

		$this->db->where('order_id' , $order_id )
				 ->update('orders' , $order );

		return True;		 
	}

	/**
	 * Update an order item
	 *
	 * @param 		Int 	$	Order ID
	 * @param 		Int 	$	Product ID
	 * @param 		Array 	$	Order item
	 *
	 * @return 		Bool
	 */
	public function update_order_item( $order_id , $product_id , $order_item )
	{

	}

	/**
	 * Update order shipping 
	 *
	 * @param 		Int 	$	Order ID
	 * @param 		Array 	$	Shipping
	 *
	 * @return 		Bool
	 */
	public function update_order_shipping( $order_id , $shipping )
	{
		unset( $shipping['challenge'] );//cant change challenge
		$this->db->where ('order_id' , $order_id )
				 ->update( 'orders_shipping'  , $shipping );
		return True;		 
	}
	/**
	 * Add an item to an order.
	 *
	 * @param 		Int 	$	Order ID
	 * @param 		Int 	$	Shop ID
	 * @param 		Int 	$	Product ID
	 * @param 		Int 	$ 	Quantity
	 * @param 		Float 	$	Price per item
	 * @param 		Float 	$	Total Cost ( Quantity * Price per item)
	 * @param 		Array 	$	Attributes
	 *
	 *
	 * @return 		Bool
	 */
	public function add_order_item( $order_id , $shop_id , $product_id , $quantity , $price_per_item , $price , $attributes )
	{
		$order_item = array();
		$order_item['order_id'] = $order_id;
		$order_item['shop_id'] = $shop_id;
		$order_item['product_id'] = $product_id;
		$order_item['qty'] = $quantity;
		$order_item['price_per_item'] = $price_per_item;
		$order_item['price'] = $price;
		$order_item['attributes_json'] = json_encode($attributes);
		$this->db->insert('order_items' , $order_item);
		return True;
	}

	/**
	 * Add an order's shipping details
	 *
	 * @param 		Int 		$	Order ID
	 * @param 		Array 		$ 	Shpping details
	 *
	 * @see _empty_shipping_options
	 *
	 * @return 		Bool
	 */
	public function add_order_shipping(  $order_id ,  $shipping_options )
	{
		if( ! is_array($shipping_options) )
			return False;
		$shipping_options['order_id'] = $order_id;
		$shipping_options['challenge'] = md5( time() );
		$this->db->insert( 'orders_shipping' , $shipping_options );
		return True;
	}

	/**
	 * Completely remove an order including order_items and shipping options
	 *
	 * @param 		Int 	$ 	Order ID
	 *
	 * @return 		Bool
	 */
	public function remove_order( $order_id )
	{
		foreach( array('order_items' , 'orders' , 'orders_shipping') as $table )
		{
			$this->db->where('order_id' , $order_id)
				 ->from( $table )
				 ->delete();	
		}
		return True;		 
	}

	/**
	 * Remove an item already added to an order
	 *
	 * @param 		Int 	$	Order ID
	 * @param 		Int 	$	Product ID
	 *
	 * @return 		Bool
	 */
	public function remove_order_item( $order_id , $product_id )
	{
		$this->db->where('order_id' , $order_id)
			 	 ->where('product_id' , $product_id)
			 	 ->delete('order_items');
		return True;	 	 
	}

	/**
	 * Get all items in the shopping cart.
	 *
	 * @see Shopping_Cart::get()
	 *
	 * @return 		Array 	$	Shopping cart items
	 */
	public function items()
	{
		
		return $this->_cart_data['items'];
	}

	/**
	 * Count the number of items in the shopping cart
	 *
	 * @return 		Int
	 */
	public function count_items()
	{
		$count = 0;
		foreach( $this->_cart_data['items'] as $item)
			$count += $item['qty'];
		return $count;
	}

	/**
	 * Add a product to the shoppping cart
	 *
	 * @param 		Array 	$	Valid product
	 * @param 		Int 	$	Number of items
	 * @param 		Array 	$ 	Customizations 
	 *
	 * @return 		Array
	 */
	public function add( $product , $items , $options  )
	{

		$item = array();
		$item['product'] = $product;
		$item['product_id'] = $product['product_id'];
		$item['options'] = $options;
		$item['cost'] = $product['price'];
		$item['subtotal'] = ( $item['cost'] * $items );
		$item['qty'] = $items;
		//schweet!
		//if already exists. will just update
		foreach(  $this->_cart_data['items'] as $key=>$_item )
		{
			if( $_item['product_id'] === $product['product_id'] )
			{
				$this->_cart_data['items'][$key] = $item;
				$this->_save_cart();
				return $item;
			}
		}
		array_push( $this->_cart_data['items'] , $item );
		$this->_save_cart();

		return $item;
	}

	/**
	 * Verify if an order exists given the verification code.
	 *
	 * @param 		Int 		$	Shop ID
	 * @param 		String 		$	Md5 Hash
	 *
	 * @return 		Mixed 		$	False or Order Id
	 */
	public function verify_order_code( $shop_id , $md5_hash )
	{
		$query = $this->db->where('v_code' , $md5_hash )
				 ->where('shop_id' , $shop_id )
				 ->from('orders')
				 ->select('order_id')
				 ->get();
		$row = $query->row_array();
		return (isset($row['order_id']) ? $row['order_id'] : False );		 
	}

	/**
	 * Get an order id exists given the verification code.
	 *
	 * @param 		String 		$	Md5 Hash
	 *
	 * @return 		Mixed 		$	False or Order Id
	 */
	public function get_order_by_collection_code( $collection_code )
	{
		$query = $this->db->where('collection_code' , $collection_code )
				 ->from('orders_shipping')
				 ->select('order_id')
				 ->get();
		$row = $query->row_array();
		return (isset($row['order_id']) ? $row['order_id'] : False );
	}

	/**
	 * Set the current shopping cart as an order
	 * 
	 *
	 *
	 *
	 *
	 */
	public function place_order( $shop_id )
	{
		if( empty($this->_cart_data['items'])  or empty($this->_cart_data['shopper'])  )
			return False;
		//check shopper
		$shopper = $this->shopper();
		$must_be_set = array('fullname' , 'phone_number' , 'city' , 'country' , 'address'  );
		foreach( $must_be_set as $key )
			if( ! isset($shopper[$key]))
				return False;
		//check Order items
		$items = $this->items();
		$must_be_set = array('product_id' , 'qty' , 'cost' , 'subtotal' , 'options' , 'product');
		foreach( $items as $item )
		{
			foreach( $must_be_set as $key )
			{
				if( ! isset($item[$key]))
					return False;
			}
		}
		//check shipping details
		$shipping = $this->shipping();
		if( empty($shipping) )
		{
			//assume collect instore
			$shipping = array();
			$shipping['type'] = 'collect_instore';
		}
		//ship to shopper's details
		//@todo 	This might be removed in future to allow for shipping to different address
		foreach( array('fullname' , 'phone_number' , 'email' , 'address' , 'city' , 'country' ) as $key )
		{
			$shipping[ $key ] = $shopper[ $key ];
		}

		$order = array();
		$order['shopper_id'] = ( isset($shopper['shopper_id']) ? $shopper['shopper_id'] : -1 );
		$order['shop_id'] = $shop_id;
		$order['is_registered'] = ( $order['shopper_id'] > 0 ) ? True : False;  
		$order['status'] = 'pending';
		$order['date_created'] = date('Y-m-d');
		//expire after 7 days by default
		$order['expire_date'] = date('Y-m-d', strtotime("+7 days"));

		//get total
		$order['total'] = $this->get_total();
		$order['v_code'] = md5(  json_encode($order ) . time() );
		$this->db->insert('orders' , $order );
		$order_id = $this->db->insert_id();
		if( $order_id <= 0 )
			return False;
		//added to db 
		$shipping['order_id'] = $order_id;

		//now add to shops
		foreach( $items as $item )
		{
			$this->add_order_item( $order_id , $shop_id , $item['product_id'] , $item['qty'] , $item['cost'] , $item['subtotal'] , $item['options'] );
			//for each item added it must be removed from stock
			
			$this->product->order_item( $item['product'] , $item['qty'] );
		}
		//awesome. now add shipping options
		$this->add_order_shipping( $order_id ,  $shipping );
		//set as ordered
		$this->_cart_data['is_ordered'] = True;
		$this->_cart_data['order_id'] = $order_id;
		$this->_cart_data['order_number'] = '#' . $order_id;
		return $order_id;

	}

	public function is_cart_ordered( )
	{
		return (isset($this->_cart_data['is_ordered']) ? $this->_cart_data['is_ordered'] : False );
	}

	public function order_number()
	{
		return ( isset($this->_cart_data['order_number']) ? $this->_cart_data['order_number'] : Null );
	}

	public function order_id()
	{
		return ( isset($this->_cart_data['order_id']) ? $this->_cart_data['order_id'] : Null );
	}

	/**
	 * Settter/Getter shopper
	 *
	 * Create a shopper object and place the details to the shopping cart.
	 *
	 * The shopper contains details which are necessary to fulfill the order.
	 * 
	 *
	 * @param 		Array 		$	Shopper
	 *
	 * @return 		Array
	 */
	public function shopper( $shopper = Null )
	{
		if( ! is_null($shopper) )
			$this->_cart_data['shopper'] = $shopper;
		return $this->_cart_data['shopper'];
	}


	/**
	 * Get a product in the shopping cart
	 *
	 * array( 'product_id' => '' , 'product' => array() , 'options' => array() , 'cost' => 0.000 , 'items' => 0 , 'subtotal' => 0.00  )
	 *
	 * @param 	Int 	$	Product ID
	 *
	 * @return 	Array 	$ 	Shopping cart item
	 */
	public function get( $product_id )
	{
		if( ! is_array($this->_cart_data['items']))
		{
			$this->clear();
		}
		foreach(  $this->_cart_data['items'] as $item )
		{
			if( $item['product_id'] == $product_id )
			{
				return $item;
			}
		}
		return Null;
	}

	/** Update **/
	public function update(  $item )
	{
		foreach( $this->_cart_data['items'] as $key => $val )
		{
			if( $item['product_id'] == $val['product_id'] )
			{
				$this->_cart_data['items'][ $key ] = $item;
				$this->_save_cart();
				return;
			}
		}
	}

	/**
	 * Remove a product from the cart.
	 *
	 * @param 		Int 	$ 		Product ID
	 *
	 * @return 		Bool
	 */
	public function remove( $product_id )
	{
		$new_cart = array();
		foreach(  $this->_cart_data['items'] as $key=>$item )
		{
			if( $item['product_id'] != $product_id )
			{
				array_push($new_cart, $item);
			}
		}
		$this->_cart_data['items'] = $new_cart;

		$this->_save_cart();
		
		return True;
	}

	/**
	 * Get total amount to be charged.
	 * This is the accumulative subtotals
	 *
	 * @return 		Float
	 */
	public function get_total( )
	{
		$total = 0.00;
		foreach(  $this->_cart_data['items'] as $item )
		{
			$total += $item['subtotal'];
		}
		return $total;
	}

	/**
	 * Get a product's subtotal
	 *
	 * @param 	Int 	$ 	Product ID
	 *
	 * @return 	Float
	 */
	public function get_subtotal( $product_id )
	{
		$item = $this->get($product_id);
		return is_null( $item ) ? 0.00 : $item['subtotal'];
	}

	/**
	 * Set order options
	 *
	 * @param 		String 	$	Option
	 * @param 		String 	$ 	Value
	 *
	 * @return 		None
	 */
	public function set_order_option( $option , $value )
	{
		//check if option exists
		foreach( $this->_cart_data['order']  as $key => $val )
		{
			if( $option === $key )
			{
				$this->_cart_data['order'][ $key ] = $value;
				$this->_save_cart();
				return;
			}
		}
		//add
		$this->_cart_data['order'][$option] = $value;
		$this->_save_cart();
	}

	/**
	 * Get an order option
	 *
	 * @param 	String 		$ 	Option
	 *
	 * @return 	Mixed
	 */
	public function order_option( $option )
	{
		foreach( $this->_cart_data['order']  as $key => $val )
		{
			if( $option === $key )
			{
				return $val;
			}
		}
		return Null;
	}

	public function order( $order = Null )
	{
		if( ! is_null($order))
			$this->_cart_data['order'] = $order;
		return $this->_cart_data['order'];
	}


	/**
	 * Set shopping options.
	 *
	 * @param 		Array 		$	Shipping options. @see get_empty_shipping_options
	 */
	public function shipping( $shipping = Null )
	{
		if( ! is_null($shipping))
			$this->_cart_data['shipping'] = $shipping;
		return $this->_cart_data['shipping'];
	}

	/**
	 * Clear the shopping cart
	 *
	 */
	public function clear()
	{
		$this->_cart_data = array('items' => array() , 'order' => array() , 'shopper' => array() , 'shipping' => array() );
		$this->_shipping = array();
		$this->_shopper = array();
	}

	/**
	 * Internal function. Used for loading shopping cart data
	 *
	 */
	private function _load_cart( )
	{
		$this->_cart_data = $this->php_session->get( 'shopping_cart' );
		if( is_null($this->_cart_data ) )
		{
			$this->clear();
		}
	}

	/**
	 * Internal function. Save the shopping cart data
	 *
	 */
	private function _save_cart( )
	{
		
		//print_r(  $this->_cart_data );
		$this->php_session->set( 'shopping_cart' , $this->_cart_data );

	}

	public function __destruct()
	{

		$this->_save_cart();
	}


}

