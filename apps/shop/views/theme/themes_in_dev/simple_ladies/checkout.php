<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>

<div class="container">
  <div class="content_box">
  	<?php if( empty($cart) ): ?>
	<div class="men cart">
	    <h2 class="text-center">It appears that your cart is currently empty!</h2>
	    <h2 class="text-center"><a href="<?= shop_url('browse') ?>">Browse products</a>.</h2>
    </div>
    <?php else: ?>
    	<div class="row" >
		   	<div class="col-md-6 well" >
					<h2 class="text-center">Checkout</h2>
					<hr/>
					<?= validation_errors(); ?>
					<form method="post" class="form">
						<label>*Fullname</label>
							<input type="text" required name="fullname" class="form-control" title="Your fullname " placeholder="Your fullname"/>
						<label>*Phone number</label>
							<input type="text" required name="phone_number" class="form-control" placeholder="+263" value="+263" title="We'll use this to contact you with your order details" />
						<label>*Email</label>
							<input type="email" required name="email" class="form-control" title="Used to contact you again" placehlder="Valid email address" />
						<label>*Delivery </label>
							<select class="form-control" name="delivery" required onchange="shipping(this.value);"	 >
								<option value="collect_instore" >Collect from shop</option>
								<option value="deliver" >Deliver to my address</option>
							</select>
						<div class="hidden" id="shipping_options" >
							<label>Country</label>
								<select class="form-control" name="country" >
									<option value="ZW">Zimbabwe</option>
								</select>
							<label>*City</label>
								<input type="text"  class="form-control" placeholder="Bulawayo" name="city" />
							<label>*Delivery Address</label>
								<input type="text"  class="form-control" placeholder="18 Frome Avenue Thorngrove" name="address" />
							<b>* NB: Delivery will incur extra charges. These charges are not set by the shop but by a third party delivery company</b>	
						</div>	
						<br/>
						<div class="form-group" >
							<input type="checkbox" id="subscribe" name="subscribe" checked />
							<label for="subscribe" >Get notifications for promotions from this shop</label>
						</div>	
						<p>
						You can pay using EcoCash, TeleCash, MasterCard, VISA, ZimSwitch Bank or Paypal
						</p>
						<input type="submit" class="btn btn-success btn-lg btn-block" value="Place order and Pay" />
						<br/>	
					</form>	
			</div>	
		   	<div class="col-md-6" >
			    <hr/>
			    <div class="row" >
			    	<div class="pull-right" >
			    		<a href="/cart/clear" ><i class="fa fa-delete"></i>Clear Cart</a> 
			  		</div>
			  	</div>
			  	<br/>
			  	<table class="table table-striped" >
			  		<thead>
			  			<th>
			  				<tr>
			  					<td>Image</td>
			  					<td>Product</td>
			  					<td>Quantity</td>
			  					<td>Price</td>
			  					<td>Subtotal</td>
			  					<td>Actio</td>
			  				</tr>

			  			</th>
			  		</thead>
			  		<tbody>
			  			<?php foreach( $cart as $cart_item ): ?>
			  			<tr>
			  				<td>
			  						<img src="<?= random_product_image($cart_item['product']) ?>" class="img-responsive" style="max-height: 64px;" />
			  				</td>
			  				<td>
			  					<b><a href="<?= product_url($cart_item['product']);?>" >
				  				<?= $cart_item['product']['name'] ?>
				  				</b></a>
			  				</td>
			  				<td>
			  					<?= $cart_item['qty'] ?>
			  				</td>
			  				<td>
			  					<b><?= money($cart_item['product']['price']) ?></b>
			  				</td>
			  				<td>
			  					<b><?= money( $cart_item['subtotal']) ?></b>
			  				</td>
			  				<td>
			  					<a href="" class="btn btn-info btn-block btn-sm" >Edit Order</a>
			  					<a href="<?= remove_cart_url( $cart_item['product'] ) ?>" class="btn btn-danger btn-block btn-sm" >Remove From Cart</a>
			  				</td>
			  			</tr>
			  			<?php endforeach; ?>
			  			<tr>
			  				<td></td>
			  				<td></td>
			  				<td></td>
			  				<td>Total</td>
			  				<td><?= money( $cart_total ); ?></td>
			  			</tr>
			  		</tbody>
			  	</table>
								
			</div>	
	<?php endif; ?>
	<script>
	function shipping( option )
	{
		var so = document.querySelector('#shipping_options');
		if( option == 'deliver')
		{
			
			so.className = '';
		}
		else
		{
			so.className = 'hidden';
		}
	}
	</script>