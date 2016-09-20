<?php
	//load things i need
	$product_categories = theme_product_categories( 10);
	$recommended_products = theme_suggested_products( 6 );
	$recently_viewed_products = theme_recently_viewed_products( 6 );
	
	
	$this->load->theme($theme ,'partials/head'); 
?>	
<!----details-product-slider--->
<!-- Include the Etalage files -->
<link rel="stylesheet" href="<?= theme_css($theme, 'etalage.css'); ?>">
<script src="<?= theme_js($theme, 'jquery.etalage.min.js'); ?>"></script>
<script src="<?= theme_js($theme, 'easyResponsiveTabs.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= theme_js($theme, 'jquery.flexisel.js'); ?>"></script>
<script type="text/javascript" src="<?= theme_js($theme, 'view_product.js'); ?>"></script>
<?php
	$this->load->theme($theme ,'partials/navigation_minimal');
?>
<div class="about_top">
  <div class="container">
		<div class="col-md-3">
			<ul class="menu1">
				<li class="item1"><a href="#" class="">What To Buy ?<img class="arrow-img" src="images/arrow.png" alt=""/> </a>
					<ul class="cute" style="display: none; overflow: hidden;">
						<li class="subitem1"><a href="single.html">Cute Kittens </a></li>
						<li class="subitem2"><a href="single.html">Strange Stuff </a></li>
						<li class="subitem3"><a href="single.html">Automatic Fails </a></li>
					</ul>
		         </li>
			 </ul>
		<?=  get_advert('250x250'); ?> 
		<div class="box1">
			<ul class="box1_list">
				<?php foreach($product_categories as $cat ): ?>
				<li><a href="<?= product_category_url($cat) ?>"><?=  $cat['name'] ?></a></li>
				<?php endforeach; ?>
				
			</ul>
		</div>
		
		 <?=  get_advert('160x600'); ?> 
		 <ul class="box3_list">
				<li><a href="<?= shop_url('search') ?>">Search </a></li>
				<li><a href="<?= shop_url('browse') ?>">Browse Products</a></li>
				<li><a href="<?=  shop_url('featured') ?>">Featured Products</a></li>
				<li><a href="<?= shop_url('cart') ?>">Checkout</a></li>
				<li><a href="<?= shop_url('about-us') ?>">About Us </a></li>
				<li><a href="<?= shop_url('contact-us') ?>">Contact Us</a></li>	
		 </ul>
		</div>
		<div class="col-md-9 content_right">
		   <div class="dreamcrub">
			   	 <ul class="breadcrumbs">
                    <li class="home">
                       <a href="<?= shop_url() ?>" title="Go to Home Page">Home</a>
                       <span>&gt;</span>
                    </li>
                    <li class="home">
                       <a href="<?= shop_url('browse') ?>" >Browse</a>
                       <span>&gt;</span>
                    </li>
                    <li class="home">
                       <?= $product['name'] ?>
                    </li>
                    
                </ul>
                <ul class="previous">
                	<li><a href="javascript:history.back();">Back to Previous Page</a></li>
                </ul>
                <div class="clearfix"></div>
			   </div>
			   <div class="singel_right">
			     <div class="labout span_1_of_a1">
				<!-- start product_slider -->
				     <ul id="etalage">
				     			<?php foreach($product['images'] as $img ):
				     				$url = product_image($img);
				     			?>
							<li>
								<a href="<?= $url ?>" target="_blank" >
									<img class="etalage_thumb_image" src="<?= $url  ?>" class="img-responsive" />
									<img class="etalage_source_image" src="<?= $url ?>" class="img-responsive" />
								</a>
							</li>
							<?php endforeach;  ?>
							<div class="clearfix"> </div>
						</ul>
					<!-- end product_slider -->
			  </div>
			  <div class="cont1 span_2_of_a1">
				<h1><?= $product['name'] ?></h1>
				
			    <div class="price_single">
				  <span class="actual"><?= money($product['price']) ?></span>
				</div>
				<h2 class="quick">Quick Overview:</h2>
				<p class="quick_desc"><?= $product['stock_left'] ?> left in stock</p>
			    
				<?php open_add_cart_form($product); ?>
						<?php $this->load->theme( $theme , 'partials/already_added_to_cart') ?>
						
						<div class="row" >
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<label>Items</label>
								<select class="form-control" name="items" >
									<?php for($x=$product['min_orders'];$x <= $product['max_orders'];$x++): ?>
									<option value=<?= $x ?> ><?= $x ?> items</option>
									<?php endfor; ?>
								</select>
							</div>
							<?php $this->load->sys_theme( 'product_customize_select' );?>
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<br/>
								<button style="margin-top:5px;" class="btn btn_form btn-block "><i class="fa fa-shopping-cart"></i> Add to cart</button>
							</div>
							
						</div>
				<?php close_add_cart_form(); ?>
			    </ul>
			    
			</div>
			<div class="clearfix"></div>
		   </div>
		   <div class="sap_tabs">	
				     <div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
						  <ul class="resp-tabs-list">
						  	  <li class="resp-tab-item" aria-controls="tab_item-0" role="tab"><span>Product Description</span></li>
							  <li class="resp-tab-item" aria-controls="tab_item-1" role="tab"><span>Product Specifications</span></li>

							  <div class="clear"></div>
						  </ul>				  	 
							<div class="resp-tabs-container">
							    <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
									
									<div class="facts">
									 <br/><br/>
									  <?=  $product['description'] ?>          
							        	</div>
							     </div>	
							      <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
									<div class="facts">
									  <ul class="tab_list">
									    <li><a href="#">augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigatione</a></li>
									  	<li><a href="#">claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum. Mirum est notare quam littera gothica</a></li>
									  	<li><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</a></li>
									  </ul>           
							        </div>
							     </div>	
							      <div class="tab-1 resp-tab-content" aria-labelledby="tab_item-2">
									<ul class="tab_list tab_last">
									  	<li><a href="#">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat</a></li>
									  	<li><a href="#">augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigatione</a></li>
									  	<li><a href="#">claritatem insitam; est usus legentis in iis qui facit eorum claritatem. Investigationes demonstraverunt lectores leg</a></li>
									  	<li><a href="#">Mirum est notare quam littera gothica, quam nunc putamus parum claram, anteposuerit litterarum formas humanitatis per seacula quarta decima et quinta decima. Eodem modo typi, qui nunc nobis videntur parum clari, fiant sollemnes in futurum.</a></li>
									  </ul>      
							     </div>	
							 </div>
					      </div>
					 </div>	
					 <h3 class="like">You Might Also Like</h3>        			
				     <ul id="flexiselDemo3">
						<?php foreach( $recommended_products as $product ): ?>
						<li><a href="<?=  product_url($product) ?>" ><img src="<?= random_product_image( $product ) ?>" class="img-responsive"/><div class="grid-flex"><strong><?= $product['name'] ?></strong><p><?= money($product['price']) ?></p></div></a></li>
						<?php endforeach; ?>
						
				     </ul>
				     <?=  get_advert('728x90'); ?> 
				   <h3 class="recent">Recently Viewed</h3>
				   <ul id="flexiselDemo1">
						<?php foreach( $recently_viewed_products as $product ): ?>
						<li><a href="<?=  product_url($product) ?>" ><img src="<?= random_product_image( $product ) ?>" class="img-responsive"/><div class="grid-flex"><strong><?= $product['name'] ?></strong><p><?= money($product['price']) ?></p></div></a></li>
						<?php endforeach; ?>
				     </ul>
				    <?=  get_advert('728x90'); ?>
		</div>
		<div class="clearfix"> </div>
	</div>
</div>   	
</div>   
<?php   $this->load->theme($theme ,'partials/footer');  ?>
