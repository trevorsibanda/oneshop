<!DOCTYPE html>
<html>
<head>
<title>Create your shop -OneSHOP</title>
<link href="<?=  public_css('bootstrap.css'); ?>" rel='stylesheet' type='text/css' />
<!-- Landing page -->
<link href="<?= public_css('landing-style.css'); ?>" rel="stylesheet" type="text/css" media="all" />

<!-- Custom Theme files -->
<script src="<?= public_js('jquery.min.js'); ?>"></script>
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pie Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--webfont-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<!-- Owl Stylesheets -->
<link rel="stylesheet" href="<?= public_css('flexslider.css'); ?>" type="text/css" media="screen" />
 <script type="text/javascript" src="<?= public_js('move-top.js'); ?>"></script>
<script type="text/javascript" src="<?= public_js('easing.js'); ?>"></script>
<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
				});
			});
		</script>
<!--Animation-->
<script src="<?= public_js('wow.min.js'); ?>"></script>
<link href="<?= public_css('animate.css'); ?>" rel='stylesheet' type='text/css' />
<script>
	new WOW().init();
</script>
</head>
<body>
	<!-- header-section-starts -->
	<div class="header" id="home">
		<div class="container">
			<div class="logo wow fadeInRight" data-wow-delay="0.4s">
				<a href="/#home"><img src="<?= public_img('landing/logo.png'); ?>" alt="" /></a>
			</div>
			<span class="menu"></span>
			<div class="top-menu fixed wow fadeInLeft" data-wow-delay="0.4s">
				<ul>
					<li><a class="active scroll hvr-shutter-out-horizontal" href="/#home">Home</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="/auth/login">Login</a></li>
				</ul>
			</div>
				<!-- script for menu -->
				<script>
				$( "span.menu" ).click(function() {
				  $( ".top-menu" ).slideToggle( "slow", function() {
				    // Animation complete.
				  });
				});
			</script>
			<!-- script for menu -->

			<div class="clearfix"></div>
		</div>
	</div>
	<div class="container" style="min-height: 768px;">

		<div >
			<div class="service-section-head text-center wow fadeInRight" data-wow-delay="0.4s">
				<h3>Create your store !</h3>
				<p>We built a great product for great people.</p>
				
            </div>
				
			</div>
			<div class="row">
				<div class="col-md-8 col-md-offset-2" >
					<div class="row bs-wizard" style="border-bottom:0;">
		                
		                <div class="col-xs-4 bs-wizard-step complete">
		                  <div class="text-center bs-wizard-stepnum">Step 1</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Your shop details.</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step "><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 2</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Choose your design</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step "><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 3</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Create your shop</div>
		                </div>
		            </div>    
					<div  class="" style="color:#709dca;" >
						<form class="form" method="post"  >
							<label>Shop Name</label>
	                        <div class="input-group">
	                                <input type="text" name="shop_name" class="form-control clear_input" placeholder="e.g Jamie's Fashion" />
	                                <span class="input-group-addon"><b style='color: green;'>OK !</b></span>
	                        </div>
	                        
							<label>TagLine</label>
							<input type="text" class="form-control clear_input" name="shop_tagline" placeholder="Shop Tagline" />
							<label>Shop Description</label>
							<textarea style="width: 100%;" class="form-control clear_input" name="shop_descr"></textarea>
							<br/>
							<div class="row" >
	                        	<div class="col-md-6" >
	                        		<label>Country</label>
			                        <select class="form-control" name="shop_country">
			                        	<option value='ZW' >Zimbabwe</option>
			                        </select>
	                        	</div>
	                        	<div class="col-md-6" >
	                        		<label>City</label>
			                        <select class="form-control" name="shop_city">
			                        	<option>Bulawayo</option>
			                        </select>	
			                        <br/>
	                        	</div>
	                        </div>
							<a href="/create-shop/2" class="btn pull-right btn-success btn-lg" style="background-color:#709dca;" type="submit">Next Step >>> </a>

						</form>
						<br/>
						<small>By clicking Next, you agree to <a href="/home/tnc" >Terms and Conditions</a> of oneShop </small> 
					</div>
					<div class="clearfix"></div>
					
				</div>
				
		</div>
	</div>
		