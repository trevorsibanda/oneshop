<?php
/**
 * Oneshop shipping and delivery endpoint demo.
 *
 * Use this code as a guide to developing your own system.
 *
 * @author 		Trevor Sibanda
 * @date 		4 August 2015
 *
 */

 


 /** NB: Dont show errors to prevent json data beign corrupted **/
 error_reporting(0);

 //use php sessions for storing data
 session_start(); 	


 /** NB: DO NOT SHARE YOUR API KEY OR SECRET **/	

 /** Obtain your key from OneShop **/	
 define('API_KEY' , '666667');

 /** Secret api key, should never be shared and should be treated like hard cash ! **/
 define('API_SECRET' , '1234567890124567890123456789012');
 
 /** Name of provider, should be EXACTLY the same as in OneShop **/
 define('API_PROVIDER' , 'Foot Soldiers');

 /** Oneshop delivery api endpoint url **/
 define('OS_SHIPPING_ENDPOINT' , 'http://secure.oneshop.co.zw/shipping_api/');


 //countries we deliver to. Must be iso ode ie ZW,ZA
 $supported_countries = array('ZW');

 //cities and towns we deliver to
 $supported_cities = array('Bulawayo' , 'Harare' , 'Mutare' ,'Gweru');

 //Our depots
 $our_depots = array('Bulawayo' => 'Bulawayo, 34 12th Ave-Jason Moyo Street' ,'Harare' => 'Harare, 5664 Corner Abel Muzoriwa Road' ,'Gweru' => 'Gweru, Midlands State University Campus' , 'Mutare' => 'Mutare 67 Mutare Drive' );

 
 /**
  * Read the posted json data and convert it to an array. Returns false on fail
  */
 function read_json_input( )
 {
 	return json_decode(file_get_contents('php://input'),true);
 }

 /**
  * Show error and exit
  */
 function emit_error($message)
 {
 	write( array('status' =>'error' , 'error' => $message ) );
 }  

 /**
  * Print output and exit
  */
 function write(  $object )
 {
 	@header('Content-type:  application/json');
 	echo json_encode($object , JSON_PRETTY_PRINT );
 	exit;
 }

 function output( $mixed )
 {
	write($mixed);
 }

 /**
  * Verify if challenge is valid.
  *
  */
 function verify_challenge( $challenge , $salt )
 {
 	$hash =  md5( $salt . API_SECRET   );
 	
 	if( $hash !== $challenge)
 		return False;
 	return True;
 }

 /**
  * Perform http request to oneshop shipping
  * api endpoint to confirm that you will
  * be handling the consignment.
  *
  */
 function http_oneshop_callback(  $response )
 {
 	$url = OS_SHIPPING_ENDPOINT . 'acknowledge/' . $response['order_challenge'];
  $post_data = json_encode($response);


 	$ch = curl_init();    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $post_data);	
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
  
    //execute post  

    $result = curl_exec($ch);  
  
    if($result)  
    {  
    	curl_close($ch);
    	$obj = json_decode($result , True);
    	if( ! is_array($obj) )
    	{
    		return array('status' => 'fail');
    	}
    	return $obj;
    }
    else
    {
    	curl_close($ch);
    	return array('status' => 'fail');
    }
 	return array('status' => 'ok');
 }	

