<?php

//=========== BEGIN CONFIGURATIONS

define('merchantid', 'funerally');
define('secretkey' , 'J37M54CK7XF1244CV7E9T6F9ROGE8Z205SCKE61HNTPH66IYMOJKC9710BU06HK8X43T9TWYT6JYM9LT');
define('callback', 'https://secure.263shop.co.zw/api/endpoint/pay4app');

//=========== END CONFIGURATIONS

define('redirect', '');
define('transferpending', '');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta content="width=device-width, initial-scale=1" name="viewport" />
	<title>Sandbox - Pay4App Checkout</title>
	<style type="text/css">
		
		html {
		  margin: 0;
		  width: 100%;
		  min-height: 100%;
		  height: 100%;
		  font: 11px/18px "Helvetica Neue", sans-serif;
		  color: #222;
		  -webkit-font-smoothing: antialiased;
		}
		
		body::before {
          position: fixed;
		  top: 0;
		  left: 0;
		  bottom: 0;
		  width: 10px;
		  content: '';
		  display: block;
		  min-height: 100%;
		  height: 100%;
		  background: #08C;
		}
		
		 body {
		  margin: 5% 8% 10% 8%;
		  padding-bottom: 5%;
		}
		a {
		  color: #007;
		  font-weight: bold;
		  text-decoration: none;
		  transition:.3s;
		}
		a:hover {
		  color: #444;
		}
		h1 {
		  font-weight: bold;
		  font-size: 35px;
		}
		h2 {
		  font-size: 25px;
		  margin-top: 35px;
		}
		p, ul {
		  font-size: 16px;
		  line-height: 28px;
		}
		
		li {
			list-style: none;
		}
		
		.error {
			padding: 10px 5px 5px 10px;
			border: 1px solid red;
			background-color: #EB0;
		}
		
		button {
			padding : 10px 5px 10px 5px;
			cursor: pointer;
		}
		
		.checkout {
			background-color: #DDD;
			padding: 10px;
		}
		
		form { display: inline; }
		
	</style>
</head>
<body>

<h1>Pay4App Sandbox Checkout</h1>

<?php

if (strlen(merchantid) < 1){
	echo "<p class='error'>Please configure your merchantid</p>";
	exit();
}

if (strlen(secretkey) < 1){
	echo "<p class='error'>Please configure your secretkey</p>";
	exit();
}

if (strlen(callback) < 1){
	echo "<p class='error'>WARNING: callback URL is not set</p>";
}

function is_valid_checkout(){
	
	$expected_fields = array(
						'merchantid',
						'orderid',
						'amount',
						'signature'						
						);
	foreach($expected_fields as $field){
		if (!isset($_REQUEST[$field])){
			echo "<p class='error'>Missing expected field: ".$field."</p>";
			return false;
		}
	}
	
	if ($_REQUEST['merchantid'] != merchantid){
		
		echo "<p class='error'>Wrong merchantid. Expected: ".merchantid."</p>";
		return false;
		
	}
	
	if (strlen($_REQUEST['orderid']) < 1){
		echo "<p class='error'>Please set an orderid</p>";
		return false;
	}
	
	if ( !is_numeric($_REQUEST['amount']) ){
		echo "<p class='error'>Amount is not numeric</p>";
		return false;
	}
	
	if (strlen(redirect) < 5){
		if (!isset($_REQUEST['redirect'])){
			echo "<p class='error'>Redirect URL not set in the test file and your request</p>";
			return false;
		}
	}
	
	if (strlen(transferpending) < 5){
		if (!isset($_REQUEST['transferpending'])){
			echo "<p class='error'>TransferPending URL not set in the test file and your request</p>";
			return false;
		}
	}
	
	$signatureWithoutForceEmail = $_REQUEST['merchantid'].$_REQUEST['orderid'].$_REQUEST['amount'].secretkey;
	$signatureWithEmail         = $signatureWithoutForceEmail."forceEmail";
	$signatureWithoutForceEmail = hash('sha256', $signatureWithoutForceEmail);
	$signatureWithEmail         = hash('sha256', $signatureWithEmail);
	
	if (!$_REQUEST['signature'] === $signatureWithEmail){
		
		if (!$_REQUEST['signature'] === $signatureWithoutForceEmail){
		
			echo "<p class='error'>Wrong Signature. Expected ".$signatureWithoutForceEmail." or ".$signatureWithEmail." (for force email)</p>";
			return false;
		
		}
	}

	$merchantid = $_REQUEST['merchantid'];
	$orderid = $_REQUEST['orderid'];
	$amount = $_REQUEST['amount'];
	$signature = $_REQUEST['signature'];
	$redirect = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : redirect;
	$transferpending = isset($_REQUEST['transferpending']) ? $_REQUEST['transferpending'] : transferpending;

//enter PHP ugliness
$emailPromptHTML=<<<EPH
<p>
	<h3>Please enter the buyer's email address below to continue</h3>

	<form method='POST' action='checkout.php'>
		<input type='hidden' name='merchantid' 	value='$merchantid' />
		<input type='hidden' name='orderid' 	value='$orderid' />
		<input type='hidden' name='amount' 		value='$amount' />
		<input type='hidden' name='signature' 	value='$signature' />
		<input type='hidden' name='redirect' 	value='$redirect' />
		<input type='hidden' name='transferpending' 	value='$transferpending' />
		<input type='email'  name='email' />
		<input type='submit' value='Continue' />
	</form>
</p>
EPH;


	if (isset($_REQUEST['email'])){
		if ( strlen($_REQUEST['email']) < 1 ){
			echo "<p class='error'>Email address is required</p>";
			echo $emailPromptHTML;
			exit();
		}

		if(!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL))
		{
			echo "<p class='error'>Not a valid email address</p>";
			echo $emailPromptHTML;
			exit();
		}
	}

	if ($_REQUEST['signature'] === $signatureWithEmail && !isset($_REQUEST['email']) ){
		
		echo $emailPromptHTML;
		exit();
	}
	
	return true;

}

