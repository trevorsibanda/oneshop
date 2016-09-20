<?php
/**
 * OneSHOP Admin Settings API
 *
 * Shop themes
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Settings extends OS_AdminController
{

	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

		$this->load->model('blogpost'); 

		if( ! is_admin() )
		{
			$this->forbidden('Permission denied. Only administrator can perform this action');
		}		
 	}

 	/**
 	 * Get shop settings.
 	 *
 	 * @param 		String 		$	Settings Type 
 	 *
 	 */
 	public function get( $type = 'all')
 	{
 		if( is_null($type))
 			$this->forbidden();
 		$settings = array();
 		switch( $type )
 		{
 			case 'all':
 			{
 				$settings = $this->shop->settings->get( $this->data['shop']['shop_id'] );
 				
 				$settings['shipping']['rules'] = json_decode( $settings['shipping']['rules_json'] , True );
 				if( ! is_array($settings['shipping']['rules']))
 					$settings['shipping']['rules'] = array();

 				unset( $settings['shipping']['rules_json'] );
 				unset($settings['theme']['settings_json']);


 			}
 			break;
 			case 'core':
 			{
 				$shop = $this->shop->get_by_id( $this->data['shop']['shop_id'] , False);
 				$shop['logo'] = $this->shop->image->get( $shop['logo_id'] );
 				$settings = $shop;
 			}
 			break;
 			case 'analytics':
 			{
 				$settings = $this->shop->settings->get_analytics_settings(  $this->data['shop']['shop_id']  );
 			}
 			break;
 			case 'payment':
 			{
 				$settings = $this->shop->settings->get_payment_settings( $this->data['shop']['shop_id'] );
 			}
 			break;
 			case 'order':
 			{
 				$settings = $this->shop->settings->get_order_settings( $this->data['shop']['shop_id'] );

 			}
 			break;
 			case 'shipping':
 			{
 				$settings = $this->shop->settings->get_shipping_settings( $this->data['shop']['shop_id'] );
 				$settings['rules'] = json_decode( $settings['rules_json'] , True );
 				if( ! is_array($settings['rules']))
 					$settings['rules'] = array();
 				unset( $settings['rules_json'] );
 			}
 			break;
 			case 'contact':
 			{
 				$settings = $this->shop->settings->get_contact_settings( $this->data['shop']['shop_id'] );
 			}
 			break;
 			case 'theme':
 			{
 				$settings = $this->shop->settings->get_theme_settings(  $this->data['shop']['shop_id'] );
 				unset($settings['settings_json']);
 			}	
 			break;
 			default:

 			break;
 		}
 		$this->render('settings' , $settings );
 	}

 	/** Update settings **/
 	public function update(  $type = Null )
 	{
 		if( is_null($type))
 			$this->forbidden();

 		$obj = $this->read_input();
 		
 		if( empty($obj) or $obj == False )
 			$this->forbidden();



 		switch($type)
 		{
 			case 'order':
 			{
 				$expected = array('order_expire_days','sms_notify_on_pay','sms_notify_on_order','email_notify_on_order','use_captcha');
 				$obj2 = $obj;
 				$obj = array();
 				foreach ($obj2 as $key => $val) 
 				{
 					
 					if( ( is_string($val) or is_numeric($val) ) and in_array($key, $expected ) )
 						$obj[$key] = $obj2[$key];
 				}
 				foreach ($expected as $key ) 
 				{
 					if( ! isset($obj[$key]) )
 						$this->forbidden();
 				}

 				if( ! in_array($obj['order_expire_days'], array(28,14,10,7,5,3,2,1)) )
 					$obj['order_expire_days'] = 14;

 				//update settings
 				$this->shop->settings->update( $this->data['shop']['shop_id'] , $obj , 'order' );
 				$this->shop->logger->settings('Updated order settings.');
 			}
 			break;
 			case 'shipping':
 			{
 				$expected = array('use_shipping_rules','allow_collect_instore','allow_deliveries','allow_cash_on_delivery','handling_fee','intracity_fee','intercity_fee','rules');

 				foreach ($expected as $key) 
 				{
 					if( ! isset($obj[$key]) )
 						$this->forbidden( );
 				}

 				foreach( $obj as $key=>$val)
 				{
 					if( ! in_array($key,$expected))
 					{
 						unset( $obj[$key] );
 					}
 				}

 				//sanitize
 				$obj['allow_deliveries'] = intval($obj['allow_deliveries']);
 				$obj['allow_collect_instore'] = intval($obj['allow_collect_instore']);
 				$obj['allow_cash_on_delivery'] = intval($obj['allow_cash_on_delivery']);
 				$OBJ['use_shipping_rules'] = intval($obj['use_shipping_rules']);

 				$obj['intracity_fee'] = floatval($obj['intracity_fee']);
 				if( $obj['intracity_fee'] < 0)
 					$obj['intracity_fee'] = 0.00;

 				$obj['intercity_fee'] = floatval($obj['intercity_fee']);
 				if( $obj['intercity_fee'] < 0)
 					$obj['intercity_fee'] = 0.00;
 				
 				$obj['handling_fee'] = floatval($obj['handling_fee']);
 				if( $obj['handling_fee'] < 0)
 					$obj['handling_fee'] = 0.00;

 				//@todo process rules
 				$rules = $obj['rules'];

 				unset($obj['rules_json']);

 				$obj['rules_json'] = json_encode($rules );
 				unset( $obj['rules'] );

 				//update settings
 				$this->shop->settings->update( $this->data['shop']['shop_id'] , $obj , 'shipping' );

 				$this->shop->logger->settings('Updated shipping settings.');
 			}
 			break;
 			case 'payment':
 			{
 				$expected = array('shop_id','use_custom','primary_gateway','secondary_gateway','primary_api_key','primary_api_secret','primary_data','secondary_api_key','secondary_api_secret','secondary_data');

 				foreach( $obj as $key => $val )
 				{
 					if( ! in_array($key, $expected) )
 						$this->forbidden($key);
 				}

 				//sanitize

 				//check if valid gateway
 				if( $obj['use_custom'] )
 				{
 					$this->load->model('payment');
 					$this->payment->load_gateway();
 					$gateways = $this->payment->gateway->get_all();
 					$gateway_names = array();
 					foreach ($gateways as $key => $g) 
 					{
 						array_push($gateway_names, strtolower($g['name'] ) );
 					}

 					if( ! in_array($obj['primary_gateway'],	$gateway_names ) )
 					{
 						//invalid gateway
 						$obj['primary_gateway'] = '';
 						$obj['primary_data'] = '';
 						$obj['primary_api_secret'] = '';
 						$obj['primary_api_key'] = '';
 					}

 					if( ! in_array( $obj['secondary_gateway'] , $gateway_names ) )
 					{
 						//invalid gateway
 						$obj['secondary_gateway'] = '';
 						$obj['secondary_data'] = '';
 						$obj['secondary_api_secret'] = '';
 						$obj['secondary_api_key'] = '';
 					}

 					if( empty($obj['secondary_gateway']) and empty($obj['primary_gateway']) )
 						$obj['use_custom'] = False;

 				}
 				if( ! $obj['use_custom'] )
 				{
 					//
 				}

 				//update
 				//update settings
 				$this->shop->settings->update( $this->data['shop']['shop_id'] , $obj , 'payment' );
 				$msg = $obj['use_custom'] ? 'Using custom gateway ' : 'Using '. OS_SITE_NAME . ' gateway ';
 				if( $obj['use_custom'] )
 				{
 					$msg .= 'Primary gateway: '. $obj['primary_gateway'] . ', Secondary gateway: ' . $obj['secondary_gateway'];
 					$msg .= "\nPrimary API KEY: " . $obj['primary_api_key'] . 'Secondary API KEY: ' . $obj['secondary_api_key'];
 				}
 				$this->shop->logger->settings('Updated payment settings. ' . $msg );

 			}
 			break;
 			case 'contact':
 			{
 				$expected = array('shop_id' ,'notify_sms','sms_sender_id','facebook_page','twitter_handle','linkedin_url','skype_id');
 				
 				$c = array();
 				foreach( $obj as $key => $val )
 				{
 					if( ! in_array($key, $expected) )
 						$this->forbidden($key);
 					$c[ $key ] = xss_clean( $val );
 				}
 				unset( $obj['shop_id']);
 				$c['sms_sender_id'] = clean( $obj['sms_sender_id'] );

 				if( $c['sms_sender_id'] !== '263SHOP')
 				{
 					$c['sms_sender_id'] = '263SHOP';
 				}

 				$this->shop->settings->update( $this->data['shop']['shop_id'] , $obj , 'contact' );
 				
 				$this->shop->logger->settings('Updated contact and social settings' );

 			}
 			break;
 			case 'core':
 			{
 				$expected = array('subdomain' , 'alias' , 'use_alias' , 'name' , 'address' , 'country' ,'city' , 'trading_name','description' , 'short_description' , 'type' ,'tagline','open_time','close_time','operate_days','keywords','telephone');

 				//not allowed to change these:
 				unset( $obj['shop_id'] );
 				unset( $obj['is_suspended']);
 				unset( $obj['logo_id'] );//must useproper channel
 				unset( $obj['logo'] );
 				unset( $obj['admin_id'] );
 				unset( $obj['alias']); //use proper channel
 				unset( $obj['subdomain']);
 				unset( $obj['type']);

 				$c = array();

 				foreach( $obj as $key => $val )
 				{
 					if( in_array($key, $expected) )
 					{
 						//clean and store if not html
 						if( $key == 'description' or $key == 'short_description')
 						{
 							
 						}
 						else
 						{
 							$val = xss_clean( $val );
 						}

 						//add
 						$c[$key] = $val;
 					}
 				}

 				//futher cleaning
 				$c['name'] = clean( $c['name'] );
 				$c['tagline'] = clean($c['tagline']);
 				$c['description'] = $this->system->safe_html( $c['description'] );
 				$c['short_description'] = str_replace( array("\r","\n") , " " ,strip_tags( str_replace("<br/>"," " ,$c['short_description'] )  ) );

 				$old_shop = $this->data['shop'];
 				
 				//check if alias changed
 				$c['alias'] = isset( $c['alias'] ) ? $c['alias'] : $old_shop['alias'];
 				if( $old_shop['alias'] != $c['alias'] )
 				{
 					//@todo check if valid alias
 					$c['use_alias'] = False;

 					//@todo send out instruction email to admin

 					//check if cname records actually match

 					
 				}

 				//save settings
 				$this->shop->update( $this->data['shop']['shop_id'] , $c );
 				$msg = 'Updated core shop settings. ';
 				$this->shop->logger->settings('Updated shop core settings. ' . $msg );


 			}
 			break;
 			case 'analytics':
 			{
 				
 				

 				//if only free account, deny
 				if( ! account_can('analytics') )
 				{
 					$this->error('Sorry, you must upgrade your account to be able to use this feature');
 				}

 				if( ! isset($obj['google_analytics_key']))
 					$this->error('Invalid post data passed');

 				foreach( $obj as $k => $v )
 				{
 					if( $k != 'google_analytics_key' )
 						$this->error('Invalid post data passed');
 				}

 				$new_key = clean(  $obj['google_analytics_key'] );

 				
 				if( account() ==  'free' )
 				{
 					$obj['google_analytics_key'] = '';
 				}

 				$this->shop->settings->update( $this->data['shop']['shop_id'] , $obj  , 'analytics' );

 				if( account() ==  'free' )
 					$this->error('Sorry, you need to be subscribed to premium or basic plan for this to work');

 				$this->shop->logger->settings('Updated google analytics key to ' . $new_key  );
 			}
 			break;
 			default:
 			{
 				return $this->error('Unknown settings option');
 			}
 			break;
 		}

 		//render new settings
 		$this->get($type);
 	}

 	/**publish or unpublish shop **/
 	public function publish(  )
 	{

 		$obj = $this->read_input();

 		if( ! is_array($obj) )
 			$this->forbidden();

 		if( ( ! isset($obj['publish']) ) or ( ! isset($obj['password']) ) or ( ! is_string($obj['password'])) )
 		{
 			$this->forbidden();
 		}

 		//check admin password
 		$user = $this->shop->user->get(  $this->user['user_id'] );
 		$b = $this->shop->user->is_password( $user , $obj['password'] );

 		if( $b )
 		{
 			$this->shop->logger->auth('Entered administrator password to approve changing shop publish status');

 			$shop =  $this->shop->get_by_id( $this->data['shop']['shop_id'] , False );

 			$shop['is_suspended'] = ( $obj['publish'] == '1' ) ? False : True;
 			$msg = $shop['is_suspended'] ? 'unpublished and is no longer availble to the public' : 'published and is now available to the public';
 			$this->shop->logger->action('Shop was ' . $msg );

 			$this->shop->update($shop['shop_id'] , $shop );

 			$shop['logo'] = $this->data['shop']['logo'];

 			//send email to admin
 			$email = array();
			$email['type'] = 'info';
			$email['header'] = $shop['is_suspended'] ? 'Your shop was unpublished.' : 'Your shop was published and is now public';

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $user['fullname'] . '<br/>'. $email['header'] . '<br/>Login to your shop to manage your shop ';
			$email['action_link'] = $this->data['shop']['url'] . 'admin/#/settings/general'; 
			$email['action_message'] = 'Manage your shop';
			$email['footer_msg'] = 'If you did not perform this task, please change your password as soon as possible.';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $shop , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $user['fullname'] , $user['email'], $email['header'] , $html );
 			


 		}
 		else
 		{
 			$this->shop->logger->auth('Attempted to unpublish shop, but incorrect admin password entered');
 			$this->error('Invalid admin password entered');
 		}

 		$this->get('core');

 	}

 	public function change_subdomain( )
 	{
 		$json = $this->read_input();
 		if(empty($json) or $json=== False)
 			$this->error('You must specify a subdomain to check ');

 		$subdomain = isset($json['subdomain']) ? $json['subdomain'] : '';
 		if( empty($subdomain) or ! is_string($subdomain))
 		{
 			$this->error('Subdomain to change to not specified ');
 		}

 		if(  $this->subdomain_available(True , $json )  )
 		{
 			$this->error('Sorry the subdomain you specified is already registered ');
 		}

 		//unchanged
 		if( $this->data['shop']['subdomain'] === $json['subdomain'])
 			$this->render('status' , array('status'=>'ok'));

 		//change 
 		$shop = array('subdomain'=> $json['subdomain'] );
 		$this->shop->update( $this->data['shop']['shop_id'] , $shop);

 		//add to used subdomain pool
 		$this->db->insert('shop_used_subdomains' , array('subdomain' => $json['subdomain'] , 'shop_id' => $this->data['shop']['shop_id'] ) );


 		$shop = $this->shop->get_by_subdomain(  $json['subdomain'] , false );
 		$shop['logo'] = $this->shop->image->get( $shop['logo_id']);

 		//send out email and sms to admin
 		//send email to admin
		$email = array();
		$email['type'] = 'warning';
		$email['header'] = 'CRITICAL: You changed your shop subdomain. ';

		$email['subheader'] = '';
		$email['message'] = 'Hello ' . $this->user['fullname'] . '<br/>'. $email['header'] . '<br/>Your shop subdomain has been changed. This means that you will no longer be able to access your shop using the old url. Your login details however did not change and can still be used on the new url. ';
		$email['action_link'] = $shop['url'] . 'admin/#/settings/general'; 
		$email['action_message'] = 'Login to your shop';
		$email['footer_msg'] = 'If you did not perform this task, please change your password as soon as possible and report the issue to a '. OS_SITE_NAME .' supprt member.';

		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $shop , array() , array() );
		//push email
		$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , $this->user['fullname'] , $this->user['email'], $email['header'] , $html );
		
		$this->shop->logger->settings('Changed shop subdomain from ' . $this->data['shop']['subdomain']. ' to '. $json['subdomain'] );
		$this->render('status' , array('status'=>'ok'));

 	}

 	//change alias
 	public function change_alias( )
 	{
 		$this->system->load_dns();

 		$json = $this->read_input();
 		if(empty($json) or ! isset($json['alias']))
 			$this->error('Invalid request');

 		if( account() == 'free')
 			$this->error('You are on the free plan and cannot use your own domain in this plan. Please upgrade to basic or premium.');


 		$use_alias = isset( $json['use_alias']) ? $json['use_alias'] : true; 
 		$alias = isset($json['alias']) ? $json['alias'] : '';
 		//remove http
		$alias = str_replace(array('http://','https://'), '', $alias); 		

 		if(empty($alias) or ! $this->system->is_valid_fqdn($alias ))
 		{
 			$this->error('Invalid custom subdomain provided');
 		}

 		//shop-1.oneshop.co.zw
 		$target = 'hosted'  .OS_BASE_DOMAIN;


 		//remove self *.263shop.co.zw will always be alias of hosted.263shop.co.zw
 		$alias = str_replace(OS_BASE_DOMAIN, '', $alias );



 		//chec dns setup
 		//check if $alias points to $target
		$b = $this->system->dns->is_alias($target , $alias );

		$shop = $this->data['shop'];
		
		if( $b )
		{
			//change subdomain
			$shop = $this->data['shop'];

			$existing_shop = $this->shop->get_by_alias(  $alias );
			if( ! empty($existing_shop)  && $existing_shop['shop_id'] != $shop['shop_id'] )
				$this->error('Sorry another shop is already using this custom domain. You cannot use it');

			
			$shop['alias'] = $alias;
			$shop['use_alias'] = $use_alias;
			unset($shop['contact']);
			$this->shop->update( $shop['shop_id'] , $shop );
			$shop = $this->shop->get_by_alias( $shop['alias'] , False );
			$shop['logo'] = $this->data['shop']['logo'];

			$url = $shop['url'] . 'admin/login';

			//send email to admin
 			$email = array();
			$email['type'] = 'info';
			$email['header'] = 'Your shop subdomain is now '. $alias;

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $this->user['fullname'] . '<br/>You recently added a cusom subdomain to your shop.<br/>Your shop will now be available using http://' . $alias . '. <br/> You can login to your shop through the '. OS_BASE_DOMAIN .' website. <a href="'. OS_BASE_SITE . '/auth/login">Click here to login</a> or you can directly login to your site by following this url <a href="'.$url .'" >'. $url .'</a> <br/>If you did not change your shop\'s custom domain yourself please login to your account and change your password immediately.<br/>Please note that Free plan does not allow using a custom subdomain, so if you downgrade to free plan your website will fallback to the '. OS_BASE_SITE .' subdomain. This will negatively affect your search ratings. ';
			$email['action_link'] = $shop['url'] . 'admin/#/settings/general'; 
			$email['action_message'] = 'Login to your shop';
			$email['footer_msg'] = 'If you did not perform this task, please change your password as soon as possible.';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $shop , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $shop['shop_id'] , $this->user['fullname'] , $this->user['email'], $email['header'] , $html );
 			


		}
		else
		{
			$this->error('Your DNS settings are not setup properly. Try again or wait for up to 48 hours for DNS propagation to complete');
		}
		$this->render('status',array('status'=>'ok' , 'shop'=>$shop));
 	}

 	public function subdomain_available( $internal_call = False , $internal_json = array() )
 	{
 		$json = $this->read_input();
 		if( $internal_call === True)
 			$json = $internal_json;

 		if(empty($json) or $json=== False)
 			$this->error('You must specify a subdomain to check ');

 		$subdomain = isset($json['subdomain']) ? $json['subdomain'] : '';
 		if( empty($subdomain) or ! is_string($subdomain))
 		{
 			$this->error('Subdomain to check not specified ');
 		}

 		//sanitize subdomain
 		//@todo use regexp
 		$subdomain = strtolower($subdomain);
 		$result = array('subdomain' => $subdomain , 'registered' => True );
 		$allowed = explode(',', 'a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,0,1,2,3,4,5,6,7,8,9');
 		$sub_tmp = '';
 		if( ! $this->shop->is_valid_subdomain($subdomain) )
 		{
 			$result['err'] = ('The subdomain name is not allowed because it is either reserved or a filtered inappropriate word');
 			if( $internal_call === True)
 				return True;

 			$result['registered'] = True;
 			$this->render('result' , $result );
 			return;
 		}
 		

 		//even if suspended
 		$shop = $this->shop->get_by_subdomain( $subdomain , False);

 		

 		if( empty($shop) )
 		{
 			//check in old subdomains
 			$query = $this->db->get_where('shop_used_subdomains' , array('subdomain' => $subdomain ) );
 			$entry = $query->row_array();
 			
 			if(! empty($entry)  )
 			{
 				//had formerly registered this subdomain
 				if( $entry['shop_id'] == $this->data['shop']['shop_id'] )
 				{
 					$result['registered'] = False;

 				}
 				else
 					$result['registered'] = True;
 			}
 			else
 			{

 				$result['registered'] =False;	
 			}


 			

 			
 		}
 		//can change to same url
 		else if(  $shop['shop_id'] == $this->data['shop']['shop_id'] )
 		{

 			$result['registered'] = False;
 		}	
 		else 
 		{
 			$result['registered'] =True;
 		}
 		if( $internal_call === True)
 			return $result['registered'];
 		$this->render('result' , $result );

 	}

 	public function check_dns_setup(  )
 	{
 		$this->system->load_dns();
 		//check dns settings
 		//@todo
 		$json = $this->read_input();
 		if(empty($json) or $json=== False)
 			$this->error('You must specify a alias to check ');
 		
 		$alias = isset($json['alias']) ? $json['alias'] : '';
 		if( empty($alias) or ! is_string($alias) or ! $this->system->is_valid_fqdn($alias) )
 		{
 			$this->error('Alias to check not specified ');
 		}

 		//hosted.{OS_BASE_DOMAIN}
 		$target = 'hosted'  . OS_BASE_DOMAIN;

 		$alias = str_replace(OS_BASE_DOMAIN, '', $alias );


 		//check if $alias points to $target
		$b =  $this->system->dns->is_alias($target , $alias );

		$data = array('status'=>'ok','alias'=>$json['alias']);
		if( ! $b )
		{
			$data['status'] ='fail';
			$data['err'] ='Make sure you have updated the settings in your dns panel. DNS propagation might take some time. ';
		}

 		$this->render('status', $data );


 	}


 	public function get_upgrade_link(  )
 	{
 		$json = $this->read_input();
 		
 		if( empty($json) or ! isset($json['plan']) or ! isset($json['months']))
 		{
 			$this->error('Your request is incomplete.');
 		}	

 		//init transaction
 		$title = 'Upgrade account';
 		$is_upgrade = true;

 		$url = $this->_subscribe_account( $json['plan'] , $json['months'] , $is_upgrade );

 		$result = array('status'=>'ok','url'=>$url,'title'=>$title);	

 		$this->render('status' , $result);
 	}

 	public function _subscribe_account($plan = 'basic',$months = 1  ,$is_upgrade = true, $p_settings  = null )
	{
		
		if($months > 12 )
			$this->error('You cannot subscribe to more than 12 months at a time');
		if( $months <= 0)
			$this->error('Invalid subscription period');


		$this->load->config('shop_account');

		$shop_id = $this->data['shop']['shop_id'];

		$transaction = array('transaction_id' => $this->data['shop']['shop_id']);

		$current_sub = $this->shop->account->get_subscription($shop_id , date('Y') , date('m') );
		if( empty($current_sub) )
		{
			//subscribe to free account and is upgrade
			$is_upgrade = true;

		}

		$this->load->model('payment');
		$this->payment->load_gateway();
		$this->payment->gateway->select('paynow');

		

		if( is_null($p_settings) )
		{
			$p_settings = $this->shop->settings->get_payment_settings( $shop_id );
		}

		if( ! in_array($plan, $this->config->item('account_plans')))
		{
			$this->error('Unknown plan');
		}

		$plan = $this->config->item( strtolower( $plan ) . '_plan');
		if( empty($plan) or is_null($plan))
			$this->error('Unknown plan');



		$this->load->config('oneshop/account_payment_gateway');


		$data = $this->config->item('os_paynow_subs_account');

		//total cost
		$total = 100.00;

		$total = $plan['amount'] * $months;
		if( $months == 12)
			$total = $plan['amount_year'];

		if( $total == 0.00 || $plan['type'] == 'free' )
		{
			//subscribe to free plan
			if( $months > 6  )
			{
				$this->error('You cannot subscribe to free plan for more than 6 months');
			}


			//subscribe to free plan
			$this->shop->account->add_subscription( $shop_id , date('Y') , date('m') , 'free' , $months );
			return $this->data['shop']['url'] . '/admin#settings/account';
		}

		//using custom payment gateway
		$api_key = $data['api_key'];
		$api_secret = $data['api_secret'];
		$api_data = $data['api_data'];

		
		$salt = md5( time() * rand() );
		$challenge = md5( $salt . $this->data['shop']['shop_id'] . $plan['type'] . $is_upgrade . $months . $salt );
		

		$order_url = $this->data['shop']['url']  . 'admin#/settings/account';
		$callback_url = 'http://' . OS_SECURE_DOMAIN . '/api/endpoint/account_subscription?shop_id=' . $this->data['shop']['shop_id'] . '&account_type=' . $plan['type'] . '&period_months=' .$months . '&is_upgrade=' . $is_upgrade . '&challenge=' . $challenge . '&salt=' . $salt;

					
		$this->payment->gateway->set('checkout_url' , $order_url  );
		//url shown when payment is pending
		$this->payment->gateway->set('pending_url' , $order_url );
		//callback url
		$this->payment->gateway->set('callback_url' , $callback_url );

		$this->payment->gateway->set('api_key' , $api_key );
		$this->payment->gateway->set('api_secret' , $api_secret );
		$this->payment->gateway->set('api_data' , $api_data );

		$info = ($is_upgrade ? 'Upgrade' : 'Subscribe' ) . ' to ' . $plan['name'] . ' for ' . $months . ' months. - ' . $this->data['shop']['name'];

		//init transaction
		$shopper = array('fullname'=> $this->data['shop']['name'] , 'email' => $this->user['email'] , 'phone' => $this->user['phone_number']  );
		$response = $this->payment->gateway->initiate_transaction( $total , $transaction , $shopper , $info );

		$url =  isset($response['data']['url']) ? $response['data']['url'] : '';

		if( ! empty($url) )
		{
			//send email to admin
			$email = array();
			$email['type'] = 'info';
			$email['header'] = 'Upgrading your account to '. $plan['name'];

			$email['subheader'] = '';
			$email['message'] = 'Hello ' . $this->user['fullname'] . '<br/>You recently tried upgrading your account to ' . $plan['name']. '.<br/>You can still upgrade your account by clicking on the link below. You will be taken to a page and asked to make a payment to upgrade your account.';
			$email['action_link'] = $url; 
			$email['action_message'] = 'Upgrade to ' . $plan['name'] . ' ( '. money($total) . ' )';
			$email['footer_msg'] = 'If you did not perform this task, please change your password as soon as possible.';

			$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] , array() , array() );
			//push email
			$this->system->pushnotification->push_email( $shop_id , $this->user['fullname'] , $this->user['email'], $email['header'] , $html );
			
		}
		else
		{
			$url = 'javascript:alert("An error occured");';
		}
		return $url;

	}

}


