<?php

class Action extends CI_Controller {


	private $theme = 'sedna';
	
        public function __construct()
        {
                parent::__construct();
                $this->load->library('form_validation');
                $this->load->library('email');

                require_once APPPATH . 'libraries/emogrifier-master/Classes/Emogrifier.php';

                $this->emogrifier =  new \Pelago\Emogrifier();

                $this->load->helper(array( 'url'));
                $this->load->database();
                $this->db->db_select('oneshop_www');
        }

        public function _inline_css( $html_code , $fetch_css_files = False )
        {
                
                $this->emogrifier->setHtml( $html_code );
                $this->emogrifier->setCss('');
                return $this->emogrifier->emogrify();
                
        }

        public function signup( )
        {
        	$this->form_validation->set_rules('admin_phone' , 'Administrator phone number' , 'required');
        	$this->form_validation->set_rules('admin_email', 'Shop administrator email' , 'required|valid_email');
        	$this->form_validation->set_rules('admin_fullname' , 'Shop admin fullname' , 'required');
        	$this->form_validation->set_rules('admin_password' , 'Password' , 'required');
        	$this->form_validation->set_rules('shop_name' , 'Shop name' , 'required');
        	$this->form_validation->set_rules('csrf_token' , 'XSRF Token','required' );
        	$this->form_validation->set_rules('selected_plan' , 'Selected plan' , 'in_list[entrepreneur,basic,premium]');
        	
        	if( $this->form_validation->run()  )
        	{
        		$phone = $this->input->post('admin_phone' , true );
        		$email = $this->input->post('admin_email',true );
        		$fullname = $this->input->post('admin_fullname' , true );
        		$password = $this->input->post('admin_password');
        		$shop_name = $this->input->post('shop_name' , true );
        		$xsrf_token = $this->input->post('csrf_token' , true );
        		$selected_plan = $this->input->post('selected_plan' );
        		
        		if( ! in_array( $selected_plan , array('free','premium','basic','entrepreneur') ) )
        			$selected_plan = 'free';
        		
        		$entry = array();
        		$signup_id = 0;
        		
        		if( $xsrf_token != $this->session->csrf_signup_token && is_null($this->session->signup_data  ) )
        		{
        			die('<script>alert("Incorrect xsrf token passed. Make sure you have cookies turned on"); history.back(0); </script>');
        			
        		}
        		
        		
        		
        		
        		if( empty($entry)  )
        		{
        			$entry = array();
        			$entry['fullname'] = $fullname;
        			$entry['email'] = $email;
        			$entry['phone_number'] = $phone;
        			$entry['plaintext_password'] = $password;
        			$entry['shop_name'] = $shop_name;
        			$entry['has_captcha'] = false;
        			$entry['ip_addr'] = $this->input->ip_address();
        			$entry['user_agent'] = $this->input->user_agent();
        			$entry['date_created'] = date('Y-m-d');	
        			$entry['challenge'] = md5('263shop' . md5($entry['email']   ) );
        			$entry['plan'] = $selected_plan;
        			
        			$this->db->insert('signup_temp' , $entry );
        			
        			$signup_id = $this->db->insert_id();
        			
        			
        			
        		}
        		else
        		{
        			$entry['reminder_count'] += 1;
        			$signup_id = $entry['signup_id'];
        		}
        		
        		$this->_send_signup_email($entry , $signup_id );
			$data = array();
			$data['signup_id'] = $signup_id;
			$data['signup_url'] = OS_BASE_SITE . '/action/continue_signup?signup_id=' . $signup_id . '&challenge=' . $entry['challenge'];
			$data['signup_cancel_url'] = $data['signup_url'] . '&action=cancel';
			$data['csrf_token'] = $this->session->csrf_token_change_email = md5(rand()*time());
		
			$data['facebook_url'] = 'http://www.facebook.com/263shop';
			$data['twitter_url'] = 'http://www.twitter.com/263shopzw';
			$this->load->view($this->theme . '/verify_email' , $data ); 
			return;
        	
        	}
		die( '<html>' . validation_errors() . '</html>' );
        	header('Location: /');
        	
        }
        
        public function continue_signup( )
        {
        	
        	$challenge = $this->input->get('challenge');
        	$signup_id = $this->input->get('signup_id');
        	
        	$this->session->signup_entry = null;
        	
        	if( is_null($challenge) or is_null($signup_id) )
        	{
        		die('Please check the link and try again');
        	}
        	
        	
        	$query = $this->db->get_where('signup_temp' , array('signup_id' => $signup_id , 'challenge' => $challenge ) );
        	
        	$entry = $query->row_array();
        	if(  empty($entry) )
        		die('<script>alert("The challenge key you passed is invalid"); history.back(0);</script>');
        		
        	$local = md5('263shop' . md5( $entry['email'] ) );
        	
        	if( $local != $challenge )
        		die('Failed to verify email. Please make sure you entered the correct link');
        	
        	//ready to signup
        	$entry['time_verified'] = date('Y-m-d H:m:s');
        	$entry['ip_addr'] = $this->input->ip_address();
        	$entry['user_agent'] = $this->input->user_agent();
        	$entry['has_captcha'] = 0;
        	
        	$this->db->where('signup_id' , $entry['signup_id'] )->update('signup_temp' , $entry );
        	
        	//setup session
        	$this->session->signup_entry = $entry;
        	$this->session->signed_up_data = Null;
        	header('Location: /build');
        		
        }
        
