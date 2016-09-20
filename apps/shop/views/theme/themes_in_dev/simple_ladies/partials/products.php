<!-- Products -->

	 <?php $x = 0; foreach( $products as $product ):  ?>
	 <a href="<?= product_url( $product ); ?>"><div class="product-grid love-grid">
						<div class="more-product-info"></div>						
						<div class="product-img b-link-stripe b-animate-go  thickbox">
							<img src="<?= random_product_image( $product ); ?>"  class="img-responsive" alt=""/>
							<div class="b-wrapper">
							<h4 class="b-animate b-from-left  b-delay03">							
							<button class="btns">ORDER NOW</button>
							</h4>
							</div>
						</div>	</a>					
						<div class="product-info simpleCart_shelfItem">
							<div class="product-info-cust">
								<h4><?= $product['name'] ?></h4>
								<span class="item_price"><?= money( $product['price']) ?></span>
								<input type="text" class="item_quantity" value="1" />
								<input type="button" class="item_add" value="ADD">	
							</div>												
							<div class="clearfix"> </div>
						</div>
					 </div>
	<?php $x++; if(isset($limit) and $x >= $limit ) break;  endforeach; ?>
	</div>
</div>
