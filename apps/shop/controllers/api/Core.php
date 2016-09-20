<?php
/**
 * OneSHOP Admin Core API
 *
 * Core api calls used by the admin interface
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Core extends OS_AdminController
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
 	}

 	/** Get Shop **/
 	public function shop(  )
 	{
 		//get shop - ignore not active
 		$shop = $this->shop->get_by_id( $this->data['shop']['shop_id'] , false ) ;
 		//logo
 		$shop['logo'] = $this->shop->image->get(  $shop['logo_id'] );
 		
 		$this->render('shop_data'  ,$shop  );
 	}

 	/**
 	 * Get the shop's summary
 	 *
 	 * This returns the shop, current subscription, orders info( total, pending, cancelled , shipped ...etc)
 	 * number of products ...etc
 	 *
 	 * @todo add recurring orders
 	 * @todo add "this week" stats to summary
 	 */
 	public function summary()
 	{
 		
 		$this->load->model('payment');

 		//update shop
 		$summary = array();

 		$summary['shop'] = $this->data['shop'];
 		$summary['shipping_orders'] = 0; //number of *paid* orders waiting to be shipped
 		$summary['pending_orders'] = 0; //number of pending orders
 		$summary['pending_transactions'] = 0; //pending transactions ( initiated but not paid )
 		$summary['purchased_orders'] = 0; //number of fulfilled orders
 		$summary['stock_count'] = 0; //total stock left in shop
 		$summary['stock_ordered'] = 0; //total stock currently ordered
 		$summary['stock_sold'] = 0; //total stock bought
 		$summary['amount_ordered'] = 0; //total amount ordered ( cummulative value of orders )
 		$summary['amount_paid'] = 0.00; //total amount paid ( cummulative value of transactions )
 		$summary['blog_count'] = 0; //number of blog posts
 		$summary['last_order_time'] = 0; //latest ordered time
 		$summary['pos_orders'] = 0; //number of pos orders
 		
 		$curr_account = $this->data['subscription'];
 		$settings = $this->data['settings'];

 		$summary['account_type'] = $curr_account['type']; //account type
 		$summary['account_expires'] = $this->shop->account->get_expire_date( $curr_account['shop_id']  ); //when does the account expire

 		$summary['support_tickets'] = array(); //customer support requests

 		$summary['notifications'] = array(); //important notifications.

 		//add notifications

 		//if payment settings not set, 
 		$keys = array('gateway','api_key','api_secret');
 		$gateways_ready = false;
 		foreach( array('primary','secondary') as $g )
 		{
 			$g_ok = True;
 			foreach( $keys as $k )
 			{
 				if( empty($settings['payment'][$g . '_' . $k])  )
 					$g_ok = False;
 			}
 			$gateways_ready = $gateways_ready or $g_ok;
 		}

 		//if one entreprenuer account, urge to upgrade
 		if(  $curr_account['type'] != 'premium' )
 			array_push($summary['notifications'], "You are not benefitting from the full features we have to offer, upgrade to premium account");

 		//show that we are in beta stage
 		array_push($summary['notifications'], "This is the beta version of 263Shop, it may be buggy and some features might be broken, please let us know so we can fix them");

 		


 		if( ! $gateways_ready )
 		{
 			array_push($summary['notifications'] , 'Your shop does not currently have properly setup payment gateway, your customers will not be able to pay online.');
 		}





 		//@todo count point of sale orders
 		$summary['pos_orders'] = 0;
 		//@todo get latest order time
 		$summary['last_order_time'] = '';

 		//get stock
 		$summary['stock_count'] = $this->product->count_shop_stock( $this->data['shop']['shop_id'] );
 		//@todo get blog count
 		$summary['blog_count'] = 0;

 		//check transactions
 		$pending_trans = $this->payment->transaction->get_empty_shop_transactions( $this->data['shop']['shop_id'] );
 		$summary['pending_transactions'] = count( $pending_trans );

 		//last 3 pending orders
 		$summary['latest_orders'] = $this->system->cart->get_shop_orders( $this->data['shop']['shop_id'] , 'all' , 3 ); 

 		//NB: Because the summary is supposed to load first and quickly, only the name and email is added to all orders
 		//this is information normally found in the shipping data
 		foreach ($summary['latest_orders'] as $key => $order)
 		{
 			unset( $order['log'] );
 			$shipping = $this->system->cart->get_order_shipping( $order['order_id'] );
 			$order['fullname'] = $shipping['fullname'];
 			$order['email'] = $shipping['email'];
 			$summary['latest_orders'][$key] = $order;	
 		}
 		
 		//orders
 		$orders = $this->system->cart->get_shop_orders( $this->data['shop']['shop_id'] , 'all' , -1 );
 		
 		foreach( $orders as  $key=>$order )
 		{
 			$order['items'] = $this->system->cart->get_order_items( $order['order_id'] );
 			$order['shipping'] = $this->system->cart->get_order_shipping( $order['order_id']);
	 		$order['transaction'] = $this->payment->transaction->get(  $order['transaction_id'] );
	 			 		//change attribute options from string
	 		if(  $order['status'] == 'paid' )
		 	{
		 		$summary['purchased_orders'] += 1;
		 		//amount paid
		 		if( $order['transaction']['amount'] != $order['total'] )
		 		{
		 			//@todo trigger an error an report to admin
		 		}
		 		$summary['amount_paid'] += $order['transaction']['amount'];
		 		//stock sold
		 		foreach ($order['items'] as $item) 
 				{
 					$summary['stock_sold'] += $item['qty'];
 				}
 				//check shipping
 				//if is ready for delivery or already dispatched and is not delivered
 				if(  ( $order['shipping']['is_ready'] || $order['shipping']['is_dispatched']   )  )
 				{
 					$summary['shipping_orders'] += 1;
 				}


		 	}	
		 	//pending orders
		 	if( $order['status'] == 'pending' )
 			{
 				$summary['pending_orders'] += 1;
 				foreach ($order['items'] as $item) 
 				{
 					$summary['stock_ordered'] += $item['qty'];
 				}
 				
				$summary['amount_ordered'] += ( $item['price'] );	
 			}

 		}


 		$this->render('summary' , $summary );
 	}


 	/** Get shop wallet **/
 	public function wallet( )
 	{
 		
 		$this->render('shop_wallet' , $this->data['wallet']);
 	}

 	/** Get shop logs within specified constraints **/
 	public function logs(  $action = 'all'  )
 	{

 	}

 	

 	/** Get shop subscription **/
 	public function subscription()
 	{
 		
 		$sub = $this->shop->account->current_subscription( $this->data['shop']['shop_id'] );
 		//if the current shop has no subscription. subscribe it for a month in free plan
 		//@to review this option.
 		if(empty($sub) or is_null($sub) )
 		{
 			$id = $this->shop->account->add_subscription( $this->data['shop']['shop_id'] , date('Y') , date('m') , 'free' , 1);
 			//log that we automtically subscribed the user
 			$this->shop->logger->action('Automatically subscribed the shop to free account because it had no subscription.');
 			$sub = $this->shop->account->get_subscription( $this->data['shop']['shop_id'] , date('Y') , date('m') );
 		}

 		//change expiry date to last subscription expiry date
 		$sub['date_expires'] = $this->shop->account->get_expire_date( $sub['shop_id'] );
 		$this->render('subscriptions' , $sub );
 	}

 	/** Get all shop subscriptions , limits then to a 36 month period **/
 	public function all_subscriptions()
 	{
 		
 		$subs = $this->shop->account->get_all_subscriptions( $this->data['shop']['shop_id'] );
 		$this->render('subscriptions' , $subs );
 	}

 	/** Subscription types **/
 	public function subscription_types( )
 	{
 		$subs = $this->shop->account->subscription_types();
 		$this->render('subs' , $subs );
 	}


 	/** Shop notifications **/
 	public function notifications( )
 	{
 		//@todo implement shop notifications	
 		$this->render('notifications' , array());
 	}



}

