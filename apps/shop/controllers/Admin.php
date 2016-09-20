<?php


require_once( APPPATH . 'core/OS_AdminController.php');

class Admin extends OS_AdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	


	public function index()
	{
		//check appmode
		$passed_mode = $this->input->post('appmode');
		$app_mode = ( is_null( $passed_mode ) ? 'app' : $passed_mode);
		if( ! in_array($app_mode, array('app' , 'html')) )
			$app_mode = 'html';
		$this->set_app_mode( $app_mode );
		//load the landing page
		$data = array();

		$data = $this->load_shop( array('title' => OS_SITE_NAME . ' Admin' , 'description' => 'Manage your '. OS_SITE_NAME .' account from here') );
		
		$this->load->view('admin/' . $app_mode . '/index' , $data );		
	}

	public function login()
	{
		$this->load->helper('captcha');

		$this->data = $this->load_shop( array('title' => 'Login to your shop' , 'description' => 'Login to access your shop admin panel' ,'keywords'=>'login' ));
		

		$this->data['email'] = '';
		switch( $this->input->get('action') )
		{
			case 'confirm_email':
			{
				//confirm sms
				$challenge = $this->input->get('challenge');

				$users = $this->shop->user->get_shop_users( $this->data['shop']['shop_id'] );
				foreach ($users as $user) 
				{
					

					$hash = md5( md5($user['shop_id']) . $user['email'] );
					if( $hash  == $challenge )
					{
						
						
						$this->shop->logger->user_id( $user['user_id'] );
						$this->shop->logger->auth('Verified your email address and activated your account');
						$old = $user['is_verified'];
						$user['is_verified'] = True;
						$this->shop->user->update($user['user_id'] , $user );
						if( $old )
						{
							//take to already rendered.
							$data = array();
							$data['shop'] = $this->data['shop'];
							$data['action'] = 'already_verified';
							$data['user'] = $user;
							$this->sys_render('login/verify_account' , $data );
						
						}else
						{
							$data = array();
							$data['shop'] = $this->data['shop'];
							$data['action'] = 'account_verified';
							$data['user'] = $user;
							$this->sys_render('login/verify_account' , $data );
						}

					} 
				}
				return;
			}
			break;
			case 'confirm_sms':
			{
				//confirm sms
				$challenge = $this->input->get('challenge');

				$users = $this->shop->user->get_shop_users( $this->data['shop']['shop_id'] );
				foreach ($users as $user) 
				{
					

					$hash = md5( md5($user['shop_id']) . $user['phone_number'] );
					if( $hash  == $challenge )
					{
						
						
						$this->shop->logger->user_id( $user['user_id'] );
						$this->shop->logger->auth('Verified your phone number and activated account');
						$old = $user['is_verified'];
						$user['is_verified'] = True;
						$this->shop->user->update($user['user_id'] , $user );
						if( $old )
						{
							//take to already rendered.
							$data = array();
							$data['shop'] = $this->data['shop'];
							$data['action'] = 'already_verified';
							$data['user'] = $user;
							$this->sys_render('login/verify_account' , $data );
						
						}else
						{
							$data = array();
							$data['shop'] = $this->data['shop'];
							$data['action'] = 'account_verified';
							$data['user'] = $user;
							$this->sys_render('login/verify_account' , $data );
						}

					} 
				}
				return;
			}
			break;
		}

		//hack, inheritance issue
		$this->form_validation->set_rules('email' , 'Email address' , 'valid_email|required' );
		$this->form_validation->set_rules('password' , 'Password' , 'min_length[6]|max_length[50]|required');
		$this->form_validation->set_rules('captcha' , 'Captcha code' , 'min_length[4]|max_length[50]');

		if(  $this->is_logged_in() )
		{
			header('Location: /admin/#/dashboard');
			return;
		}
		//@todo if using an alias, redirect to oneshop secure login
		if( $this->data['shop']['use_alias']  )
		{
			$data = array('name' => $this->data['shop']['name'] , 'url' => 'http://' . $this->data['shop']['alias'] );
			//challenge
			$data['c'] = md5( 'secure' . $data['name'] . $data['url'] );

			$data = urlencode( base64_encode( json_encode($data) )	 );
			header('Location: '. OS_BASE_SITE . '/home/login?site=' . $data );
			return;
		}

		//enable captcha on login form
		$vals = array(
        'img_path'      => ASSETS_DIR . 'captcha/',
        'img_url'       => ASSETS_BASE . 'captcha/',
        'font_path'     => APPPATH . 'third_party/fonts/shift_bold.ttf',
        'img_width'     => '250',
        'img_height'    => 50,
        'expiration'    => 7200,
        'word_length'   => 6,
        'font_size'     => 24,
        'img_id'        => 'Imageid',
        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyz',

        // White background and border, black text and red grid
        'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 0, 255)
        )
		);

		$cap = create_captcha($vals);

        $errors = array();


		if( $this->form_validation->run() )
		{


			$email = $this->input->post('email');
			$this->data['email'] =  $email;


			
			$password = $this->input->post('password');
			$user = $this->shop_user->get_by_email( $email );
            $captcha = $this->input->post('captcha');
            $last_captcha = $this->php_session->get('login_captcha');
            if( is_null($last_captcha) )
                die('You must provide captcha code');
            
            if(  $captcha !== $last_captcha['word'] )
            {
            	
                $errors[] = "The captcha code you entered is not valid";
                
                $this->php_session->set('login_captcha' , $cap );
                

                $this->data['enable_captcha'] = True;
                $this->data['captcha'] =  $cap;
                $this->data['errors'] = $errors;

                $this->sys_render('login/login' , $this->data );
				return;
                return;
            }
			else if( isset($user['user_id'])  and $user['shop_id'] == $this->data['shop']['shop_id'] )
			{
					
				$this->shop->logger->user_id( $user['user_id'] );

				if(  $this->shop->user->is_password( $user , $password ) == False )
				{
					$this->form_validation->set_message('password','Invalid email address or password.');
					$user['failed_login_attempts'] += 1;
					
					if( $user['failed_login_attempts'] >= 5)
					{
						//enable captcha, too many login attempts
						$this->data['enable_captcha'] = True;
					}


					if( $user['failed_login_attempts'] == 5 && account() != 'free')
					{
						//@todo send notification email/sms to user alerting him/her
						$message = 'Warning. There have been more than 5 failed login attempts to your account. Your account will be suspended on 10 failed attempts';
						
						$this->system->pushnotification->push_sms( $this->data['shop']['shop_id'] ,  $user['phone_number'] , $message ,  9 );
					}
					if( $user['failed_login_attempts'] == 10 )
					{
						//@todo lock out account and send notification sms and email to user
						$user['is_suspended'] = True;
						$message = $user['fullname'] . ' your account has been suspended due to 10 failed login attempts. Please contact the administrator to help you recover your account';
						$email = array();
				 		$email['type'] = 'danger';
				 		$email['header'] = 'Your account has been suspended !';

				 		$email['message'] = $message . '. This is usually a sign of an attempt to hack into your account, we recommend you recover your account and change your password';
				 		$email['action_link'] = $this->data['shop']['url'] . 'admin/login?email='. $user['email'] ;
				 		$email['action_message'] = 'Login to recover account';
				 		$email['footer_msg'] = 'If this persists, please contact the administrator.';
						$email['footer_action'] = '';
						$email['footer_action_url'] = ''; 		
				 		
				 		$this->data['shop']['contact'] = $this->shop->settings->get_contact_settings( $this->data['shop']['shop_id'] );
				 		
				 		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] ,  array() , array() );
						
						//push email
						$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'] , 'WARNING: Your account has been suspended' , $html , 5 );			 		
						
						//add notification
						$this->shop->logger->auth('Suspended account due to multiple failed login attempts '  );

					}
										
					$this->shop->logger->auth('Failed login attempt from ' . $this->input->ip_address() );

					$this->shop->user->update( $user['user_id'] , $user );
					$this->php_session->set('login_captcha' , $cap );

					$this->data['enable_captcha'] = True;
					$this->data['captcha'] =  $cap;
                    $this->data['errors'] = $errors;

					$this->sys_render('login/login' , $this->data );
					return;
				}

				$user['failed_login_attempts'] = 0;
				//generate a new login token
				$user['login_token'] = $this->shop->user->make_login_token($user);
				$this->shop->user->update( $user['user_id'] , $user );
				


				//sweet
				$user['last_active'] = time();
				$this->php_session->set('user' , $user );
				//record login
				$this->shop->logger->auth($user['fullname'] .  ' logged in at ' . date('r') . ' from ' . $this->input->ip_address() );
				
				

				header('Location: /admin/' );
				return;
			}
		}

		$this->php_session->set('login_captcha' , $cap );

		$this->data['enable_captcha'] = True;
		$this->data['captcha'] =  $cap;
        $this->data['errors'] = $errors;

		
		$this->sys_render('login/login' , $this->data );
	}

	public function token_login(   )
	{
		
		$this->data = $this->load_shop( array('title' => 'Login to your shop' , 'description' => 'Login to access your shop admin panel' ,'keywords'=>'login' ));
		
		$token_key = $this->input->get_post('token');
		
		$user_id =  $this->input->get_post('user_id');

		$user = $this->shop->user->get( $user_id );

		$target_url = $this->input->get('url');

		if( empty($user) or $user['shop_id'] != $this->data['shop']['shop_id'] or $user['login_token'] != $token_key )
		{

			header('Location: /admin/login');
			return;
		}

		//successful login, now remove token
		$user['failed_login_attempts'] = 0;
		//generate a new login token
		$user['login_token'] = $this->shop->user->make_login_token($user);
		$this->shop->user->update( $user['user_id'] , $user );

		//sweet
		$user['last_active'] = time();
		$this->php_session->set('user' , $user );
		//record login
		$this->shop->logger->user_id(  $user['user_id'] );
		$this->shop->logger->auth($user['fullname'] .  ' logged in at ' . date('r') . ' from ' . $this->input->ip_address() );
		
		if( ! is_null($url) )
		{
			header('Location: /admin/' . $url );
		}
		header('Location: /admin/' );
		return;


	}

	public function recover_password( )
	{
		$this->load->helper('captcha');

		$this->data = $this->load_shop( array('title' => 'Login to your shop' , 'description' => 'Login to access your shop admin panel' ,'keywords'=>'login' ));
		

		//enable captcha on login form
		$vals = array(
        'img_path'      => ASSETS_DIR . 'captcha/',
        'img_url'       => ASSETS_BASE . 'captcha/',
        'font_path'     => APPPATH . 'third_party/fonts/shift_bold.ttf',
        'img_width'     => '250',
        'img_height'    => 50,
        'expiration'    => 7200,
        'word_length'   => 6,
        'font_size'     => 24,
        'img_id'        => 'Imageid',
        'pool'          => '0123456789abcdefghijklmnopqrstuvwxyz',

        // White background and border, black text and red grid
        'colors'        => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 0, 255)
        )
		);

		$this->form_validation->set_rules('email','Email address' ,'required|valid_email');
		$this->form_validation->set_rules('csrf_token' ,'CSRF Token' , 'required');
		$this->form_validation->set_rules('captcha' ,'Captcha code' , 'required');

		


		if( $this->form_validation->run() )
		{
			
			if( $this->input->post('captcha') != $this->php_session->get('recover_password_captcha') )
			{
				
				die('<script>alert("Captcha codes do not match. Please try again");history.back();</script>');
			}
			if( $this->input->post('csrf_token') != $this->php_session->get('recover_password_csrf') )
			{
				die('CSRF Token mismatch');
			}

			$users = $this->shop->user->get_all_accounts_by_email( $this->input->post('email') );
    		if( empty($users ) )
    		{
    			die('<script>alert("Sorry, no user by that email address exists.");history.back();</script>');
    		}
    		$user = array();
    		foreach( $users as $a_user )
    		{
    			if( $a_user['shop_id'] == $this->data['shop']['shop_id'] )
    			{

    				$user = $a_user;
    				break;
    			}
    		}

    		if( empty($user ) )
    		{
    			
    			die('<script>alert("Sorry, no user by that email address exists.");history.back();</script>');
    		}

    		//token login and change password
    		$url = $this->data['shop']['url'] . 'admin/recover_password?challenge=' . md5( md5( $user['login_token'] . '263shop' ) ) . '&token=' . $user['login_token'] . '&user_id=' . $user['user_id'];

    		//send email
    		$email = array();
	 		$email['type'] = 'danger';
	 		$email['header'] = 'You requested a new password';

	 		$email['message'] = 'You requested a new password.<br/>If you did not request a new password most likely someone is trying to hack into your account.<br/><br/>We recommend you report this to 263Shop support team and change your password.';
	 		$email['action_link'] = $url;
	 		$email['action_message'] = 'Change your password';
	 		$email['footer_msg'] = 'If this persists, please contact the administrator.';
			$email['footer_action'] = '';
			$email['footer_action_url'] = ''; 		
	 		
	 		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] ,  array() , array() );
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'] , 'You requested a password change' , $html , 5 );			 		
			
			//add notification
			$this->shop->logger->user_id(  $user['user_id'] );
			$this->shop->logger->auth('Requested a password change. Details sent to email '  );
				
			die('<script>alert("An email has been sent with a link to change your password."); window.location="/admin/login";</script>');

		}

		$cap = create_captcha($vals);

        $errors = array();



        $this->data['csrf_token'] = md5( rand() ); 

		$this->php_session->set('recover_password_captcha' , $cap['word'] );
		$this->php_session->set('recover_password_csrf' ,$this->data['csrf_token'] );


		$this->data['enable_captcha'] = True;
		$this->data['captcha'] =  $cap;
        $this->data['errors'] = $errors;
        $this->data['is_password_changed'] = False;

        if( ! is_null($this->input->get('challenge')) && ! is_null($this->input->get('token')) && ! is_null($this->input->get('user_id')) )
		{
			
			$challenge = $this->input->get('challenge');
			$token = $this->input->get('token');
			$user_id = $this->input->get('user_id');
			
			$user = $this->shop->user->get( $user_id );
			if( empty($user))
				die('Sorry this user has been deleted');

			if( md5( md5( $user['login_token'] . '263shop')  ) != $challenge )
				die('Sorry, the challenge failed, Make sure you type the url correctly');

			//show recover password page
			$password = $this->shop->user->generate_password();

			$this->shop->user->change_password( $user['user_id'] , $password );

			$this->shop->logger->user_id( $user['user_id'] );
				$this->shop->logger->action('Changed password requested through recover password');
			
			$email = array();
			$email['type'] = 'warning';
			$email['header'] = 'You have changed your password.';

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $user['fullname'] . '<br/>This is your new password.<br/> As soon as you login please change this password and delete this email.<br/><b>Your new password is ' . $password . '</b>';
			$email['action_link'] = $this->data['shop']['url'] . 'admin#/settings/users' ; 
			$email['action_message'] = 'Login to my account';
			$email['footer'] = 'Remember to delete this email!!!';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'], $email['header'] , $html );
			
			$this->data['is_password_changed'] = True;
		}

		
		$this->sys_render('login/recover_password' , $this->data );


	}

	public function account_subscribe(   )
	{
		if( ! is_admin() )
		{
			die('<script>alert("Only admin can subscribe or upgrade plans. Please login using admin details");</script>');
		}
		$plan = $this->input->get_post('plan');
		$period = $this->input->get_post('period');
		$is_upgrade = $this->input->get_post('is_upgrade');

		if( is_null($plan) or is_null($period) or is_null($is_upgrade)  )
			die("<script>alert('Please use a proper URL');</script>");

		if( ! in_array($plan , array('free','basic','premium')))
			$plan = 'basic';

		if( ! in_array($period , array(1,3,6,12)))
			$period = 6;

		$is_upgrade = intval($is_upgrade);

		if( $plan == 'free' && $period >= 6 )
		{
			die("<script>alert('You cannot subscribe to free plan for more than 6 months.');</script>");
		}

		//check if form is running
		$data = $this->load_shop();
		$data['salt'] = md5(time());
		$data['challenge'] = md5( rand() . $data['shop']['shop_id'] . $plan . $is_upgrade . $period . $data['salt'] );
		
		$data['sub'] = array('plan'=>$plan,'period'=>$period,'is_upgrade'=>$is_upgrade);

		$this->sys_render('account/subscribe' , $data );

	}

	
	public function create_user(  )
	{
		$data  = $this->load_shop();
		$user_id = $this->shop_user->add($data['shop']['shop_id'] , 'Trevor Sibanda' , 'trevorsibb@gmail.com' , '059627-j-19' );
		$this->shop_user->change_password(  $user_id , 'password' );
		print_r( $this->shop_user->get( $user_id ) );
	}

	public function logout( )
	{
		$this->php_session->destroy();
		if( $this->input->is_ajax_request() )
		{
			$this->output->set_status_header(401 , 'Logged out');
			return;
		}
		header('Location: /admin/login');
		return;
	}

	

}