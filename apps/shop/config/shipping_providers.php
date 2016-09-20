<?php
/**
 * Shipping Providers
 *
 *
 *
 */


/** List of all supported providers **/
$config['shipping_providers'] = array(/*'SWIFT-ZW' Swift Zimbabwe */  'Swift Zimbabwe' , 'ZimPost' /*Oneshop delivery *also debug* - foot soldiers*/);


/** Is the shipping api in debug mode **/
$config['is_shipping_debug'] = False;


$config['Foot Soldiers'] = array(
	  'name' => 'Foot Soldiers Delivery' //company name 
	, 'website' => 'http://localhost:9090/'  //company website
	, 'contact_email' => 'foot@localhost'  //company contact email
	, 'contact_phone' => '+26399999999' //company phone number
	, 'contact_skype' => 'skype_id'  //skype id
	, 'depots' => array('Bulawayo, ZW - 45 Koala Street')  //list of depots to dispatch or receive packages
	, 'cities' => array('Bulawayo' , 'Harare') //cities and town delivered to
	, 'countries' => array('ZW')  //countries delivered to
	, 'deliver_residential' => True  //does the provider deliver to residential areas
	, 'min_period' => '48 Hrs'  //minimum period it takes to deliver a product
	, 'max_period' => '72 Hrs'  //maximum period taken to deliver a product
	, 'api' => array( 'check'    =>    'http://localhost:9090/checkonline.php' , //check if provider is accepting deliveries
	     			  'quote'    =>    'http://localhost:9090/quote.php' , //get quote on a consignment
	     			  'dispatch' =>    'http://localhost:9090/dispatch.php' ,//dispatch a consignment
	     			  'get' =>    'http://localhost:9090/get.php' , //get information about a consignment given id
	     			  'info' => 'http://localhost:9090/info.php'  //get terms and conditions of provider as well as  extra info
	     			  )
	, 'key'  => '666667'  //provider api key
	, 'secret' => '1234567890124567890123456789012' //md5 hash secret.
	);
	
$config['Swift Zimbabwe'] = array(
	  'name' => 'Swift Zimbabwe' //company name 
	, 'website' => 'http://www.swift.co.zw/'  //company website
	, 'contact_email' => 'hello@swift.co.zw'  //company contact email
	, 'contact_phone' => '08677 000 777' //company phone number
	, 'contact_skype' => 'skype_id'  //skype id
	, 'depots' => array('')  //list of depots to dispatch or receive packages
	, 'cities' => array('Bulawayo' , 'Harare') //cities and town delivered to
	, 'countries' => array('ZW')  //countries delivered to
	, 'deliver_residential' => True  //does the provider deliver to residential areas
	, 'min_period' => '48 Hrs'  //minimum period it takes to deliver a product
	, 'max_period' => '72 Hrs'  //maximum period taken to deliver a product
	, 'api' => array( 'check'    =>    'http://internal.263shop.co.zw/shipping/swift_zw/checkonline.php' , //check if provider is accepting deliveries
	     			  'quote'    =>    'http://internal.263shop.co.zw/shipping/swift_zw/quote.php' , //get quote on a consignment
	     			  'dispatch' =>    'http://internal.263shop.co.zw/shipping/swift_zw/dispatch.php' ,//dispatch a consignment
	     			  'get' =>    'http://internal.263shop.co.zw/shipping/swift_zw/get.php' , //get information about a consignment given id
	     			  'info' => 'http://internal.263shop.co.zw/shipping/swift_zw/info.php'  //get terms and conditions of provider as well as  extra info
	     			  )
	, 'key'  => 'swift_zw'  //provider api key
	, 'secret' => 'swift_zw_1234567890098765432123456789' //md5 hash secret.
	);
	
$config['ZimPost'] = array(
	  'name' => 'ZimPost' //company name 
	, 'website' => 'http://www.zimpost.co.zw/index.php/2013-08-23-08-11-15'  //company website
	, 'contact_email' => 'hello@swift.co.zw'  //company contact email
	, 'contact_phone' => '0800 4249/9101 ' //company phone number
	, 'contact_skype' => 'skype_id'  //skype id
	, 'depots' => array('')  //list of depots to dispatch or receive packages
	, 'cities' => array('Bulawayo' , 'Harare') //cities and town delivered to
	, 'countries' => array('ZW')  //countries delivered to
	, 'deliver_residential' => True  //does the provider deliver to residential areas
	, 'min_period' => '48 Hrs'  //minimum period it takes to deliver a product
	, 'max_period' => '72 Hrs'  //maximum period taken to deliver a product
	, 'api' => array( 'check'    =>    'http://internal.263shop.co.zw/shipping/zimpost/checkonline.php' , //check if provider is accepting deliveries
	     			  'quote'    =>    'http://internal.263shop.co.zw/shipping/zimpost/quote.php' , //get quote on a consignment
	     			  'dispatch' =>    'http://internal.263shop.co.zw/shipping/zimpost/dispatch.php' ,//dispatch a consignment
	     			  'get' =>    'http://internal.263shop.co.zw/shipping/zimpost/get.php' , //get information about a consignment given id
	     			  'info' => 'http://internal.263shop.co.zw/shipping/zimpost/info.php'  //get terms and conditions of provider as well as  extra info
	     			  )
	, 'key'  => 'zimpost'  //provider api key
	, 'secret' => 'zimpost_zw_34urgfrg4th45gtrg45grtt54g4gsf' //md5 hash secret.
	);		

