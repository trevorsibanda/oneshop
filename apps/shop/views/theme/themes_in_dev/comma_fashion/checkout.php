<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content">
  <div class="content_box">
  	<?php if( empty($cart) ): ?>
	<div class="men cart">
	    <h2 class="text-center">It appears that your cart is currently empty!</h2>
	    <h2 class="text-center"><a href="<?= shop_url('browse') ?>">Browse products</a>.</h2>
    </div>
    <?php else: ?>
    	<div class="row" >
		   	<div class="col-md-6" >
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
								<?php if( $shipping['allow_collect_instore'] ): ?>
                                <option value="collect_instore" >Collect from shop</option>
								<?php endif; ?>
                                <?php if( $shipping['allow_deliveries'] ): ?>
                                <option value="deliver" >Deliver to my address</option>
								<?php endif; ?>
                                <?php if( $shipping['allow_cash_on_delivery'] ): ?>
                                <option value="cash_on_delivery" >Cash on delivery</option>
								<?php endif; ?>
                                
							</select>
						<div class="hidden" id="shipping_options" >
							<label>Shipping costs</label>
								<?php if( $shipping['use_shipping_rules'] == False  ): ?>
                                <table class="table table-responsive table-striped" >
                                    <tr>
                                        <th>Within <?= $shop['city'] ?></th>
                                        <th>Outside <?= $shop['city'] ?></th>
                                    </tr>
                                    <tr>
                                        <td><?= money( $shipping['intracity_fee'] ) ?></td>
                                        <td><?= money( $shipping['intercity_fee'] ) ?></td>
                                    </tr>
                                </table>
                                <h4 class="text-center" >Your shipping cost is <span class="shipping-cost"><?= money($shipping_cost) ?></span></h4>
                                <?php else:  ?>
                                <table class="table table-responsive table-striped" >
                                    <tr>
                                        <td>Total shipping costs</td>
                                        <td><?= money( $shipping_cost ) ?></td>
                                    </tr>
                                </table>
                                <?php endif; ?>
                                <small>Note that this cost will be added to your order.</small><br/>
							
							<label>Country</label>
								<select class="form-control" name="country" >
									<option value="ZW">Zimbabwe</option>
								</select>
                            <label>*City</label>
								<input type="text"  class="form-control" placeholder="Bulawayo" name="city" />
							<label>*Suburb</label>
								<input type="text"  class="form-control" placeholder="Bulawayo" name="Suburb" />
							
							<label>*Address</label>
								<input type="text"  class="form-control" placeholder="18 Frome Avenue Thorngrove" name="address" />
							
						</div>	
						<br/>
                        <?php if( isset($captcha)): ?>
						<label>Captcha</label>
                        <br/>
						<div class="row" >
                            <div class="col-sm-12 col-md-6">
                                <?= $captcha['image'] ?>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <input type="text" class="form-control" name="captcha" style="height: 50px;" placeholder="Verify that you're a human" />
                            </div>
                        </div>
                        
						<br/>	
						<?php endif;?>
						
						<?php foreach( $gateways as $gateway ): ?>
						<p>
						<?= $gateway['checkout_msg'] ?></p>
						<img src="<?= $gateway['checkout_img'] ?>" class="img-responsive" />
						
						<button type="submit" name="gateway"  class="btn btn-success btn-lg btn-block" value="<?= $gateway['gateway'] ?>" >Pay <?= money($cart_total) . ' using '  . $gateway['name'] ?></button>
						<?php endforeach; ?>
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
							<span class="text-center" ><b><?= $cart_total_items ?></b></span>
						</div>
						<div class="col-md-4" >
							<span class="text-center" ><b><?=  money( $cart_total ); ?></b></span>
						</div>
					</div>
                    <p>Please note that the cost shown above does not include shipping costs. </p>
				</div>
				
			</div>	
	<?php endif; 
	$this->load->sys_theme('checkout_cart_js');
	?>
	
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>		