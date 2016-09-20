<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>

<div class="container">
  	<?php if( empty($cart) ): ?>
	<div class="men cart">
	    <h2 class="text-center">It appears that your cart is currently empty!</h2>
	    <h2 class="text-center"><a href="<?= shop_url('browse') ?>">Browse products</a>.</h2>
    </div>
    <?php else: ?>
    <h4 class="text-center" >Your Shopping Cart</h4>
    <hr/>
    <div class="row" >
    	<div class="col-md-9 col-md-offset-3" >
		    <div class="row" >
		    	<div class="pull-right" >
		    		<a href="/cart/clear" ><i class="fa fa-delete"></i>Clear Cart</a> | <a href="/cart/checkout" >Checkout</a>	

		  		</div>
		  	</div>
		  	<br/>
		  	<div class="row" style="font-weight: bold;" >
		  		<div class="col-md-4" >
		  			<span class="text-center" >Product</span>
		  		</div>
		  		<div class="col-md-2" >
		  			<span class="text-center" >Price</span>
		  		</div>
		  		<div class="col-md-2" >
		  			<span class="text-center" >Quantity</span>
		  		</div>
		  		<div class="col-md-2 " >
		  			<span class="text-center" >SubTotal</span>
		  		</div>
		  		<div class="col-md-2" >
		  			<span class="text-center" >Options</span>
		  		</div>
		  	</div>
			<?php foreach( $cart as $cart_item ): ?>
				<div class="row well" >
			  		<div class="col-md-2" >
			  			<img src="<?= product_image($cart_item['product']['images'][0]) ?>" class="img-responsive" style="max-height: 64px;" />
			  		</div>
			  		<div class="col-md-2" >	
			  			<span class="" >
			  				<b><a href="<?= product_url($cart_item['product']);?>" >
			  				<?= $cart_item['product']['name'] ?>
			  				</b></a>
			  			</span>
			  		</div>
			  		<div class="col-md-2" >
			  			<span class="text-center" ><b><?= money($cart_item['product']['price']) ?></b></span>
			  		</div>
			  		<div class="col-md-2 " >
			  			<span class="text-center" ><b><?= $cart_item['qty'] ?></b></span>
			  		</div>
			  		<div class="col-md-2" >
			  			<span class="text-center" ><b><?= money($cart_item['subtotal']) ?></b></span>
			  		</div>
			  		<div class="col-md-2" >
			  			<a href="" class="btn btn-info btn-block btn-sm" >Edit Order</a>
			  			<a href="<?= remove_cart_url( $cart_item['product'] ) ?>" class="btn btn-danger btn-block btn-sm" >Remove From Cart</a>
			  		</div>
		  		</div>
				<?php endforeach; ?>	
				<div class="row well" >
					<div class"col-md-2" >
					</div>
					<div class="col-md-2" >
						
					</div>
					<div class="col-md-2" >
						
					</div>
					<div class="col-md-2" >
						<span class="pull-right" ><b>Total:</b></span>

					</div>
					<div class="col-md-2" >
						<span class="text-center" ><b><?= $cart_total_items ?> Items</b></span>
					</div>
					<div class="col-md-2" >
						<span class="text-center" ><b><?=  money( $cart_total ); ?></b></span>
					</div>
					<div class="col-md-2" >
						<a href="/cart/checkout" class="btn btn-block btn-lg btn-success" >Checkout</a>
					</div>
				</div>
			</div>
		</div>	
				
	<?php endif; ?>