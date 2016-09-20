<?php


class Ui extends CI_model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->scope_model($this , 'ui/theme' , 'theme');
	}


	public function load_ad_engine()
	{
		$this->load->scope_model($this , 'ui/Ad_engine' , 'ad_engine');
	}
    
    public function load_analytics( )
    {
        $this->load->scope_model($this , 'ui/Analytics','analytics');   
    }
    
    public function load_css_engine()
    {
        $this->load->scope_model($this, 'ui/CSS_Engine' ,'css');   
    }

	/**
	 * Generate an email html code. 
	 *
	 * After calling this function, System::email may be called and any
	 * extra/required files added
	 * 
	 * @param 		String 	$theme 			Theme currently in use by shop.
	 * @param 		Array 	$email			SHould contain type =>(info,order,warning,delivery,danger) , header => text,subheader=>text,message=>text,ation_link=>text,action_message=>text
	 * @param 		Array 	$shop 			Shop
	 * @param 		Array 	$products 		Products to be shown in email *optional*
	 * @param 		Array 	$order 			Order info *optional* 
	 *
	 * @return 		Mixed 	String on success, False on fail
	 */
	public function generate_email( $theme , $email , $shop , $products , $order = array() )
	{
		$file = APPPATH . '/views/theme/' . $theme . '/email.php';
		$theme_view = 'theme/' . $theme . '/email';
		if( ! file_exists($file))
		{
			$theme_view = 'system/theme/email';
		}
		$data = array('email' =>  array() , 'shop' => array() , 'products' => $products , 'order' => $order );
		$data['email'] = $email; //array('type' => $type , 'header' => $header , 'subheader' => $subheader , 'message' => $message , 'action_link' => $action_link );
		$data['shop'] = $shop;
		$data['products'] = $products;
		$data['order'] = $order;

		$html = $this->load->view( $theme_view , $data , true );
		return $html;
	}
	
	

}
