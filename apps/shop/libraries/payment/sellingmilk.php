<?php

/*********************************************
1. Define Constants
*********************************************/
define('ps_error', 'Error');
define('ps_ok','Ok');
define('ps_created_but_not_paid','created but not paid');
define('ps_cancelled','cancelled');
define('ps_failed','failed');
define('ps_paid','paid');
define('ps_awaiting_delivery','awaiting delivery');
define('ps_delivered','delivered');
define('ps_awaiting_redirect','awaiting redirect');
define('site_url', 'http://localhost/');

/*********************************************
2. sitewide variables, settings
*********************************************/
$order_id = ''; //current shopping session ID, we set it down there later (this is managed by your shopping cart)
$integration_id ='1196';
$integration_key = '8c39c6ac-6c45-4cf5-a5b8-1955cc9d5e47'; //oops this MUST BE SECRET, take it from a Database, encrypted or something
$authemail = '';
$initiate_transaction_url = 'https://www.paynow.co.zw/Interface/InitiateTransaction';
$orders_data_file = 'ordersdata.ini';
$checkout_url = site_url.'/sellingmilk.php';

$_POST = array('order_id'=>1);
$_POST['resulturl'] = 'http://localhost/sellingmilk.aspx?action=notify&order_id=2';
$_POST['returnurl'] = 'http://localhost/sellingmilk.aspx?action=return&order_id=2';
$_POST['reference'] = 'Order Number: #Mil140';
$_POST['amount'] = 1;
$_POST['id'] = 1169;
$_POST['additionalinfo'] = 'Some Info: milk tastes good.';
$_POST['authemail'] ='';
$_POST['status'] = 'Message';

/*********************************************
3. site routing
*********************************************/
$action = isset($_GET['action']) ? $_GET['action'] : 'createtransaction';
switch ($action)
{
	case 'createtransaction': //create or initiate a transaction on paynow
		WereCreatingATransaction();
	break;
	
	case 'return': //entry point when returning/redirecting from paynow
		WereBackFromPaynow();
	break;
	
	case 'notify': //listen for transaction-status paynow
		PaynowJustUpdatingUs();
	break;
	
	default: //default	
		JustTheDefault();
	break;
	
}

//route entry functions
function JustTheDefault()
{
	global $order_id;
	//Unique Order Id for current shopping session
	$order_id = rand ( 1, 60 ); //lets just use simple numbers here, 1-60, but yours should be the order number of the current shopping session, one you can use to identify this particular transaction with for eternity, in your shopping cart.	
	getShoppingCartHTML();
}

function WereBackFromPaynow()
{
	global $integration_id;
	global $integration_key;
	global $checkout_url;
	global $orders_data_file;

	$local_order_id = $_GET['order_id'];
			
	//Lets get our locally saved settings for this order
	$orders_array = array();
	if (file_exists($orders_data_file))
	{
		$orders_array = parse_ini_file($orders_data_file, true);
	}
	
	$order_data = $orders_array['OrderNo_'.$local_order_id];
	
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $order_data['PollUrl']);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	$result = curl_exec($ch);

	if($result) {

		//close connection
		$msg = ParseMsg($result);
		
		$MerchantKey =  $integration_key;
		$validateHash = CreateHash($msg, $MerchantKey);

		if($validateHash != $msg["hash"]){
			header("Location: $checkout_url");
		}
		else
		{
			/***** IMPORTANT ****
			On Paynow, payment status has changed, say from Awaiting Delivery to Delivered
			
				Here is where you
				1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save data to DB
				2. Email, SMS Notifications to customer, merchant etc
				3. Any other thing
			
			*** END OF IMPORTANT ****/
			//1. Lets write the updated settings
			$orders_array['OrderNo_'.$local_order_id] = $msg;
			$orders_array['OrderNo_'.$local_order_id]['returned_from_paynow'] = 'yes';
			
			write_ini_file($orders_array, $orders_data_file, true);	
		}
	}
	
	//Thank	your customer
	getBackFromPaynowHTML();
}

