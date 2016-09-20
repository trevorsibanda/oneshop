<?php
/**
 * Oneshop shipping and delivery endpoint demo.
 *
 * Use this code as a guide to developing your own system.
 *
 * Generate a quote. This example uses the weight only and has
 * a list of supported source and destination cities/towns.
 *
 * @author 		Trevor Sibanda
 * @date 		4 August 2015
 *
 */

 require_once('config.php');

 $consignment = read_json_input();

 if( empty($consignment) or $consignment === False )
 {
 	emit_error('Invalid json data posted to api');
 } 

 //verify authenticity of request
 if( verify_challenge($consignment['challenge'] , $consignment['salt']) == False )
 {
 	emit_error('Failed to verify http request authenticity');
 }


 $response = array('status' => 'ok' ,'message' => '' , 'price' => 0.00 , 'provider' => $consignment['provider'] , 'logo' => 'http://assets.263shop.co.zw/public/www/sedna/img/payment/swift.png' );


 //check if both source country and source city are supported

 $src = $consignment['route']['source'];

 $dst = $consignment['route']['destination'];

 //check source country and city
 if(  ( ! in_array($src['country'], $supported_countries) ) or ( ! in_array($src['city'], $supported_cities) ) )
 {
 	//we do not ship to this country
 	$response['status'] = 'fail';
 	$response['message'] = 'We do not ship good from ' . $src['country'] . '/' . $src['city'] ;
 	output($response); 
 }

 //check destination country and city
 if(  ( ! in_array($dst['country'], $supported_countries) ) or ( ! in_array($dst['city'], $supported_cities) ) )
 {
 	//we do not ship to this country
 	$response['status'] = 'fail';
 	$response['message'] = 'We do not ship to ' . $dst['country'] . '/' . $dst['city'] ;
 	output($response); 
 }

 //Calculate costs
 //If in same city add $2 if in different cities its $5

 if( $src['city'] == $dst['city'] )
 {
 	$response['price'] += 2.00;
 }
 else
 {
 	$response['price'] += 5.00;
 }


 $package = $consignment['package'];
 
 $sender = $consignment['sender'];
 
 //query remote server
$params = array('source'  => $src['city'],
'destination' => $dst['city'] ,
'weight' => $package['weight'],
'length' => $package['dimensions']['l'],
'width' => $package['dimensions']['w'],
'height'=> $package['dimensions']['h'],
'description' => $package['comments'] ,
'sender_name' => $sender['fullname'],
'email_address' => $sender['email'],
'contact_no' => $sender['phone'],
'calculate' => 'Calculate',
'email' => '');

$url = SWIFT_ENDPOINT . $dst['city'];

$resp = rest_helper($url, $params, 'POST', 'html');

if( $resp == False )
{
	emit_error('Failed to communicate with Swift Servers');
	return;
}

//attempt to extract data
$matches = array();
$pattern = '/class="right">\$[0-9]+/';

$r = preg_match( $pattern , $resp , $matches );

if( ! is_array($matches) or empty($matches) )
{
	emit_error('Failed to get quotation from the shipping provider');
}
$price = str_replace('class="right">$' , '' , $matches[0] );


$response['price'] = $price;
$response['message'] = 'Prices are quoted in USD';

 
write( $response );

