<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Build extends CI_Controller {


	const THEME = 'sedna';
	
	private $_cities = array("Amandas"
,"Beitbridge"
,"Bindura"
,"Birchenough"
,"Bulawayo"
,"Chatsworth"
,"Chegutu"
,"Chinhoyi"
,"Chipinge"
,"Chiredzi"
,"Chitungwiza"
,"Chivhu"
,"Chivi"
,"Concession"
,"Eiffel"
,"Glendale"
,"Gokwe"
,"Gutu"
,"Gwanda"
,"Gweru"
,"Harare"
,"Hwange"
,"Jerera"
,"Kadoma"
,"Kariba"
,"Karoi"
,"Kwekwe"
,"Marondera"
,"Masvingo"
,"Mazoe"
,"Mt Darwin"
,"Mubayira"
,"Murewa"
,"Mutare"
,"Mutoko"
,"Mvurwi"
,"Norton"
,"Nyanga"
,"Nyika"
,"Plumtree"
,"Rusape"
,"Shurugwi"
,"Triangle"
,"Victoria Falls"
,"Zvishavane"
);
	
	public function __construct()
	{
		error_reporting(0);
		parent::__construct();
		$this->load->database();

		require_once APPPATH . 'libraries/emogrifier-master/Classes/Emogrifier.php';
                $this->emogrifier =  new \Pelago\Emogrifier();

		$this->load->model('signup');
		$this->load->model('ui');
		$this->load->model('product');
		$this->load->library('email');
		
		if( is_array( $this->session->signed_up_data ) )
			$this->_take_to_shop();
		
		if( is_null( $this->session->signup_entry ) )
		{
			header('Location: /#signup');
			return;
		}
		
		
	}
	
	/**
	 * Index Page for this controller.
	 *
	 *
	 */
	public function index()
	{
		if( is_null($this->session->build_step ) )
		{
			$this->session->build_step = 1;
			header('Location: /build#/step/1');
			return;
		}
		else
		{
			$data = array('signup' => $this->session->signup_entry );
			$data['title'] = 'Build your shop :: 263Shop';
			$data['description'] = '';
			$data['keywords'] = '';
		
			$data['cities'] = $this->_cities;
			$data['suggested_subdomain'] = $this->shop->suggest_subdomain(  $data['signup']['shop_name'] );
			
			//@todo get categories
			$data['categories'] = array('Small business','Electronics','Software','Music','Ebooks','Jewelery','Home made products');
			
			//@todo get themes
			$themes = $this->ui->theme->all_themes();
			$data['themes'] = array();
			foreach( $themes as $theme )
			{
				https://assets.263shop.co.zw/theme/comma_fashion/screenshots/4.png
				$theme_info = $this->ui->theme->get_theme_info( $theme );
				foreach($theme_info['screenshots'] as $k => $v )
				{
					$theme_info['screenshots'][$k] = theme_resource($theme , 'screenshots' , $v ); 
				} 
				array_push( $data['themes'] , $theme_info );
				
			}
			
			$this->load->view(Build::THEME . '/build'   , $data );
			
		}
		
	}
	
	public function _inline_css( $html_code , $fetch_css_files = False )
        {
                
                $this->emogrifier->setHtml( $html_code );
                $this->emogrifier->setCss('');
                return $this->emogrifier->emogrify();
                
        }

	//@todo check available
	public function check_subdomain_available( )
	{
		$data = $this->_read_input();
		if( empty($data) or ! $data )
			die('Failed to load data');
		
		$result = array('status' => 'ok' , 'sub' => $data['sub'] );
		
		if( ! $this->shop->is_valid_subdomain( $data['sub'] ) )
		{
			$result['status'] = 'fail';
		}
		else
		{
			//check if proper
			 if( ! $this->signup->is_subdomain_available( $data['sub'] ) )
			 {
			 	$result['status'] = 'fail';
			 }
			 else
			 {
			 	$result['status'] = 'ok';
			 }
		
		}
			
		die( json_encode( $result ) );	
	
	}
	
	public function do_build_shop()
	{
		
		$data = $this->_read_input();
		
		if( $this->session->is_building_shop != True )
		{
			if( $this->session->built_shop == True )
			{
				$this->_take_to_shop();
				return;
			}
			$this->session->is_building_shop = True;
			
		}
		else
		{
			//die('Already building shop, please wait and reload page');
		}	
		
		$expected = array('shop_name','subdomain','alias','theme','has_payment_gateway','gateway_name','gateway_key','gateway_secret','plan','tagline','city','country','short_descr' , 'category');
		
		if( ! isset($data['data'] ) )
			die('Error occured, invalid data posted');
		
		$input = $data['data'];
		
		foreach( $input as $k => $v )
		{
			if( ! in_array( $k , $expected ) )
				die('Unknown key : ' . $k );
			if( is_array($v) or is_object($v) )
				die('Invalid data type detected');
			
				
			$input[ $k ] = $this->security->xss_clean( $v );	
		}
		
		if( strlen( $input['shop_name'] ) < 2 or strlen( $input['shop_name'] ) > 200 )
			$input['shop_name'] = $this->session->signup_entry['shop_name'];
		
		if( $input['has_payment_gateway'] )
		{
			if( $input['gateway_name'] != 'pay4app' && $input['gateway_name'] != 'paynow' )
				die('Unknown payment gateways.');
			
			if( empty( $input['gateway_key'] ) or empty( $input['gateway_secret'] ) )
				$input['has_payment_gateway'] = False;
					
		}
		else
		{
			
			/**
			 * Signup user for Pay4App - to be finalised 
			$input['gateway_name'] = 'pay4app';
			
			//@todo.... signup user for pay4app
			$input['gateway_key'] = rand();
			$input['gateway_secret'] = rand();
			**/
			//must send email notifying user of payment gateway options
			$input['gateway_name'] = '';
			$input['gateway_key'] = '';
			$input['gateway_secret'] = '';

				
		}
		
		if( ! in_array( $input['plan'] , array('entrepreneur','basic','premium') ) )
			$input['plan'] = 'entrepreneur';
		
		$input['country'] = 'ZW';
		
		if( ! in_array( $input['city'] , $this->_cities ) )
			$input['city'] = 'Bulawayo';
		
		
		
		
		
		
		//add user
		$user_id = $this->shop->user->add( -1 ,  $this->session->signup_entry['fullname'] , $this->session->signup_entry['email'] , $this->session->signup_entry['phone_number'] , '*');
		
		if( ! $user_id )
			die('Failed to add user');
		
		$user = $this->shop->user->get( $user_id );
		if( empty($user ) )
			die('Failed to get user');
		
		//create shop
		$shop_id = $this->shop->add( $user['user_id'] , $input['shop_name'] , $input['city'] , $input['country'] , 'Address not provided' , $input['category']  , $input['short_descr'] );
		
		
		
		$shop = $this->shop->get_by_id( $shop_id , False );
		
		
		
		if( empty( $shop ) )
		{
			//remove user
			$this->shop->user->delete( $user['user_id'] );
			die('Failed to add shop to database');
		}
		
		//add essential settings
		$settings = array('contact' => array(	'shop_id' => $shop['shop_id'],
		 					
		 					'notify_sms' => $user['phone_number'] ,
		 					'sms_sender_id' => '263SHOP' ) ,
		 					
				  'order' => array('shop_id' => $shop['shop_id'] ) ,
				  'shipping' => array('shop_id' => $shop['shop_id'] ),
				  'payment' => array(	'shop_id' => $shop['shop_id'] , 
				  			'primary_gateway' => $input['gateway_name'] ,
				  			'primary_api_key' => $input['gateway_key'] ,
				  			'primary_api_secret' => $input['gateway_secret']   ),
				  'theme' => array(	'shop_id' => $shop['shop_id'] , 'theme' => $input['theme'] )	,
				  'analytics' => array('shop_id' => $shop['shop_id'] )	 
				);		   
		$b = $this->shop->settings->add( $shop['shop_id'] , $settings  ,'all' );
		
		if( ! $b )
		{
			//remove user
			$this->shop->user->delete( $user['user_id'] );
			$this->shop->remove( $shop['shop_id'] );
			die(__LINE__);
			die('Failed to add shop settings to database');
		}
		
		
		//subscribe the shop to an account
		$n_months = 1;
		if( $input['plan'] == 'entrepreneur' )
		{
			$n_months = 3;
			$input['plan'] = 'free';
		}
		$b = $this->shop->account->add_subscription( $shop['shop_id'] , date('Y') , date('m') , $input['plan'] , $n_months );
		
		if( ! $b )
		{
			
			//failed to subscribe account
			$this->shop->user->delete( $user['user_id'] );
			$this->shop->remove( $shop['shop_id'] );
			$this->shop->settings->delete( $shop['shop_id'] );
			die('Failed to asubscribe account');
		}

		//reduce sms credit to 5 per month
		$this->db->where('shop_id' , $shop['shop_id'] )->update('shop_account_subscription' , array('sms_credit' => 5) );
		
		//update a few settings
		$shop['contact_email'] = 'N/A';
		$shop['contact_phone'] = 'N/A';	
		$shop['type'] = $input['category'];
		
		$shop['is_active'] = True;
		$shop['is_suspended'] = False;
		//$shop['alias'] = $input['alias'];
		$shop['use_alias'] = False;
		$shop['tagline'] = $input['tagline'];
		$shop['description'] = $input['short_descr'];
		$shop['short_description'] = 'Welcome to ' . $shop['name'] . ' online store, we are based in ' . $shop['city'] . ' Zimbabwe, ' . $shop['tagline'];
		
		//add shop subdomain
		//shop with specified subdomain exists. create new subdomain
		$x = 0;
		$new_subdomain = $input['subdomain'];
		do
		{
			if( $x != 0 )
				$new_subdomain = $input['subdomain'] . '_' . $x;
			$x += 1;	
		}while( ! empty( $this->shop->get_by_subdomain( $new_subdomain ) ) );
		
		
		//put logo
		$logo_data = file_get_contents( APPPATH . 'views/logo.png');
		$tmp_name = '/tmp/' . time() . rand() . rand() . '.jpg';
		file_put_contents( $tmp_name , $logo_data );
		
		$_FILES = array( 'file' => array('tmp_name' => $tmp_name , 'error' => 0 , 'size' => filesize($tmp_name) , 'type' => 'image/jpeg' , 'name' => 'default_263shop_logo.jpeg'  ) );
		
		
		
		$image_id = $this->shop->image->add( $shop['shop_id'] , 'Default 263Shop logo, added on signup' );
		$shop['logo_id'] = $image_id;
		
		
		$shop['subdomain'] = $new_subdomain;
		
		$this->shop->update( $shop['shop_id'] , $shop );
		
		//add to used subdomain pool
 		$this->db->insert('shop_used_subdomains' , array('subdomain' => $shop['subdomain'] , 'shop_id' => $shop['shop_id'] ) );
 		
 		//add product category, latest products
 		$logo_data = file_get_contents( APPPATH . 'views/logo.png');
		$tmp_name = '/tmp/' . time() . rand() . rand() . '.jpg';
		file_put_contents( $tmp_name , $logo_data );
		
		$_FILES = array( 'file' => array('tmp_name' => $tmp_name , 'error' => 0 , 'size' => filesize($tmp_name) , 'type' => 'application/data' , 'name' => 'default_263shop_logo.jpeg'  ) );
		
 		$p_id = $this->product->image->add(  $shop['shop_id'] , 'Default image, you must change this' );
 		
 		$this->product->category->add( $shop['shop_id'] , 'Latest Products' , 'View all our latest and newest products we have to offer' , $p_id , True , -1 );
 	
 		
 		

		
		$user['permission_admin'] = True;
		$user['shop_id'] = $shop['shop_id'];
		$user['is_verified'] = True;
		
		
		$this->shop->user->update( $user['user_id'] , $user );
		$this->shop->user->change_password( $user['user_id'] , $this->session->signup_entry['plaintext_password'] );
		
		
		//apply the theme
		$themes = $this->ui->theme->all_themes();
		if( ! in_array( $input['theme'] , $themes ) )
		{
			//choose first theme
			$input['theme'] = $themes[0];
		}
		
		//apply the theme
		$this->ui->theme->set_shop_id( $shop['shop_id'] );
		$this->ui->theme->apply_theme( $input['theme'] , 'shop' );
		$theme = $this->ui->theme->theme_data();
		
	
 		
 		//send emails
 		$this->session->is_building_shop = False;
 		
 		$this->session->built_shop = True;
 		
 		
 		
 		
 		$data = array('user' => $user , 'shop' => $shop , 'signup_data' => $input );
 		$this->session->signed_up_data  = $data;
 		//send welcome to 263shop
 		
		$html = $this->_inline_css(  $this->load->view( 'sedna/signup_welcome_email' , $data , True ) );
		
		$this->email->from('no-reply-signup@' . OS_DOMAIN , OS_SITE_NAME );
		//send email to 263shop admin notifying of signup
		$this->email->to( array($user['email'] , 'signups@' . OS_DOMAIN ) );

		$this->email->subject( 'Your account has been created, Welcome to '. OS_SITE_NAME );
		$this->email->message($html);
		
		$this->email->send();
		
		//if signed up for pay4app send details
		if( ! $input['has_payment_gateway']  )
		{
			$html = $this->_inline_css(  $this->load->view( 'sedna/signup_pay4app_email' , $data , True ) );
		
			$this->email->from('no-reply-signup@' . OS_DOMAIN , OS_SITE_NAME );
			$this->email->to($user['email']);

			$this->email->subject( OS_SITE_NAME . ' - How to setup a payment gateway '  );
			$this->email->message($html);
		
			$this->email->send();
		}
		
		//send getting started guide
		$html = $this->_inline_css(  $this->load->view( 'sedna/getting_started_email' , $data , True ) );
		
		$this->email->from('trevor@' . OS_DOMAIN , 'Trevor Sibanda' );
		$this->email->to($user['email']);

		$this->email->subject(  OS_SITE_NAME . ' Getting started with your account' );
		$this->email->message($html);
		
		$this->email->send();
		 
		//done, now redirect user to logged in view.

		//delete signup log
		$this->db->db_select('oneshop_www');
		$this->db->where('signup_id' , $this->session->entry['signup_id'] )->delete('signup_temp');
		$this->db->db_select('oneshop');
		$this->_take_to_shop();
		
	}
	
	public function _take_to_shop()
	{
		$this->db->db_select('oneshop');
		$data = $this->session->signed_up_data;
		
		
		
		//create login token
		$shop = $this->shop->get_by_id( $data['shop']['shop_id'] , False );
		
		
		$data['user'] = $this->shop->user->get( $data['user']['user_id'] );
		
		$salt = md5( rand() );	
		$login_url = $shop['url'] . 'admin/token_login?token=' . $data['user']['login_token'] . '&user_id=' . $data['user']['user_id'] . '&url=#/welcome';
		
		//delete session
		$this->session->signup_entry = Null;
		session_destroy();
		
		if(  ! empty( $this->_read_input()) )
			die(json_encode( array('status' =>'ok','url' => $login_url ) ) );
		else
			header('Location: ' . $login_url );
	}

	
	public function _read_input() 
	{
		return json_decode($this->input->raw_input_stream,true);
	}
}

