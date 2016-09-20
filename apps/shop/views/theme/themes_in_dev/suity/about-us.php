<?php

	$this->load->theme($theme,'partials/head');
	$this->load->theme($theme,'partials/navigation');
?>
<div class="about_top">
  <div class="container">
		<div class="col-md-3 about_sidebar">
			<ul class="menu1">
				<li class="item1"><a href="#" class="">What To Buy ?<img class="arrow-img" src="<?= theme_img($theme,'arrow.png') ?>" alt=""/> </a>
					<ul class="cute" style="display: none; overflow: hidden;">
						<li class="subitem1"><a href="single.html">Cute Kittens </a></li>
						<li class="subitem2"><a href="single.html">Strange Stuff </a></li>
						<li class="subitem3"><a href="single.html">Automatic Fails </a></li>
					</ul>
		         </li>
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
		 
		</div>
		<div class="col-md-9">
		   <div class="dreamcrub">
			   	 <ul class="breadcrumbs">
                    <li class="home">
                       <a href="<?=  shop_url('') ?>" title="Go to Home Page">Home</a>&nbsp;
                       <span>&gt;</span>
                    </li>
                    <li class="women">
                        About Us
                    </li>
               </ul>
                <ul class="previous">
                	<li><a href="javascript:history.back();">Back to Previous Page</a></li>
                </ul>
                <div class="clearfix"></div>
		</div>
		  
		 <div class="grid_2">
		 	<h2>About Us</h2>
 		 	<?= $shop['description'] ?>		 
		 </div>
		 <div class="grid_1">
		   <div class="col-md-4">
			  <div class="row" >	
			  <?php $x =0; foreach($featured_products as $product ): if($x >= 3)break;  ?>
			  
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
		   
		   <div class="clearfix"> </div>
		 </div>
	  </div>
	 <div class="clearfix"> </div>
</div>   	
</div>   
<?php
	$this->load->theme($theme , 'partials/footer' );
?>		