        public function signup_change_email(  )
        {
        	$this->form_validation->set_rules('new_email' , 'New email address' , 'valid_email|required' );
        	$this->form_validation->set_rules('xsrf_token' , 'XSRF Token' , 'required');
        	
        	if( $this->form_validation->run() )
        	{
        		$new_email = $this->input->post('new_email');
        		if( $this->input->post('xsrf_token') != $this->session->csrf_token_change_email ) 
        		{
        			die('<script>alert("Invalid CSRF token"); history.back(0); </script>');
        		}
        		if( is_null($this->session->signup_data ) )
        		{
        			die('Sorry your session has expired');
        		}
        		
        		$data = $this->session->signup_data;
        		
        		$data['email'] = $new_email;
        		$data['challenge'] = md5('263shop' . md5($new_email  ) );
        		
        		$this->db->where('signup_id' , $data['signup_id'] )->update('signup_temp' , $data );
        		$this->_send_signup_email(  $data , $data['signup_id'] );
        		header('Location: /action/signup');
        		
        	}
        }
        
        
        public function _send_signup_email( $entry , $signup_id )
        {
        	$data = array();
		$data['signup'] = $entry;
		$this->session->signup_data = $entry;
		
		$data['signup_id'] = $signup_id;
		$data['signup_url'] = OS_BASE_SITE . '/action/continue_signup?signup_id=' . $signup_id . '&challenge=' . $entry['challenge'];
		$data['signup_cancel_url'] = $data['signup_url'] . '&action=cancel';
		$data['xsrf_token'] = $this->session->csrf_token_change_email = md5(rand()*time());
		
		$data['facebook_url'] = 'http://www.facebook.com/263shop';
		$data['twitter_url'] = 'http://www.twitter.com/263shop';
		
		//send email0
		$html = $this->_inline_css( $this->load->view( $this->theme . '/signup_email' , $data , True ) );
		
		$this->email->from('no-reply-signup@' . OS_DOMAIN , OS_SITE_NAME );
		$this->email->to($entry['email']);

		$this->email->subject( OS_SITE_NAME . ' :: Verify your email address');
		$this->email->message(    $html );
		
		//for debug
		file_put_contents('/tmp/verify_email.html' , $html);
		
		if( ! $this->email->send() )
		{
			die('<script>alert("Failed to send email to the provided email address");history.back(0);</script>');
		};
        }
        
        
        public function login()
        {
        	$this->form_validation->set_rules('user_email' , 'Email address' , 'valid_email|required');
        	$this->form_validation->set_rules('user_password' , 'Password' , 'min_length[4]|required');
        	$this->form_validation->set_rules('csrf_token' , 'CSRF Token' , 'required');
        	//$this->form_validation->set_rules('captcha' , 'Captcha Code' ,'');
        	
        	if( $this->input->get('action') == 'token_login_select_account')
        	{
        		if( is_array( $this->session->login_tokens ) && count( $this->session->login_tokens ) > 1 && is_numeric( $this->input->get('token') ) && $this->input->get('token') < count( $this->session->login_tokens ) )
        		{
        			$token = $this->session->login_tokens[ $this->input->get('token') ];
                                if( empty($token) or is_null($token))
                                        return header('Location: /auth/login');
        			//can only login once
        			session_destroy();
        			$login_url = $token['shop']['url'] . 'admin/token_login?token=' . $token['user']['login_token'] . '&user_id=' . $token['user']['user_id'];
				header('Location: ' . $login_url );
				return;	  
        		}
        		header('Location: /auth/login');
        	}
        	
        	
        	if( $this->form_validation->run() )
        	{
        		$email = $this->input->post('user_email');
        		$password = $this->input->post('user_password');
        		$csrf_token = $this->input->post('csrf_token');
        		$captcha_code = $this->input->post('captcha');
        		
        		
        		if( $csrf_token != $this->session->csrf_login_token )
        		{
                                echo $this->session->csrf_login_token . ' - ' . $csrf_token;
        			die('CSRF Token do not match');
        		}
        		/*
        		if( $captcha_code != $this->session->login_captcha_code )
        		{
        			
        			header('Location: /auth/login?email=' . $email ); 
        		}
        		*/
        		
        		$this->load->model('shop');
        		$this->db->db_select('oneshop');
        		
        		$users = $this->shop->user->get_all_accounts_by_email( $email );
        		if( empty($users ) )
        		{
        			$this->session->login_err_msg = 'Invalid email address or password entered';
        			header('Location: /auth/login');
        			return;
        		}
        		
        		$accounts = array();
        		foreach( $users as $user )
        		{
        			if( $this->shop->user->is_password( $user , $password ) )
        			{
        				array_push( $accounts , $user );
        			}
        		
			}
			
			
			
			if( count($accounts) == 1 )
			{
				//only one account using this email and password
				//login
				$user = $accounts[0];
				$shop = $this->shop->get_by_id( $user['shop_id'] );
				$login_url = $shop['url'] . 'admin/token_login?token=' . $user['login_token'] . '&user_id=' . $user['user_id'];
				header('Location: ' . $login_url );
				return;
			}
        		else
        		{
        			$login_tokens = array();
        			foreach(  $accounts as $user )
        			{
        				$shop = $this->shop->get_by_id( $user['shop_id'] );
        				$shop['logo'] = $this->shop->image->get( $shop['logo_id'] );
        				$url = '/action/login?action=token_login_select_account&token=' . count( $login_tokens );
        				array_push( $login_tokens , array( 'shop' => $shop , 'user' => $user  ,'url' => $url ) );
        			}
        			$this->session->login_tokens = $login_tokens;
        			$this->session->login_tokens_expire_time = time() + (60*10); //expires in 10 minutes
        			$data = array('login_tokens' => $login_tokens );
        			$this->load->view('sedna/login-select-account' , $data );
        		}
        		
        	}
        	else
        	{
        		header('Location: /auth/login');
        	}
        
        }
}
