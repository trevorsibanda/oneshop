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
 define('API_KEY' , 'zimpost');

 /** Secret api key, should never be shared and should be treated like hard cash ! **/
 define('API_SECRET' , 'zimpost_zw_34urgfrg4th45gtrg45grtt54g4gsf');
 
 /** Name of provider, should be EXACTLY the same as in OneShop **/
 define('API_PROVIDER' , 'ZimPost');

 /** Oneshop delivery api endpoint url **/
 define('OS_SHIPPING_ENDPOINT' , 'http://secure.263shop.co.zw/api/endpoint/shipping');
 
 define('SWIFT_ENDPOINT' ,'http://swift.co.zw/shipping/quote?mode=ajax&destination=' );


 //countries we deliver to. Must be iso code ie ZW,ZA
 $supported_countries = array('ZW');

 //cities and towns we deliver to, support all 
 $supported_cities = array(
);

 //Our depots
 $our_depots = array();

 
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
 
 
 
 /**
 * Rest helper
 *
 * @param 		String 	$url 		Url
 * @param 		Mixed 	$params 	Parameters, string or array
 * @param 		String 	$verb 		HTTP VERB
 * @param 		String 	$format 	Format
 *
 * @return 		Mixed 	$			Depending on format, returns array, object or string. returns False on Fail
 */
function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
{
  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true
    )
  );
  if ($params !== null) {
    $params = http_build_query($params);
    if ($verb == 'POST') {
      $cparams['http']['content'] = $params;
    } else {
      $url .= '?' . $params;
    }
  }

  $context = stream_context_create($cparams);
  $fp = @fopen($url, 'rb', false, $context);
  if (!$fp) {
    $res = false;
  } else {
    // If you're trying to troubleshoot problems, try uncommenting the
    // next two lines; it will show you the HTTP response headers across
    // all the redirects:
    // $meta = stream_get_meta_data($fp);
    // var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }

  if ($res === false) {
    return False;
  }

  switch ($format) {
    case 'json':
      $r = json_decode($res);
      if ($r === null) {
        return $r;
      }
      return $r;

    case 'xml':
      $r = simplexml_load_string($res);
      if ($r === null) {
        return $r;
      }
      return $r;
  }
  return $res;
}	

