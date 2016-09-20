
<!DOCTYPE html>
<html>
<head>
<title>OneSHOP - Create your online store for free</title>
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
				<a href="#"><img src="<?= public_img('landing/logo.png'); ?>" alt="" /></a>
			</div>
			<span class="menu"></span>
			<div class="top-menu wow fadeInLeft" data-wow-delay="0.4s">
				<ul>
					<li><a class="active scroll hvr-shutter-out-horizontal" href="#home">Home</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="#service">Our Services</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="#work">How it works</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="#pricing">Pricing</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="#about">About us</a></li>
					<li><a class="scroll hvr-shutter-out-horizontal" href="#faq">FAQ</a></li>
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
	<div class="banner wow fadeInUp" data-wow-delay="0.4s">
		<div class="container">
			<div class="banner-info text-center">
				<h2 class="wow bounceInLeft" data-wow-delay="0.4s"><b>Create your online shop</b></h2>
				<p class="wow bounceInLeft" data-wow-delay="0.4s">Get started today, add products, manage stock and orders and accept EcoCash, MasterCard, VISA or ZimSwitch payments!</p>
				<div class="details wow fadeInLeft" data-wow-delay="0.4s">
					<form method="post" action="/create-shop?plan=basic&referer=landing&section=banner" >
						<li>
							<input type="text" name="shop_name" class="text"  placeholder="Your shop name" />
							<a class="name" href="#"></a>
						</li>
						<li>
							<input type="text" class="text" name="email"  placeholder="Your email address" />
							<a class="mail" href="#"></a>
						</li>
						<li>
							<input type="text" class="text" name="phone_number" value="+263" placeholder="Your phone number.">
							<a class="num" href="#"></a>
						</li>
							<input type="submit" value="Create my shop">
					</form>
				</div>
			</div>
			<div class="header-bottom">
		<div class="right-grid-1">
					<section class="slider">
							<div class="flexslider">
							  <ul class="slides">
								<li>
									<img src="<?= public_img('landing/slide/pic1.jpg'); ?>" class="img-responsive" alt="" />
									</li>
									<li>
									<img src="<?= public_img('landing/slide/pic2.jpg'); ?>" class="img-responsive" alt="" />
									</li>
									<li>
									<img src="<?= public_img('landing/slide/pic3.jpg'); ?>" class="img-responsive" alt="" />
									</li>
							  </ul>
							</div>
						  </section>
						  <!-- FlexSlider -->
						  <script defer src="<?= public_js('jquery.flexslider.js'); ?>"></script>
						  <script type="text/javascript">
							$(function(){
							  SyntaxHighlighter.all();
							});
							$(window).load(function(){
							  $('.flexslider').flexslider({
								animation: "slide",
								start: function(slider){
								  $('body').removeClass('loading');
								}
							  });
							});
						  </script>
                    </div>	
				</div>					
		</div>
		</div>
		<div class="content">
			<div class="service-section" id="service">
				<div class="container">
					<div class="service-section-head text-center wow fadeInRight" data-wow-delay="0.4s">
						<h3>Why use OneSHOP ?</h3>
						<p>We built a great product for great people.</p>
					</div>
					<div class="service-section-grids">
						<div class="col-md-6 service-grid">
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s1"></i>
								</div>
								<div class="icon-text">
									<h4>Easy to setup</h4>
									<p>You can have your online store up in 2 minutes! And in 5 minutes you'll be a PRO at running your own online shop. If you can use Facebook, then you can master OneSHOP!</p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s2"></i>
								</div>
								<div class="icon-text">
									<h4>Powerful and intelligently built</h4>
									<p>We built oneSHOP for people and we built it to offer speed, usability and great power straight out of the box. Do so much more, with very little effort !</p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s3"></i>
								</div>
								<div class="icon-text">
									<h4>Don't let location limit you!</h4>
									<p>Increase your market, don't let geography stop your business from growing. Accept orders from anywhere and ship to your new customers. Shipping is super easy and directly integrated into your shop.</p>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-md-6 service-grid">
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s4"></i>
								</div>
								<div class="icon-text">
									<h4>Customize your shop</h4>
									<p>You don't need to be a designer to customise you shop ! Select from hundreds of themes and customize your your shop to your liking! It's awesome, you wouldn't believe it until you tried it. </p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s5"></i>
								</div>
								<div class="icon-text">
									<h4>Secure trusted payments</h4>
									<p>Your shop is totally safe for your customers. <b>https://your-shop.oneshop.co.zw</b>. And we use only the best trusted payment gateways to guarantee that you always get paid whilst offering the best convenience to your customers! </p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon">
									<i class="s6"></i>
								</div>
								<div class="icon-text">
									<h4>Save on web hosting + domain</h4>
									<p>Save on web hosting costs! All oneSHOP plans come with free hosting of your shop which includes unlimited bandwidth and file storage. You can also register your own domain name and use it with your shop!</p>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="work-section" id="work">
				<div class="container">
					<div class="work-section-head text-center wow fadeInRight" data-wow-delay="0.4s">
						<h3>How it works</h3>
						<p>Let's make you a guru at ecommerce !</p>
					</div>
					<div class="work-section-grids text-center wow fadeInUp" data-wow-delay="0.4s">
						<div class="col-md-3 work-section-grid">
							<i class="ear-phones"></i>
							<h4>Step 1</h4>
							<p>Signup and create your shop using OneSHOP.</p>
							<span class="arrow1"><img src="<?= public_img('landing/arrow1.png'); ?>" alt="" /></span>
						</div>
						<div class="col-md-3 work-section-grid wow fadeInUp" data-wow-delay="0.6s">
							<i class="lock"></i>
							<h4>Step 2</h4>
							<p>Add your products, enter stock details and customise your shop design</p>
							<span class="arrow2"><img src="<?= public_img('landing/arrow2.png'); ?>" alt="" /></span>
						</div>
						<div class="col-md-3 work-section-grid wow fadeInUp" data-wow-delay="0.8s">
							<i class="cloud"></i>
							<h4>Step 3</h4>
							<p>Publish your shop, share it to customers, friends, family !</p>
							<span class="arrow1"><img src="<?= public_img('landing/arrow1.png'); ?>" alt="" /></span>
						</div>
						<div class="col-md-3 work-section-grid wow fadeInUp" data-wow-delay="0.10s">
							<i class="done"></i>
							<h4>Step 4</h4>
							<p>Watch the money role in and get paid for your hardwork.</p>
						</div>
						<div class="clearfix"></div>
						<a class="work" href="/create-shop?plan=basic&referer=landing&section=steps">Get start Now</a>
					</div>
				</div>
			</div>
			<div class="price-section" id="pricing">
				<div class="container">
					<div class="price-section-head text-center wow fadeInLeft" data-wow-delay="0.4s">
						<h3>Choose your price</h3>
						<p>Always do more than you're paid for.</p>
					</div>
					<div class="price-section-grids">
						<div class="col-md-4 price-value text-center wow fadeInLeft" data-wow-delay="0.4s">
							<div class="price-section-grid">
								<h5>Entrepreneur plan</h5>
								<h3><span>$</span>0.00</h3>
								<p>per month</p>
								<ul>
									<li>10 products</li>
									<li>Secure payments </li>
									<li>Shop designer</li>
									<li>Unlimited bandwidth + file storage</li>
									<li>5% charge every time you withdraw funds</li>
									<li><b>Ideal for selling home made goods, software, music</b></li>								
								</ul>
								<a href="/create-shop?plan=free&referer=landing&section=plan">Get Started</a><br/>

							</div>
						</div>
						<div class="col-md-4 price-value price-value-active text-center wow fadeInUp" data-wow-delay="0.6s">
							<div class="price-section-grid">
								<h5>Basic plan</h5>
								<h3><span>$</span>4.99</h3>
								<p>per month</p>
								<ul>
									<li>50 Products</li>
									<li>Point of Sale</li>
									<li>Secure payments</li>
									<li>Shop designer</li>
									<li>Unlimited bandwidth + file storage</li>
									<li>Coupons and Discounts</li>
									<li>Analytics + Campaign manager</li>
									<li>10 Sms Credits</li>
									<li>Use your own domain</li>
									<li><b>Ideal for small to medium sized retail stores, fashion outlets, restaraunts</b></li> 								
								</ul>
								<a href="/create-shop?plan=basic&referer=landing&section=plan">Get Started</a>
							</div>
						</div>
						<div class="col-md-4 price-value text-center wow fadeInRight" data-wow-delay="0.4s">
							<div class="price-section-grid">
								<h5>Premium plan</h5>
								<h3><span>$</span>19.99</h3>
								<p>per month</p>
								<ul>
									<li>Unlimited Products</li>
									<li>Point of Sale</li>
									<li>Secure payments</li>
									<li>Shop designer</li>
									<li>Unlimited users</li>									
									<li>Unlimited bandwidth + file storage</li>
									<li>Coupons and Discounts</li>
									<li>Analytics + Campaign manager</li>
									<li>50 Sms Credits</li>
									<li>Free .co.zw Domain</li>
									<li>Use your own domain</li>
									<li><b>Ideal for businesses that scale fast and have a wide range of products</b></li> 							
								</ul>
								<a href="/create-shop?plan=premium&referer=landing&section=plan">Get Started</a>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="about-section" id="about">
				<div class="col-md-6 about_left wow bounceInLeft">
					<img src="<?= public_img('landing/about_pic.jpg'); ?>" alt="" />
				</div>
				<div class="col-md-6 about_right wow bounceInRight">
					<h3>ABout us</h3>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the ore recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
					<a href="#">Read more</a>
				</div>
				<div class="clearfix"></div>
			</div>
			
			
			<div class="happy-clients" id="clients">
				<div class="container">
					<div class="happy-clients-head text-center wow fadeInRight" data-wow-delay="0.4s">
						<h3>Happy Clients</h3>
						<p>Some of the shops we proudly power.</p>
					</div>
					<div class="happy-clients-grids">
						<div class="col-md-6 happy-clients-grid wow bounceIn" data-wow-delay="0.4s">
							<div class="client">
								<img src="<?= public_img('landing/client_1.jpg'); ?>" alt="" />
							</div>
							<div class="client-info">
								<p><img src="<?= public_img('landing/open-quatation.jpg'); ?>" class="open" alt="" />Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make.<img src="<?= public_img('landing/close-quatation.jpg');?>" class="closeq" alt="" /></p>
								<h4><a href="#">Darwin Michle, </a>Project manager</h3>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-6 happy-clients-grid span_66 wow bounceIn" data-wow-delay="0.4s">
							<div class="client">
								<img src="<?= public_img('landing/client_2.jpg'); ?>" alt="" />
							</div>
							<div class="client-info">
								<p><img src="<?= public_img('landing/open-quatation.jpg'); ?>" class="open" alt="" />Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.<img src="<?= public_img('landing/close-quatation.jpg');?>" class="closeq" alt="" /></p>
								<h4><a href="#">Madam Elisabath, </a>Creative Director</h3>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-6 happy-clients-grid wow bounceIn" data-wow-delay="0.4s">
							<div class="client">
								<img src="<?= public_img('landing/client_3.jpg'); ?>" alt="" />
							</div>
							<div class="client-info">
								<p><img src="<?= public_img('landing/open-quatation.jpg'); ?>" class="open" alt="" />Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make.<img src="<?= public_img('landing/close-quatation.jpg'); ?>" class="closeq" alt="" /></p>
								<h4><a href="#">Clips arter, </a>Lipsum directer</h3>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-6 happy-clients-grid span_66 wow bounceIn" data-wow-delay="0.4s">
							<div class="client">
								<img src="<?= public_img('landing/client_4.jpg'); ?>" alt="" />
							</div>
							<div class="client-info">
								<p><img src="<?= public_img('landing/open-quatation.jpg'); ?>" class="open" alt="" />Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.<img src="<?= public_img('landing/close-quatation.jpg'); ?>" class="closeq" alt="" /></p>
								<h4><a href="#">zam cristafr,  </a>manager</h3>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>	
			<div class="service-section" id="faq">
				<div class="container">
					<div class="service-section-head text-center wow fadeInRight" data-wow-delay="0.4s">
						<h3>Frequently Asked Questions</h3>
						
					</div>
					<div class="service-section-grids">
						<div class="col-md-6 service-grid">
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								<div class="icon" >
									<span class="flaticon-businessman2"></span>
								</div>
								<div class="icon-text">
									<h4>Is creating my shop hard ?</h4>
									<p><b>No</b>, setting up your shop is as simple as creating a Facebook account. You don't need to be a designer or programmer to create your shop. You don't even have to hire a designer!</p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								
								<div class="icon-text">
									<h4>Can I use my own domain ?</h4>
									<p>Yes, you can link your domain or subdomain to your shop. If you choose to use <b>https://shop.mydomain.com</b> or <b>https://mydomain.com</b> both will work just perfectly !</p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								
								<div class="icon-text">
									<h4>How exactly do the payments work ?</h4>
									<p>When a customer pays you, the money is stored in your oneShop wallet. You can configure oneShop to wire the money into your bank details on a daily or weekly basis. This is done to lower costs and add security for your clients.</p>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-md-6 service-grid">
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								
								<div class="icon-text">
									<h4>Do I need to pay to try out oneShop?</h4>
									<p>No, you can use the Entrepreneur Plan which does not require you to pay a monthly subscription. You can upgrade or downgrade from any plan anytime.</p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								
								<div class="icon-text">
									<h4>Should I trust oneShop?</h4>
									<p><b>Yes, you can trust oneShop</b>. We protect all our stores with an <b>https</b> certificate and ensure that all communications containing sensitive information are <b>encrypted</b>. We work with <b>trusted secure payments</b> gateways ONLY and help you settle disputes timely. We are also bound by the law to be transparent and settle payments on time. </p>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="service-section-grid wow bounceIn" data-wow-delay="0.4s">
								
								<div class="icon-text">
									<h4>Are there any other costs?</h4>
									<p>No, everything you need to run your shop is included in your plan. There are no hidden monthly costs or extra transaction fees. You can pay for extra <b>optional</b> services such as credit for SMS notifications.</p>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="service-section-head text-center wow fadeInRight" data-wow-delay="0.4s">
						<br/>
						<p>If you have any other questions <a href="/home/faq" >View more Frequently Asked Questions</a> or contact us </p>
					</div>
				</div>
			</div>
			<div class="contact-section" id="contact">
				<div class="container">
					<div class="contact-section-head text-center wow fadeInLeft" data-wow-delay="0.4s">
						<h3>oneShop Newsletter</h3>
						<p>Get the latest news on oneShop and great tips on running a successful ecommerce site straight to your inbox!</p>
					</div>
					<div class="banner-form wow fadeInLeft" data-wow-delay="0.4s">
						<form>
							<li>
								<input type="text" class="text" value="Name" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Name';}">
								<a class="name" href="#"></a>
							</li>
							<li>
								<input type="text" class="text" value="Email Address" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email Address';}">
								<a class="mail" href="#"></a>
							</li>
								<input type="submit" value="Subscribe now">
						</form>
					</div>
					<div class="map">
						<img src="<?= public_img('landing/map.jpg'); ?>" alt="" />
						<div class="location wow bounceIn" data-wow-delay="0.4s">
							<div class="address text-center">
								<h4>Contact us anytime</h4>
								<p>Enquiries: <a href="mailto:enquiries@oneshop.co.zw">enquiries@oneshop.co.zw</a></p>
								<p>Complaints:<a href="mailto:complaints@oneshop.co.zw">complaints@oneshop.co.zw</a></p>
								<p>Call : +263783012754</p>
								<p>Call : +263772760326</p>
								<a href="example@mail.com">info@businessface.com</a>
								<span class="locate"><img src="<?= public_img('landing/locate.png'); ?>" alt="" /></span>
							</div>
							<div class="bottom-logo text-center">
								<img src="<?= public_img('landing/logo.png'); ?>" alt="" />
							</div>
						</div>
					</div>
					<div class="contact-bottom text-center">
						<div class="bottom-menu wow bounceInRight" data-wow-delay="0.4s">
							<ul>     
								<li><a class="scroll" href="#home">Home</a></li>                                                                               
								<li><a class="scroll" href="#service">Service</a></li>
								<li><a class="scroll" href="#about">About us</a></li>
								<li><a class="scroll" href="#pricing">Pricing Table</a></li>
								<li><a class="scroll" href="#work">How it work</a></li>
								<li><a class="scroll" href="#clients">Happy Clients</a></li>
								<li><a class="scroll" href="#contact">Contact Us</a></li>
							</ul>
						</div>
						<p class="call wow fadeInRight" data-wow-delay="0.4s"><i class="phone wow fadeInLeft" data-wow-delay="0.4s"></i> +987 9976 999</p>
						<div class="social-icons">
							<a class="wow bounceIn" data-wow-delay="0.4s" href="#"><i class="twitter"></i></a>
							<a class="wow bounceIn" data-wow-delay="0.4s" href="#"><i class="facebook"></i></a>
							<a class="wow bounceIn" data-wow-delay="0.4s" href="#"><i class="googlepluse"></i></a>
							<a class="wow bounceIn" data-wow-delay="0.4s" href="#"><i class="linkedin"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<div class="container">
				<div class="copyright text-center wow bounceInLeft" data-wow-delay="0.4s">
					<p>Copyright &copy; <?= date('Y'); ?> All rights reserved | OneShop</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
						$(document).ready(function() {
							/*
							var defaults = {
					  			containerID: 'toTop', // fading element id
								containerHoverID: 'toTopHover', // fading element hover id
								scrollSpeed: 1200,
								easingType: 'linear' 
					 		};
							*/
							
							$().UItoTop({ easingType: 'easeOutQuart' });
							
						});
					</script>
				<a class="scroll" href="#home" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
</body>
</html>