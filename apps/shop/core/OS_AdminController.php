<?php

/**
 * Base Admin Controller
 *
 * Inherited by admin controller, inherits os controller
 *
 * @package		Controllers/Admin_Controller
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 *
 * @date 		May 24 2015
 *
 *
 */

class OS_AdminController extends OS_Controller
{
	/** App Mode **/
	private $_app_mode = 'json'; 	//JSON or HTML
	private $_is_login_page = False;  //is the current page a login page

	public function __construct( $config = array() )
	{
		parent::__construct();
		$this->load->library('form_validation');
		
		$this->load->helper('user');

		//check if logged on
		$this->check_auth();

		//automatically set app mode
		if( ! $this->input->is_ajax_request() )
			$this->set_app_mode('html');

		//if account is suspended
		//@see user_helper
		if( user_account_suspended() )
		{
			$data = array();
			$data['shop'] = array('name'=> OS_SITE_NAME);
			$data['action'] = 'account_suspended';
			$data['user'] = $this->user;
			//render and die
			$this->sys_render('login/verify_account' , $data ,true);
		}

		$this->request_manager();

		
	}



	protected function set_app_mode( $mode )
	{
		$this->_app_mode = $mode;
	}

	public function read_input( )
	{
		return json_decode($this->input->raw_input_stream,true);
	}

	public function error( $message )
	{
		$this->output->set_status_header('403');
		$this->output->set_content_type( 'application/json' );
		die(json_encode(array('error' => '' , 'msg' => $message ) ) );
	}

	public function render_json( $data )
	{

 		$this->output->set_content_type( 'application/json' )
 					 ->set_output(json_encode( $data )  );
 		return;
	}

	public function load_shop( $page = array('title' => '' , 'description' => ''))
	{
		return $this->load_shop_essentials(  $this->shop_subdomain , $page );
	}

	/**
	 * Loads basic shop admin records.
	 *
	 * @overload 		OS_Controller::load_shop_essentials	
	 *
	 * @param	String	$	Shop URL
	 * @param	Array	$	Page settings  
	 *
	 * @return  Array 	$	Data essential for shop administration.
	 */
	public function load_shop_essentials( $shop_url , $page )
	{
		if( is_array($page) )
		{
			//add override so all admin interfaces can be used even when page is offline
			$page['ignore_state'] = True;
		}
		$data = parent::load_shop_essentials( $shop_url , $page );
		//set log shop id
		$this->shop_log->shop_id( $data['shop']['shop_id'] );
		
		
		//Get shop account
		//$data['account'] = $this->shop->account->get( $data['shop']['shop_id'] );
		//current subscription
		$data['subscr'] = $this->shop->account->current_subscription( $data['shop']['shop_id'] );
		//wallet
		$data['wallet'] = array();


		return $data; 
	}

	/**
	 * Handles shop authentication checks 
	 *
	 * Checks if a user is logged in and if the session
	 * has expired and automatically handles the header
	 * responses.
	 *
	 * NB: For this function to work well remember to specify if
	 *     you are using an HTML admin template or a JSON response
	 */
	protected function check_auth( )
	{
		$page = $this->uri->segment( 2);
		$section = $this->uri->segment(1);		
		$function = $this->uri->segment(3);
		
		if( $section == 'api' and $page == 'users' and $function == 'login' )
			return;

		$logged_in = $this->is_logged_in();
		$excused_uri = array('login' , 'recover_password' , 'auth_help' , 'token_login');
		if( ! $logged_in )
		{
			//if in app mode
			if( $this->_app_mode == 'json' and $section != 'admin'  )
			{
				
				$this->output->set_status_header('401');
				exit;
			}
			//check if is login page
			if( $section == 'admin'  and ( ! in_array($page, $excused_uri) ) )
			{
				header('Location: /admin/login');
				exit;
			}
		}

		

		return;
	}

