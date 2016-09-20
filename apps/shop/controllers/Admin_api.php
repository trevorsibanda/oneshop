<?php
/**
 * OneSHOP Admin API
 *
 * This is the api used for the admin interface 
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Currently the API only works on 
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


 class Admin_API extends OS_AdminController
 {
 	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

 		
 	}



 	

 	
 	
 	


 	///////////// BULK READ OPERATIONS ////////////////////////////


 	

 	

 	

 	public function get_shop_users()
 	{

 	}





 	public function wow()
 	{
 		
 		

 		//print_r( $this->data['cart']);
 		$email = array();
 		$email = array();
 		$email['type'] = 'order';
 		$email['header'] = 'Your order is waiting';

 		$email['message'] = 'Hello ' . 'Trevor Sibanda' . '<br/>Thank you for choosing ' . $this->data['shop']['name'] .'<br/>You can complete and change you order at any time and still enjoy the convenience of paying using EcoCash, TeleCash, Visa, ZimSwitch or MasterCard!';
 		$email['action_link'] = 'http://oneshop.co.zw/';
 		$email['action_message'] = 'Pay ' .  money($this->system->cart->get_total() );
 		$email['footer_msg'] = 'After making the payment you will receive an SMS with a collection code you will be required to produce at our stores, you will then be able to collect your order.';
		$email['footer_action'] = 'Cancel this order';
		$email['footer_action_url'] = 'http://oneshop.co.zw/order/cancel?4324324324324234324324234234234234'; 		
 		$cart = $this->system->cart->items();
 		$this->data['shop']['contact'] = $this->shop->settings->get_contact_settings( $this->data['shop']['shop_id'] );
 		$html = $this->ui->generate_email($this->data['theme']['info']['dir'] ,$email , $this->data['shop'] ,  $this->data['products'] , $cart );
 		
 		
 		//$this->shop->shop_log->shop_id( $this->data['shop']['shop_id']  );
 		//$this->shop->shop_log->product('Added product Mazoe 2L');
 		echo $html;
 		$this->system->pushnotification->push_email( $this->data['shop']['shop_id'] , 'Trevor Sibanda' , 'trevorsibb@gmail.com', 'Your order is ready', $html );
 	}



 	

 	public function get_all_shop_log(  )
 	{

 	}

 	public function get_all_shop_wallet_log( )
 	{

 	}

 	public function get_shop_images()
 	{
 		
 		$images = $this->shop->image->shop_images( $this->data['shop']['shop_id'] );
 		$this->render('images' , $images );
 	}

 	public function get_shop_image( $image_id )
 	{
 		if( is_null($image_id) or empty($image_id))
 			$this->forbidden();
 		$image =$this->shop->image->get( $image_id );
 		if( empty($image) or $image['shop_id'] != $this->data['shop']['shop_id'])
 			$this->forbidden();
 		$this->render('image' , $image );
 	}

 	public function get_product_image( $image_id )
 	{
 		if( empty($image_id) or is_null($image_id))
 			$this->forbidden();
 		$image =$this->product->image->get( $image_id );
 		if( empty($image) or $image['shop_id'] != $this->data['shop']['shop_id'])
 			$this->forbidden();
 		$this->render('image' , $image );
 	}

 	public function get_product_images(  )
 	{
 		
 		$images = $this->product->image->shop_images(  $this->data['shop']['shop_id'] );
 		$this->render('images' , $images );
 	}


 	public function get_product_file( $file_id )
 	{
 		if( empty($file_id) or is_null($file_id))
 			$this->forbidden();
 		$file = $this->product->file->get_by_id( $file_id );
 		if( empty($file) or ($file['shop_id'] != $this->data['shop']['shop_id']))
 			$this->forbidden();
 		$this->render('file' , $file);
 	}

 	public function get_product_files( )
 	{
 		
 		$files = $this->product->file->get_shop_files( $this->data['shop']['shop_id'] );
 		$this->render('files' , $files );
 	}


 	public function get_shop_followers( )
 	{

 	}

 

 	


 	






 }