<?php
/**
 * Product Search.
 *
 * Search for a product given a number of different parameters.
 * Allows for search , sorting, filtering , paged results etc
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @package 	Models/Product/Search
 * 
 *
 */

 
 class Product_search extends CI_Model
 {
	 
	/** Max Items **/
	private $_max_items = 10;

	/** Order **/
	private $_sort = 'default';

	/** Valid orders for reference **/
	private $_valid_sort = array(
		'latest' , //Most recently created
		'oldest' , //oldest createdname_
		'popularity' , //TB Announced
		'name_a-z' , //Name A-Z
		'name_z-a' , //Name Z-A
		'price_low-to-high' , //Proce low to high
		'price_high-to-low' , //Price high to low
		'random' , //Random
		);

	/** Offset **/
	private $_offset = 0;

	/** Filter **/
	private $_filter = array('type' => '' , 'data' => '');

	/** Results data **/
	private $_result_data = array();

	/** Total results count **/
	private $_total_result_count = 0;

	/** Results count **/
	private $_result_count = 0;

	/** ctor **/ 
	 public function __construct()
	 {
		 parent::__construct();
		 $this->load->database();
	 }
	 
	/**
	 * Setter/Getter shop id.
	 *
	 * @param 		Int 	$ 	Shop ID
	 *
	 * @return 		Int
	 */
	public function shop_id( $_shop_id  = Null )
	{
		if( !is_null($_shop_id) )
			$this->_shop_id = $_shop_id;
		return $this->_shop_id;
	} 

	/**
	 * Get featured products.
	 *
	 * @param 		Int 		$		Max items, 0 for no limit
	 *
	 * @return 		Array 		$		Products
	 */
	public function get_featured_products( $max_count = 6 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('featured' , true );
		$this->sort(  'random' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;
	}

	/**
	 * Recommend products based on a given product.
	 *
	 * @param 		Array 		$		Product
	 * @param 		Int 		$		Max items, 0 for no limit
	 *
	 * @return 		Array 		$		Products
	 */
	public function get_recommended_products($intel_product  = Null , $max_count = 3 )
	{
		$this->reset();
		$this->items( $max_count );
		
		
		if( isset($intel_product['product_id']) && isset($intel_product['name']) && isset($intel_product['is_featured']) && isset($intel_product['price']) )
		{
			switch( rand()%4 )
			{
				case 0:
				{
					$this->filter('brand' , $intel_product['brand'] );
				}
				break;
				case 1:
				{
					$half = $intel_product['price']/2;
					//range is half of price --> price + half of price
					$vals = array('low' =>  $half ,'high' => $half *3 );
					$this->filter('price_range' ,  $vals );
				}
				break;
				case 2:
				{
					$this->filter('featured' , $intel_product['is_featured'] );
				}
				break;
				case 3:
				{
					$parts = explode(' ', $intel_product['name']);
					$this->filter('free_text' , $parts[0] ); 
				}
				break;
			}	
		}
		else
		{
			//use random product
			$this->filter('all' , '' );
		}
		
		$this->sort(  'random' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}
		
		return $results;
	}

	/**
	 * Get product categories
	 *
	 * @param 		Int 		$		Max items to return
	 *
	 * @return 		Array 		$		Product categories
	 */
	public function get_product_categories( $max_count = 10 )
	{
		$categories = $this->product->category->shop_categories( $this->_shop_id , $max_count );
		foreach ($categories as $key => $cat) 
		{
			$categories[$key]['image'] = $this->product->image->get( $cat['image_id'] );
		}
		return $categories;
	}

	/**
	 * Get most recently viewed products.
	 *
	 * @param 		Int 		$	Maximum number od products to return
	 *
	 * @return 		Array 		$	Products
	 */
	public function get_last_viewed_products( $max_count = 3 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('all' , '' );
		$this->sort(  'recently_viewed' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;
	}

	/**
	 * Get most popular products.
	 *
	 * @param 		Int 		$	Maximum number od products to return
	 *
	 * @return 		Array 		$	Products
	 */
	public function get_popular_products( $max_count = 6 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('all' , '' );
		$this->sort(  'popular' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;
	}

	/**
	 * Get cheapest products.
	 *
	 * @param 		Int 		$	Maximum number od products to return
	 *
	 * @return 		Array 		$	Products
	 */
	public function get_cheap_products( $max_count = 6 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('all' , '' );
		$this->sort(  'price_low-to-high' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;
	}

	/**
	 * Get most expensive products.
	 *
	 * @param 		Int 		$	Maximum number od products to return
	 *
	 * @return 		Array 		$	Products
	 */
	public function get_expensive_products( $max_count = 6 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('all' , '' );
		$this->sort(  'price_high-to-low' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;
	}

	/**
	 * Get products by a similar brand.
	 *
	 * @param 		String 		$	Brand name
	 * @param 		Int 		$	Maximum number od products to return
	 *
	 * @return 		Array 		$	Products
	 */
	public function get_branded_products( $brand_name , $max_count = 3 )
	{
		$this->reset();
		$this->items( $max_count );
		$this->filter('brand' , $brand_name );
		$this->sort(  'popular' );

		$this->run();
		$results = $this->results();

		//prepare and return
		foreach( $results as $key => $product )
		{
			$product['images'] = $this->product->image->get_images( $product['images'] );
		 	$product['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 	$results[$key] = $product;
		}

		return $results;

	}



	 /**
	  * Set or get filter 
	  *
	  * @param 		String 			$ 	Type ( all , brand , price_range , free_text , extended_free_text  )
	  * @param 		String|Array 	$	Value. Can be an array as in price_range array('low' => 0.00 , 'high' => 12.00);
	  *
	  * @return 	Array
	  */
	 public function filter( $type = Null , $value = Null )
	 {
	 	if( is_null($type) or is_null($value) )
	 		return $this->_filter;
	 	$this->_filter = array();
	 	$this->_filter['type'] = $type;
	 	$this->_filter['value'] = $value;
	 	return $this->_filter;
	 }
	 
	/**
	 * Setter/Getter for offset
	 *
	 * Results offset
	 * @param 		Int 	$ 		Results offset 
	 *
	 * @return 		Int
	 */
	public function offset( $n = Null )
	{
		if( ! is_null($n) )
			$this->_offset = $n;
		return $this->_offset;
	}


	/**
	 * Set Page.
	 *
	 * Sets the offset of results in terms of pages.
	 * Uses stored items_per_page and calculates
	 *
	 * @param 		Int 	$ 	Page
	 *
	 * @return 		
	 */
	public function set_page( $n )
	{
		return $this->offset(  ($n-1) * $this->_max_items );
	}

	/**
	 * Number of result pages given params
	 *
	 * @return 		Int
	 */
	public function max_pages(  )
	{
		$p = $this->_total_result_count/ $this->_max_items;
		if( $p != ( $this->_total_result_count * $this->_max_items) )
			$p += 1;
		return $p;
	}

	 /**
	  * Getter/Setter for sorting to use
	  *
	  * @param 		String 	$	Valid sorting option
	  *
	  * @see 		Product_Search::$_valid_sort
	  *
	  * @return 	String
	  */
	 public function sort( $sort = Null)
	 {
		 if( ! is_null($sort))
		 	$this->_sort = $sort;
		 return $this->_sort;
	 }
	 
	 /**
	  * Getter/Setter for number of items to return
	  *
	  * @param 		Int		$	Number of items to return in search. If null returns current values
	  *
	  * @return 	Int
	  */
	 public function items( $n = Null )
	 {
		 if( ! is_null($n))
		 	$this->_max_items = $n;
		 return $this->_max_items;
	 }
	 
	 /**
	  * Perform the search.
	  * 
	  * @return 	Bool 
	  */
	 public function run(  )
	 {
		 return $this->_run_search();
	 }

	 public function reset()
	 {
	 	$this->_max_items = 10;
		$this->_sort = 'default';
		$this->_offset = 0;
		$this->_filter = array('type' => '' , 'data' => '');
		$this->_result_data = array();
		$this->_total_result_count = 0;
		$this->_result_count = 0;
	 }
	 
	 /**
	  * Get the search results
	  *
	  * @return 	Array 	
	  */
	 public function results()
	 {
		 return $this->_result_data;
	 }

	 /**
	  * Number of results
	  *
	  * @return 	Int 	
	  */
	 public function result_count()
	 {
	 	return $this->_result_count;
	 }

	 /**
	  * Total number of results.
	  *
	  * @return 	Int 
	  */
	 public function total_results_count()
	 {
	 	return $this->_total_result_count;
	 }

	 /**
	  * Run the search
	  *
	  *
	  *
	  *
	  */
	 private function _run_search()
	 {
	 	//start generating sql query
	 	$this->_total_result_count = $this->_sql_query('count');

	 	$this->_result_data = $this->_sql_query('results');

	 	$this->_result_count = count( $this->_result_data );
	 	return True;
	 }

	 /**
	  * Run a SQL query and return results
	  *
	  * @param 		String 	$	Type of query's return ( results , count)
	  *
	  * @return 	Array
	  */
	 private function _sql_query($type = 'results' )
	 {
	 	$this->db->select('*')
	 			 ->from('product')
	 			 ->where('shop_id' , $this->_shop_id );
	 	//filters
	 			 
	 	switch( $this->_filter['type'] )
	 	{
	 		case 'all':
	 		//just get all
	 		break;
	 		case  'brand':
	 		{
	 			$this->db->group_start();
	 			$this->db->where('brand' , $this->_filter['value'] );
	 			$this->db->group_end();
	 		}
	 		break;
	 		case 'featured':
	 		{
	 			$this->db->group_start();
	 			$this->db->where('is_featured' ,  $this->_filter['value'] );
	 			$this->db->group_end();
	 		}
	 		break;
	 		case 'price_range':
	 		{
	 			$this->db->group_start();
	 			$this->db->where('price >= ' , $this->_filter['value']['low'] )
	 					 ->where('price <= ' , $this->_filter['value']['high'] );
	 			$this->db->group_end();		 
	 		}
	 		break;
	 		case 'free_text':
	 		{
	 			$this->db->group_start();
	 			$this->db->like('name' , $this->_filter['value'] )
	 					 ->or_like('description' , $this->_filter['value']);
	 			$this->db->group_end();		 
	 		}
	 		break;
	 		case 'product_category':
	 		{
	 			$this->db->group_start();
	 			$this->db->where('category_id' , $this->_filter['value'] );
	 			$this->db->group_end();

	 		}
	 		break;
	 		case 'extended_free_text':
	 		{
	 			$this->db->group_start();
	 			$this->db->like('name' , $this->_filter['value'] )
	 					 ->or_like('description' , $this->_filter['value'])
	 					 ->or_like('brand' , $this->_filter['value'] );
	 			$this->db->group_end();		 
	 		}
	 		break; 
	 	}
	 	
	 	
	 	//ordering
	 	switch( $this->_sort )
	 	{
	 		case 'latest':
	 		{
	 			$this->db->order_by('product_id' , 'DESC' );
	 		}
	 		break;
	 		case 'oldest':
	 		{
	 			$this->db->order_by('product_id' , 'ASC');
	 		}
	 		break;
	 		case 'recently_viewed':
	 		{
	 			$this->db->order_by('last_viewed' , 'DESC');
	 		}
	 		//no break deliberately //break;
	 		case 'popular':
	 		{
	 			$this->db->order_by('stock_sold' , 'DESC')
	 					 ->order_by('views' , 'DESC')
	 					 ->order_by('shares' , 'DESC')
	 					 ;
	 		}
	 		break;
	 		case 'name_a-z':
	 		{
	 			$this->db->order_by('name' , 'ASC' );
	 		}
	 		break;
	 		case 'name_z-a':
	 		{
	 			$this->db->order_by('name' , 'DESC');
	 		}
	 		break;
	 		case 'price_high-to-low':
	 		{
	 			$this->db->order_by('price' , 'DESC');
	 		}
	 		break;
	 		case 'price_low-to-high':
	 		{
	 			$this->db->order_by('price' , 'ASC');
	 		}
	 		break;
	 		default:

	 		break;
	 	}
	 	

	 	//now run query
	 	if( $type == 'results' )
	 	{
	 		//set offset and limit
	 		if( $this->_max_items > 0 )
	 			$this->db->limit(  $this->_max_items );

	 		$this->db->offset( $this->_offset  );
	 		$query = $this->db->get();
	 		return $query->result_array();
	 	}
	 	else
	 	{
	 		return $this->db->count_all_results();
	 	}

	 }




 }