	/**
	 * Manages the number of HTTP requests the client is processing in a given time.
	 *
	 * A limit of 20 requests per 5 second is enforced per session.
	 * If the number of requests persists above this threshold - the session is terminated. 
	 *
	 *
	 */
	public function request_manager(  )
	{
		$last_request = $this->php_session->get('last_request');
		//5 second interval
		if(  $last_request+5 >= time() )
		{
			$req_count = $this->php_session->get('request_count');
			$req_count += 1;
			$this->php_session->set('request_count' , $req_count);
			if( $req_count >=  20 )
				$this->error('Too many HTTP requests from your session.');
		}
		else
		{
			$this->php_session->set('request_count' , 0 );
		}
		$this->php_session->set('last_request' , time( ) );
			
	}



	/**
	 * Authenticates and deauthenticates sessions
	 *
	 * On login an array('email' => '' , 'phone' => '' , 'password' => '') is expected. 
	 * On token_login an array('token' => '' , 'challenge' => '' ) is expected
	 * On logout no data is expected
	 * On recover_password an array('email' => '' , 'phone' => '' ) is expected. Captcha checking should be handled seperately
	 *
	 * @param 		String 		$	Action ('login' ,'token_login' , 'logout' , 'recover_password' )
	 * @param 		Array 		$	Checked Data
	 *
	 * @return 		None
	 */
	protected function auth( $action = 'login' , $data = Null )
	{

	}

	/**
	 * Check if a shop user is currently logged in.
	 *
	 * @return 		Bool
	 *
	 */
	protected function is_logged_in( )
	{
		$this->user = $this->php_session->get('user');
		if( is_null($this->user))
			return False;
		if( ! $this->is_timed_out() )
			return False;
		$this->user['last_active'] = time();
		$this->php_session->set('user' , $this->user );

		//apply user permissions globally
		//@see user_helper
		apply_global_perms( $this->user );


		
		return True;
	}

	public function forbidden($message = '')
	{
		if( $this->_app_mode == 'json' )
		{
			$this->output->set_status_header('403');
			die($message);
		}
		else
		{
			header('Location: /error/403?message=' . urlencode(htmlentities($message) ) );
			exit;
		}
	}

	public function not_found( )
	{
		if( $this->_app_mode == 'json' )
		{
			$this->output->set_status_header('404');
			exit;
		}
		else
		{
			header('Location: /error/404');
			exit;
		}
	}


	/**
	 * Checks if the user's session from last activity has timed out
	 *
	 * @return 		Bool
	 */ 
	protected function is_timed_out( )
	{
		$user = $this->php_session->get('user');
		if( $user['last_active'] <= time()-1800 ) //times out in 30 minutes
			return False; 
		return True;	
	}

	/**
	 * Render a page
	 *
	 * This function is an overloaded version to allow for support for 
	 * the JSON app or HTML app
	 *
	 * @overload 		OS_Controller::render
	 *
	 * @param 		String 		$	Page Name
	 * @param 		Array 		$	Data to Pass
	 */
	 protected function render( $page_name , $data )
	 {
	 	//append security headers
	 	$this->append_security_headers( $data );
	 	if( $this->_app_mode === 'json' )
	 	{
	 		//exits at once !
	 		return $this->render_json(  $data );
	 	}
	 	$this->load->view('admin/html/partials/head' , $data );
	 	$this->load->view('admin/html/' . $page_name , $data );
	 	//$this->load->view
	 } 

	 /**
	  * Append security headers to each request 
	  *
	  * If running in app mode, these will be HTTP headers
	  * to be processed by the app for authentication and other minor updates.
	  *
	  * If running in HTML mode,  security meta data will be appended to the data
	  *
	  * @param 		Array 		$	Data
	  *
	  * @return 	None
	  */
	  protected function append_security_headers( &$data )
	  {
	  	if( ! is_array($data) )
	  	{
	  		die('Failed to append security headers because Invalid Data was passed');
	  	}

	  	if( $this->_app_mode === 'json' )
	  	{
	  		header('OS-Security-Token: ' . time() );
	  	}
	  	else
	  	{
	  		//append meta data
	  		$data['security_metadata'] = array();
	  	}
	  }

}
