<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>
	<div class="banner">
		<div class="container">
				<div class="row" >
					<div class="col-sm-4 " >
						<h3 class="text-center"><?= $featured['name'] ?></h3>
						<h4 class="text-center"><?= money( $featured['price'] ); ?></h4>
						<img src="<?= random_product_image($featured ) ?>" class="img-responsive" />
						<p><?= substr($featured['description'] , 0 , 160) ?></p>
						<a href="" class="btn btn-block btn-info btn-lg" >Add to Cart</a>
						<br/>
					</div>
					<div class="col-sm-8" >
						<!-- Header Text -->
						<div class="well">
							<p><?= theme_page( $theme , 'landing_page' , 'header_text' ) ?></p>
							
						</div>
						<!-- End header text -->
						<div class="slider">
							<div class="callbacks_container">
								<ul class="rslides" id="slider">
									<li>
											<img src="<?= theme_img( $theme , 'img18.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
									<li>
											<img src="<?= theme_img( $theme , 'img15.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
									<li>
											<img src="<?= theme_img( $theme , 'img17.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
									<li>
											<img src="<?= theme_img( $theme , 'img18.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
									<li>
											<img src="<?= theme_img( $theme , 'img15.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
									<li>
											<img src="<?= theme_img( $theme , 'img17.jpg'); ?>" class="img-responsive" alt="">
										<div class="caption">
											<p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna.</p>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>		
		</div>
	</div>
<!-- header -->
<!-- Large message -->
	<div class="ipsum">
		<div class="container">
			<h3><span>"</span><?= theme_page( $theme , 'landing_page' , 'large_message' ) ?><span>"</span></h3>
		</div>
	</div>
<!-- End large message -->
 <div class="container">
 	<div class="row" >
<?php
	$this->load->theme($theme , 'partials/products' , array('products' => $products) );
?>
	</div>
</div>	
</div>

<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	
