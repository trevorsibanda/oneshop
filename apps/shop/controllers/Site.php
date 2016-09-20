<?php
/**
 * Site Controller
 *
 *
 *
 *
 *
 *
 */

 
 class Site extends OS_Controller
 {
	 
	 public function __construct()
	 {
		 parent::__construct();
		 
		
	 }
	 
	 /**
	  * Redirect to shop Page
	  *
	  */
	 public function to_index(  )
	 {
		//read settings and determine which page to redirect to
	 	$data = $this->load_shop();
	 	
	 	$default_page = $data['settings']['theme']['default_page'];

	 	if( $default_page == 'shop')
	 		return header('Location: '. shop_url() );
	 	else if( $default_page == 'blog')
	 		return header('Location: /blog');
	 }
	 
	 public function index()
	 {
		$data = array();
		$data = $this->load_shop(  );
		$data['page']['title'] = 'Home'; 
		$data['page']['description'] = $data['shop']['short_description'];	
		$data['page']['canonical_url'] = shop_url('');
		

		$this->render('home' ,  $data  );
	 }
	 
	 
	 public function view_product( $product_id )
	 {
		 

		 $data = array();
		 
		 $data = $this->load_shop();

		 //get product and attributes
		 $data['product'] = $this->product->get( $product_id , True , $data['shop']['shop_id'] );
		 if(  empty($data['product']) or $data['product']['shop_id'] != $data['shop']['shop_id'] )
		 {
			 $this->error_404();
			 return;
		 }

		 $data['product']['images'] = $this->product->image->get_images( $data['product']['images'] );
		 $data['product']['public_files'] = array();//$this->product->file->get_product_files( $data['product']['product_id'] );
		 
		 
		 $data['page']['title'] = $data['product']['name'];
		 $data['page']['description'] = 'Order online and buy ' . $data['product']['name'] . ' from ' . $data['shop']['name'] . ' ';
		 $data['page']['canonical_url'] = shop_url('product/' . $data['product']['product_id']);

		 $data['cart_entry']  = in_cart($data['product'] , $data['cart']);

		 $this->render( 'view_product' ,  $data );
	 }
	 
	 
	 public function browse( $filter = 'all' )
	 {
	 	$filter = urldecode($filter);
		$filter_val = '';
		$page = array('title' => 'Browse Products' , 'description' =>'Browse a list of all products sold at '  ); 
		$page['items_per_page'] = intval( $this->input->get_post('items_per_page') ); 
		$page['page'] = intval( $this->input->get_post('page') );
		if( $page['page'] <= 0 and $page['page'] <= 1000 )
			$page['page'] = 1;
		$page['sort'] = $this->input->get_post('sort' , true);
		if( empty($page['sort']) )
			$page['sort'] = 'default';

		
		if( substr($filter , 0,11 ) == 'price_range' )
		{
			
			$r = str_replace( array('price_range[' , ']' ) , '' , $filter );
			$r = explode( '-' , $r );
			if( is_array($r) && count($r) == 2 )
			{
				$filter = 'price_range';
				$filter_val = array('low' => $r[0] , 'high' => $r[1] );

			}
			else
			{
				$filter = 'free_text';
				$filter_val = '';
			}
		}
		$page['filter'] = $filter;
		



		//only allow 5,9,10,12,15,20,50 items per page
		$page['items_per_page'] = ( in_array( $page['items_per_page'] , array(5,9,10,12,15,20,50) )  ) ? $page['items_per_page'] : 9;
		
		
		$data = $this->load_shop(  $page );
		//use search to get products
		$this->product->search->shop_id( $data['shop']['shop_id'] );
		$this->product->search->filter($filter , $filter_val);
		$this->product->search->items( $page['items_per_page'] );
		$this->product->search->set_page( $page['page'] );
		$this->product->search->sort( $page['sort'] );

		$this->product->search->run();

		$data['products'] = $this->product->search->results();
		

		foreach( $data['products'] as $key => $product )
		{
			$data['products'][$key]['images'] = $this->product->image->get_images( explode(',' , $product['images']) ); 
		}

		$data['ranges'] = get_product_price_ranges(  $data['products']   ,5);
		$data['max_pages'] = $this->product->search->max_pages();

		$data['page'] = $page;
		$data['page']['canonical_url'] = current_url();
		 

		$this->render('browse'  , $data );
	 }

	 public function search( $filter = 'all' )
	 {
	 	$query = $this->input->get_post('q');
		$query = clean(  $query );
		$page = array('title' => 'Search results for "' . $query . '"' , 'description' =>'Browse a list of all products sold at ' , 'page' => 1  ); 
		$page['items_per_page'] = $this->input->get_post('items_per_page'); 
		$page['page'] = intval( $this->input->get_post('page') );

		if( $page['page'] <= 0 and $page['page'] <= 1000 )
			$page['page'] = 1;
		$page['sort'] = $this->input->get_post('sort');
		if( empty($page['sort']) )
			$page['sort'] = 'default';
		$page['filter'] = $filter;
		
		//only allow 5,9,10,12,15,20,50 items per page
		$page['items_per_page'] = ( in_array( $page['items_per_page'] , array(5,9,10,12,15,20,50) )  ) ? $page['items_per_page'] : 10;
		

		
		$data = $this->load_shop(  $page );

		$this->product->search->shop_id(  $data['shop']['shop_id'] );
		//use search to get products
		$this->product->search->filter('free_text' , $query );
		$this->product->search->items( $page['items_per_page'] );
		$this->product->search->set_page( $page['page'] );
		$this->product->search->sort( $page['sort'] );



		$this->product->search->run();

		$data['max_pages'] = $this->product->search->max_pages();
		$data['total_results_count'] = $this->product->search->total_results_count();
		$data['results_count'] = $this->product->search->result_count();
		
		$data['products'] = $this->product->search->results();
		foreach(  $data['products'] as $key => $product )
		{
			$data['products'][$key]['images'] = $this->product->image->get_images( $product['images'] );
			$data['products'][$key]['public_files'] = array();	
		}
		$data['query'] = $query;
		$data['page']['canonical_url'] = current_url();

		if( ! isset($data['page']['page']))
			$data['page']['page'] = 1;



		$this->render('search'  , $data );


	 }

	 public function view_category( $category_id )
	 {
	 	if( ! is_numeric($category_id) )
	 		return $this->error_404();
	 	$page = array('title' => 'View category ' , 'description' =>' View category ' , 'page' => 1 , 'keywords' => 'category'  ); 

	 	$page['items_per_page'] = $this->input->get_post('items_per_page'); 
		$page['items_per_page'] = ( in_array( $page['items_per_page'] , array(5,9,10,12,15,20,50) )  ) ? $page['items_per_page'] : 10;
		
		$page['sort'] = $this->input->get_post('sort');
		if( is_null($page['sort']) )
			$page['sort'] = 'popular';

		$page['page'] = intval( $this->input->get_post('page') );

		if( $page['page'] <= 0 )
			$page['page'] = 1;


		$data = $this->load_shop( $page );
		$data['page'] = $page;

		$data['category'] = $this->product->category->get( $category_id );
		if( empty($data['category']) or $data['category']['shop_id'] !== $data['shop']['shop_id'])
		{
			$this->error_404();
		}

		$data['category']['image'] = $this->product->image->get( $data['category']['image_id'] );

		$this->product->search->shop_id(  $data['shop']['shop_id'] );
		//use search to get products
		$this->product->search->filter('product_category' , $category_id );
		$this->product->search->items( $page['items_per_page'] );
		$this->product->search->set_page( $page['page'] );
		$this->product->search->sort( $page['sort'] );

		$this->product->search->run();

		$data['max_pages'] = $this->product->search->max_pages();
		$data['total_results_count'] = $this->product->search->total_results_count();
		$data['results_count'] = $this->product->search->result_count();
		
		$data['products'] = $this->product->search->results();
		foreach(  $data['products'] as $key => $product )
		{
			$data['products'][$key]['images'] = $this->product->image->get_images( $product['images'] );
			$data['products'][$key]['public_files'] = array();	
			if( is_array($data['products'][$key]['tags']))
				foreach(  $data['products'][$key]['tags'] as $tag )
				{
					$data['page']['keywords'] .=  $tag .',';
				}
			elseif( is_string($data['products'][$key]['tags']))
				$data['page']['keywords'] .=  $data['products'][$key]['tags'];	
		}


		$data['page']['title'] =  $data['category']['name'] . ' category ( ' . $data['total_results_count'] . ' items )';
		$data['page']['description'] = substr( strip_tags( $data['category']['description'] ) , 0 , 255 );
		


		$data['page']['canonical_url'] = $data['shop']['url'] . 'shop/category/' . $category_id;
		$prev_page = $data['page']['page']-1;
		$data['prev_page_url'] = $data['page']['canonical_url'] . "?page={$prev_page}&sort={$page['sort']}&items_per_page={$page['items_per_page']}";
		$next_page = $data['page']['page']-1;
		$data['next_page_url'] = $data['page']['canonical_url'] . "?page={$next_page}&sort={$page['sort']}&items_per_page={$page['items_per_page']}";
		

		$this->render('view_category' , $data );
	 }

	 public function view_featured( )
	 {

	 }
	 
	 public function view_cart(  )
	 {
		 $page = array('title' => 'Your Shopping Cart' , 'description' =>'Add products to your shopping cart and checkout'  );
		$data = $this->load_shop(  $page );
		$this->render('cart' , $data ); 
	 }
	 

	 public function error_404()
	 {
		 $page = array('title' => '404 Page Not Found' , 
		'description' => 'The requested page could not be found.' ); 
		 $this->output->set_status_header( 404 );
		 $this->quick_view( '404' , $page );
	 }
	 
	 public function about_us( )
	 {
		$page = array('title' => 'About us' , 'description' =>'Who we are, what we do and everything about '   );
		$data = $this->load_shop(  $page );
		$data['page']['description'] =  $data['shop']['tagline'] . $data['page']['description'] . $data['shop']['name'];
		$data['page']['keywords'] = $data['shop']['keywords'];

		//get recommended products
		//@todo recommend product based on last product
		$last_product = 0;
		$data['recommended_products'] =  $this->product->search->get_recommended_products( $last_product , 3 );

		//featured products
		$data['featured_products'] = $this->product->search->get_featured_products( 3 );

		//categories
		$data['product_categories'] = $this->product->search->get_product_categories( 10 );


		$this->render('about-us' , $data ); 

	 }

	 public function contact_us(  )
	 {
		$page = array('title' => 'Contact Us' , 
		'description' => 'View our contact details and leave us a message or find us on social media.' ); 
		$data = $this->load_shop( $page ); 
		$this->render( 'contact_us' , $data ); 
	 }

	 public function rss_feed()
	 {
	 	die('Feature not yet active');
	 }

	 
	 
 }
