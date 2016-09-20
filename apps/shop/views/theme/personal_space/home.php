<?php
	$this->load->theme($theme , 'partials/head' , array('curr_page' =>'home') );
	

	$products = theme_popular_products( 9);
	$posts = theme_popular_blog_posts( 3 );
?>

			
				<!-- Mobile, tablet logo -->
				<div id="logo" class="visible-sm visible-xs">
					<img src="<?= theme_img($theme ,'index.png'); ?>" alt="" />
					
				</div>

				<!-- end mobile, tablet logo -->

				<h2 class="text-center" ><?=  theme_page( $theme , 'home' , 'welcome_msg' )  ?></h2>
				<div id="banner">
					<img src="<?= shop_image([]) ?>" alt="" class="image-full" />
				</div>
				<ul id="cd-gallery-items" >
					<?php $x= 1; foreach( $products as $product ):
						$url = product_url( $product ); 
						if( $x%5 == 0 && account_can('adverts') )
						{
							echo '<li><label>Advertisement</label>' . get_advert('300x250') .  '</li>';
						}	
					?>
					<li>
						<a href="<?= $url;?>" >
							<img src="<?= random_product_image($product); ?>" alt="Preview image">
						</a>
						<h3><?= $product['name'] ?></h3>
						<h4><?= money( $product['price'] ). ' x '. $product['stock_left'] ?> in stock</h4>
						<a href="<?= $url ?>" class="btn btn-block btn-default">Add to cart</a>
					
					</li>
					<?php $x++; endforeach; ?>
					
				</ul>
				<div class="row" >
					<div class="col-md-4 col-sm-2"></div>
					<div class="col-md-4 col-xs-12 col-sm-8">
						<a href="" class="btn btn-block btn-lg btn-success" >Browse Store</a>
					</div>	
				</div>
				<div id="featured">
					<div class="title">
						<h2>From our blog</h2>
						<span class="byline">Articles written by me and some friends.</span>
					</div>
					<ul class="style1">
						<?php foreach ($posts as $post ): $post_url = blog_post_url( $post ); ?>
						<li class="first">
							<div class="row" >
								<div class="col-sm-3" >
									<a href="<?= $post_url ?>" >
									<img src="<?= shop_image($post['image'] ) ?>"  class="img-responsive" />
									</a>
									
								</div>
								<div class="col-md-9" >
									<legend><a href="<?= $post_url ?>" ><?= $post['title'] ?></a></legend>
									<p><?= $post['extract'] ?></p>
									
									<small><b>Published <?= $post['date_published'] ?> by <?= $post['author'] ?></b></small>
								</div>
								<div class="col-xs-12" >
									
								</div>
							</div>
							
						</li>
						<?php endforeach; ?>
						
					</ul>
				</div>


				
<?php 

$this->load->theme($theme , 'partials/footer') ?>
