<?php
/**
 * RouteSMS PHP API
 *
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		25 October 2015
 * @package 	Libraries/RouteSms
 *
 * @todo 		Add support for bulk sms
 */


class RouteSms 
{

	//Plain Text (GSM 3.38 Character encoding)
	const MSG_TYPE_PLAIN_TEXT = 0;

	//Flash Message (GSM 3.38 Character encoding)
	const MSG_TYPE_FLASH = 1;

	//Unicode
	const MSG_TYPE_UNICODE = 2;

	//Reserved
	const MSG_TYPE_RESERVED = 3;

	//Wap push
	const MSG_TYPE_WAP_PUSH = 4;

	//Plain Text (ISO-8859-1 Character encoding)
	const MSG_TYPE_PLAIN_TEXT_2 = 5;

	//Unicode Flash
	const MSG_TYPE_UNICODE_FLASH = 6;

	//Flash Message (ISO-8859-1 Character encoding)
	const MSG_TYPE_FLASH_2 = 7;


	//Success, Message Submitted Successfully, In this case you will receive
	//the response 1701|<CELL_NO>|<MESSAGE ID>, The message Id can
	//then be used later to map the delivery reports to this message.
	const ERROR_SUCCESS = '1701';

	//Invalid URL Error, This means that one of the parameters was not provided or left blank
	const ERROR_INVALID_URL = '1702';

	//Invalid value in username or password field
	const ERROR_INVALID_USERNAME_PASSWORD_PAIR = '1703';

	//Invalid value in "type" field
	const ERROR_INVALID_TYPE_FIELD = '1704';

	//Invalid Message
	const ERROR_INVALID_MESSAGE = '1705';

	//Invalid Destination
	const ERROR_INVALID_DESTINATION = '1706';

	//Invalid source (Sender )
	const ERROR_INVALID_SOURCE = '1707';

	//Invalid value for dlr ( Indicates whether the client wants delivery report for this message )
	const ERROR_INVALID_DLR = '1708';

	//User authentication failed
	const ERROR_USER_AUTH_FAILED = '1709';

	//Internal error
	const ERROR_INTENRAL_ERROR = '1710';

	//Other unknown gateway error
	const ERROR_UNKNOWN = Null;

	//Insufficient credit
	const ERROR_INSUFFICIENT_CREDIT = '1025';

	//Server address:port
	private $_server_port  = 'localhost:8080';

	//Username for SMPP account
	private $_username = '';

	//Password for SMPP account
	private $_password = '';

	//is delivery report required ?
	private $_require_delivery_report = False;

	//Source address for RouteSms
	private $_source_address = 'ROUTESMS';

	//Last error encountered
	private $_last_error = '';


	/**
	 * Constructor
	 *
	 * @param 		Array 		$	Configuration. array('source_address' => String , 'delivery_report'=> Bool, 'password' => String , 'username' => String , 'server_port' => String)
	 */
	public function __construct( $config = array() )
	{
		if( ! is_array($config))
			return;

		$this->_source_address = isset( $config['source_address'] ) ? $config['source_address'] : $this->_source_address;
		$this->_require_delivery_report = isset( $config['delivery_report'] ) ? $config['delivery_report'] : $this->_require_delivery_report;
		$this->_password = isset($config['password']) ? $config['password'] : $this->_password;
		$this->_username = isset( $config['username'] ) ? $config['username'] : $this->_username;
		$this->_server_port = isset( $config['server_port'] ) ? $config['server_port'] :  $this->_server_port;

	}

	public function server_port( $server_port = Null )
	{
		if( ! is_null( $server_port))
			$this->_server_port = $server_port;
		return $this->_server_port;
	}

	public function source_address( $src_addr = Null )
	{
		if( ! is_null( $src_addr))
			$this->_source_address = $src_addr;
		return $this->_source_address;
	}

	public function credentials( $username = Null , $password = Null)
	{
		if( !is_null($username) )
			$this->_username = $username;
		if( !is_null($password) )
			$this->_password = $password;
		return array('username' => $this->_username , 'password' => $this->_password );
	}

	public function delivery_report(  $do_get_report = null )
	{
		if( ! is_null($do_get_report) )
			$this->_require_delivery_report = $do_get_report;
		return $this->_require_delivery_report;
	}

	public function last_error( )
	{
		return $this->_last_error;
	}

	/**
	 * Send plain text message
	 *
	 * @param 		String 		$ 		Destination (+26377xxxx)
	 * @param 		String 		$		Message
	 * @param 		Int 		$		Encoding type 1 -> MSG_TYPE_PLAIN_TEXT , 2 => MSG_TYPE_PLAIN_TEXT_2
	 *
	 * @return 		Array
	 */
	public function send_plaintext( $destination , $message , $encoding_type = 1 )
	{
		$msg_type = ($encoding_type == 1) ? RouteSms::MSG_TYPE_PLAIN_TEXT : RouteSms::MSG_TYPE_PLAIN_TEXT_2;
		return $this->send_message( $destination , $message , $msg_type );
	}

