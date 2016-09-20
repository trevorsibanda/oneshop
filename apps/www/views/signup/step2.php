<!DOCTYPE html>
<html>
<head>
<title>Create your shop -> Step 2 | OneSHOP</title>
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
	<div class="container" style="min-height: 1024px;">

			<div class="row">
				<div class="col-md-8 col-md-offset-2" >
					<div class="row bs-wizard" style="border-bottom:0;">
		                
		                <div class="col-xs-4 bs-wizard-step complete">
		                  <div class="text-center bs-wizard-stepnum">Step 1</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="/create-shop/1" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Your shop details.</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step active"><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 2</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="/create-shop/2" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Choose your design</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step "><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 3</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="/create-shop/1" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Create your shop</div>
		                </div>
		            </div>    
					<div  class="" style="color:#709dca;" >
						<form class="form" method="post"  >
							<h3 class="text-center" style='color: black; font-weight: bold;'>Jamie's Fashion</h3>
							<p class="text-center">
							 We sell the best fashion in Bulawayo</p>
							 <span ><label >Shop URL </label><span class="pull-right" style='color: black;'>You can change this later</span></span>
							 <div class="input-group">
	                                <span class="input-group-addon"><b style='color: green;'>HTTPS://</b></span>
	                                <input type="text" name="shop_name" class="form-control clear_input" placeholder="e.g Jamie's Fashion" />
	                                <span class="input-group-addon"><b style='color: green;'>.oneshop.co.zw</b></span>
	                        </div>
	                        <p class="text-center" style='color: black;' >https://trevor-sibanda.oneshop.co.zw/ is available !</p>
	                        <br/>
							<div class="row" >
								<div class="col-md-6" >
									<label>Select a Theme</label>
								</div>
								<div class="col-md-6" >
									<select class="form-control" >
										<option value='1' >Modern Shop</option>
										<option value='2' >Classic Shop</option>
										<option value='3' >Multi purpose store</option>
										<option value='4' >Fashion Theme</option>
										<option value='5' >T-Shirt SHop Theme</option>
									</select>
								</div>
							</div>
							
								
								<?php for( $x = 1; $x <= 1 ; $x++ ): ?>
								<div class="row"  style="margin-top: 20px; " >	
									<div class="col-md-8 col-xs-6 " >
										<img src="<?=  public_img('landing/' . $x .'.png'); ?>" style="border: solid 1px #343acc;"  class="img-responsive" />
										
									</div>	
									<div class="col-md-4 col-xs-12" >
										<h4 class="text-center" style='color: black; font-weight: bold;' >Moderna Classic Theme</h4>
										<hr/>
										<label>Description</label>
										<p style='color: black;'>
Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.
										</p>

									</div>
								</div>	
								<?php  endfor; ?>
								<input type="hidden" name="site_theme" id="site_theme" value="0" />
							
							<br/>
							<label>Let' get to know you !</label>
							<div class="row" >
								
	                        	<div class="col-xs-12 col-md-12" >
			                        <span class="form-control"><input type="checkbox" class="pull-left "  >I already have a physical shop</span>
	                        	</div>
	                        	<div class="col-xs-12 col-md-12" >
			                        <span class="form-control"><input type="checkbox" class="pull-left "  >I want to ship to my clients</span>
	                        	</div>
	                        	
	                        </div>
							<a href="/create-shop/3" class="btn pull-right btn-success btn-lg" style="background-color:#709dca;" type="submit">Next Step >>> </a>

						</form>
						<br/>
					</div>
					<div class="clearfix"></div>
					
				</div>
				
		</div>
	</div>
	<script>
		$(".theme_image").click( function(evt)
			{
				$('#site_theme').val( $(this).attr('data-theme-id') );
				$(".theme_image").attr('style' , '' );
				$(this).attr('style' , 'border: solid 3px #709dca; ');
				evt.preventDefault();
			}
			)
	</script>	