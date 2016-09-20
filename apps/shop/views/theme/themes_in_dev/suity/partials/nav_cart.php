<ul class="icon1 sub-icon1 profile_img">
					 <li><a class="active-icon c1" href="/cart/checkout">My Cart </a><div class="rate"><?= $cart_total_items ?></div>
						<ul class="sub-icon1 list">
						  <h3>Recently added items(<?= $cart_total_items ?>)</h3>
						  <div class="shopping_cart">
							  <?php foreach( theme_cart_items() as $cart_item ): ?>
							  <div class="cart_box">
							   	 <div class="message">
							   	     <div class="alert-close"> </div> 
					                	     <div class="list_img"><img src="<?= random_product_image($cart_item['product'] ); ?>" class="img-responsive" alt=""/></div>
								    <div class="list_desc"><h4><?= $cart_item['product']['name'] ?></h4><?= $cart_item['qty'] ?> x<span class="actual"><?= money($cart_item['product']['price']) ?></span></div>
		                              			    <div class="clearfix"></div>
	                              			  </div>
	                              			  <?php endforeach; ?>
						       <div class="total">
								<div class="total_left">CartSubtotal : </div>
								<div class="total_right"><?= money( $cart_total ) ?></div>
								<div class="clearfix"> </div>
							</div>
                            <div class="login_buttons">
							  <div class="check_button"><a href="/cart/checkout">Check out</a></div>
							  <div class="login_button"><a href="/admin/login">Login</a></div>
							  <div class="clearfix"></div>
						    </div>
					      <div class="clearfix"></div>
						</ul>
					 </li>
		      </ul>