function PaynowJustUpdatingUs()
{
	global $integration_id;
	global $integration_key;
	global $checkout_url;
	global $orders_data_file;

	$local_order_id = $_GET['order_id'];
	
	//write a file to show that paynow silently visisted us sometime
	file_put_contents('sellingmilk_log.txt', date('d m y h:i:s').'   Paynow visited us for order id '.$local_order_id.'\n', FILE_APPEND | LOCK_EX);
		
	//Lets get our locally saved settings for this order
	$orders_array = array();
	if (file_exists($orders_data_file))
	{
		$orders_array = parse_ini_file($orders_data_file, true);
	}
	
	$order_data = $orders_array['OrderNo_'.$local_order_id];
	
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $order_data['PollUrl']);
	curl_setopt($ch, CURLOPT_POST, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	//execute post
	$result = curl_exec($ch);

	if($result) {

		//close connection
		$msg = ParseMsg($result);
		
		$MerchantKey =  $integration_key;
		$validateHash = CreateHash($msg, $MerchantKey);

		if($validateHash != $msg["hash"]){
			header("Location: $checkout_url");
		}
		else
		{
			/***** IMPORTANT ****
			On Paynow, payment status has changed, say from Awaiting Delivery to Delivered
			
				Here is where you
				1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save data to DB
				2. Email, SMS Notifications to customer, merchant etc
				3. Any other thing
			
			*** END OF IMPORTANT ****/
			
			//1. Lets write the updated settings
			$orders_array['OrderNo_'.$local_order_id] = $msg;
			$orders_array['OrderNo_'.$local_order_id]['visited_by_paynow'] = 'yes';
			
			write_ini_file($orders_array, $orders_data_file, true);	
		}
	}
	exit;	
}

function WereCreatingATransaction()
{
	global $_POST;
	global $integration_id;
	global $integration_key;
	global $authemail;
	global $initiate_transaction_url;
	global $orders_data_file;
	global $checkout_url;
	$local_order_id = $_POST['order_id'];
	
	
	//set POST variables
	$values = array('resulturl' => $_POST['resulturl'],
			'returnurl' =>  $_POST['returnurl'],
			'reference' =>  $_POST['reference'],
			'amount' =>  $_POST['amount'],
			'id' =>  $integration_id,
			'additionalinfo' =>  $_POST['additionalinfo'],
			'authemail' =>  $authemail,
			'status' =>  'Message'); //just a simple message
			
	$fields_string = CreateMsg($values, $integration_key);
	
	$url = $initiate_transaction_url;

	$result = rest_helper($url, $fields_string, 'POST', '');
	print $result;
	
	if($result)
	{
		$msg = ParseMsg($result);
		
		//first check status, take appropriate action
		if ($msg["Status"] == ps_error){
			header("Location: $checkout_url");
			exit;
		}
		else if ($msg["Status"] == ps_ok){
		
			//second, check hash
			$validateHash = CreateHash($msg, $integration_key);
			if($validateHash != $msg["hash"]){
				$error =  "Paynow reply hashes do not match : " . $validateHash . " - " . $msg["hash"];
			}
			else
			{
				
				$theProcessUrl = $msg["BrowserUrl"];

				/***** IMPORTANT ****
				On User has approved paying you, maybe they are awaiting delivery etc
				
					Here is where you
					1. Save the PollURL that we will ALWAYS use to VERIFY any further incoming Paynow Notifications FOR THIS PARTICULAR ORDER
					1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save any other relavant data to DB
					2. Email, SMS Notifications to customer, merchant etc
					3. Any other thing
				
				*** END OF IMPORTANT ****/
				
				//1. Saving mine to a PHP.INI type of file, you should save it to a db etc
				$orders_array = array();
				if (file_exists($orders_data_file))
				{
					$orders_array = parse_ini_file($orders_data_file, true);
				}
				
				$orders_array['OrderNo_'.$local_order_id] = $msg;
				
				write_ini_file($orders_array, $orders_data_file, true);				
				
			}
		}
		else {						
			//unknown status or one you dont want to handle locally
			$error =  "Invalid status in from Paynow, cannot continue.";
		}

	}
	else
	{
	   $error = curl_error($ch);
	}
	
	//close connection
	curl_close($ch);

			
	//Choose where to go
	if(isset($error))
	{
		//back to checkout, show the user what they need to do
		header("Location: $checkout_url");
	}
	else
	{
		//redirect to paynow for user to complete payment
		header("Location: $theProcessUrl");
	}
	exit;	
	
}
?>

