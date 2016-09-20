<?php
/**
 * OneSHOP Admin API. Wallet
 *
 * Perform wallet related tasks.
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');

class Wallet extends OS_AdminController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->shop->load_wallet();
		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );

 		die('Not allowed');
	}

	/** Get shop's wallet **/
	public function get( )
	{
		//get current wallet
		$wallet = $this->shop->wallet->get( $this->data['shop']['shop_id'] );
		if( empty($wallet) )
			$this->forbidden();
		$this->render('wallet' , $wallet );
	}

	/** Request withdrawing a certian amount **/
	public function request_withdraw( $amount )
	{
		//get current wallet
		$wallet = $this->shop->wallet->get( $this->data['shop']['shop_id'] );
		if( empty($wallet) )
			$this->forbidden();
		if( $amount > $wallet['amount'] )
			$this->error('Requested amount is greater than amount in wallet');
		$b = $this->shop->wallet->request_withdraw( $this->data['shop']['shop_id'] , $amount );
		if( ! $b )
			$this->error('Request withdraw failed');
		$this->render('' , array('status' => 'ok'));
	}

	
}