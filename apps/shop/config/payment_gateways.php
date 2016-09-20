<?php
/**
 * Config for payment gateways
 *
 *
 *
 *
 */
 
 /**
  * Is the payment gateway in testing/debug mode
  *
  */
 $config['payment_gateway_debug_mode'] = True;	


 $config['payment_gateways'] = array();
 /**
  * Pay4APP Gateway
  *
  */
 
 /** Callback url - called when payment is completed **/
 //{{var}} will be replaced.
 $config['payment_gateway_callback_url'] = 'https://'. OS_SECURE_DOMAIN . '/api/endpoint/{{gateway_name}}/{{transaction_id}}/{{extra_data}}';

 /** Callback url (transfer pending) - called when user has made payment but gateway is still waiting for confirmation **/
 $config['payment_gateway_pending_callback_url'] = 'https://'. OS_SECURE_DOMAIN . '/api/endpoint/{{gateway_name}}/{{transaction_id}}/{{extra_data}}';

 

 /** Free account, payment gateway **/
 $config['free_account_payment_gateway'] = 'pay4app'; //@todo should be paynow

 /** Free account details **/
 $config['free_account_gateway_config'] = array('api_key' =>'funerally','api_secret'=>'J37M54CK7XF1244CV7E9T6F9ROGE8Z205SCKE61HNTPH66IYMOJKC9710BU06HK8X43T9TWYT6JYM9LT','api_data'=>'');

 $config['free_account_payment_gateway_config'] = array(); //free payments

 $config['payment_gateways']['pay4app'] = array('name' => 'Pay4App' ,  //name of the gateway
 							'description' => 'Pay4App is a Zimbabwean payment gateway', //description
 							'refund' => False , //can the gateway perform refunds
 							'website' => 'https://pay4app.com/' , //website for the payment gateway
 							'checkout_img' => 'https://pay4app.com/img/splashwide.png',//image shown on checkout
 							'checkout_msg' => 'Pay4App is the easiest way to pay online. Pay using EcoCash, TeleCash, Mastercard, Visa or ZimSwitch in a quick and secure way',//message printed on checkout
 							'max_amount' => 10000 ,//maximum amount the gateway can process
 							'min_amount' => 0.5, //minimum amount the gateway can process 
 							'supported' => array('EcoCash' , 'TeleCash'  , 'MasterCard' , 'Visa' , 'ZimSwitch') ,//list of supported methods
 							'gateway' => 'pay4app'
 							);

/**
 * PayNow gateway
 */
$config['payment_gateways']['paynow'] = array('name' => 'PayNow' ,  //name of the gateway
 							'description' => 'PayNow is a Zimbabwean payment gateway', //description
 							'refund' => False , //can the gateway perform refunds
 							'website' => 'https://paynow.co.zw/' , //website for the payment gateway
 							'checkout_img' => 'https://www.paynow.co.zw/Content/icons/paynow-logo-blue.png',//image shown on chekcout
 							'checkout_msg' => 'Secure and trusted MasterCard, VISA, EcoCash, TeleCash and ZimSwitch bank payments with PayNow Zimbabwe.',//message printed on checkout
 							'max_amount' => 10000 ,//maximum amount the gateway can process
 							'min_amount' => 1, //minimum amount the gateway can process 
 							'supported' => array('EcoCash' , 'TeleCash'  , 'MasterCard' , 'Visa' , 'ZimSwitch'), //list of supported methods
 							'gateway' => 'paynow'
 							);

/**
 * FloCash
 */ 


/** Default payment gateways **/
$config['primary_payment_gateway'] = 'PayNow';

/** Secondary payment gateway **/
$config['secondary_payment_gateway'] = 'Pay4App';




