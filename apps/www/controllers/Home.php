<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {


	const THEME = 'sedna';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();

				
		
	}
	
	/**
	 * Index Page for this controller.
	 *
	 *
	 */
	public function index()
	{
		
		//@todo parse rss feed and cache results
		$this->load->library('rssparser');
		$this->rssparser->set_feed_url('http://blog.263shop.co.zw/feeds/posts/default');
		$this->rssparser->cache_dir = APPPATH . '/cache/';
		$this->rssparser->set_cache_life(60*24);//->parse()	; //every day
		$rss = array();//$this->rssparser->getFeed(3);  // Get six items from the feed

		foreach( $rss as $k => $v )
		{
			$doc = new DOMDocument();
			$doc->loadHTML($v['description'] );
			$img = $doc->getElementsByTagName('img')->item(0);
			if( $img )
			{
				$rss[$k]['img'] = str_replace('http://' ,'https://' ,$img->getAttribute('src') );
				
			}
			else
			{
				$rss[$k]['img'] = public_resource('www/sedna/img/blog-img-01.jpg'); 
			}
			
		}

		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = '263Shop :: The best way to sell online for Zimbabwean companies';
		$data['description'] = 'Create a free online shop and start selling online in minutes. Accept EcoCash,MasterCard,VISA and ZimSwitch payments. ';
		$data['keywords'] = 'ecommerce,zimbabwe,ecocash,zimswitch,mastercard,visa,create website,sell online,make money online,sell using internet,online job for zimbabwe';
		$data['csrf_login_token'] = $this->session->csrf_login_token;
		$data['csrf_signup_token'] =  $this->session->csrf_signup_token;
		$data['blog_feed'] = $rss;
		
		
		$this->load->view( Home::THEME .'/index' , $data);
		
	}


	

	public function login( )
	{
		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = 'Login to access your shop :: 263Shop';
		$data['description'] = '';
		$data['keywords'] = '';

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email','Email address'  , 'required|valid_email');
		$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[20]');
		$this->form_validation->set_rules('url', 'Shop Url','required');
		$this->form_validation->set_rules('challenge', 'Challenge','required|min_length[32]');

		$data['csrf_login_token'] = $this->session->csrf_login_token;
		$data['csrf_signup_token'] =  $this->session->csrf_signup_token;

		

		if( $this->form_validation->run() )
		{
			//resirect to shop
			$challenge = md5($this->input->post_get('challenge', True) );
			
			$salt = rand();
			
			$token = md5(  $this->input->post_get('email') . $this->input->post_get('password') . date('Y-m-d h') );

			$form = '<html><head><title>Redirecting...</title></head><body onload="document.forms[0].submit()"><form action="' . $this->input->get_post('url') . '/admin/login?action=token&token=' . $token . '&salt=' . $salt . '" mthod="POST" >';
			$form .='<input type="hidden" name="email" value="' . $this->input->post('email' , true ) . '" />';
			$form .='<input type="hidden" name="password" value="' . $this->input->post('email' , true ) . '" />';
			$form .= '</form>' ;
			echo $form;
			return;
		}
		
		//check if shop exists
		$site_login = ( $this->input->post_get('site') == Null ) ? False :  True;
		$data['site'] = $this->_extract_params();
		$data['form_challenge'] = md5( time() . rand() );
		//	$this->session->set('form_challenge' , md5( $data['form_challenge'] ) );

		if( empty($data['site']) and $site_login )
		{
			//unknown shop
			header('Location: /auth/login');
			return;
		}
		//generate capctha

		$this->load->view( Home::THEME . '/login' , $data );
	}
	
	public function terms_and_conditions( )
	{
		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = 'Terms and Conditions :: 263Shop';
		$data['description'] = 'Terms and conditions for using the 263Shop service, 263Shop is an ecommerce website builder for Zimbabwean companies';
		$data['keywords'] = '263shop, ecommerce zimbabwe, make money online zimbabwe, zimbabwe, bulawayo , harare, ecocash , telecash, mastercard, visa,zimswitch';
		
		$this->load->view( Home::THEME .'/legal' , $data);
	}

	public function blog()
	{
		header('Location: http://blog.263shop.co.zw/');
		return;
	}
	
	public function features( )
	{
		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = 'We make doing everything ecommerce better :: 263Shop';
		$data['description'] = '';
		$data['keywords'] = '';
		
		$data['csrf_login_token'] = $this->session->csrf_login_token;
		$data['csrf_signup_token'] =  $this->session->csrf_signup_token;
		
		
		$this->load->view( Home::THEME .'/features' , $data);
	}

	public function pricing( )
	{
		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = 'Pricing - Create an online store for free :: 263Shop';
		$data['description'] = 'Create a free online shop and start selling online in minutes. Accept EcoCash,MasterCard,VISA and ZimSwitch payments. ';
		$data['keywords'] = 'ecommerce,zimbabwe,ecocash,zimswitch,mastercard,visa,create website,sell online,make money online,sell using internet,online job for zimbabwe';
		$data['csrf_login_token'] = $this->session->csrf_login_token;
		$data['csrf_signup_token'] =  $this->session->csrf_signup_token;
		
		
		$this->load->view( Home::THEME .'/pricing' , $data);

	}

	public function error_404()
	{
		echo '404 Page not found';
	}
	
	public function contact_us( )
	{
		$this->session->csrf_signup_token =  md5( time() * rand() ); 
		$this->session->csrf_login_token = hash('md4' , rand()  );

		$data = array();
		$data['title'] = 'Contact us :: 263Shop';
		$data['description'] = '';
		$data['keywords'] = '';
		
		$this->load->view( Home::THEME .'/contact' , $data);
	}
	
	public function privacy( )
	{
		$data = array();
		$data['title'] = 'Our privacy policy :: 263Shop';
		$data['description'] = '';
		$data['keywords'] = '';
		
		$this->load->view( Home::THEME .'/privacy' , $data);
	
	}
	
	

	private function _extract_params(  )
	{
		$data  = $this->input->get_post('site' );
		if( empty($data) )
			return array();
		$site = base64_decode($data);
		if( empty($site) or $site == False )
			return array();

		$site = json_decode($site , True );
		if( empty($site) or $site == False )
			return array();

		$site['name'] = htmlentities($site['name']);
		$site['url'] = htmlentities($site['url']);
		//secure login failed
		if(  md5('secure' . $site['name'] . $site['url'])  !=  $site['c'] )
			return array();

		return $site;
	}
}