<?php
/*********************************************
Helper Functions
*********************************************/
function ParseMsg($msg) {
	$parts = explode("&",$msg);
	$result = array();
	foreach($parts as $i => $value) {
		$bits = explode("=", $value, 2);
		$result[$bits[0]] = urldecode($bits[1]);
	}

	return $result;
}

function UrlIfy($fields) {
	$delim = "";
	$fields_string = "";
	foreach($fields as $key=>$value) {
		$fields_string .= $delim . $key . '=' . $value;
		$delim = "&";
	}

	return $fields_string;
}

function CreateHash($values, $MerchantKey){
	$string = "";
	foreach($values as $key=>$value) {
		if( strtoupper($key) != "HASH" ){
			$string .= $value;
		}
	}
	$string .= $MerchantKey;
	
	$hash = hash("sha512", $string);
	return strtoupper($hash);
}

function CreateMsg($values, $MerchantKey){
	$fields = array();
	foreach($values as $key=>$value) {
	   $fields[$key] = urlencode($value);
	}

	$fields["hash"] = urlencode(CreateHash($values, $MerchantKey));

	$fields_string = UrlIfy($fields);
	return $fields_string;
}	


function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
{
  $cparams = array(
    'http' => array(
      'method' => $verb,
      'ignore_errors' => true
    )
  );

  if ($params !== null   ) 
  {
    if( ! is_string($params) )
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
    $meta = stream_get_meta_data($fp);
    var_dump($meta['wrapper_data']);
    $res = stream_get_contents($fp);
  }

  if ($res === false) {
    throw new Exception("$verb $url failed: $php_errormsg");
  }

  switch ($format) {
    case 'json':
      $r = json_decode($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as json");
      }
      return $r;

    case 'xml':
      $r = simplexml_load_string($res);
      if ($r === null) {
        throw new Exception("failed to decode $res as xml");
      }
      return $r;
  }
  return $res;
}


//cutom function to write php config type of file from array
function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
    $content = ""; 
    if ($has_sections) { 
        foreach ($assoc_arr as $key=>$elem) { 
            $content .= "[".$key."]\n"; 
            foreach ($elem as $key2=>$elem2) { 
                if(is_array($elem2)) 
                { 
                    for($i=0;$i<count($elem2);$i++) 
                    { 
                        $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                    } 
                } 
                else if($elem2=="") $content .= $key2." = \n"; 
                else $content .= $key2." = \"".$elem2."\"\n"; 
            } 
        } 
    } 
    else { 
        foreach ($assoc_arr as $key=>$elem) { 
            if(is_array($elem)) 
            { 
                for($i=0;$i<count($elem);$i++) 
                { 
                    $content .= $key2."[] = \"".$elem[$i]."\"\n"; 
                } 
            } 
            else if($elem=="") $content .= $key2." = \n"; 
            else $content .= $key2." = \"".$elem."\"\n"; 
        } 
    } 

    if (!$handle = fopen($path, 'w')) { 
        return false; 
    } 
    if (!fwrite($handle, $content)) { 
        return false; 
    } 
    fclose($handle); 
    return true; 
}

?>

<?php
/*********************************************
HTML Functions
*********************************************/
?>

<?php
function getShoppingCartHTML()
{
	global $order_id;
?>
<div>
<form name="frmSellingMilk" action="?action=createtransaction" method="post">
			<span>Packet of Milk (Just $1)</span>
			<input type='hidden' name='resulturl' value='<?php echo site_url; ?>/sellingmilk.php?action=notify&order_id=<?php echo $order_id; ?>' />
			<input type='hidden' name='returnurl' value='<?php echo site_url; ?>/sellingmilk.php?action=return&order_id=<?php echo $order_id; ?>' />
			<input type='hidden' name='reference' value='Order Number: #Mil140'/>
			<input type='hidden' name='amount' value='1'/>
			<input type='hidden' name='additionalinfo' value='Some Info: milk tastes good.'/>		
			<input type='hidden' name='order_id' value='<?php echo $order_id; ?>'/>
			<button type='submit'>Paynow (for Milk)</button>
</form>
</div>
<?php }
?>

<?php
function getBackFromPaynowHTML()
{?>
<div>Thank you for your business, it's much appreciated! <a href="sellingmilk.php">back to shopping cart</a></div>
<?php }
?>