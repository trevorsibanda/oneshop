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


 $response = array('status' => 'ok' ,'message' => '' , 'price' => 0.00 , 'provider' => $consignment['provider'] , 'logo' => 'http://localhost:9090/logo.png' );


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

 //Use weight range to calculate price
 //weight is in kg
 if( $package['weight'] <= 1.00 )
 {
 	//50 cents for orders under $1,00
 	$response['price'] += 0.50;
 }
 else if( $package['weight'] <= 2.00 )
 {
 	$response['price'] += 0.75;
 }
 else if( $package['weight'] <= 5.00 )
 {
 	$response['price'] += 1;
 }
 else if($package['weight'] <= 10.00 )
 {
 	$response['price'] += 2;
 }
 else
 {
 	//we handle deliveries over 10 kg but we do not accept payment online ..
 	$response['price'] += 3.00;
 	$response['message'] = 'For deliveries over 10 kg you will have to come to our depot and get a quotation there.';

 }



 //done

 //if we wish to save the query for caching purposes or stat analysis later
 //we can do so.
 if( empty($response['message']))
 	$response['message'] = 'Our depots are open from 8am to 5pm everyday.';

 
write( $response );

