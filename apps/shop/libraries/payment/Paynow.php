<?php
/**
 * PayNow Payment API
 *
 * A much simpler library to use for custom integration into PayNow API
 * The API currently supports all of the functionality provided in the API
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		2 Sept 2015 
 * @version 		0.2
 *
 * Sample usage:
 *
 *<?php
 *
 * define('PAYNOW_ID' , '1234');
 * define('PAYNOW_KEY' , '23rfdsad4rfsdg5436543');
 *
 * $paynow = new PayNow( array('id' => PAYNOW_ID , 'key' => PAYNOW_KEY) );
 * $paynow->set_result_url('https://myapp.com/callback.php?gateway=paynow');
 *
 * $reference = rand(); //get reference from database. must be unique 
 *
 * $transaction = $paynow->make_transaction($reference , 12.00 , 'Payment for something' , 'http://myapp.com/thank-you-for-paying')
 * $response = $paynow->init_transaction($transaction);
 *
 * if( isset($response['error']) ){  }
 *
 * if( $response['status'] !== PayNow::ps_ok )
 * {
 *	die($response['msg']); 	
 * }
 * //redirect user to paynow pay url if everything ok
 * header('Location: ' . $response['browserurl']); 
 *
 *?>
 * @todo 		Add more error reporting
 */

class PayNow
{

	/** Paynow status responses **/
	const  ps_error 			= 'Error'; //status Error
	const  ps_ok 				= 'Ok'; //status Ok
	const  ps_created 			= 'Created'; //transaction created by paynow but not yet paid
	const  ps_sent 				= 'Sent'; //paynow has sent customer to upstream payment provider
	const  ps_cancelled 		= 'Cancelled';//paynow cancelled transaction on server
	const  ps_disputed 			= 'Disputed'; //customer has disputed transation, funds now in suspense
	const  ps_paid 				= 'Paid'; //transaction paid successfully, merchant will receive funds at next settlement
	const  ps_awaiting_delivery = 'Awaiting Delivery'; //customer has to confrim delivery of goods
	const  ps_delivered 		= 'Delivered'; //customer has acknowledged he has received goods
	const  ps_refunded 			= 'Refunded'; //refunded back to customer



	/**
	 * PayNow Integration Key
	 *
	 * Must be kept private and well stored !
	 */
	private $_integration_key;

	/**
	 * Paynow Merchant integration ID
	 * Must be set in contrsuctor using config
	 */
	private $_integration_id;

	/**
	 * Paynow API url to init transaction
	 */
	private $_init_transaction_url =  'https://www.paynow.co.zw/interface/initiatetransaction';

	/**
	 * PayNow Callback Url
	 *
	 * Paynow will post transaction details to this URL
	 */ 
	private $_result_url = 'https://';

	/**
	 * Transaction return Url
	 *
	 * Url to redirect the user to once the transaction is complete.
	 * NB: This value is overriden by the url in the transaction object.
	 */
	private $_return_url = 'https://';

	/** Last HTTP request data */
	private $_http_data = Null;
	
	/**
	 * Empty transaction array.
	 */
	private $_empty_transaction_request = array( 
		'reference' => '' , //Merchant Transaction ID 
		'amount' => 0.00 ,  //Amount
		'additionalinfo' => '' , //Additional info
		'returnurl' =>'' , //URL to redirect the user to after payment
		'authemail' => '' //User email . Recommended to be set to nothing
		);

	/**
	 * Use Curl ?
	 *
	 */
	private $_use_curl = False;

	private $_use_proxy = False;
	private $_proxy = 'localhost:8082';


