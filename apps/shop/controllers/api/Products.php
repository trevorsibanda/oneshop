<?php
/**
 * OneSHOP Admin API. Products
 *
 * This is the api used for the admin interface products
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Currently the API only works on 
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Products extends OS_AdminController
{
	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

 		
 	}

 	/** Get a product **/
 	public function get(  $product_id )
 	{
 		if( empty($product_id) or is_null($product_id))
 			$this->forbidden();

 		$product = $this->product->get( $product_id , True , $this->data['shop']['shop_id'] );
 		if( empty($product)  )
 		{
 			$this->forbidden();
 			return;
 		}
 		//get images

 		$product['images'] = $this->product->image->get_images( $product['images'] );
 		$product['file'] = ( $product['file_id'] != -1 ) ? $this->product->file->get( $product['file_id'] ) : array();
 		
 		$this->render('product' , $product );
 	}


 	/** Get all products within the specified constraints **/
 	public function all( )
 	{
 		
 		$products = $this->product->shop_products(  $this->data['shop']['shop_id'] );
 		//images
 		foreach($products as $key => $product )
 		{
 			$products[ $key ]['images'] = $this->product->image->get_images( $product['images'] );
 			$products[ $key ]['file'] = ( $product['file_id'] != -1 ) ? $this->product->file->get( $product['file_id'] ) : array();
 		}
 		//make them 500
 		/* Debug - Supports 500 products on desktop, poorly on mobile
 		for( $i = count($products) ; $i < 500; ++$i)
 		{
 			array_push($products, $products[ rand()%count($products)]);
 		}*/
 		$this->render('products' , $products );
 	}

 	/** Get a shop's categories **/
 	public function categories( )
 	{
 		
 		$categories = $this->product->category->shop_categories( $this->data['shop']['shop_id'] );
 		foreach( $categories as $key => $cat )
 		{
 			$categories[ $key ]['image'] = $this->product->image->get(  $cat['image_id'] );
 		}
 		$this->render('shop_categories' , $categories );
 	}

 	/** Get a single product category **/
 	public function get_category( $category_id = Null )
 	{
 		if(empty($category_id) or is_null($category_id))
 			$this->forbidden();
 		$cat = $this->product->category->get(  $category_id );
 		if( empty($cat) or ( $this->data['shop']['shop_id'] != $cat['shop_id'] ))
 			$this->forbidden();
 		$cat['image'] = $this->product->image->get(  $cat['image_id'] );
 		$this->render('category' , $cat );
 	}

 	/** Add a product **/
 	public function add( )
 	{
 		
 		$product = $this->read_input();

 		
 		if( empty($product) or $product === False)
 		{
 			$this->error('You have an error in your request. Json object expected');
 		}

 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}

 		$product_count = $this->product->count_shop_products(  $this->data['shop']['shop_id'] );
 		if( $product_count >= account_can('max_products') )
 		{
 			$this->error('Sorry you cannot add any more products, upgrade your account to add more products ');
 		}

 		$expected = array( 'name' , 'type' , 'category_id' ,  'attributes' , 'description' , 'price' , 'brand' , 'stock_left' , 'min_orders' , 'max_orders' , 'file_id' , 'images' ,'weight_kg');
 		//cheack price
 		if( ! isset($product['price'])  or ( $product['price'] == 0.00 ) )
 			$product['price'] = -1.00;
 		if( $product['price'] == -1.00  )
 			$this->error('You did not specify a valid price for the product');

 		if( ! isset($product['stock_left']) )
 			$product['stock_left'] = 0;

 		$excused = array('files' , 'price', 'file_id','stock_left');

 		if( isset($product['weight_kg']) && $product['type'] == 'virtual')
 			array_push($excused, 'weight_kg');

 		foreach($expected as $key )
 		{
 			if( ! isset($product[$key]) )
 			{

 				$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}
 			if( !in_array($key,  $excused) and empty($product[$key])  )
 			{
 				if( $key != 'attributes')
 					$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}
 			//if is in list of arrays and is array
 			if( in_array($key, array('images' , 'attributes' , 'files','attributes')) and ( !is_array($product[$key]) ) )
 			{
 				$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}

 			//xss clean values
 			if( $key != 'description' )
 				$product[$key] = clean( $product[$key] );

 			

 		}
 		if(  ! in_array($product['type'], array('physical' , 'virtual')) )
 			$this->error('You must specify product type');
 		
 		$product['description'] = $this->system->safe_html(   $product['description'] );

 		$images = array();
 		$tags = array();
 		//check all images and put ids
 		foreach(  $product['images'] as $image )
 		{
 			if( ! isset($image['image_id']) )
 				continue;
 			$image = $this->product->image->get( $image['image_id'] );
 			if( empty($image))
 				continue;
 			if( $image['shop_id'] != $this->data['shop']['shop_id'] )
 				continue;
 			array_push( $images , $image['image_id'] );
 		}
 		$images = array_unique($images);

 		//fix tags
 		if( is_array($product['tags']) )
 		{
 			foreach( $product['tags'] as $tag )
 			{
 				if( is_string($tag))
 					array_push( $tags , clean($tag) );
 				else if( is_array($tag) and isset($tag['text']))
 					array_push($tags, clean($tag['text']) );
 			}
 		}
 		else if( is_string($product['tags']))
 		{
 			$tags = explode(',' , $tags );
 			foreach( $tags as $k => $v )
 			{
 				$tags[ $k ] = clean( $v );
 			}
 		}

 		$product['name'] = clean( $product['name'] );
 		$product['brand'] = clean( $product['brand']  );
 		$product['weight_kg'] = floatval( $product['weight_kg']);
 		
 		//fix min and max items per order
 		$product['max_orders'] = ( $product['max_orders'] >= 0 ) ? $product['max_orders'] : 1;
 		$product['min_orders'] = ( $product['min_orders'] >= 0 ) ? $product['min_orders'] : 1;
 		if( $product['min_orders'] > $product['max_orders']  )
 		{
 			$tmp = $product['min_orders'];
 			$product['min_orders'] = $product['max_orders'];
 			$product['max_orders'] = $tmp;
 		}

 		//fix stock left
 		$product['stock_left'] = ( $product['stock_left'] >= 0 ) ? $product['stock_left'] : 0;

 		//add file if virtual
 		$file = null;
 		if( $product['type'] == 'virtual' )
 		{
 			$file = $this->product->file->get( $product['file_id'] );
 			if( empty($file) or $file['shop_id'] != $this->data['shop']['shop_id'] )
 			{
 				$this->error('You want to create a virtual product but did not specify a file ');
 			}
 		}


 		$product_id = $this->product->add( $this->data['shop']['shop_id'] , $product['name'] , $product['brand'] , $product['price'] , $images , $product['category_id'] , $product['description'] , $product['stock_left'] , $tags );
 		if( $product_id === False )
 		{
 			$this->error('Failed to add the product ! Please try again.');
 		}
 		//add attributes
 		foreach($product['attributes'] as $attr )
 		{
 			if ( isset($attr['attribute_name']) && isset($attr['attribute_value']) && isset( $attr['is_customize'] ) && isset($attr['options']) && is_array($attr['options']) )
 			{
 				//fix attribute options
 				$options = array();
 				foreach( $attr['options'] as $option )
 				{
 					if( isset($option['value']))
 						array_push($options, clean($option['value']) );
 				}
 				$options = array_unique($options);
 				$attr['is_customize'] = intval($attr['is_customize'] );
 				$this->product->add_attribute( $product_id , clean( $attr['attribute_name'] ) , clean($attr['attribute_value']) , $attr['is_customize'] , $options );
 			}

 		}
 		$newproduct = $this->product->get(  $product_id );
 		//if no featured products add featured product
 		$newproduct['is_featured'] = ( $this->product->count_featured( $newproduct['shop_id'] ) ? $product['is_featured'] : True );
 		$newproduct['max_orders'] = intval($product['max_orders']);
 		$newproduct['min_orders'] = intval($product['min_orders']);
 		$newproduct['stock_left'] = intval(  $product['stock_left'] );
 		$newproduct['type'] = in_array($product['type'], array('virtual','physical')) ? $product['type'] : 'physical';
 		$newproduct['weight_kg'] = floatval( $product['weight_kg']);
 		if(  $newproduct['type'] == 'virtual' )
	 		$newproduct['file_id'] = $product['file_id'];



 		$this->product->update( $newproduct['product_id'] , $newproduct );

 		$this->render('product' , $newproduct );
 	}

 	/** Add a product category **/
 	public function add_category(  )
 	{
 		$category = $this->read_input();
 		if( $category == False || empty($category))
 			$this->forbidden('You have an error in your request');

 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}
 		

 		$expected = array('image_id' , 'name' , 'description' , 'is_menu' , 'parent_id' );

 		foreach( $expected as $key )
 		{
 			if( ! isset($category[$key]))
 				$this->error('You have an error in your post data. Missing attribute ' . $key );
 			if( $key != 'description')
 				$category[ $key ] = clean( $category[$key] );
 		}

 		$category['name'] = clean( $category['name']);

 		$category['is_menu'] = (  $category['is_menu'] == 1 ) ? true: false;
	 	$category['description'] = $this->system->safe_html(  $category['description'] );
	 	$cat_id =	$this->product->category->add( $this->data['shop']['shop_id'] , $category['name'] , $category['description'] ,$category['image_id'] ,$category['is_menu'] );
	 	

	 	if( $cat_id == False or $cat_id == -1 )
	 	{
	 		$this->error('An internal server error occured. Failed to add category');
	 	}
	 	$this->shop->logger->action('Created product category - ' . $category['name'] );
	 	$category = $this->product->category->get( $cat_id );
	 	$this->render('category' , $category );
 	}

 	/** Delete a product **/
 	public function delete( $product_id )
 	{
 		
 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}

 		$product = $this->product->get( $product_id );
 		if( empty($product) or ( $product['shop_id'] != $this->data['shop']['shop_id'] ))
 			$this->forbidden('You have an error in your request');

 		//get all pending orders
 		$where_ordered = $this->system->cart->get_where_ordered( $this->data['shop']['shop_id'] , $product['product_id'] );
 		$pending_orders = array();
 		//save product json for all other order items in order
 		//this means that even if he product is deleted we can still
 		//view the product and what exactly was ordered.
 		//@todo move this to cart or some model

 		$product['is_deleted'] = True;//show that this order has been deleted.
 		//get all product images
 		$product['images'] = $this->product->image->get_images( $product['images'] );
 			
 		$json = json_encode($product);
 		$this->db->where('product_id' , $product['product_id'] )->update('order_items' , array('product_json' => $json ));

 		foreach( $where_ordered as $order  )
 		{
 			
 			if( $order['status'] === 'pending' )
 				array_push($pending_orders, $order );

 		}
 		$affected_orders = 0;
 		if( ! empty($pending_orders) )
 		{
 			//if there are pending orders, delete the orders.
 			//a possible problem could arise when the user pays at the
 			//same time the order is been deleted :(
 			foreach( $pending_orders as $order )
 			{
 				//delete order.
 				$shipping = $this->system->cart->get_order_shipping( $order['order_id'] );
 				$items = $this->system->cart->get_order_items( $order_id );
 				$this->system->cart->remove_order( $order['order_id'] );
 				$affected_orders += 1;
 				//log order deletion
 				$this->shop->logger->action("Deleted order #{$order['order_id']} placed by {$shipping['fullname']} and totalling \${$order['total']} because it contained a product which was deleted. {$product['name']}  -  {$product['brand']}  - \${$product['price']} ");
				

 				//restore stock for each item which was ordered
		 		foreach(  $items as $item )
		 		{
		 			if( ! empty( $item['product_json']))
		 				continue; //deleted product, this should never happen
		 			//add stock back
		 			$product = $this->product->get( $item['product_id'] );
		 			if( ! empty($product) )
		 			{
		 				$this->product->add_stock( $product , $item['qty'] );
		 			}
		 		}

 				//send the user a notification
 				$email = array();
		 		$email['type'] = 'warning';
		 		$email['header'] = 'Your order has been cancelled';

		 		$email['subheader'] = '';
		 		$email['message'] = 'Hello ' . $shipping['fullname'] . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/> We are no longer selling some products which were in your order and have cancelled your order to reflect this change. Just visit our shop and choose from our available products and add them to your cart and proceed to checkout. We are terribly sorry for the inconvience, we hope to see you soon! ';
		 		$email['action_link'] = $this->data['shop']['url'];
		 		$email['action_message'] = 'Restart your order ';

		 		$sms =  $this->data['shop']['name'] . "\nIMPORTANT: Your order has been cancelled.\nVisit " . $this->data['shop']['url'] . ' to restart your order. ';
		 		$cart = array();
		 		
		 		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , $this->data['products'] , $cart );
		 		//push email
		 		$this->system->pushnotification->push_email( $order['shop_id'] , $shipping['fullname'] , $shipping['email'], 'IMPORTANT: Your order has been cancelled', $html );
		 		//push sms
		 		if( account() != 'free')
		 			$this->system->pushnotification->push_sms( $order['shop_id'] ,  $shipping['phone_number'] , $sms ,   9 ); //send asap to prevent someone paying for a cancelled order!

 			}
 		}

 		if( $affected_orders )
 		{
 			//@todo add shop notification explaining deleted orders.

 		}

 		//remove product along with all the all attributes.
 		$this->product->remove_product( $product['product_id'] , True );
 		$this->shop->logger->product('Deleted product ' . $product['name'] . ' - ' . $product['brand'] . ' - ' . money($product['price']));
 		
 		//check if there are any featured products left.
 		if(  $this->product->count_featured( $this->data['shop']['shop_id'] )  == 0 )
 		{
 			//choose a random product and set it as featured.
 			$this->product->feature_random( $this->data['shop']['shop_id']) ;
 		}
 		//@todo other checks

 		$this->render('delete_product' , array('success' => 'Deleted product'));
 	}

 	/** Delete a product category **/
 	public function delete_category( $category_id )
 	{
 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}
 	}

 	/** Update a product **/
 	public function update(   )
 	{
 		$product = $this->read_input();

 		$do_cancel_orders = False;
 		
 		if( empty($product) or $product === False)
 		{
 			$this->error('You have an error in your request. Json object expected');
 		}

 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}

 		$expected = array( 'product_id' , 'name' , 'type' , 'category_id' , 'attributes' , 'description' , 'price' , 'brand' , /*'stock_left' ,*/ 'min_orders' , 'max_orders' , 'file_id' , 'images');

 		if( isset( $product['type']) && $product['type'] == 'physical')
 			array_push($expected, 'weight_kg');
 		
 		//check price
 		if( ! isset($product['price'])  or ( $product['price'] == 0.00 ) )
 			$product['price'] = -1.00;
 		if( $product['price'] == -1.00  )
 			$this->error('You did not specify a valid price for the product');

 		foreach($expected as $key )
 		{
 			if( ! isset($product[$key]) )
 			{
 				if( $key != 'attributes')
 					$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}
 			if( !in_array($key, array('files' , 'price', 'file_id' , 'attributes')) and empty($product[$key])  )
 			{
 				
 				$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}
 			//if is in list of arrays and is array
 			if( in_array($key, array('images' , 'attributes' , 'files')) and ( !is_array($product[$key]) ) )
 			{
 				$this->error('An expected key is missing. ' . $key . ':' . __LINE__ );
 			}

 			//xss clean values
 			if( $key != 'description' )
 				$product[$key] = clean( $product[$key] );
 			
 		}

 		$curr_product = $this->product->get(  $product['product_id'] );


 		if( empty($curr_product) or $curr_product['shop_id'] !== $this->data['shop']['shop_id'] )
 		{
 			$this->error('Specified product does not exist');
 		}

 		


 		if(  ! in_array($product['type'], array('physical' , 'virtual')) )
 			$this->error('You must specify product type');
 		//@todo clean description for dangerous html and php flags

 		$images = array();
 		$tags = array();
 		//check all images and put ids
 		foreach(  $product['images'] as $image )
 		{
 			if( ! isset($image['image_id']) )
 				continue;
 			$image = $this->product->image->get( $image['image_id'] );
 			if( empty($image))
 				continue;
 			if( $image['shop_id'] != $this->data['shop']['shop_id'] )
 				continue;
 			array_push( $images , $image['image_id'] );
 		}
 		$images = array_unique($images);

 		//fix tags
 		if( is_array($product['tags']) )
 		{
 			foreach( $product['tags'] as $tag )
 			{
 				if( is_string($tag))
 					array_push( $tags , xss_clean($tag) );
 				else if( is_array($tag) and isset($tag['text']))
 					array_push($tags, clean($tag['text']) );
 			}
 		}
 		else if( is_string($product['tags']))
 		{
 			$tags = explode(',' , $tags );
 		}

 		if(empty($images) )
 			$this->error('You must specify at least one image');

 		if( empty( $tags))
 			$this->error('You must specify at least one tag');


 		$product['images'] = $images;
 		$product['tags'] = $tags;
 		unset(  $product['file'] );

 		$static_vals = array('views','shares','last_viewed','stock_sold','stock_ordered','stock_left' , 'shop_id' );
 		foreach( $static_vals as $val )
 		{
 			unset( $product[$val]);
 		}


 		$product['name'] = clean( $product['name'] );
 		$product['brand'] = clean( $product['brand']  );
 		$product['weight_kg'] = floatval( $product['weight_kg']);
 		$product['price'] = make_money( $product['price'] );
 		
 		//fix min and max items per order
 		$product['max_orders'] = ( $product['max_orders'] >= 0 ) ? $product['max_orders'] : 1;
 		$product['min_orders'] = ( $product['min_orders'] >= 0 ) ? $product['min_orders'] : 1;
 		if( $product['min_orders'] > $product['max_orders']  )
 		{
 			$tmp = $product['min_orders'];
 			$product['min_orders'] = $product['max_orders'];
 			$product['max_orders'] = $tmp;
 		}

 		
 		//remove dangerous html tags.
 		$product['description'] = $this->system->safe_html(  $product['description'] );

 		//add file if virtual
 		$file = null;
 		if( $product['type'] == 'virtual' )
 		{
 			$file = $this->product->file->get( $product['file_id'] );
 			if( empty($file) or $file['shop_id'] != $this->data['shop']['shop_id'] )
 			{
 				$this->error('You want to create a virtual product but did not specify a file ');
 			}
 		}

 		//remove all attributes
 		$attribute_ids = array();
		foreach( $curr_product['attributes'] as $attr )
		{
			array_push( $attribute_ids , $attr['attribute_id'] );
		}


		//@todo enforce max attributes
 		//add attributes
 		foreach($product['attributes'] as $attr )
 		{
 			$do_update = False;
 			if( isset($attr['attribute_id']))
 				$do_update = in_array( $attr['attribute_id'] , $attribute_ids );

 			if ( isset($attr['attribute_name']) && isset($attr['attribute_value']) && isset( $attr['is_customize'] ) && isset($attr['options']) && is_array($attr['options']) )
 			{

 				//fix attribute options
 				$options = array();
 				foreach( $attr['options'] as $option )
 				{
 					if( isset($option['value']) && is_string($option['value']) && ! empty($option['value']) )
 						array_push($options, clean($option['value']) );
 				}
 				$options = array_unique($options);
 				$attr['is_customize'] = intval($attr['is_customize'] );
 				if( ! $do_update )
 				{
	 				$this->product->add_attribute( $product['product_id'] , clean( $attr['attribute_name'] ) , clean($attr['attribute_value']) , $attr['is_customize'] , $options );	
 				}else
 				{
 					//update existing
 					$base_attr = array( 'product_id' => $product['product_id'] , 'attribute_name' => $attr['attribute_name'] , 'attribute_value' => $attr['attribute_value'] , 'is_customize' => $attr['is_customize'] );
 					$this->product->update_attribute( $attr['attribute_id'] , $base_attr  );

 					

 					//delete current options
 					$this->product->remove_attribute_options(  $attr['attribute_id'] );
 					//update options
 					foreach( $options as $opt )
 					{
 						//option does not exist
 						$this->product->add_attribute_option( $attr['attribute_id'] , $opt );
 						
 					}
 					//done :)
 				}
 			}

 		}
 		unset( $product['attributes'] );

 		if( $curr_product['price'] !== $product['price'] )
 			$do_cancel_orders = True;

 		//if no featured products add featured product
 		$product['is_featured'] = ( $this->product->count_featured( $this->data['shop']['shop_id'] ) ? $product['is_featured'] : True );
 		$product['max_orders'] = intval($product['max_orders']);
 		$product['min_orders'] = intval($product['min_orders']);
 		//$product['stock_left'] = intval(  $product['stock_left'] );
 		$product['type'] = in_array($product['type'], array('virtual','physical')) ? $product['type'] : 'physical';
 		$product['weight_kg'] = floatval( $product['weight_kg'] );
 		
 		$this->product->update( $product['product_id'] , $product );

 		//if( $do_cancel_orders )
 		
 		$this->render('product' , $product );

 	}

 	/** Get the number of orders that the product is ordered in **/
 	public function count_ordered( $product_id )
 	{
 		if( empty($product_id) or is_null($product_id))
 			$this->forbidden('You have an error in your request');

 		$product = $this->product->get( $product_id );
 		if( empty($product) or ( $product['shop_id'] != $this->data['shop']['shop_id'] ))
 			$this->forbidden('Product does not exist');

 		$result = array();
 		$result['count'] = $this->system->cart->is_ordered( $this->data['shop']['shop_id'] ,  $product_id );
 		$this->render('is_ordered' , $result );
 	}

 	public function set_stock( $product_id , $new_stock_count )
 	{
 		if( empty($product_id) or is_null($product_id))
 			$this->forbidden('You have an error in your request');

 		if( ! has_permission('manage_products') )
 		{
 			$this->error('You do not have permission to manage products');
 		}

 		$product = $this->product->get( $product_id );
 		if( empty($product) or ( $product['shop_id'] != $this->data['shop']['shop_id'] ))
 			$this->forbidden('Product does not exist');

 		//update stock value.
 		$past = $product['stock_left'];
 		$product['stock_left'] = intval($new_stock_count );
 		if( $product['stock_left'] < 0 )
 			$product['stock_left'] = 0;

 		$this->shop->logger->product('Updated product ' . $product['name'] . ' stock from ' . $past . ' to ' . $product['stock_left'] );

 		$this->product->update( $product['product_id'] , $product );

 		$this->render('', array('status'=>'ok'));
 	}






}