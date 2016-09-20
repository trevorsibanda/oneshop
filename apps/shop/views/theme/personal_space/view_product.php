<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'browse' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 

	$products = theme_popular_products( 3);
	
?>
			
				
				<div class="row" >
					<div class="col-md-4" >
						<a href="" >
							<img src="<?= random_product_image( $product ) ?>" class="img-responsive" alt="Preview image">
						</a>

						<h3><?= $product['stock_left'] ?> in stock</h3>
						<?=  get_advert('300x250')  ?>
						
					</div>
					<div class="col-md-8" >
						<legend><h1 class="text-left"><?= $product['name'] ?></h1></legend>
						<h2><span class="pull-right"><?= money($product['price']) ?></span><br/></h2>
						<?php $this->load->theme( $theme , 'partials/already_added_to_cart') ?>
						<legend class="text-right"><small >Customise your order</small></legend>
						<?php open_add_cart_form($product); ?>
						
						<div class="row" >
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<label>Items</label>
								<select class="form-control" name="items" >
									<?php for($x=$product['min_orders'];$x < $product['max_orders'];$x++): ?>
									<option value=<?= $x ?> ><?= $x ?> items</option>
									<?php endfor; ?>
								</select>
							</div>
							
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<br/>
								<button style="margin-top:5px;" class="btn btn-block btn-info"><i class="fa fa-shopping-cart"></i> Add to cart</button>
							</div>
							
						</div>
						<br/>
							<?php $this->load->sys_theme( 'product_customize_select' );?>
						<br/>
						<?php close_add_cart_form(); ?>
						<?php $this->load->sys_theme('share_product_buttons'); ?>
						<legend class="text-right"><small >Product description</small></legend>
						<table class="table table-striped" >
							<tbody>
								<tr>
									<td>Brand</td>
									<td><?= $product['brand'] ?></td>
								</tr>
								<?php foreach( $product['attributes'] as $attrib ): ?>
									<tr>
										<td><?= $attrib['attribute_name'] ?></td>
										<td><?= $attrib['attribute_value'] ?></td>
									</tr>
								<?php endforeach; ?>
								<tr>
									<td>Minimum per order</td>
									<td><?= $product['min_orders'] ?></td>
								</tr>
								<tr>
									<td>Maximum per order</td>
									<td><?= $product['max_orders'] ?></td>
								</tr>
							</tbody>
						</table>
						<?= $product['description'] ?>
					</div>

				</div>
				<div class="row" >
					<div class="col-sm-12" >
						<h4 class="text-left">You might also like</h4>
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
					</div>
				</div>
<?php $this->load->theme($theme , 'partials/footer'); ?>
