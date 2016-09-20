<?php
	//load things i need
	$product_categories = theme_product_categories( 10);
	$featured_products = theme_featured_products(2);
	$popular_products = theme_popular_products( 3 );
	$recommended_products = theme_suggested_products( 3 );
	
	
	
	$this->load->theme($theme,'partials/head');
	$this->load->theme($theme,'partials/navigation');
?>
<div class="about_top">
  <div class="container">
		<div class="col-md-3 about_sidebar">
			<ul class="menu1">
				<li class="item1"><a href="#" class="">What To Buy ?<img class="arrow-img" src="images/arrow.png" alt=""/> </a>
					<ul class="cute" style="display: none; overflow: hidden;">
						<li class="subitem1"><a href="single.html">Cute Kittens </a></li>
						<li class="subitem2"><a href="single.html">Strange Stuff </a></li>
						<li class="subitem3"><a href="single.html">Automatic Fails </a></li>
					</ul>
		         </li>
			 </ul>
		<div class="box1">
			<ul class="box1_list">
				<li><a href="#">Jeans</a></li>
				<li><a href="#">Hoodies</a></li>
				<li><a href="#">Watches</a></li>
				<li><a href="#">Suits</a></li>
				<li><a href="#">Ties</a></li>
				<li><a href="#">Shirts</a></li>
				<li><a href="#">T-Shirts</a></li>
				<li><a href="#">Underwear</a></li>
				<li><a href="#">Accessories</a></li>
				<li><a href="#">Caps & Hats</a></li>
			</ul>
		</div>
		<ul class="box2_list">
				<li><a href="#">New Arrivals</a></li>
				<li><a href="#">Sales</a></li>
				<li><a href="#">Collection '15</a></li>
				<li><a href="#">Mystery</a></li>
				<li><a href="#">Story Behind</a></li>
				<li><a href="#">About US</a></li>
				<li><a href="#">Contacts</a></li>
		 </ul>
		 <ul class="box3_list">
				<li><a href="#">New Arrivals</a></li>
				<li><a href="#">Sales</a></li>
				<li><a href="#">Collection '15</a></li>
				<li><a href="#">Mystery</a></li>
				<li><a href="#">Story Behind</a></li>
				<li><a href="#">About US</a></li>
				<li><a href="#">Contacts</a></li>
		 </ul>
		</div>
		<div class="col-md-9">
		   <div class="dreamcrub">
			   	 <ul class="breadcrumbs">
                    <li class="home">
                       <a href="index.html" title="Go to Home Page">Home</a>&nbsp;
                       <span>&gt;</span>
                    </li>
                    <li class="women">
                        Contact
                    </li>
               </ul>
                <ul class="previous">
                	<li><a href="index.html">Back to Previous Page</a></li>
                </ul>
                <div class="clearfix"></div>
		   </div>
		    <div class="map">
			  <iframe src="https://www.google.com/maps?q=<?= urlencode($shop['address']) ?>"> </iframe>
			</div>
			<div class="contact_right">
					<h3>Contact us</h3>
					<form method="POST" >
									<div class="text">
										<div class="text-fild">
											<span>Name:</span>
											<input type="text" class="text"  placeholder="Your fullname " required name="name" />
										</div>
										<div class="text-fild">
											<span>Email:</span>
											<input type="text" class="text"  placeholder="Your email address" name="email" required />
										</div>
										<div class="clearfix"> </div>
									</div>
									<div class="msg-fild">
											<span>Subject:</span>
											<input type="text" class="text" placeholder="Email subject " name="subject" />
									</div>
									<div class="message-fild">
											<span>Message:</span>
											<textarea name="message" > </textarea>
									</div>
									<div class="row" >
										<div class="col-sm-6" >
											<img src="/favicon.png" class="img-responsive" />
										</div>
										<div class="col-sm-6" >
											<div class="">
												<span>Enter captcha code</span>
												<input type="text" class="form-control"  placeholder="Captcha code" required name="captcha" />
											</div>
										</div>
										
									</div>		
									<div class="btn-toolbar form-group">
								        <input type="submit" id="contactFormSubmit" value="Send" class="btn btn-primary">
								      	<input type="reset" value="Clear" class="btn btn-info">
    								</div>
					</form>
	          </div>
	    </div>
	    <div class="clearfix"> </div>   	
    </div>   
</div>
<div class="footer">
	<div class="container">
		<img src="images/f_logo.png" alt=""/>
		<p><a href="mailto:info@mycompany.com">info(at)suity.com</a></p>
		<div class="copy">
			<p>© 2015 All Rights Reseverd Template by <a href="http://w3layouts.com/">W3layouts</a> </p>
		</div>
		<ul class="social">
		  <li><a href="#"> <i class="fb"> </i> </a></li>
		  <li><a href="#"> <i class="tw"> </i> </a></li>
		</ul>
	</div>
</div>
</body>
</html>		
