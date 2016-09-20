<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'about' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 
	
	$products = theme_popular_products( 3);
?>
<div class="title">
	<h2 class="text-center">404</h2>
	<p class="text-center" >We could not find the page you were looking for</p>
</div>
<h3 class="text-right" >You might be interested in</h3>
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
<?php
$this->load->theme($theme , 'partials/footer' );
?>

