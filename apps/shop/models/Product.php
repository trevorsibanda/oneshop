<?php
/**
 * Product CRUD operations
 *
 *
 * @author		Trevor Sibanda <trevorsibb@gmail.com>
 *
 * @date			8 May 2015
 *
 */
 
 
 class Product extends CI_Model
 {
	 
	 private $empty_product = array
	 (
		'product_id' => array() ,
		'name' => array() ,
		'price' => array() ,
		'is_featured' => array() ,
		'min_orders' => array() ,
		'max_orders' => array(),
		'brand' => array() ,
		'description' => array() ,
		'stock_left' => array() ,
		'shop_id' => array() ,
		'stock_ordered' => array() ,
		'stock_sold' => array()
	 );
	 
	 private $empty_attribute = array
	 (
		'attribute_id' => array() ,
		'attribute_name' => array() ,
		'attribute_value' => array() ,
		'is_customize' => array() ,
		'is_filter' => array() ,	 
	);
	 
	 /** Ctor */
	 public function __construct()
	 {
		 parent::__construct();
		 $this->load->database();

		 //load other models
		 //to be accessed as (in controller) $this->product->image->get
		 $this->load->scope_model($this ,'product/product_image' , 'image');

		 $this->load->scope_model( $this ,  'product/Product_category'  , 'category' );
		 $this->load->scope_model( $this , 'product/Product_file' , 'file');
		 $this->load->scope_model( $this , 'product/Product_search' , 'search');
	 }

	 
	 
	 /**
	  * Get a product
	  *
	  * @param		Int		$	Product ID
	  * @param		Bool	$	Get a product ID
	  * @param 		Int 	$	Shop ID. Ignored if Null 	  
	  *
	  * @return		Array	$	Empty on fail
	  */
	 public function get(  $product_id , $get_attributes = True , $shop_id = Null )
	 {
		 $this->db->select( '*' )
				  ->from('product')
				  ->where('product_id' , $product_id );
		 if( ! is_null($shop_id) )
		 {
		 	$this->db->where('shop_id' , $shop_id );
		 }		  
		 $query = $this->db->get();
		 if( $query->num_rows() <= 0 )
			return array();
		 $row = $query->row_array();
		 //explode images
		 $row['images'] = explode(',', $row['images']);
		 //explode tags
		 $row['tags'] = explode(',', $row['tags']);
		 if( $get_attributes )
			$row['attributes'] = $this->get_attributes( $product_id );
		 return $row;
	 }

	 
	 /**
	  * Get all shop products
	  *
	  * @param		Int		$	Shop ID
	  * @param		Bool	$	Get product attributes
	  *
	  * @return		Array
	  */
	 public function shop_products( $shop_id  ,  $get_attributes = True)
	 {
		 $this->db->select( '*' )
				  ->from('product')
				  ->where('shop_id' , $shop_id )
				  ->order_by('product_id' , 'DESC');
		 $query = $this->db->get();
		if( $query->num_rows() <= 0 )
			return array();

		$results = $query->result_array();
		
		foreach( $results  as $key => $row )
		{
		 	//explode images
		 	$row['images'] = explode(',', $row['images']);
		 	//explode tags
		 	$row['tags'] = explode(',', $row['tags']);
			if( $get_attributes )
			{
				$row['attributes'] = $this->get_attributes( $row['product_id'] );
			}
			$results[ $key ] = $row;
		}
		
		 return $results;		 
	 }

	 /**
	  * Count all shop products
	  *
	  * @param		Int		$	Shop ID
	  *
	  * @return		Array
	  */
	 public function count_shop_products( $shop_id  )
	 {
		 $this->db
				  ->from('product')
				  ->where('shop_id' , $shop_id );
		return $this->db->count_all_results();		 
	 }
	 
	  /**
	  * Get featured shop products
	  *
	  * @param		Int		$	Shop ID
	  * @param		Int		$	Product max Count
	  * @param		Bool	$	Get attributes
	  *
	  * @return		Array
	  */
	 public function featured_shop_products( $shop_id  , $count = 5 ,  $get_attributes = True )
	 {
		 
		 $this->db->select( '*' )
				  ->from('product')
				  ->where('shop_id' , $shop_id )
				  ->where('is_featured' , True )
				  ->limit( $count )
				  ->order_by('rand()');
		 $query = $this->db->get();
		if( $query->num_rows() <= 0 )
			return array();

		$results = $query->result_array();
		if( $get_attributes )
		{
		 foreach( $results  as $key => $row )
		 {
		 	//explode images
		 	$row['images'] = explode(',', $row['images']);
		 	//explode tags
		 	$row['tags'] = explode(',', $row['tags']);
			$row['attributes'] = $this->get_attributes( $row['product_id'] );
			$results[ $key ] = $row;
		 }
		}
		 return $results;		 
	 }

	 /**
	  * Count the number of stock left in a shop.
	  *
	  * @param 		Int 	$	Shop ID
	  *
	  * @return 	Int
	  */
	 public function count_shop_stock( $shop_id )
	 {
	 	$query = $this->db->select('stock_left')->from('product')->where('shop_id' , $shop_id )->get();
	 	$results = $query->result_array();
	 	$count = 0;
	 	foreach(  $results as $result )
	 	{
	 		$count += $result['stock_left'];
	 	}
	 	return $count;

	 }


	 
	 
	 /**
	  * Get a product ID
	  *
	  * @param		Int		$	Product ID
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_attribute( $attribute_id )
	 {
		 $this->db->select( $this->full_query_string($this->empty_attribute) )
				  ->from('product_attribute')
				  ->where('attribute_id' , $product_id );
		 $query = $this->db->get();
		 if( $query->num_rows() <= 0 )
			return array();
		 $row = $query->row_array();
		 //now get options
		 if( isset($row['attribute_id']) )
		 {
		 	$row['options'] = $this->get_attribute_options( $row['attribute_id'] );
		 }
		 return $row;		 
	 }
	 
	 /**
	  * Get all attributes for a particular product
	  * 
	  * @param		Int		$	Product ID
	  *
	  * @return		Array	$	Empty array on fail
	  */
	 public function get_attributes( $product_id )
	 {
		 $this->db->select( '*' )
				  ->from('product_attribute')
				  ->where('product_id' , $product_id );
		 $query = $this->db->get();
		 if( $query->num_rows() <= 0 )
			return array();
		 $results = $query->result_array();
		 if( ! empty($results) )
			 foreach( $results as $key=>$value )
			 {
			 	$results[ $key ]['options'] = $this->get_attribute_options( $value['attribute_id'] );
			 }
		 return $results;
	 }


	 /**
	  * Check if an option is a valid attribute option
	  *
	  * @param 		Int 		$	Attribute ID
	  * @param 		String 		$ 	Attribute option
	  *
	  * @return 	Bool
	  */
	 public function is_attribute_option( $attribute_id , $value )
	 {
	 	$this->db->select('')
	 	         ->from('product_attribute_option')
	 	         ->where('value' , $value)
	 	         ->where('attribute_id' , $attribute_id);
	 	$query = $this->db->get();
	 	if( $query->num_rows() )
	 		return True;
	 	return False;         
	 }


	 
	 /**
	  * Get all category ID's from a product.
	  *
	  * The product categories are stored as a semicolon seperated list. 
	  * This is a helper function 
	  *
	  * @param		Array	$	Product
	  *
	  *	@return		Array
	  */
	 public function get_attribute_options( $attribute_id )
	 {
		 $query = $this->db->where('attribute_id' , $attribute_id)
		  				   ->from('product_attribute_option')
		  				   ->get();
		 return $query->result_array(); 				   
	 }
	 
	 /**
	  * Add more stock to the current stock count
	  *
	  * @param		Array	$	Product
	  * @param		Int		$	Quantity
	  *
	  * @return		Int		$ 	New stock count
	  */
	 public function add_stock( $product , $quantity )
	 {
		if( ! $this->is_valid($product) )
			return 0;
		$product['stock_left'] += $quantity;
		$this->update( $product['product_id'] , $product );
		return $product['stock_left'];
	 }
	 
	 
	 /**
	  * Order an item
	  *
	  * @param		Array	$	Product
	  * @param		Int		$	Quantity
	  *
	  * @return		Int		$ 	New stock count. 
	  */
	 public function order_item( $product , $quantity  = 1 )
	 {
		if( ! $this->is_valid($product) )
			return 0;
		$product['stock_left'] -= $quantity;
		$product['stock_ordered'] += $quantity;
		$this->update( $product['product_id']  , $product );
		return $product['stock_left'];
	 }	 
	 
	 /**
	  * Remove stock
	  *
	  * @param		Array	$	Product
	  * @param		Int		$	Quantity
	  *
	  * @return		Int		$ 	New stock count
	  */
	 public function remove_stock( $product , $quantity = 1  )
	 {
		$quantity *= -1; 
		return $this->add_stock(  $product , $quantity );		 
	 }
	 
	 /**
	  * Add more stock to the current stock count
	  *
	  * @param		Int		$	Product ID
	  * @param		Array	$	Product
	  *
	  * @return		Int		$ 	New stock count
	  */
	 public function update( $product_id , $product )
	 {
		//if( ! $this->is_valid( $product ) ) 
		//	return False;
		//unset dangerous keys
		unset( $product['file'] );
		if( isset($product['product_id'] ) )
			unset( $product['product_id'] );
		if( isset($product['shop_id'] ) )
			unset( $product['shop_id'] );

		if( isset($product['tags']) and is_array($product['tags']))
			$product['tags'] = implode(',', $product['tags']);

		//unset attributes
		unset( $product['attributes'] );
		//unset images
		$images = $product['images'];
		$new_images = array(); 
		if(is_array($images))
		foreach( $images as $image )
		{
			if( is_numeric($image))
			{
				array_push($new_images, $image );
				continue;
			}
			else
			{
				if( isset( $image['image_id']) )
					array_push($new_images, $image['image_id'] );
			}
		}
		unset( $product['images'] );
		unset($images );
		$product['images'] = implode(',', $new_images );
		
		$this->db->where ('product_id' , $product_id )
				 ->update( 'product'  , $product );
		
		return True; 
	 }
	 
	 /**
	  * Add more stock to the current stock count
	  *
	  * @param		Int		$	Shop ID
	  * @param		String	$	Product name e.g 2 Litres Mazoe Orange Crush 
	  * @param		String	$	Brand name	e.g  Mazoe
	  * @param		String	$	Price
	  * @param 		Array 	$	Image ID's array of id's
	  * @param 		Int 	$	Product category ID
	  * @param		String	$	Product Description
	  * @param		Int		$	Stock Count, default is one
	  * @param 		Array 	$	Tags Array (mazoe,drink,orange...)
	  *
	  * @return		Int		$ 	Product ID or False on fail
	  */
	 public function add( $shop_id , $name , $brand , $price , $images , $category_id , $description , $stock_count  = 1 , $tags = array()  )
	 {
		$product = array();
		foreach( $this->empty_product as $key => $value )
			$product[ $key ] = '';
		unset( $product['product_id']);
		$product['shop_id'] = $shop_id;
		$product['name'] = $name;
		$product['brand'] = $brand;
		//fix description html
		$doc = new DOMDocument();
		$doc->loadHTML($description);
		$product['description'] = $doc->saveHTML();
		$product['stock_left'] = $stock_count ;
		$product['price'] = $price;
		$product['tags'] = (is_array($tags)) ? implode(',', $tags ) : '';
		$product['images'] = is_array($images) ? implode(',', $images) : '';
		$product['category_id'] = $category_id;

		$this->db->insert('product' , $product);
		return ( $this->db->insert_id() > 0 ) ? $this->db->insert_id() : False;
 	 }
	 
	 /**
	  * Add a product attribute
	  *
	  * @param		Int		$	Product ID
	  * @param		String	$	Attribute name e.g Size 
	  * @param		String	$	Type . See /$this->valid_attribute_options
	  * @param		Bool	$	Is this attribute customisable when placing an order
	  * @param		Array	$	Valid options
	  * @param		Bool	$	Is this atribute usable as a product filter for search.
	  *
	  * @return		Int		$ 	Attrbute ID or False
	  */
	 public function add_attribute( $product_id , $attribute_name , $attribute_value ,  $is_customize , $options , $is_filter = False   )
	 {
		$attrib = array();
		foreach( $this->empty_attribute as $key => $value )
			$attrib[ $key ] = '';
			
		$attrib['product_id'] = $product_id;
		$attrib['attribute_name'] = $attribute_name;
		$attrib['attribute_value'] = $attribute_value;
		if( empty($attribute_value) )
		{
			//first attribute should be attribute option
			if( empty( $options) )
				return False; //attribute has to have value
			$attrib['attribute_value'] = $options[0];
		}
		else
		{
			//check if attribute exists,
			if( ! in_array($attribute_value, $options) )
				array_push($options , $attribute_value );
		}

		$attrib['is_customize'] =  $is_customize;
		$attrib['is_filter'] = $is_filter;
	
		$this->db->insert('product_attribute' , $attrib);

		$attr_id =  $this->db->insert_id();
		if( $attr_id > 0 )
		{
			//add attribute options
			$options = array_unique($options);
			foreach( $options as $opt )
			{
				$this->add_attribute_option( $attr_id , $opt   );
			}
		}
		return ( $attr_id > 0 ) ? $attr_id : False;
	 }

	 /**
	  * Count featured products
	  *
	  *
	  * @param 		Int 	$	Shop ID 
	  *
	  * @return 	Int
	  * @todo		Fix this shit !
	  */
	 public function count_featured( $shop_id )
	 {
	 	$query = $this->db->where('shop_id' , $shop_id )
	 			 	  ->select('product_id')
	 			 	  ->from('product')
	 			 	  ->get();
	 	return $query->num_rows();		 	  
	 }

	 /**
	  * Chooses a random product and sets it as featured
	  *
	  * @param 		Int 		$	Shop ID
	  * @param 		Int 		$	Number of new featured products to add/set
	  * @param 		Bool 		$	Clear currently featured products ?
	  *
	  * @return 	Array 		$	Newly featured products.
	  */
	 public function feature_random( $shop_id , $count = 3 ,  $clear_current_featured = False )
	 {
	 	if( $clear_current_featured )
	 		$this->db->update('product' , array('is_featured' , False ));
	 	$this->db->where('shop_id' , $shop_id )->limit( $count )->update( 'product' , array('is_featured' , True) );
	 	$query = $this->db->select('product_id')->where( array('shop_id' => $shop_id , 'is_featured' => True ))->get('product');
	 	return $query->result_array(); 
	 }

	 /**
	  * Add attribute option
	  *
	  * @param 		Int 	$ 	Attribute ID
	  * @param 		String 	$ 	Option
	  *
	  */
	  public function add_attribute_option( $attribute_id , $option )
	  {
	  	$data = array( 'attribute_id' => $attribute_id , 'value' => $option );
	  	$this->db->insert('product_attribute_option' , $data );
	  	return $this->db->insert_id();
	  }

	 
	 /**
	  * Update a product attribute
	  *
	  * @param		Int		$	Attribute ID
	  * @param		Array	$	Atribute
	  *
	  * @return		Bool
	  */
	 public function update_attribute( $attribute_id  ,  $attribute )
	 {
		if( ! $this->is_valid( $attribute ) ) 
			return False;
		//unset dangerous keys
		if( isset($attribute['attribute_id'] ) )
			unset( $attribute['attribute_id'] );
		
		
		$this->db->where ('attribute_id' , $attribute_id )
				 ->update( 'product_attribute'  , $attribute );
		
		return True;  
	 }

	 /**
	  * Update a product attribute option
	  *
	  * @param		Int		$	Attribute ID
	  * @param		Array	$	Atribute Option ID
	  * @param 		String 	$ 	New product attribute id value
	  *
	  * @return		Bool
	  */
	 public function update_attribute_option( $attribute_id  ,  $attribute_option_id , $option_value )
	 {

		$this->db->where ('attribute_id' , $attribute_id )
				 ->where ('option_id' , $attribute_option_id )
				 ->update( 'product_attribute_option' ,  array('value'  => $option_value ) );
		
		return True;  
	 }
	 
	 /**
	  * Remove a product attribute
	  *
	  * @param		Int		$	Attribute ID
	  *
	  * @return		Bool
	  */
	 public function remove_attribute( $attribute_id )
	 {
		$this->db->where('attribute_id' , $attribute_id )
				 ->delete('product_attribute');
		//remove attribute options
		$this->remove_attribute_options( $attribute_id );		 
		return True;		 
	 }

	 /**
	  * Remove a product's attribute options
	  *
	  * @param		Int		$	Attribute ID
	  *
	  * @return		Bool
	  */
	 public function remove_attribute_options( $attribute_id )
	 {
	 	$this->db->where('attribute_id' , $attribute_id )
				 ->delete('product_attribute_option');
		return True;		 
	 }
	 
	 /**
	  * Check if a product is valid
	  *
	  * @param		Array	$	Product
	  *
	  *	@return		Bool
	  */
	 public function is_valid( $product )
	 {
		foreach( $this->empty_product as $key => $value )
			if( ! isset( $product[ $key ]  ) )
				return False;
		return True; 	 
	 }
	 
	 /**
	  * Check f a product's attributes are valid
	  *
	  * @param		Array	$	Attributes
	  *
	  * @return		Bool
	  */
	 public function is_valid_attributes( $attrib )
	 {
		 foreach( $this->empty_attribute as $key => $value )
			if( ! isset( $attrib[ $key ]  ) )
				return False;
		return True; 	
	 }
	 
	 /**
	  * Remove a product
	  *
	  * @param		Int		$	Product ID
	  * @param		Bool	$	Clean slate delete, removes attributes
	  *
	  * @return 	Bool
	  */
	 public function remove_product( $product_id , $cleanslate = True)
	 {
		 $tables = array('product' );
		 $this->db->delete('product' , array('product_id' => $product_id ) );
		 if( $cleanslate )
		 {
		 	$attrs = $this->get_attributes( $product_id );
		 	$this->db->delete('product_attribute' , array('product_id' => $product_id ) );
		 	foreach( $attrs as $attr )
		 	{
		 		$this->remove_attribute( $attr['attribute_id'] );
		 	}
		 } 	 
		return True;
	 }
	 
	 private function full_query_string( $db_array )
	 {
		$parts = array();
		foreach( $db_array as $key => $val )
			array_push( $parts , $key ); 
		return implode( " , " , $parts );
	 }
	 
 };
 
 