<?php
	$product_categories = theme_product_categories( 10);
	
	$this->load->theme($theme ,'partials/head');
	$this->load->theme($theme ,'partials/navigation_minimal'); 
?>
<link href="<?= theme_css($theme,'component.css'); ?>" rel='stylesheet' type='text/css' />

<div class="about_top">
  <div class="container">
		<div class="col-md-3">
			<ul class="menu1">
				<li class="item1"><a href="#" class="">What To Buy ?<img class="arrow-img" src="<?= theme_img($theme , 'arrow.png'); ?>" alt=""/> </a>
					<ul class="cute" style="display: none; overflow: hidden;">
						<li class="subitem1"><a href="single.html">Cute Kittens </a></li>
						<li class="subitem2"><a href="single.html">Strange Stuff </a></li>
						<li class="subitem3"><a href="single.html">Automatic Fails </a></li>
					</ul>
		         </li>
			 </ul>
		<?= get_advert('200x200'); ?>
		<ul class="box2_list">
				<li><label>Categories</label></li>
				<?php foreach($product_categories as $cat ): ?>
				<li><a href="<?= product_category_url($cat) ?>"><?=  $cat['name'] ?></a></li>
				<?php endforeach; ?>
		 </ul>
		 <div class="box1">
			
			<ul class="box1_list">
				<li><label>My Cart</label></li>
				
			</ul>
		</div>
		<?= get_advert('200x200'); ?>
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
                       <a href="<?= shop_url('') ?>" title="Go to Home Page">Home</a>&nbsp;
                       <span>&gt;</span>
                    </li>
                    <li class="home">
                        <a href="<?= shop_url('browse') ?>" title="Browse products">Search</a>
                        <span>&gt;</span>
                    </li>
                    <li class="women">
                        <?=  $query ?>
                    </li>
               </ul>
                <ul class="previous">
                	<li><a href="javascript:history.back();">Back to Previous Page</a></li>
                </ul>
                <div class="clearfix"></div>
		</div>
		<div class="row " >
				<form class="row" method="get" action="<?= shop_url('search') ?>"  >
					
					<div class="col-md-9 col-sm-6" >
						<div class="input-group" >
							<input type="search" name="q"  class="form-control " placeholder="Search for products , categories here" value="<?= $query ?>" />
							<span class="input-group-addon" ></span>
						</div>
					</div>
					<div class="col-md-3 col-sm-6" >
						<button class="btn btn-default btn-block" type="submit" >Search</button>
					</div>
				</form>
				<hr style="border:  solid #337AB7 2px;"/>
				
				<div class="row" >	
					<div class="col-md-6 col-sm-6" >
						<strong>Found <?= $total_results_count ?> results for query</strong>
					</div>
					<div class="col-md-3 col-sm-6" >
						<label>Go to Page</label>
						<select class="form-control" id="page_number" >
							<?php for($x = 1; $x < $max_pages ; $x++ ): ?>
							<option value=<?= $x ?> ><?= $x ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6" >
						<label>Items per page</label>
						<select class="form-control" id="items_page" >
							<option>9</option>
							<option>12</option>
							<option>15</option>
						</select>
					</div>
				</div>
				</div>		
				<div id="cbp-vm" class="cbp-vm-switcher cbp-vm-view-grid">
					<div class="cbp-vm-options">
						<a href="#" class="cbp-vm-icon cbp-vm-grid cbp-vm-selected" data-view="cbp-vm-view-grid" title="grid">Grid View</a>
						<a href="#" class="cbp-vm-icon cbp-vm-list" data-view="cbp-vm-view-list" title="list">List View</a>
					</div>
				
					<div class="clearfix"></div>
					<ul>
					  <?php foreach( $products as $product ):
					  		$p_url = product_url($product);
					  ?>
					  <li>
							<a class="cbp-vm-image" href="<?= $p_url ?>">
							 <div class="view view-first">
					   		  <div class="inner_content clearfix">
								<div class="product_image">
									<img src="<?= random_product_image($product); ?>" class="img-responsive" alt=""/>
									<div class="product_container">
									   <div class="cart-left">
										 <p class="title"><?= $product['name'] ?></p>
									   </div>
									   <div class="price"><?= money($product['price'] ) ?></div>
									   <div class="clearfix"></div>
								     </div>		
								  </div>
			                     </div>
		                      </div>
		                    </a>
							<div class="cbp-vm-details">
								<?= $product['stock_left'] ?> in stock 
							</div>
							<a class="cbp-vm-icon cbp-vm-add" href="<?= $p_url ?>">Add to cart</a>
					</li>
					<?php endforeach; ?>
					</ul>
				</div>
				
		</div>
		<div class="clearfix"> </div>
	</div>
</div>   	
</div> 
<script src="<?= theme_js($theme,'cbpViewModeSwitch.js'); ?>" type="text/javascript"></script>
<script src="<?= theme_js($theme,'classie1.js'); ?>" type="text/javascript"></script>
<?php   $this->load->theme($theme ,'partials/footer'); 
	$this->load->sys_theme('browse_page_js');
?>
</body>
</html>		
