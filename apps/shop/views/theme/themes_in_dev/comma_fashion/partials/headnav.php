<body>
<div class="wrap">	
   <div class="container">
        <?php if( $cart_ordered ): ?>
			<div class="alert alert-info" >
				<p>You have a order in progress. Order <?= $cart_order_number ?> 
				<span class="pull-right" ><a class="btn btn-small btn-primary">Checkout</a>|<a class="btn btn-small btn-danger">Cancel Order</a></span> </p>
			</div>
		<?php endif; ?>	

        <div class="header_top">
		  <div class="col-sm-9 col-md-9 h_menu4">
				<?= get_advert('728x90'); ?>
				<ul class="megamenu skyblue">
					  <li class="active grid"><a class="color8" href="<?= shop_url() ?>">Home</a></li>	
					  <li class="grid"><a class="color2" href="<?= shop_url('browse') ?>"><?= theme_vocabulary($theme , "browse_store") ?></a></li>
					  <?php
					  //print out up to three categories
					  $menu_categories = get_category_menus($product_categories  );
					  $index = 0;
					  foreach( $menu_categories as $category ):
					  ++$index;
					  if( $index == 3 )
						  break;
					  ?>
					  <li class="grid"><a class="color2" href="<?= category_url( $category); ?>"><?= $category['name'] ?></a>
					  <?php 
						$children = get_category_children( $product_categories , $category );
						if( ! empty($children)  ):
							
					  ?>
					  <div class="megapanel">
						<div class="row">
							<div class="col-md-12">
								<div class="">
									<ul class="list-inline">
									<?php foreach( $children as $child ): ?>
										<li><a href="<?= category_url($child) ?>"><?= $child['name'] ?></a></li>
									<?php endforeach; ?>	
										
									</ul>	
								</div>							
							</div>
						</div>
						</div>
				<?php endif; ?>
					</li>
				<?php endforeach; ?>	
				<li><a class="color1" href="<?= shop_url('featured') ?>">Featured Products</a></li>
				<li><a class="color5" href="<?= site_url('blog') ?>" >Blog</a></li>
				<li><a class="color4" href="<?= site_url('about-us') ?>">About Us</a></li>				
				
			  	</ul>


			</div>
			<div class="col-sm-3 header-top-right">
			    
				<div class="register-info">
				    <ul>
						<li><a href="<?= shop_url('checkout') ?>" >Checkout</a></li>
						
					</ul>
			    </div>
				<div class="clearfix"> </div>
   			 </div>
   			 <div class="clearfix"> </div>
	</div>
    <div class="header_bootm  row_2">
		<div class="col-sm-4 col-xs-12 ">
		  <div class="logo">
			<a href="/"><h3><?= $shop['name'] ?></h3>
			</a>
			<small><?= $shop['tagline'] ?></small>
		  </div>
		</div>
		<div class="col-sm-8  col-xs-12 ">
		  <div class="header_bottom_right">
			<div class="search">
				<form action="/shop/search" method="get" >
			 	 	<input type="text" name="query" placeholder="Search here ">
			  		<input type="submit" value="">
			  	</form>	
	  		</div>
	  		<ul class="bag">
	  			<a href="<?= shop_url('cart') ?>"><i class="bag_left"> </i></a>
	  			<a href="<?= shop_url('cart') ?>"><li class="bag_right"><p><?= money($cart_total) ?></p> </li></a>
	  			<div class="clearfix"> </div>
	  		</ul>
	  		<div class="clearfix"> </div>
		   </div>
		</div>
		 <div class="clearfix"></div>
	</div>
	