<?php
/**
 * OneShOP Admin Users API
 *
 * Admin users
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Users extends OS_AdminController
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

 	/** Get currently logged in user **/
 	public function me( )
 	{
 		$user = $this->php_session->get( 'user');
 		$user = $this->shop->user->get( $user['user_id'] );
 		//dnt show password
 		unset( $user['password'] );
 		unset( $user['login_token']);
 		$this->render('user' , $user );
 	}

 	/** Get a user given user id and permissions **/
 	public function get( $user_id )
 	{
 		if( $user_id == "me")
 			return $this->me();
 	}

 	/** Get all shop users **/
 	public function all( )
 	{
 		$users = $this->shop->user->get_shop_users( $this->data['shop']['shop_id'] );
 		foreach ($users as $key => $user) 
 		{
 			unset( $users[ $key ]['password'] );
 			unset( $users[ $key ]['login_token'] );
 				
 		}
 		$this->render('users' , $users );
 	}

 	/** Add a user if the user has valid permissions **/
 	public function add( )
 	{
 		$json = $this->read_input();

 		$me = $this->php_session->get('user');
 		$user = isset($json['user']) ? $json['user'] : array();
 		if( empty($user))
 			$this->forbidden();

 		if( ! is_admin() )
 		{
 			$this->forbidden('You are not an administrator');
 		}

 		$user_count = $this->shop->user->count_shop_users(  $me['shop_id'] );
 		
 		if(  $user_count >= account_can('max_users') )
 		{
 			$this->error('Sorry, your account cannot add anymore users. Please upgrade your plan to add more users');
 		}

 		//@todo check account user limits, basic account cannot add more users

 		$expected = array('fullname' , 'email' , 'phone_number' , 'national_id' , 'gender' , 'permission_manage_products','permission_manage_orders','permission_blog','permission_designer','permission_pos' , 'is_suspended' );
		foreach( $user as $key => $value )
 		{
 			if( ! in_array($key , $expected))
 			{
 				unset( $user[$key] );
 				continue;
 			}
 			$user[$key] = xss_clean( $value );
 			if( is_null($user[$key]))
 				$this->forbidden('Invalid data in request');
 			if( is_string($user[$key]) or is_numeric($user[$key]) )
 				continue;
 			else
 				$this->forbidden('Invalid data in request');
 		}


 		//same email can be used in different shops
 		//but not in same shop
 		//i.e tinashe.{OS_BASE_DOMAIN} might have a user with tinashe@gmail.com
 		// and trevor.{OS_BASE_DOMAIN} might have the same user tinashe@gmail.com
 		// but two users cannot exist in the same shop with same email and phone number
 		$existing = $this->shop->user->get_shop_users( $this->data['shop']['shop_id'] );

 		foreach( $existing as $e_user )
 		{
 			if( $e_user['email'] == $user['email'] )
 			{
 				return $this->error('A user with this email address already exists - ' . $user['fullname']);
 			}
 			if( $e_user['phone_number'] == $user['phone_number'] )
 			{
 				return $this->error('A user with this phone number already exists - ' . $user['fullname']);
 			}
 		}



 		$password = $this->shop->user->generate_password();

 		//add user
 		$user_id = $this->shop->user->add( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'] , $user['phone_number'] , $user['national_id']  );

 		if( $user_id === false )
 		{
 			return $this->error('Failed to add user, unknown server error ');
 		}
 		
 		//change password
 		$this->shop->user->change_password($user_id , $password );
 		
 		//log adding user
 		$this->shop->logger->action('Created new user - ' . $user['fullname'] . ' - ' . $user['email'] . ' ' . $user['phone_number'] );
		
		//send out activation email
		if( 1 )
 		{
 			//@todo generate account activation email
 			$challenge = md5( md5($this->data['shop']['shop_id']) . $user['email']  );
 			$email = array();
			$email['type'] = 'info';
			$email['header'] = 'Verify your account.';

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $user['fullname'] . '<br/>To be able to login to your account, you need to verify your email address. <br/><h3>Your password is ' . $password . '</h3><p>We recommend you change this as soon as you login.</p>';
			$email['action_link'] = $this->data['shop']['url'] . 'admin/login?action=confirm_email&challenge=' . $challenge; 
			$email['action_message'] = 'Verify your email address';
			$email['footer'] = 'If there has been a mistake, simply ignore this email.';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
			
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'], 'Activate your ' . $this->data['shop']['name'] . ' account', $html );
 			//@todo send sms link
 			//@todo move generate sms confirmation link to model under system
 			$challenge = md5( md5($this->data['shop']['shop_id']) . $user['phone_number']  );
 			$url = $this->data['shop']['url'] . 'admin/login?action=confirm_sms&challenge=' . $challenge; 
 			$message = 'To activate your account: ' . $url ;
 			if( account() != 'free' )
 				$this->system->pushnotification->push_sms( $this->data['shop']['shop_id'] ,  $user['phone_number'] , $message ,  7 );

 		}

 		//now log user account 
 		$this->shop->logger->user_id(  $user_id );
 		$this->shop->logger->action('Account created by admin');
 		$this->shop->logger->user_id( $me['user_id'] );

		$this->render('status' , array('status'=>'ok'));
 	}

 	/** Delete a user , given the user id and appropriate permissions. Cannot delete self or leave shop with no users **/
 	public function delete( $user_id = null )
 	{
 		if( is_null($user_id))
 			$this->forbidden();

 		if( ! is_admin() )
 		{
 			return $this->forbidden('Only administrator can delete accounts');
 		}

 		$user = $this->shop->user->get( $user_id );
 		$me = $this->php_session->get('user');

 		if( empty($user) or $user['shop_id'] != $this->data['shop']['shop_id'] )
 			$this->forbidden();

 		//if user is admin, you cannot delete him
 		if( $user['permission_admin'] )
 		{
 			return $this->error('User is an administrator, cannot be deleted.');
 		}

 		//if user is you, you cannot delete
 		if( $user['user_id'] == $me['user_id'] )
 		{
 			return $this->error('You cannot delete yourself.');
 		}

 		//delete user
 		$this->shop->logger->action('Deleted user - ' . $user['fullname'] );

 		$this->shop->user->delete( $user['user_id'] );

 		//send email
 		$email = array();
		$email['type'] = 'warning';
		$email['header'] = 'Your account has been deleted.';

		$email['subheader'] = '';
		$email['message'] = 'Hello ' . $user['fullname'] . '<br/>Your account has been permanently deleted by the administrator. <br/> ';
		$email['action_link'] = $this->data['shop']['url'] ; 
		$email['action_message'] = 'Visit shop website';
		$email['footer'] = 'If there has been a mistake, simply ignore this email or contact the shop administrator for more information.';

		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
		//push email
		$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'], 'Notice: Your account has been deleted ' , $html );
		
		$this->render('status' , array('status'=>'ok'));
 	}

 	/** Update a user's details **/
 	public function update( )
 	{
 		$json = $this->read_input();

 		$json = isset($json['user']) ? $json['user'] : array();

 		if( empty($json) )
 			$this->forbidden();



 		$user = $this->shop->user->get( intval($json['user_id']) );
 		$me = $this->php_session->get('user');

 		if( empty($user) or $user['shop_id'] != $this->data['shop']['shop_id'] )
 		{
 			$this->forbidden();
 		}

 		if( (! is_admin()) and ($user['user_id']  != $me['user_id'] ))
 		{
 			$this->forbidden('You cannot update another user\'s profile if you are not administrator ');
 		}


 		//keys which can be updated
 		$keys = array('fullname','gender' ,'email','phone_number','national_id');
 		$priv_keys = array('permission_manage_products','permission_manage_orders','permission_blog','permission_designer','permission_pos' , 'is_suspended');
 		
 		if( is_admin() )
 		{
 			foreach( $priv_keys as $k )
 				array_push( $keys , $k );


 		}

 		foreach( $user as $key => $value )
 		{
 			if( ! in_array($key , $keys))
 			{
 				unset( $json[$key] );
 				continue;
 			}
 			if( is_string($json[$key]) or is_numeric($json[$key]) )
 				continue;
 			else
 				$this->forbidden();
 		}

 			


 		$email_changed = False;
 		$phone_changed = False;

 		//@todo verify phone number
 		//@todo verify email

 		foreach ($keys as $key) 
 		{
 			if( $user[$key] != $json[$key] )
 			{
 				//update this entry
 				if( $key == 'email')
 					$email_changed = True;
 				if( $key == 'phone_number')
 					$phone_changed = True;
 				$user[$key] = $json[ $key ];
 			}
 		}
 		//now update
 		//just to be extra sure :)
 		unset($user['shop_id']);
 		unset($user['password']);
 		unset( $user['failed_login_attempts']);
 		unset( $user['date_joined']);
 		unset($user['last_accessed']);
 		unset($user['is_verified']);

 		//cannot suspend another admin/self account
 		if( $user['permission_admin'] )
 		{
 			unset( $user['is_suspended']);
 		}

 		if( $email_changed or $phone_changed )
 			$user['is_verified'] = False;

 		$this->shop->user->update( $user['user_id'] , $user);

 		//get updated details with login token
 		$user = $this->shop->user->get( $user['user_id'] );

 		//either email or phone number can activate the account
 		if( $email_changed )
 		{
 			//@todo generate account activation email
 			$challenge = md5( md5($this->data['shop']['shop_id']) . $user['email']  );
 			$email = array();
			$email['type'] = 'info';
			$email['header'] = 'Verify your account.';

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $user['fullname'] . '<br/>To be able to login to your account, you need to verify your email address.';
			$email['action_link'] = $this->data['shop']['url'] . 'admin/login?action=confirm_email&challenge=' . $challenge; 
			$email['action_message'] = 'Verify your email address';
			$email['footer'] = 'If there has been a mistake, simply ignore this email';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'], 'Activate your ' . $this->data['shop']['name'] . ' account', $html );
 		}
 		if(  $phone_changed )
 		{
 			//@todo send sms link
 			//@todo move generate sms confirmation link to model under system
 			$challenge = md5( md5($this->data['shop']['shop_id']) . $user['phone_number']  );
 			$url = $this->data['shop']['url'] . 'admin/login?action=confirm_sms&challenge=' . $challenge; 
 			$message = 'To activate your account: ' . $url ;
 			if( account() != 'free' )
 				$this->system->pushnotification->push_sms( $this->data['shop']['shop_id'] ,  $user['phone_number'] , $message ,  7 );

 		}

 		if( is_admin() )
 		{
 			$this->shop->logger->action('Updated user #' . $user['user_id'] . ' - ' . $user['fullname'] . ' account');
 			$this->shop->logger->user_id( $user['user_id'] );
 			$this->shop->logger->action('Administrator updated your account ');
 			$this->shop->logger->user_id( $me['user_id'] );
 				
 		}
 		else
 		{
 			$this->shop->logger->action('Updated account details ');
 		}
 		$this->render('updated_user' , $user );
 		//$this->render('status' , array('status'=>'ok'));
 	}

 	/** change the password **/
 	public function update_password( )
 	{
 		$params = $this->read_input();

 		$prompt_password = True;
 		$user = array();
 		$c_user = $this->php_session->get('user');

 		if( empty($params) )
 			$this->forbidden();

 		$expected = array('user_id' , 'current_pass' , 'new_pass');
 		foreach( $expected as $key )
 			if( ( ! isset($params[$key]) ) or ( ! is_string($params[$key])) )
 				$this->forbidden();

 		$params['user_id'] = intval($params['user_id']);

 		$user = $this->shop->user->get( $params['user_id'] );
 		if( empty($user) or $user['shop_id'] != $this->data['shop']['shop_id'] )
 			$this->forbidden();

 		if(  is_admin() )
 		{
 			if( $user['user_id'] != $c_user['user_id'] )
 				$prompt_password = False;
 		}

 		if( $prompt_password )
 		{
 			if( ! $this->shop->user->is_password($user , $params['current_pass']) )
 			{
 				return $this->error('Invalid password provided');
 			}
 		}

 		if( strlen($params['new_pass']) <= 3 )
 		{
 			$params['new_pass'] = $this->shop->user->generate_password();
 		}

 		$this->shop->user->change_password( $user['user_id'] , $params['new_pass'] );

 		if( is_admin() && ($c_user['user_id'] != $user['user_id'] ) )
 		{
 			$this->shop->logger->action('Changed password of user #'. $user['user_id'] . '- ' . $user['fullname'] );
 			$this->shop->logger->user_id( $user['user_id'] );
 			$this->shop->logger->action('Administrator changed your password ');
 			$this->shop->logger->user_id( $c_user['user_id'] );

 			//send email notifying user
 			//send email
	 		$email = array();
			$email['type'] = 'warning';
			$email['header'] = 'Administrator changed your password.';

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $c_user['fullname'] . '<br/>Your shop administrator changed your password. As soon as you login please change this password and delete this email.<br/><b>Your new password is ' . $params['new_pass'] . '</b>';
			$email['action_link'] = $this->data['shop']['url'] . 'admin#/settings/users' ; 
			$email['action_message'] = 'Login to my account';
			$email['footer'] = 'Remember to delete this email!!!';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $c_user['fullname'] , $c_user['email'], $email['header'] , $html );
			
 		}	
 		else
 		{
 			$this->shop->logger->action('Changed password');
 		}

 		$this->render('' , array('status' => 'ok'));
 	}

 	/** Json login the user **/
 	public function login( )
 	{
 		$data = OS_Controller::load_shop(); 
 		$params = $this->read_input();
 		if( isset($params['email']) and isset($params['password']) )
 		{
 			//actually attempt to login
			$user = $this->shop_user->get_by_email( $params['email'] );
			if( ! empty( $user ) )
			{
				//now check password
				if( isset($user['user_id']) and $this->shop_user->is_password( $user , $params['password'] ) and $user['shop_id'] == $data['shop']['shop_id'] )
				{
					//sweet
					$user['last_active'] = time();
					$this->php_session->set('user' , $user );
					//record login
					$this->shop_log->auth($user['fullname'] .  ' logged in at ' . date('r') . 'from ' . $this->input->ip_address() );
				
					
					$status = array('status' =>'success');
					$this->render('login' , $status );
					return;
				}
				else
				{
					$this->error('Failed to login user - Check if provided details are correct.');
				}
			}
 		}
 		$this->output->set_status_header('401');
 		//failed to login
 	}

 	/** Gets a user activity log withing within specified constraints **/
 	public function logs( $user_id  , $type = 'all' )
 	{
 		if( ( ! is_admin() ) and ( $user_id != $this->user['user_id'] ) )
 		{
 			//only admin can view everyone's logs
 			$this->error('Only administrator can view other users logs.');

 		}

 		//set user_id
 		$this->shop->logger->user_id( $user_id );


 		$logs = array();
 		$max = $this->input->get('max');
 		if( is_null($max))
 			$max = 50;
 		if( $type !== 'all' )
 		if( ! in_array($type , array('auth','wallet','product','settings','action')	) )
 		{
 			$this->error('Unknown log filter.');
 		}
 		if( $type == 'all')
 		{

 			$logs = $this->shop->logger->get_all( $max );
 		}
 		else
 			$logs = $this->shop->logger->get_log( $type , $max );
 		$this->render('logs' , $logs);
 	}



}


