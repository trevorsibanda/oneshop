<?php
/**
 * Oneshop will never allow you to get here
 * if it has not first gotten a quotation for
 * the delivery
 *
 */
require_once('config.php');

//consignment
$cons = array();

//current step, there are 2 in this example
$step = isset( $_GET['step']) ? $_GET['step'] : 1;


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

//Note
//he order challenge is very important and should be stored well as it will be sent as part of the callback to the user
 
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
			<?php if( $step == 1): ?>
			<div class="row" >
				<div class="col-xs-12" >
					<h1 class="text-center">
					<img src="logo.png" class="img-responsive logo"  alt="Logo" /
					<br/>
					Welcome to Foot Soldiers!</h1>
					<p>Thank you for using Foot Soldiers. We asure you only the best quality. Please contact our supprt team if you need any help
					sending your package online. You can also visit one of our many depots for freindly customer service!</p>
					<hr/>
				</div>
			</div>
			<?php elseif( $step == 2): ?> 
				<form action="pay4order.php" method="POST" >
				<div class="row" >
					<div class="col-xs-12" >
						<h1 class="text-center">
						<img src="logo.png" class="img-responsive logo"  alt="Logo" /
						<br/>
						Complete your order</h1>
						<p>Thank you for using Foot Soldiers. We asure you only the best quality. Please contact our supprt team if you need any help
						sending your package online. You can also visit one of our many depots for freindly customer service!</p>
						<hr/>
					</div>
				</div>
				<noscript>
					<div class="alert alert-warning" >
						Javascript must be enabled for this to work !
					</div>
				</noscript>
				<div class="panel panel-primary" >
					<div class="panel-heading" >
						<h4>Customise your shipment</h4>
					</div>
					<div class="panel-body" >
						<div class="row" >
							<div class="col-sm-6" >
								<p>We can come and collect the package from your address or you can drop off the package at our nearest depot.<br/>
								Please note that we will charge you an extra $5,00 for collecting from a residential address. Deliveries from business
								addresses ( located withing Central Business District ) are completely free.<br/>
								</p>
								<br/>
								<label>Package Collection</label>
								<select class="form-control" id="collection" name="collection" >
									<option value="business">Collect from business address ( + $0,00 )</option>
									<option value="residential">Collect from residential area ( + $5,00 )</option>
									<option value="manual" >No collection. I'll send it to the depot</option>
								</select>
								<br/>
								<div class="well" id="choose_depot" style="display: hidden;" >
									<p>Select a depot where you'll drop off the depot</p> 
									<label>Select a depot</label>
									<select class="form-control" id="depots" name="depot_drop" >
										<?php foreach( $our_depots as $depot => $value ): ?>
										<option value="<?= $depot ?>"><?=  $value ?></option>
										<?php endforeach; ?>
									</select>
									<p>When will you deliver the package to this depot?</p>
									<label>Select date</label>
									<select class="form-control" id="depot_date" name="depot_drop_date" >
										<?php /*Print up to 7 days from now */
										for( $x = 0; $x < 7 ; $x++ ): ?>
										<option ><?= date("D d M Y" , time() + ( $x * 24 * 60 * 60 ) ) ?></option>	
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="col-sm-6 well" >
								<h4>Summary</h4>
								<table class="table table-striped" >
									<tbody>
										<tr>
											<td>Delivery Cost</td>
											<td><?= '$' .$cost ?></td>
										</tr>
										<tr>
											<td>Pickup Cost</td>
											<td>$<span class="pickupcost">0.00</span></td>
										</tr>
										<tr>
											<td><b>Grand Total</b></td>
											<td>$<span class="grandtotal">23,00</span></td>
										</tr>	
												
									</tbody>
								</table>
								<input type="hidden" name="challenge" value="<?= $cons['order_challenge'] ?>" />
								<input type="hidden" name="cost" id="cost" />	
								<button type="submit"  class="btn btn-block btn-success btn-lg" >Pay $<span class="grandtotal">23,00</span> with PayNow</button>
								
							</div>
						</div>		
					</div>
				</div>	
				</form>
			<!-- End step 2 -->
			<?php endif; ?>
			<div class="row">
				<div class="col-sm-12" >
					<div class="panel panel-info" >
						<div class="panel-heading" >
							<h5>Package</h5>
						</div>
						<table class="panel-body table table-striped" >
							<thead>
								<th>
									<tr>
										<td>Contents</td>
										<td>Weight</td>
										<td>Length</td>
										<td>Width</td>
										<td>Height</td>
										<td>Volume</td>
										<td>Properties</td>
									</tr>
								</th>
							</thead>
							<tbody>
								<tr>
									<td><?= $pckg['contents']['type'] ?></td>
									<td><?= $pckg['weight'] ?>Kg</td>
									<td><?= $pckg['dimensions']['l'] ?> cm</td>
									<td><?= $pckg['dimensions']['w'] ?>  cm</td>
									<td><?= $pckg['dimensions']['h'] ?>  cm</td>
									<td><?= $pckg['dimensions']['l'] * $pckg['dimensions']['w'] * $pckg['dimensions']['h'] ?>  cm<sup>3</sup></td>
									<td>
										<ul class="list-unstyled">
											<?php if( $package['contents']['medical'] ): ?>
											<li><b style="color: red;">Medical</b></li>
											<?php endif; ?>
											
											<?php if( $package['contents']['fragile'] ): ?>
											<li>Fragile</li>
											<?php endif; ?>
											<?php if( $package['contents']['liquid'] ): ?>
											<li>Liquid</li>
											<?php endif; ?>
											
										</ul>
									</td>	
								</tr>
							</tbody>
						</table>
					</div>		
				</div>	
			</div>
			<div class="row" >
				<div class="col-sm-6" >
					<div class="panel panel-warning" >
						<div class="panel-heading" >
							<h5>Source</h5>
						</div>
						<table class="panel-body table-striped table ">
							<tbody>
								<tr>
									<td>Fullname</td>
									<td><?= $sender['fullname'] ?></td>
								</tr>
								<tr>
									<td>Contact Phone</td>
									<td><?= $sender['phone'] ?></td>
								</tr>
								<tr>
									<td>Contact Email</td>
									<td><?= $sender['email'] ?></td>
								</tr>
								<tr>
									<td>Country</td>
									<td><?= $src['country'] ?></td>
								</tr>
								<tr>
									<td>City</td>
									<td><?= $src['city'] ?></td>
								</tr>
								<tr>
									<td>Suburb</td>
									<td><?= $src['suburb'] ?></td>
								</tr>
								<tr>
									<td>Collection Address</td>
									<td><?= $src['address'] ?></td>
								</tr>				
						</table>
					</div>
				</div>
				<div class="col-sm-6" >
				<div class="panel panel-success" >
						<div class="panel-heading" >
							<h5>Source</h5>
						</div>
						<table class="panel-body table-striped table ">
							<tbody>
								<tr>
									<td>Fullname</td>
									<td><?= $receiver['fullname'] ?></td>
								</tr>
								<tr>
									<td>Contact Phone</td>
									<td><?= $receiver['phone'] ?></td>
								</tr>
								<tr>
									<td>Contact Email</td>
									<td><?= $receiver['email'] ?></td>
								</tr>
								<tr>
									<td>Country</td>
									<td><?= $dst['country'] ?></td>
								</tr>
								<tr>
									<td>City</td>
									<td><?= $dst['city'] ?></td>
								</tr>
								<tr>
									<td>Suburb</td>
									<td><?= $dst['suburb'] ?></td>
								</tr>
								<tr>
									<td>Address</td>
									<td><?= $dst['address']  ?></td>
								</tr>				
						</table>
					</div>
				</div>
			</div>		
			<hr/>
			<?php if( $step == 1 ): ?>
			<div class="row" >
				<div class="col-sm-6">
					
				</div>
				<div class="col-sm-6" >
					
					<h2 ><small>Estimated Cost</small><span class="pull-right"><?= '$' .$cost ?></span></h2>
					<a href="deliver.php?step=2" class="pull-right btn btn-lg btn-info" >Continue</a>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<!-- End step 1 -->

	</body>
	<script type="text/javascript">

		var cost = <?= $cost; ?>;
		//update price 
		$('#collection').change(function(obj)
		{
			var val = $(this).val();
			if(val == 'manual')
			{
				//enable the choose depot action
				$('#choose_depot').show();
				$('.pickupcost').html('0.00');
				//add 5,00 to grand total
				$('.grandtotal').html( cost + 0.00 );

			}
			else
			{
				$('#choose_depot').hide();
				$('.pickupcost').html('0.00');
				//add 5,00 to grand total
				$('.grandtotal').html( cost + 0.00 );
			}
			//if pickup from residential add 5,00 to price
			if( val == 'residential')
			{
				$('.pickupcost').html('5.00');
				//add 5,00 to grand total
				$('.grandtotal').html( cost + 5.00 );
			}
			//set total cost
			$('#cost').val( $('.grandtotal').html() );
		});

		//emulate change
		$('#collection').change();

		//set cost
		$('.grandtotal').html(cost);

	</script>
</html>