<?php
	//load things i need
	$product_categories = theme_product_categories( 10);
	$featured_products = theme_featured_products(2);
	$popular_products = theme_popular_products( 3 );
	$recommended_products = theme_suggested_products( 3 );
	
	
	
	$this->load->theme($theme,'partials/head');
	$this->load->theme($theme,'partials/navigation');
?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
			<ul class="menu1">
				<li class="item1"><a href="#" class="">What To Buy ?<img class="arrow-img" src="<?= theme_img($theme,'arrow.png') ?>" alt=""/> </a>
					<ul class="cute" style="display: none; overflow: hidden;">
						<li class="subitem1"><a href="single.html">Cute Kittens </a></li>
						<li class="subitem2"><a href="single.html">Strange Stuff </a></li>
						<li class="subitem3"><a href="single.html">Automatic Fails </a></li>
					</ul>
		         </li>
			 </ul>
		<div class="box1">
			<ul class="box1_list">
				<li><label>Categories</label></li>
				<?php foreach($product_categories as $cat ): ?>
				<li><a href="<?= product_category_url($cat) ?>"><?=  $cat['name'] ?></a></li>
				<?php endforeach; ?>
		 	</ul>
		</div>
		<?=  get_advert('250x250'); ?> 
		<ul class="box2_list">
				<li><a href="#">New Arrivals</a></li>
				<li><a href="#">Sales</a></li>
				<li><a href="#">Collection '15</a></li>
				<li><a href="#">Mystery</a></li>
				<li><a href="#">Story Behind</a></li>
				<li><a href="#">About US</a></li>
				<li><a href="#">Contacts</a></li>
		 </ul>
		 <?=  get_advert('250x250'); ?>
		</div>
		<div class="col-md-9 content_right">
		  
		  
		   <div class="client_box">
		       <div class="banner-wrap">
		       	  <br/>
		         <h3><?= $shop['tagline'] ?></h3>
		         <?=  get_advert('728x90'); ?> 
		        </div>
			
		   </div>
		   <div class="content_right-box">
			<div class="col-md-8">
			  <?php foreach( $featured_products as $product ): ?>
			  <div class="grid1">
			    <h5><?= $product['name'] ?></h5>
   				  <div class="view view-first">
                     			<img src="<?= random_product_image($product ) ?>" class="img-responsive" alt="<?= $product['name'] ?>"/>
   				       <a href="<?=  product_url($product);	 ?>">
	   				       <div class="mask">
	   			            		<h3>Quick Look</h3>
		                			<p>-----Or----</p>
		                			<h4>Add To Basket</h4>
		              			</div>
                      			</a>
                   		  </div> 
               			  <h6><?= money($product['price']) ?></h6>
			  </div>
			  <?php endforeach; ?>
			   
			</div>
			
			<div class="col-md-4">
			  <div class="row" >	
			  <?php $x =0; foreach($popular_products as $product ): if($x >= 3)break;  ?>
			  
			  <a href="<?= product_url($product); ?>">
			  	
			  	<div class="grid2  span_1">
				  <div class="view view-first">
                     		      <img src="<?= random_product_image($product) ?>" class="img-responsive" alt="<?= $product['name'] ?>"/>
   				      <h5><?= $product['name'] ?></h5>
   				      <h6><?= money($product['price']) ?></h6>
   			      	  </div>
   			      	</div>   
               		  </a>
               		  
               		  <?php  ++$x; endforeach; ?>
			  </div>
			 </div> 
			<div class="clearfix"> </div>
		   </div>
		   <?=  get_advert('728x90'); ?> 
		   <div class="box3 row">
		   	<br/>	
			   <?php foreach( $recommended_products as $product ): ?>
			   <div class="col-md-4">
			     <a href="<?= product_url($product) ?>"> <div class="grid3 view view-first">
			   	   <img src="<?= random_product_image($product) ?>" class="img-responsive" alt=""/>
			   	</div>
			     </a>
			   </div>
			   <?php endforeach; ?>
			   <div class="clearfix"> </div>
			</div>
			<div class="box4">
				<?php $x=0; foreach($product_categories as $cat ):  $x+=1; ?>
				<div class="col-md-6">
				 <div class="grid1">
				    <h5><?= $cat['name'] ?></h5>
	   				  <div class="view view-first">
	                     			<img src="<?= product_image($cat['image']) ?>" class="img-responsive" alt="<?= $cat['image']['meta'] ?>"/>
	   					<a href="<?= product_category_url($cat); ?>">
		   					<div class="mask mask2">
			   			            	<h3>View category</h3>
					        		
			              		 	</div>
	                      			</a>
	                   		  </div> 
				  </div>
				</div>
				<?php if($x >= 2) break; endforeach; ?>
				
				<div class="clearfix"> </div>
			</div>
		</div>
		<div class="clearfix"> </div>
	</div>
</div>   
<?php
	$this->load->theme($theme , 'partials/footer');
?>	
</body>
</html>		
