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
