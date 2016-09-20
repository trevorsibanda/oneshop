<?php
/**
 * Account types
 *
 * For interget values if set to -1 it means unlimited
 *
 *
 *
 */

 /* Valid account types 
  *
  * NB: Always mantain order !!!
  */
 $config['account_plans'] = array('free' , 'basic' , 'premium');

 /** Free Account **/
 $config['free_plan'] = array( 
 		'name' => 'Entrepreneur Plan',
 		'max_products' => 10 ,
 		'custom_domain' => False ,
 		'max_users' => 1 ,
 		'allow_pos' => False ,
 		'allow_analytics' => False,
 		'custom_payment_details' => True,
 		'ad_supported' => True,
 		'allow_coupons' => False ,
 		'sms_credit' => 5 ,
 		'type' => 'free' ,
 		'app_credit' => 0 ,
 		'free_domain' => False,
 		'amount' => 0.00 ,
 		'amount_year' => 0.00
 		  );

 /** Basic Plan **/
 $config['basic_plan'] = array( 
 		'name' => 'Basic Plan',
 		'max_products' => 50 ,
 		'custom_domain' => True ,
 		'max_users' => 5 ,
 		'allow_pos' => True ,
 		'allow_analytics' => True,
 		'custom_payment_details' => True,
 		'allow_coupons' => True ,
 		'sms_credit' => 20 ,
 		'type' => 'basic' ,
 		'app_credit' => 100 ,
 		'ad_supported' => True,
 		'amount' => 4.99 ,
 		'free_domain' => False,
 		'amount_year' => 59.99
 		  ); 

 /** Premium Plan **/
 $config['premium_plan'] = array( 
 		'name' => 'Premium Plan',
 		'max_products' => 99999 ,
 		'custom_domain' => True ,
 		'max_users' => 100 ,
 		'allow_pos' => True ,
 		'allow_analytics' => True,
 		'custom_payment_details' => True,
 		'allow_coupons' => True ,
 		'sms_credit' => 1000 ,
 		'type' => 'premium' ,
 		'app_credit' => 9999 ,
 		'free_domain' => True,
 		'ad_supported' => True,
 		'amount' => 19.99 ,
 		'amount_year' => 239.99
 		  ); 