function complete_checkout(){

}

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
 echo "<p class='error'>Request method must be POST</p>";
 //exit();
}

if (!is_valid_checkout()){
	exit();
}
else{
	
	//YrMmDdHrMnSecRand'
	$checkoutid = date('ymdhs').rand(10, 99);
	
	$transferpending_fields = array(
		'merchant' 	=> $_REQUEST['merchantid'],
		'order'		=> $_REQUEST['orderid'],
		'digest'	=> hash("sha256", $_REQUEST['merchantid'].$_REQUEST['orderid'].secretkey),
	);
	
	$transferpending_url = isset($_REQUEST['transferpending']) ? $_REQUEST['transferpending'] : transferpending ;
	
	if ( strpos($transferpending_url, '?') === false ) $transferpending_url .= '?';
	
	foreach($transferpending_fields as $field => $value){
		$transferpending_url .= $field .'='. urlencode($value) .'&' ;
	}	
	
	$redirect_fields = array(
		'merchant' 	=> $_REQUEST['merchantid'],
		'checkout'	=> $checkoutid,
		'order'		=> $_REQUEST['orderid'],
		'amount'	=> $_REQUEST['amount'],
		'email'		=> '',
		'phone'		=> '',
		'timestamp' => time(),
	);
	
	$digest = $redirect_fields['merchant'].$redirect_fields['checkout'].$redirect_fields['order'].$redirect_fields['amount'];
	$digest .= $redirect_fields['email'].$redirect_fields['phone'].$redirect_fields['timestamp'].secretkey;

	$digesthash = hash("sha256", $digest);
	
	$redirect_fields['digest'] = $digesthash;
	
	$redirect_url = isset($_REQUEST['redirect']) ? $_REQUEST['redirect'] : redirect ;
	$callback_url = ( strlen(callback) > 0 ) ? callback : '';
	
	if ( strpos($redirect_url, '?') === false ) {
		$redirect_url .= '?';
	} else {
		$redirect_url .= '&';
	}


	if (strlen(callback)){
		if ( strpos($callback_url, '?') === false ){
			$callback_url .= '?';
		} else {
			$callback_url .= '&';
		}

	}
	
	foreach($redirect_fields as $field => $value){
		$redirect_url .= $field .'='. urlencode($value) .'&';
		if(strlen($callback_url)) $callback_url .= $field .'='. urlencode($value) .'&';
	}
	
	?>
	
	<div class='checkout'>
	
		<ul>
			<li>Amount&nbsp;&nbsp;&nbsp;- $<?php echo $_REQUEST['amount']; ?></li>
			<li>Order ID           - <?php echo $_REQUEST['orderid']; ?></li>
		</ul>
		
	</div>
		
	<p>

		<?php
			echo "<p>In a successful checkout, the callback notification usually goes out before the redirect. And in our plugins, they only process payments on the callback URL</p>";
			if (strlen($callback_url)) echo "&nbsp;&nbsp;&nbsp;<a target='_blank' href='".$callback_url."'><button>Send success notification to callback URL</button></a>";
		?>

		<a target='_blank' href='<?php echo $redirect_url; ?>'><button>Continue as successful checkout</button></a>
	
		&nbsp;&nbsp;&nbsp;
	
		<a target='_blank' href='<?php echo $transferpending_url; ?>'><button>Continue as payment still pending</button></a>
		
		
	</p>
	
	<?php
	
}

?>
<div style='position:absolute; bottom:0; font-size: 11px; text-align: center; padding-bottom: 10px; border-top: 1px dashed gray'>
	This is a tool for development purposes. Do not use it on your live website, or with the same API keys as your real ones. Have ideas, improvements or feedback? Let us know on <a href="https://github.com/Pay4App" target="_blank">Github</a>
</div>
</body>
</html>
