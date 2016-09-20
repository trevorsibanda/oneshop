<?php
/**
 * Base Controller
 * 
 * Base controller to be parent of all controllers
 *
 * @author		Trevor Sibanda <trevorsibb@gmail.com>
 *
 * @date		7 May 2015
 *
 */
 
 
 class OS_controller extends CI_Controller
 {
	 
	 //content to load see load_shop function
 	 private $_content_to_load = array();

	 public function __construct()
	 {
		 parent::__construct();
		 
		 $this->shop_subdomain = $this->get_shop_subdomain();

		 $this->load->model('shop' , 'shop');
		 $this->load->model('product' , 'product' );
		 $this->load->model('system' , 'system');
		 $this->load->model('blogpost');
		 $this->load->model('ui' , 'ui');

		 $this->load->helper('account');
		 $this->load->helper('blog');

		 //shooping cart
		 //$this->load->library('cart');
		 $this->load->library('php_session');
		 $this->load->driver('cache' , array('adapter' => 'file', 'backup' => 'file', 'key_prefix' => 'shop_') );

		 $this->ui->load_ad_engine();
		 $this->ui->load_analytics();
		 $this->ui->load_css_engine();
	 }
	 
	 
	 public function index()
	 {
		 die('Direct access not allowed');
	 }

	 /**
	  * Set content to load.
	  *
	  * Set the content you want load for the particular page.
	  * NB: Shop , SHop settings, Product categories and featured products are always loaded 
	  *     
	  * This function seeks to reduce the memory footprint of each store
	  *
	  *
	  */
	  public function set_content( $content )
	  {
	  	$this->_content_to_load = $con;
	  }
	 
	 /**
	  * Loads the current shop.
	  *
	  * This function loads the essential shop information
	  * including shop details and settings, featured products
	  * and any categories referenced in the theme.
	  *
	  * @return Array	$	Essential shop information
	  *
	  */
	  public function load_shop( $page = array('title' => '' , 'description' => '')  )
	  {
		  return $this->load_shop_essentials( $this->shop_subdomain , $page );
	  }
	 
	 /**
	  * Quick Mode.
	  *
	  * Load the default shop with config options and
	  * renders the output.
	  *
	  * @param		String	$	Page
	  * @param		Array	$	Page config options
	  *
	  * @return		Void
	  */
	  public function quick_view( $page_name , $page  = array('title' => '' , 'description' => '') )
	  {
		  $this->render( $page_name , $this->load_shop( $page ) );
	  }
	  
	 /**
	  * Get the site url from the request information
	  *
	  * i.e get from subdomain or $_GET parameters
	  *
	  * @return		String	$	Returns 404 page on fail
	  */
	 public function get_shop_subdomain(  )
	 {
		 //use defined in index.php, if not available extract ot ourselves
	 	 return ( defined('SHOP_SUBDOMAIN') ? SHOP_SUBDOMAIN : SHOP_DOMAIN );
	 }
	 
	 /**
	  * Load essential shop details
	  *
	  * @param	String	$	Shop URL
	  * @param	Array	$	Page settings
	  */
	 public function load_shop_essentials( $shop_subdomain , $page   )
	 {
		 
		 
		 //check if site data has been loaded
		 $shop = array();
		 if( $shop_subdomain == 'hosted')
		 {
		 	header('Location: '. OS_BASE_SITE . '/auth/login');
		 	return;
		 }
		 if( defined('SHOP_SUBDOMAIN'))
		 {
		 	$shop =  $this->shop->get_by_subdomain($shop_subdomain , false );
	 	 	
	 	 }
	 	 else
	 	 {
	 	 	
	 	 	$shop =  $this->shop->get_by_alias($shop_subdomain,false);
	 	 }


	 	 //Unregistered/Non existant shop
		 if( empty($shop) )
		 {
		 	//@todo check old site urls
		 	$this->load->database();
		 	$query = $this->db->get_where('shop_used_subdomains' , array('subdomain' => $shop_subdomain ) );
		 	$result = $query->result_array();
		 	if( ! empty($result) )
		 	{
		 		$shop = $this->shop->get_by_id(  $result[0]['shop_id'] , False );
		 		
		 		//permanent redirect to url
		 		header('Location: ' . $shop['url'] . $this->input->server('REQUEST_PARAM') , true , 301 );
	 	 		exit;

		 	}
		 	$this->shop_unregistered($shop_subdomain);
		 	exit;
		 }
		 
	 	 $redirect_allowed = True;
	 	 //cron jobs do not  redirect
	 	 $gets = $this->input->get();
	 	 if( isset($gets['os_cron_job_no_redirect']))
	 	 	$redirect_allowed = False;

	 	 if( $shop['use_alias']  and defined('SHOP_SUBDOMAIN') and SHOP_SUBDOMAIN != $shop['alias'] and $redirect_allowed )
	 	 {
	 	 	//permanent redirect.

	 	 	header('Location: http://' . $shop['alias'] . $this->input->server('REQUEST_PARAM') , true , 301 );
	 	 	exit;
	 	 }
	 	 if( (! $shop['use_alias']) and defined('SHOP_DOMAIN')  )
	 	 {
	 	 	
	 	 	//someone setup their own cname record to our shop without our knowledge
	 	 	//permanent redirect.
	 	 	
	 	 	header('Location: ' . $shop['url'] . $this->input->server('REQUEST_PARAM') , true , 301 );
	 	 	exit;
	 	 }

		 
		 //get shop logo
		 $shop['logo'] = $this->shop->image->get($shop['logo_id']);


		 $shop_settings = $this->shop->settings->get( $shop['shop_id'] );

		 $shop['contact'] = $shop_settings['contact'];

		 $this->product->search->shop_id( $shop['shop_id'] );
		
		 $data = array('shop' => $shop);

		 //get account subscription
		 $data['subscription'] = $this->shop->account->current_subscription( $shop['shop_id'] );
		 
		 if( empty($data['subscription']))
		 {
		 	//site down

		 	$this->shop_unavailable();
		 }
		 //@todo might not have subscription, check

		 //apply subscription and limits
		 //@see helper/account_helper.php
		 apply_account( $data['subscription'] );

		 //bootstrap analytics
		 $this->ui->analytics->bootstrap(  $data['subscription'] , $shop_settings['analytics'] );

		 //apply ad network
	 	 //if on free account or ads turned on, enable ads
	 	 if( account_can('adverts') )
	 	 {
	 	 	$this->ui->ad_engine->init($data['shop']);
	 	 }
		 
		 //Get time
		 $data['last_updated'] = time();
		 
		 //page attributes
		 $data['page'] = $page;
		 
		 //shop settings
		 
		 $data['settings'] = $shop_settings;
		 
		 //theme
		 $this->_load_theme( $shop , $shop_settings['theme']  , $data );
		 

		 //can be overriden
		 if( isset($page['ignore_state']) and $page['ignore_state'] )
		 {

		 }else
		 {
		 	//Inactive or suspended shop
		 	if( $shop['is_active'] == False or $shop['is_suspended'] == True )
			 {
			 	$this->shop_unavailable( $data );
			 	exit;
			 }
		 }
		 

		 
		 //@todo
		 //Current shopper details
		 $data['shopper'] = array();
		 
		 //@todo
		 //Get page template settings
		 $data['page'] = array(); 
		 

		 //Shopping cart
		 $data['cart'] = $this->system->cart->items();
		 $data['cart_total'] = $this->system->cart->get_total();
		 $data['cart_total_items'] = $this->system->cart->count_items();
		 $data['cart_ordered'] = $this->system->cart->is_cart_ordered();
		 $data['cart_order_number'] = $this->system->cart->order_number();

		 //Set page attributes,		 
		 $data['page']['title'] = element( 'title' , $page , 'Welcome');
		 $data['page']['description'] = element( 'description' , $page , $shop['short_description'] );
		 $data['page']['keywords'] =  element( 'keywords' , $page , $shop['keywords'] );
		 $data['page']['canonical_url'] = '';
		 
		 
		 //Get product categories 
		 //$data['product_categories'] = array();
		 //$data['product_categories'] = $this->product->category->shop_categories(  $shop['shop_id'] );
		 
		 

		 //@todo
		 //Top blog posts
		 $data['top_posts'] = array();
		 
		 //@todo
		 //Recent blog posts
		 $data['recent_posts'] = array();
		 
		 //Get all shop products including attributes
		 //@todo remove this
		 
		

		 
		 return $data;
	 }
	 
	 /**
	  * Get a shops data
	  *
	  * @param		String	$	Shop URL
	  *
	  */
	 public function get_site_data( $shop_subdomain )
	 {
		 return $this->php_session->get( 'shop_' . $shop_subdomain );
	 }

	 /**
	  * Load theme. can reparse theme or load from cache.
	  *
	  * @param 		Array 	$	Shop
	  * @param 		Array 	$	Settings
	  * @param 		Bool 	$	Reload from cache ?
	  *
	  * @return 	Null
	  */
	 public function _load_theme( $shop , $theme_settings , &$data , $clear_cache = False )
	 {
		 //@todo cache theme
		 $this->ui->theme->set_shop_id( $shop['shop_id'] );

		 $key =  $shop['shop_id'] . '_theme';
		 $data['theme'] = $this->cache->get( $key );
		 
		 if( $data['theme'] == False or $clear_cache )
		 {

			 if( ! $this->ui->theme->load_theme( $theme_settings['theme'] ) )
			 {
			 	//failed to load theme
			 	$this->shop_unavailable();
			 	return;
			 }
			 $data['theme'] = $this->ui->theme->theme_options();
			 //every 5 mins
			 $this->cache->save($key , $data['theme'] , 300 );
		}
		
	 }
	 
	 /**
	  * Check if the site data is loaded
	  *
	  * @param		String		$	Shop Url
	  *
	  */
	 public function check_site_loaded( $shop_subdomain )
	 {
	 	if( ENVIRONMENT != 'production')
	 		return False;
		$data = $this->php_session->get('shop_' . $shop_subdomain ); 
		if(  is_null($data) or ! isset( $data['products'] )  )
		{
			return False;
		}
		
		//check time expired
		//30 minutes expire
		if(  time()  >= ( $data['last_updated'] + ( 60 * 30)   ) )
			return False;
		return True;
	 }
	 
	 /**
	  * Handle unregistered shop URL's
	  *
	  * @param 		String 		$	Shop URL (subdomain or domain)
	  *
	  * @return 	None
	  */
	 public function shop_unregistered($shop_subdomain)
	 {
	 	header('Location: '. OS_BASE_SITE . '?unregistered_shop=' . urlencode($shop_subdomain));
	 	exit;
	 }

	 /**
	  * Handle requests to shops that are currently unpublished or suspended.
	  *
	  * @param 		Array 		$	Shop Data
	  *
	  * @return 	None
	  */
	 public function shop_unavailable( $data )
	 {
	 	header('Location: /site/currently_unavailable' );
	 	return;
	 }

	 public function currently_unavailable(  )
	 {

	 	$this->render('site_down' , array('shop'=>array('name'=> OS_SITE_NAME,'short_description'=>'An error occured.'),'page'=>array('title' => 'Site Currently unavailable' , 'description' => 'Site curent unavaliable check again later' , 'ignore_state' => True) ) );	
	 }


	 /**
	  * Render the page
	  *
	  * @param		String	$	Page Name
	  * @param		Array	$	Data
	  *
	  * @todo	Add support for rendering pages based on templated html using Rain Templating Language
	  */
	 protected function render( $page_name ,  $data )
	 {

		 if( $this->input->is_ajax_request() )
		 {
		 	$this->output->set_content_type('application/json');
		 	$this->output->set_output( json_encode($data) );
		 	return;             
		 }

		 if( ! isset($data['page']['keywords']))
			 $data['page']['keywords'] = $data['shop']['short_description'];
		if( ! isset( $$data['page']['canonical_url']))
			$data['page']['canonical_url'] = current_url();
				
		 $data['page']['title'] .= ' :: ' .  $data['shop']['name'];
		 $data['page']['render'] = $page_name;
		 $theme_view = isset( $data['theme']['info']['dir'] ) ? 'theme/' .$data['theme']['info']['dir'] . '/' . $page_name  : 'system/theme/server_error';
		 $this->load->view( $theme_view , $data );
		 
	 }

	 /**
	  * Render a system page
	  *
	  * @param		String	$	Page Name
	  * @param		Array	$	Data
	  * @param 		Bool 	$	Die after printing data
	  *
	  * @todo	Add support for rendering pages based on templated html using Rain Templating Language
	  */
	 protected function sys_render( $view_page ,  $data , $die = False)
	 {
		$data = $this->load->view('system/' . $view_page , $data , true );
		if( $die )
		{
			die($data);
		}
		else
			echo $data;	 
		 //$this->load->view('system/benchmark');
	 }

	 

	 

	 
	 
	 
 };
 
 
