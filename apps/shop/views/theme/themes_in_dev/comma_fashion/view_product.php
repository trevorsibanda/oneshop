<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content" >
  <div class="content_box">
	<div class="row" >
		<div class="col-md-3" >
			<ul id="etalage">
				<?php foreach( $product['images'] as $image ): ?>
				<li>
					<a href="<?= '' ?>">
						<img class="etalage_thumb_image" src="<?= product_image( $image ); ?>" class="img-responsive" />
						<img class="etalage_source_image" src="<?= product_image( $image ); ?>" class="img-responsive" title="" />
					</a>
				</li>
				<?php endforeach; ?>
			</ul>
			<br/>
			<h3 class="text-center"><?= $product['stock_left'] ?> in stock</h3>
			<?=  get_advert('300x250')  ?>
		</div>
		<div class="col-md-8 col-md-offset-1" >
				
				<h1><?= $product['name'] ?></h1>
			    <p class="m_5"><?= money($product['price']) ?><br/>	</p>
				<?php
					$cart_entry = in_cart($product , $cart);
					if( is_array($cart_entry) ): ?>
					<div class="alert alert-info" >
						<p>
							<b>Added to your shopping cart</b>
							<br/>
							<?= $cart_entry['qty'] ?> items ordered totalling <?= money($cart_entry['subtotal']) ?>.
						</p>
						<table class="table table-responsive" >
							<?php foreach( $cart_entry['options'] as $opt ): ?>
								<tr>
									<td><?=  $opt['option'] ?></td>
									<td><?= $opt['value'] ?></td>
								</tr>
							<?php endforeach; ?>	
						</table>
					</div>

				<?php endif; ?>
				<form  method="post" action="/cart/add" class="form" > 
					<input type="hidden" value="<?= $product['product_id'] ?>" name="product_id" />
					
				    
					 <br/>
					 <label>Customise your order</label>
					 <hr/>
					 <div class="row" >
						<div class="col-md-3" >
							<label>Items</label>
							<select class="form-control" name="items" >
								<?php
									for($x = $product['min_orders']; $x <= $product['max_orders'] ; $x++)
									{
										$selected = '';
										if( is_array($cart_entry) and $cart_entry['qty'] == $x)
											$selected = 'selected';
										echo "<option value='{$x}' {$selected} >{$x}</option>";
									}
								?>
							</select>
						</div>
						<?php  foreach( $product['attributes'] as $attribute ): 
									$cart_option = cart_option( $cart_entry , $attribute['attribute_value'] );						
									if( $attribute['is_customize'] != True )
									{
										continue;
									}

								
						?>
						<div class="col-md-3" >
							<label><?= $attribute['attribute_name'] ?></label>
							<select class="form-control" name="custom_<?= $attribute['attribute_id'] ?>" >
								<?php if( ! is_null($cart_option) ): ?>
								<option selected value="<?= $cart_option ?>" ><?= $attribute['attribute_value'] ?></option>
								<?php else: ?>	
								<option selected value="<?= $attribute['attribute_value'] ?>" ><?= $attribute['attribute_value'] ?></option>
								<?php endif;?>
								<?php 
									
									foreach( $attribute['options'] as $option )
									{
										if( $attribute['attribute_value'] == $option ['value'] or $attribute['attribute_value'] == $cart_option['value'] )
										{
											continue;
										}
										echo '<option value="' . $option['value'] . '" >' . $option['value'] . '</option>';
									}
								?>
							</select>
						</div>
						<?php endforeach; ?>
					 </div>
					 <br/>
					 <input type="submit" class="btn1 btn4 btn-1 btn1-1b " value="Add to cart" title="">
				</form>
				<hr/>
				<br/>
				<h3>Product Details</h3>
				<p class="m_text2"><?= $product['description'] ?></p>
				<hr/>
				<table class="table table-responsive" >
					<thead>
					
					</thead>
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
							<td>Min orders</td>
							<td><?= $product['min_orders'] ?></td>
						</tr>
						<tr>
							<td>Max orders</td>
							<td><?= $product['max_orders'] ?></td>
						</tr>
					</tbody>
				</table>
				
				<?php if( ! empty($product['public_files']) ): ?>
					<hr/>
					<h3 class="text-left" >Product Files</h3>
					<table class="table table-responsive" >
						<thead>

						</thead>
						<tbody>
							<?php foreach( $product['public_files'] as $file ): ?>
							<tr>
								<td><?= $file['filename'] ?></td>
								<td><?=  $file['ext'] ?></td>
								<td><?= round( ($file['bytes']/1024) , 2 ) . " Kb" ?></td>
								<td><a href="<?= product_file_url( $file , 'open'); ?>" target="_blank" ><span class="" >Open File</span></a></td>
							</tr>	
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
    
			</div>
		    <div class="clearfix"></div>
		</div>
		<div class="row" >
			<div class="col-md-12" >
				<h4 class="head_single">Related Products</h4>
				 <div class="single_span_3">
				 <?php
				 //print out 3 related products
					if( ! empty($related_products))
						for( $x = 0; $x < 3; $x++  ):				
				?>
					 <div class="col-sm-3 span_4">
						 <img src="<?= theme_img( $theme , 'm6.jpg'); ?>" class="img-responsive" alt=""/>
						 <h3><?= $p['name'] ?></h3>
						 <p><?= $p['description'] ?></p>
						 <h4>USD $<?= money($p['price'])  ?></h4>
					 </div> 
				<?php endfor; ?>
				<div class="clearfix"></div>
			 </div>
			</div>
		</div>
	</div>
	
<link rel="stylesheet" href="<?=  theme_css($theme , 'etalage.css'); ?>">
<script src="<?=  theme_js($theme , 'jquery.etalage.min.js'); ?>"></script>
<script>
			jQuery(document).ready(function($){

				$('#etalage').etalage({
					thumb_image_width: 300,
					thumb_image_height: 400,
					source_image_width: 900,
					source_image_height: 1200,
					show_hint: true,
					click_callback: function(image_anchor, instance_id){
						alert('Callback example:\nYou clicked on an image with the anchor: "'+image_anchor+'"\n(in Etalage instance: "'+instance_id+'")');
					}
				});

			});
		</script>

<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>			