	/** ctor 
 	 *
 	 * @param 	Array 		$	PayNow Config (Integration keys)
 	 *
 	 * @return 	None
	 */
	public function __construct( $config = array('id' => '' , 'key' => '' , 'result_url' => '') )
	{
		if( empty($config['id']) or empty($config['key'])  )
			die('PayNow Invalid Config Passed: ' . __FILE__ . ':' . __LINE__ );
		$this->_integration_key = $config['key'];
		$this->_integration_id = $config['id'];
		$this->_result_url = isset( $config['result_url'] ) ? $config['result_url'] : '';
		$this->_use_curl =  isset($config['use_curl']) ? $config['use_curl'] : False;
		$this->_use_proxy =  isset($config['use_proxy']) ? $config['use_proxy'] : False;
		$this->_proxy =  isset($config['proxy']) ? $config['proxy'] : False;
		//check return url
		$this->_return_url = (isset($config['return_url'])  ? $config['return_url'] : '' );

		$this->_empty_transaction_request = array( 
		'reference' => '' , //Merchant Transaction ID 
		'amount' => 0.00 ,  //Amount
		'additionalinfo' => '' , //Additional info
		'returnurl' =>'' , //URL to redirect the user to after payment
		'authemail' => '' //User email . Recommended to be set to nothing
		);
	}

	/**
	 * Set the result URL
	 *
	 * @param 	String 		$	Result URL
	 *
	 * @return 	None
	 */
	 public function set_result_url( $url )
	 {
	 	$this->_result_url = $url;
	 }

	 

	 /**
	  * Set return url
	  *
	  * Will be used if none if specified when making a transaction object
	  *
	  * @param 		String 		$	Url
	  * 
	  */
	 public function set_return_url( $url )
	 {
	 	$this->_return_url = $url;
	 }

	 /**
	  * Make a transaction object.
	  *
	  * The transaction object is later used to initiate a transaction.
	  *
	  * @param 		String 		$	Reference ID ( Your database order ID)
	  * @param 		Float 		$	Amout in USD
	  * @param 		String 		$	Additional info to pass to PayNow
	  * @param 		String 		$	Return Url, if not specified, PayNow::_return_url is used instead
	  *
	  * @return 	Array 		$	Transaction or Empty array on fail
	  */
	 public function make_transaction( $reference , $amount , $additionalinfo = '' , $return_url = '')
	 {
	 	$transaction = $this->_empty_transaction_request;
	 	
	 	$transaction['reference'] = $reference;
	 	$transaction['amount'] = $amount;
	 	$transaction['additionalinfo'] = $additionalinfo;
	 	$transaction['returnurl'] = ( empty($return_url)  ? $this->_return_url  : $return_url );
	 	return $transaction;
	 }

	/**
	 * Initiate a transaction.
	 *
	 * @param 		Array 		$	Transaction
	 *
	 * @return 		Array 		$	Result. Returns empty array on fail
	 */ 
	public function init_transaction( $transaction )
	{
		//reorder to paynow order wich is utterly stupid and pathetic on Paynow's part !!!
		$paynow_ordered = array(
			'resulturl' => utf8_decode( $this->_result_url ) ,  
            'returnurl' =>  utf8_encode( empty( $transaction['returnurl'] ) ? $this->_return_url : $transaction['returnurl'] ),  
            'reference' =>  utf8_encode($transaction['reference']),  
            'amount' =>  utf8_encode($transaction['amount']),  
            'id' =>  utf8_encode($this->_integration_id),  
            'additionalinfo' =>  utf8_encode($transaction['additionalinfo']),  
            'authemail' =>  utf8_encode($transaction['authemail']),  
            'status' =>  utf8_encode('Message') );

		
		//post data
		//$post_data = $this->make_http_request_param( $paynow_ordered );

		$paynow_ordered["hash"] = utf8_encode( strtoupper( $this->generate_hash($paynow_ordered) ) );
		
		//perform request
		$this->_http_data = $this->http_request( $this->_init_transaction_url , 'POST' , $paynow_ordered );
		if( is_null($this->_http_data) )
		{
			return array();
		}
		//convert to array
		$result = $this->make_array( $this->_http_data );

		return $result;
	}

	/**
	 * Validate the authenticity of a poll update message.
	 *
	 * Pass the global $_POST variable as a parameter. AVoid passing $_REQUEST
	 *
	 * @param 		Array 		$	global $_POST variable
	 *
	 * @return 		Bool
	 */
	public function is_valid_status_update( $_post_ )
	{
		if( ! is_array($_post_))
			return False;
		$hash = $this->generate_hash(  $_post_ );
		return ( $hash == $_post_['hash'] );
	}

