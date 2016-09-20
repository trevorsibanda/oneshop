<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
   
	$featured_product = element( 0 , $featured_products , array() );
	$large_featured_product = array();
	
	//Large featured product
	if( sizeof( $featured_products ) > 1 )
	{
		$large_featured_product = random_element(  $featured_products );
	}
	else
	{
		$large_featured_product = $featured_product;
	}
		
		
?>

<div class="grid_1">
		<div class="col-md-3 banner_left">
			<?php
			if( isset($featured_product['name']) ):
			?>
			<img src="<?= product_image( $featured_product['images'][0] ); ?>" class="img-responsive" alt=""/>
			<div class="banner_desc">
				<h1><?= element('name' , $featured_product , 'Featured products') ?></h1>
				<h2><?=  element('brand' , $featured_product , '' ) ?></h2>
				<h5><?= money($featured_product['price']) ?>
					   <small>Only</small>
					</h5>
			    <a href="<?= product_url( $featured_product  ) ?>" class="btn1 btn4 btn-1 btn1-1b">Add To Cart</a>
			</div>
			<?php
				endif;
			?>
		</div>
		<div class="col-md-9 banner_right">
			 <!-- FlexSlider -->
              <section class="slider">
				  <div class="flexslider">
					<ul class="slides">
						<li><img src="<?= theme_img( $theme , 'banner.jpg'); ?>" alt=""/></li>
						<li><img src="<?= theme_img( $theme , 'banner1.jpg'); ?>" alt=""/></li>
					</ul>
				  </div>
	          </section>
              <!-- FlexSlider -->
		</div>
		<div class="clearfix"></div>
	</div>
<div class="content">
  <div class="content_box">
	<ul class="grid_2">
		<?php
			$count = 0;
			if( ! empty($products) )
			foreach( $products as $product ):
		?>
		<a href="<?= product_url( $product ) ?>"><li class="last1"><img src="<?= product_image( random_element( $product['images'] ) ); ?>" class="img-responsive" alt=""/>
			<span class="btn5"><?= money($product['price']) ?></span>
			<p><?= $product['name'] ?></p>
		</li></a>
		<?php
			if( $count == 10 )
				break;
			endforeach;
		?>
		<div class="clearfix"> </div>
	</ul>
	<div class="grid_3">
		<div class="col-md-6 box_2">
			<div class="section_group"> 
		      	<?php
					if( isset($large_featured_product['images'][0] ) ):
				?>
				<div class="col_1_of_2 span_1_of_2">
		      		<img src="<?= product_image( $large_featured_product['images'][0] ); ?>" class="img-responsive" alt=""/>
		        </div>
				<?php
					
					if( isset($large_featured_product['images'][1] ) ):
				?>
                <div class="col_1_of_2 span_1_of_2">
		      		<img src="<?= product_image( $large_featured_product['images'][1] ); ?>" class="img-responsive" alt=""/>
		        </div>
				<?php
					endif;
					endif;
				?>
                <div class="clearfix"> </div>
            </div>
		</div>
		<div class="col-md-6">
			<div class="box_3">
			  <div class="col_1_of_2 span_1_of_2 span_3">
		      		<h3><?= $large_featured_product['name'] ?></h3>
                  <h4><?= money($large_featured_product['price']) ?></h4>
                  <p><?= substr( $large_featured_product['description'] , 0 , 300 ) . "..."; ?></p>
                  <a href="#" class="btn1 btn6 btn-1 btn1-1b">Add To Cart</a>
		        </div>
                <div class="col_1_of_2 span_1_of_2 span_4">
                   <div class="span_5">
		      		 <img src="<?= product_image( $large_featured_product['images'][0] ); ?>" class="img-responsive" alt=""/>
		      	    </div>
		        </div>
                <div class="clearfix"> </div>
            </div>
		</div>
		<div class="clearfix"> </div>
	</div>
	<div class="row" >
		<h2 class="text-center"><a href="<?= shop_url('browse'); ?>" class="btn-warning btn-lg " >Browse entire store</a></h2>
	</div>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	
