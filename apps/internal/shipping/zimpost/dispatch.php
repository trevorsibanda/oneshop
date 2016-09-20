<?php
/**
 * Oneshop shipping and delivery endpoint demo.
 *
 * Use this code as a guide to developing your own system.
 *
 * Dispatch a quote for example here, the user can choose to have the package picked up
 * from his house or he can choose to make the payment here and deliver the package to .
 * the nearest delivery center. 
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

 
 $response = array('status' => 'ok' /** ok if ready to process dispatch, fail otherwise **/ , 
 				   'provider' => $consignment['provider'] , /** Your provider name , best to get it from consignment **/
 				   'url' => '' /** Url Oneshop should redirect user to , must not be relative i.e should be http://mysite.com/something **/ );


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
 	$response['message'] = 'We do not ship to ' . $dst['country'] . '/' . $dst['city'] ;
 	output($response); 
 }


// Here we save the consignment to databse or temporary file
// For demonstration we'll use temporary file
// Dont save to session as oneshop will redirect user and cookie will be lost

$data = json_encode($consignment );

//create random filename, should be random
$fname = md5($data);

$fh = fopen('temp_data/' . $fname, 'w');
fwrite($fh, $data);
fclose($fh);

//print out form to be submited to swift_zw
 $package = $consignment['package'];
 
 $sender = $consignment['sender'];
 
if(  $package['weight'] > 2 )
{
	emit_error('ZimPost supports a maximum 2kg package');
} 

$url = SWIFT_ENDPOINT . $dst['city'];

$response['url'] = "http://www.zimpost.co.zw/index.php/2013-08-23-08-11-15";



write( $response );

