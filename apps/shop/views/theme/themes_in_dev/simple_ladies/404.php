<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>

<div class="product-model">	 
	 <div class="container">
		 <h1 class="text-center" >Page not found !</h1>
		 <p class="text-center">We could not find the page you are looking for, it might have been removed or has not been created
		  yet.<br/>In the mean time check out our featured products.</p>			
		 <div class="col-md-12 product-model-sec" id="products">
		 	
		 	<?php $this->load->theme($theme , 'partials/products' , array('products' => $featured_products , 'limit' =>2 ) ); ?>
		 </div>
	 </div>
</div>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	