	/**
	 * Send flash message
	 *
	 * @param 		String 		$ 		Destination (+26377xxxx)
	 * @param 		String 		$		Message
	 * @param 		Int 		$		Encoding type 1 -> MSG_TYPE_FLASH , 2 => MSG_TYPE_FLASH_2 , 3 => unicode_flash
	 *
	 * @return 		Array
	 */
	public function send_flash( $destination , $message  , $encoding_type = 1 )
	{
		$msg_type = ($encoding_type == 1) ? RouteSms::MSG_TYPE_FLASH : RouteSms::MSG_TYPE_FLASH_2;
		if( $encoding_type == 3 or $encoding_type == 'unicode' )
			$msg_type = MSG_TYPE_UNICODE_FLASH;
		return $this->send_message( $destination , $message , $msg_type );
	}

	/**
	 * Send unicode message
	 *
	 * @param 		String 		$ 		Destination (+26377xxxx)
	 * @param 		String 		$		Message
	 * 
	 * @return 		Array
	 */
	public function send_unicode( $destination , $message )
	{
		return $this->send_message( $destination , $message , RouteSms::MSG_TYPE_UNICODE );
	}

	/**
	 * Send unicode message
	 *
	 * @param 		String 		$ 		Destination (+26377xxxx)
	 * @param 		String 		$		Message
	 * @param 		String 		$		Wap push url
	 * 
	 * @return 		Array
	 */
	public function send_wap_push( $detination , $message , $url )
	{
		return $this->send_message( $destination , $message , RouteSms::MSG_TYPE_WAP_PUSH , Null , $this->delivery_report()  , $url );
	}

	/**
	 * Send a message to RouteSMS Endpoint
	 *
	 * @param 		String 		$		Destination (+26377xxxxxxx )
	 * @param 		String 		$		Message to send to user
	 * @param 		Int 		$		Message type to send - default is plain text
	 * @param 		String 		$ 		Source address ( Sender ID )
	 * @param 		Bool 		$		Get Delivery report
	 * @param 		String 		$ 		If msg type is WAP_PUSH, this is the url to send.
	 *
	 * @return 		Array 		$ 		Response on success, False on Fail. 
	 */
	public function send_message( $destination , $message , $msg_type = RouteSms::MSG_TYPE_PLAIN_TEXT , $source_address = Null , $delivery_report = False , $wap_url = Null  )
	{
			//Unicode text needs to be sent hex encoded
			if(  $msg_type == RouteSms::MSG_TYPE_UNICODE || $msg_type == RouteSms::MSG_TYPE_UNICODE_FLASH )
			{
				$message = $this->sms_unicode(  $message );
			}

			$delivery_report = intval($delivery_report);
			if( $delivery_report != 0 && $delivery_report != 1)
				$delivery_report = 0;

			if( is_null($source_address))
				$source_address = $this->source_address();


			//http://<server>:<port>/bulksms/bulksms?username=XXXX&password=YYYYY
			//                                      &type=Y&dlr=Z&destination=QQQQQQQQQ
			//                                      &source=RRRR&message=SSSSSSSS<&url=KKKK>
			$url = 'http://' . $this->server_port() . '/bulksms/bulksms';

			$creds = $this->credentials();

			$params = array( 'username' => $creds['username'],
							 'password' => $creds['password'] ,
							 'type'  => $msg_type ,
							 'dlr' => $delivery_report ,
							 'destination' => $destination ,
							 'source' => $source_address ,
							 'message' => $message );

			if( $msg_type == RouteSms::MSG_TYPE_WAP_PUSH )
			{
				$params['url'] = $wap_url;
			}
			try
			{
				$response = $this->rest_helper( $url , $params , 'GET' , 'text');
				if( $response == False)
					return False;

				$result = $this->parse_response(  $response );
				return $result;

			}catch( Exception $e )
			{
				$this->_last_error = 'Caught Exception: ' . $e->getMessage();
				return False;
			}
			return Null;
	}

	/**
	 * Parse RouteSMS return data.
	 *
	 * Data is in the following format:
	 * <Error_Code>|<destination>|<message_id>
	 * @param 		String 		$		Http result data
	 *
	 * @return 		Mixed 		$		Array on success, false on fail
	 */
	public function parse_response(  $response )
	{
		if( ! is_string($response))
			return False;

		$result = explode('|', $response );
		if( count($result) < 3)
		{
			$this->_last_error = 'Invalid reply from server. Got: ' . json_encode($result);
			return False;
		}	

		$result = array('code' => $result[0] , 'destination' => $result[1] , 'message_id' => $result[2] );

		return $result;
	}

	/** Hex encode SMS message **/
	private function sms_unicode($message)
	{
		$hex1='';
		if (function_exists('iconv')) 
		{
			$latin = @iconv('UTF-8', 'ISO-8859-1', $message);
			if (strcmp($latin, $message)) 
			{
				$arr = unpack('H*hex', @iconv('UTF-8', 'UCS-2BE', $message));
				$hex1 = strtoupper($arr['hex']);
			}
			if($hex1 =='')
			{
				$hex2='';
				$hex='';
				for ($i=0; $i < strlen($message); $i++)
				{
					$hex = dechex(ord($message[$i]));
					$len =strlen($hex);
					$add = 4 - $len;
					if($len < 4)
					{
						for($j=0;$j<$add;$j++)
						{
							$hex="0".$hex;
						}
					}
					$hex2.=$hex;
				}
				return $hex2;
			}
			else
			{
				return $hex1;
			}
		}
		else
		{
			//@todo throw exception
			die( __FILE__ . ':' . __LINE__ . '  : iconv Function Not Exists !');
		}
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
	private function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
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

