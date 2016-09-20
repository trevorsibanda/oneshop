<?php
/**
 * Ok so the user has confirmed that he wants to pay for this order.
 * 
 * 1) Check $_SESSION['consignment'] to see if its valid
 * 2) The amount the user the user was charged should be the same with the price you come up with, if not redirect to the previous page
 * 3) If everything is ok, make the payment. 
 *
 * 4) When the user is from the payment api and its confirmed that he has paid, you must now
 *    notify oneshop that you are the provider handling this delivery.
 *
 * 5) Remember this is a demo you can add more complexity and features as long as you follow the oneshop rules and notify us
 *    
 **/

 require_once('config.php');

 //expected $_POST, always put security first 
 $expected = array('collection' , 'depot_drop' , 'depot_drop_date' , 'cost' , 'challenge' );

 foreach( $_POST as $key )
 {
 	if( ! isset($expected) )
 	{
 		die('Error ' . __LINE__ );
 	}
 }

 //Calculate cost again to be extra sure

if(  isset($_SESSION['consignment']) )
{
	$cons = $_SESSION['consignment'];
}
else if( isset($_GET['consignment']))
{
	//simple sanitise to prevent lfi
	$fname = str_replace('..', '', $_GET['consignment']);
	$data = file_get_contents('temp_data/' . $fname );

	$cons = json_decode($data , True); //return array
	//on all fails terminate the window. 
	if( empty($cons) )
	{
		echo '<script>alert("Consignment does not exist!");window.close();</script>';
		return;
	}
	//set session
	$_SESSION['consignment'] = $cons;

}
else
{
	echo '<script>alert("Consignment does not exist!");window.close();</script>';
	return;
}

//source
$src = $cons['route']['source'];
$sender = $cons['contacts']['sender'];

//destination
$dst = $cons['route']['destination'];
$receiver = $cons['contacts']['receiver'];

//package
$pckg = $cons['package'];

//total cost
$cost = 0.00;


 if( $_POST['collection'] == 'manual' )
 {
 	 
 	$cost += 0.00;
 	 //check if supported depot
	 if( ! isset($our_depots[ $_POST['depot_drop']]))
	 {
	 	die('Unknown, unsupported depot - ' . $_POST['depot_drop']);
	 }
	 //check date and time...etc

 }
 else if( $_POST['collection'] == 'residential')
 {
 	$cost += 5.00;
 }
 

 //Calculate costs
 //If in same city add $2 if in different cities its $5

 if( $src['city'] == $dst['city'] )
 {
 	$cost += 2.00;
 }
 else
 {
 	$cost += 5.00;
 }


 $package = $cons['package'];

 //Use weight range to calculate price
 //weight is in kg
 if( $package['weight'] <= 1.00 )
 {
 	//50 cents for orders under $1,00
 	$cost += 0.50;
 }
 else if( $package['weight'] <= 2.00 )
 {
 	$cost += 0.75;
 }
 else if( $package['weight'] <= 5.00 )
 {
 	$cost += 1;
 }
 else if($package['weight'] <= 10.00 )
 {
 	$cost += 2;
 }
 else
 {
 	//we handle deliveries over 10 kg but we do not accept payment online ..
 	$cost += 3.00;
 	$response['message'] = 'For deliveries over 10 kg you will have to come to our depot and get a quotation there.';

 }
 
 //done calculating cost, make sure we dont overcharge user

if( $cost - $_POST['cost'] != 0 )
 {
 	header('Location: /dispatch.php?step=2');
 	return;
 }

 //Since this is a demo lets assume that an http get request to paynow.co.zw emulates the users payments
 //$paying_emulate = file_get_contents('https://paynow.co.zw/');

 //ok payment received and you have updated your database etc,
 //now you must tell paynow that you'll be handling this delivery
 //you can either send the tracker code to oneshop so oneshop sends the 
 //tracker code to the customer and update the shop order info 

 $response = array();
 $response['status'] = 'ok'; //oneshop simply ignores all fails
 $response['salt'] = time();
 $response['challenge'] = md5( $response['salt'] . API_SECRET   );
 $response['provider'] = API_PROVIDER;
 
 //give us the tracker code
 $response['tracker_code'] = md5( rand() );

 //order challenge used to verify if this provider is truely allowed to handle consignment
 $response['order_challenge'] = $_POST['challenge'];

 //provide any extra info.
 //i.e the ip adrress of the payee, etc, info the provider feels is necessary
 //we recommend adding info such as when the order will be picked up,..etc
 $response['info'] = 'Payment received at ' . date('r') ;

 //required
 //url the shop owner can click on to be taken to the delivery detals/tracker page
 // you can set this to your shops web page if it doesnt apply to you.
 $response['url'] = 'http://tracer.footsoldiers.co.zw/track?order_id';

 //as soon as oneshop receives the http_request it'll mark the delivery as complete
 //oneshop will no longer be involved in the delivery.
 $reply = http_oneshop_callback( $response );


 $message = ''; 
 //if the oneshop request fails you must implement some caching mechanism on your part 
 //so that the request is performed later.
 //i,e a cron job which checks for orders which have been paid for but oneshop doesnt know
 //that they've been paid for.
 if( $reply['status'] == 'ok')
 {
 	//all is well, oneshop is now aware that you are handling the request.
 	//we can now do whatever we want

 	//update database to indicate that oneshop is aware of us handling the request

 	//do whatever you want Oneshop is done :)
	$message = "<p> Your payment has been recieved  and your OneShop account has also been updated to indicate that this order is been delivered and a notification email has been sent out to the client with the tracker code and other information.</p><br/><small>You can view more info about this delivery on the order page.</small>  ";
 }
 else
 {
 	//payment received and ready to be dispatched but oneshop doent know we are handling this delivery
 	//@todo cache this for cron job
 	// or update database to indicate that oneshop is still not aware of us handling the request
 	$message = '<p>Your payment has been received, however we had trouble updating the OneShop order, you will have to do this manually and set the tracker code to ' . $response['tracker_code'] . '. Thank you !';

 }
 

?>
<!DocType HTML>
<html>
	<head>
		<title>Dispatch delivery to <?= $dst['address'] ?>, <?= $dst['suburb'] ?>, <?= $dst['city'] ?> , <?= $dst['country'] ?></title>
		<link rel="stylesheet" href="assets/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/style.css" />
		<script src="assets/js/jquery.min.js" ></script>
	</head>	
	<body  > 
		<!-- Step 1 -->
		<div class="container"  >
			
			<div class="row" >
				<div class="col-xs-12" >
					<h1 class="text-center">
					<img src="logo.png" class="img-responsive logo"  alt="Logo" /
					<br/>
					Thank you!</h1>
					<?= $message ?><hr/>
				</div>
			</div>
			<button class="btn btn-success btn-lg " style="margin-left: 48%;" onclick="window.close();">Close Window</button>