	/**
	 * Validate the authenticity of an init transaction response.
	 *
	 * Pass the result from initiate_transaction
	 *
	 * @param 		Array 		$	global $_POST variable
	 *
	 * @return 		Bool
	 */
	public function is_valid_init_response( $response )
	{
		if( ! is_array($response))
			return False;
		$hash = $this->generate_hash( $response );
		if( $hash != $response['hash'] )
		{
			return False;
		}
		return True;
	}

	/**
	 * Validate the authenticity of a poll url response.
	 *
	 * Pass the result obtained from calling the poll url
	 *
	 * @param 		Array 		$	poll url result
	 *
	 * @return 		Bool
	 */
	public function is_valid_poll_response( $poll_result )
	{
		if( ! is_array($poll_result))
			return False;
		$hash = $this->generate_hash( $poll_result );
		return (  $hash == $poll_result['hash'] );
	}

	/**
	 * Poll Paynow for the status of a transaction
	 *
	 * @param 		String 		$	Poll Url as stored in the initiated transaction.
	 *
	 * @return 		Array 		$ 	Empty array on fail
	 */
	public function poll_transaction(  $poll_url )
	{
		$post_data = null;
		$this->_http_data = $this->http_request($poll_url, 'GET' , $post_data);
		if( is_null($this->_http_data) )
		{
			return array();
		}
		return $this->make_array(  $this->_http_data );
	}

	/**
	 * Generate PayNow Hash
	 *
	 * Sent out to ensure authenticity of request
	 *
	 * @param 		Array 		$	Transaction
	 *
	 * @todo 		Make sure order is always the same
	 *
	 * @return 		String 		$	SHA512 Hash
	 */
	protected function generate_hash( $transaction )
	{
		$string = "";
		foreach($transaction as $key=>$value) {
			if( strtoupper($key) != "HASH" ){
				$string .= $value;
			}
		}
		$string .= $this->_integration_key;
		
		$hash = hash("sha512", $string);
		return strtoupper($hash);
	}

	/**
	 * Perform HTTP request.
	 * On fail sets an error
	 *
	 * @param 		String 		$	Url to request
	 * @param 		String 		$ 	Request type ( get , post )
	 * @param 		Array 		$	Data to Post. Ignored if HTTP request
	 *
	 * @return 		Mixed 		$	Http Response data 
	 */
	protected function http_request( $url , $method = 'POST' ,   $post_data = Null )
	{
		if( $this->_use_curl )
		{

			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url );
			if( $method == 'POST')
			{
				$post_data = $this->urlify($post_data);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
			}
			if( $method == 'GET')
				curl_setopt($ch, CURLOPT_POST, false);

			if( $this->_use_proxy )
			{
				//socks proxy
				curl_setopt($ch, CURLOPT_PROXY, $this->_proxy);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				

			}
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			//execute post
			$result = curl_exec($ch);

			curl_close($ch);

			return $result;
		}
		else
		{
			$data = $this->rest_helper($url, $post_data , $method , '' );	
		}
		
		return $data;
	}

	protected function make_http_request_param( $transaction  )
	{
		$fields = array();
		foreach($transaction as $key=>$value) {
		   $fields[$key] = urlencode($value);
		}

		$transaction["hash"] = $this->generate_hash($transaction) ;

		//$fields_string = $this->urlify($fields);
		return $transaction;
    }


    protected function urlify( $fields )
    {
    	$delim = "";
		$fields_string = "";
		foreach($fields as $key=>$value) {
			$fields_string .= $delim . $key . '=' . $value;
			$delim = "&";
		}

		return $fields_string;
    }


	/**
	 * Convert data obtained from PayNow API into an Array
	 *
	 * @param 		String 		$	Url Encoded Data 
	 *
	 * @return 		Array 		$	Empty array on fail
	 */
	protected function make_array(  $paynow_http_request_data )
	{
		$parts = explode("&",$paynow_http_request_data);  
        $result = array();  
        foreach($parts as $i => $value) 
        {  
            $bits = explode("=", $value, 2);  
            if( count($bits) == 2 )
            {
            	$result[$bits[0]] = urldecode($bits[1]);	
            }
            else
            {
            	//log error
            }
              
        }  
  		return $result;  
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


}



