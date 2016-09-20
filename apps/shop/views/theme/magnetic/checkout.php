<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$popular_products = theme_popular_products( 2 );

$cart_items = theme_cart_items();

$posts = theme_most_commented_blog_posts( 3);
?>

<?php $this->load->theme(  $theme , 'partials/header'); ?>	

	<section class="main clearfix">

    <div class="product-big-title-area">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Shopping Cart</h2>
                </div>
            </div>
        </div>
    </div> <!-- End Page title area -->
    
    <div class="row">
                
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="woocommerce">
                            <form method="post" action="/cart/update">
                                <table cellspacing="0" class="shop_table cart table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach( $cart_items as $item ): $product = $item['product']; $url = product_url( $product  ); ?>
                                        <tr class="cart_item">
                                            <td class="product-remove">
                                                <a title="Remove this item" class="remove" href="/cart/remove?product_id=<?= $product['product_id'] ?>">×</a> 
                                            </td>

                                            <td class="product-thumbnail">
                                                <a href="<?= $url ?>"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="<?= random_product_image( $product ) ?>"></a>
                                            </td>

                                            <td class="product-name">
                                                <a href="<?= $url ?>"><?= $product['name'] ?></a> 
                                            </td>

                                            <td class="product-price">
                                                <span class="amount"><?= money( $item['cost'] ) ?></span> 
                                            </td>

                                            <td class="product-quantity">
                                                <div class="quantity buttons_added">
                                                    <input type="button" class="minus" value="-" onclick="var elem = document.querySelector('#qty_<?= $item['product_id'] ?>'); if(elem.value > 0)elem.value = parseInt(elem.value)-1;" >
                                                    <input type="number" size="4" class="input-text qty text" name="cart_item_qty_<?= $item['product_id'] ?>" id="qty_<?= $item['product_id'] ?>" title="Qty" value=<?= $item['qty'] ?> min="0" step="1">
                                                    <input type="button" class="plus" value="+" onclick="var elem = document.querySelector('#qty_<?= $item['product_id'] ?>'); if(elem.value < <?= $product['max_orders'] ?>)elem.value = parseInt(elem.value)+1;">
                                                </div>
                                            </td>

                                            <td class="product-subtotal">
                                                <span class="amount"><?= money( $item['subtotal'] ) ?></span> 
                                            </td>
                                        </tr>
                                    	<?php endforeach; ?>
                                        <tr>
                                            <td class="actions" colspan="6">
                                                
                                                <input type="submit" value="Update Cart" name="update_cart" class="button">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>

                            <div class="cart-collaterals">


                            <div class="cross-sells">
                                <h2>You may be interested in...</h2>
                                <ul class="products">
                                    <?php foreach( $popular_products as $product ): $url= product_url($product); ?>
                                    <li class="product">
                                        <a href="<?= $url ?>">
                                            <img width="325" height="325" alt="T_4_front" class="attachment-shop_catalog wp-post-image" src="<?= random_product_image($product) ?>">
                                            <h3><?= $product['name'] ?></h3>
                                            <span class="price"><span class="amount"><?= money( $product['price'] ) ?></span></span>
                                        </a>

                                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="22" rel="nofollow" href="<?= $url ?>">Select options</a>
                                    </li>
	                                <?php endforeach; ?>

                                </ul>
                            </div>


                            <div class="cart_totals ">
                                <h2>Cart checkout</h2>
                                <?= validation_errors(); ?>
                                <form method="POST" action="/cart/checkout" >
                                	<label>* Your fullname</label>
                                	<input type="text" required class="form-control" name="fullname" />
                                	<label>* Your Email Address</label>
                                	<input type="email" required class="form-control" name="email" />
                                	<label>* Your phone number</label>
                                	<input type="text" required class="form-control" value="+263" name="phone_number" required pattern="^(\+|00)263(77|78|73|71|72)\d{7,8}+" />
                                	<label>* Delivery Type</label>
                                	<select class="form-control" name="delivery"  required onchange="shipping(this.value);"	 >
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
											<input type="text"  class="form-control" placeholder="Your suburb" name="Suburb" />
										
										<label>*Address</label>
											<input type="text"  class="form-control" placeholder="Your address" name="address" />
										
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
									
									<button type="submit" name="gateway"  class="checkout-button button alt wc-forward" value="<?= $gateway['gateway'] ?>" >Checkout using <?= $gateway['name'] ?></button>
									<?php endforeach; ?> 
									<?php if( empty($gateways) ): ?>
									<h4>Sorry you cannot checkout because there is no payment gateway configured</h4>
									<?php endif; ?>       
                                </form>
                                <br/>
                                <table cellspacing="0">
                                    <tbody>
                                        <tr class="cart-subtotal">
                                            <th>Cart Subtotal</th>
                                            <td><span class="amount"><?= money($cart_total) ?></span></td>
                                        </tr>

                                        <tr class="shipping">
                                            <th>Shipping and Handling</th>
                                            <td class="shipping_cost"><?= money( $shipping_cost) ?></td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>


                            </div>
                        </div> 
                    </div>
                </div>                               
    		</div>
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        
    </div>		
	</section>

<?php 
$this->load->sys_theme('checkout_cart_js');
$this->load->theme(  $theme , 'partials/footer'); ?>		