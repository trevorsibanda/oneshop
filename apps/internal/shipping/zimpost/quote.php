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


 $response = array('status' => 'ok' ,'message' => '' , 'price' => 0.00 , 'provider' => $consignment['provider'] , 'logo' => 'http://swift.co.zw/images/swift-small-grayscale.png' );


 //check if both source country and source city are supported

 $src = $consignment['route']['source'];

 $dst = $consignment['route']['destination'];

 //check source country and city
 if(  ( ! in_array($src['country'], $supported_countries) )  )
 {
 	//we do not ship to this country
 	$response['status'] = 'fail';
 	$response['message'] = 'We do not ship good from ' . $src['country'] . '/' . $src['city'] ;
 	output($response); 
 }

 //check destination country and city
 if(  ( ! in_array($dst['country'], $supported_countries) )  )
 {
 	//we do not ship to this country
 	$response['status'] = 'fail';
 	$response['message'] = 'We do not ship to ' . $dst['country'] . '/' . $dst['city'] . ' We only ship inside Zimbabwe.' ;
 	output($response); 
 }


 $package = $consignment['package'];
 
 if(  $package['weight'] > 2 )
{
	emit_error('ZimPost supports a maximum 2kg package');
}
 
 $sender = $consignment['sender'];

$response['price'] = 2.00; //simple default zimpost price
$response['message'] = 'Prices are quoted in USD';

 
write( $response );

