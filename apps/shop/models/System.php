<?php


class System extends CI_Model
{
	//HTML Purifirer
	public $html_purifier;

	public function __construct()
	{
		parent::__construct();
		$this->load->scope_model($this , 'system/shopping_cart' , 'cart' );
		$this->load->scope_model( $this , 'system/pushnotification' , 'pushnotification' );

		require_once APPPATH . 'libraries/HTMLPurifier/HTMLPurifier.includes.php';
		require_once APPPATH . 'libraries/emogrifier-master/Classes/Emogrifier.php';
		
		$this->html_purifier = new HTMLPurifier();
		$this->emogrifier =  new \Pelago\Emogrifier();
	}

	public function load_email_template()		
	{
		$this->load->scope_model( $this , 'system/email_template' , 'email_template');
	}


	public function load_sms()
	{
		$this->load->scope_model( $this , 'system/sms' , 'sms' );
	}

	public function load_dns()
	{
		$this->load->scope_model( $this , 'system/dns' , 'dns' );
	}

	public function load_shipping()
	{
		$this->load->scope_model($this , 'system/shipping' , 'shipping' );
	}

	public function safe_html( $html )
	{
		
		 return $this->html_purifier->purify($html);
	}

	public function inline_css( $html_code , $fetch_css_files = False )
	{
		
		$this->emogrifier->setHtml( $html_code );
		$this->emogrifier->setCss('');
		return $this->emogrifier->emogrify();
		
	}


	/**
	 * Create order url from shop and order
	 *
	 * @param 		Array 	$shop 		Shop
	 * @param 		Array 	$order 		Order
	 *
	 * @return 		String
	 */
	public function order_url( $shop , $order )
	{
		return $shop['url'] . 'cart/view_order/' . $order['order_id'] . '/' . md5( $order['date_created'] . OS_BASE_DOMAIN . $order['order_id'] ) ;
	}

	//is valid fully qualified domain name
	public function is_valid_fqdn(  $subdomain )
	{
		return preg_match("/^[a-z0-9][a-z0-9\-\.]+[a-z]$/i", $subdomain ); 
		
	}

	public function is_valid_email( $email )
	{
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public function is_valid_phone_number(  $phone_number )
    {
            //only sending to zim phone numbers
            return preg_match('/^(\+|00)263(77|78|73|71|72)\d{7,8}+/', $phone_number );
    }

    public function make_international_number(  $phone_number )
    {
    	if( $this->is_valid_phone_number($phone_number) )
    		return $phone_number;
    	//tae a phone number and make it into an international phone number
    	//only applies to zim numbers
    	$prefixes = array('077','078','073','071','072');
    	if( ! in_array(substr($phone_number, 0,3), 	$prefixes))
    		//not zim number
    		return False;
    	//+26377xxxxxxx
    	return '+263' . substr($phone_number, 1);
    }


	


}

