<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>
<div class="main">
		<div class="container">
		   <div class="content">	 	 
		 		<div class="section group">
					<div class="col-md-4  col-sm-3 col-xs-12">
				    	<div class="slider">
							<div class="callbacks_container">
								<ul class="rslides" id="slider">
							<?php foreach( $product['images'] as $image ): ?>
									<li>
										<img class="img-responsive" src="<?= product_image( $image ); ?>"  />
										<div class="caption">
											<p><?= $image['meta'] ?></p>
										</div>
									</li>
							<?php endforeach; ?>
								</ul>
							</div>		
						</div>
						<br/>
						<h3 class="text-center"><?= $product['stock_left'] ?> in stock</h3>			
					</div>
					<div class="col-sm-9 col-md-8 col-xs-12 cont span_2_of_3">
					  <div class="blog_grid">
							  <h1><?= $product['name'] ?></h1>
						    	<h3><?= money($product['price']) ?></h3>
							<?php
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
							<?php open_add_cart_form($product); ?> 
								 <label>Customise your order</label>
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
									<?php $this->load->sys_theme( 'product_customize_select' );?>
							
								 </div>
								 <br/>
								 <input type="submit" class="btn btn-lg btn-success " value="Add to cart" title="">
							<?php close_add_cart_form(); ?>
							<h3>Product Details</h3>
							<div class="article"><?= $product['description'] ?></div>
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
							<div class="clearfix"> </div>				  
			      		</div>
					</div>
				</div>
			</div>	
		</div>
		<div class="row" >
			<h3>Related Products</h3>
			<div class="container">
			<?php
				$this->load->theme($theme , 'partials/products' , array('products' => $related_products) );
			?>
		</div>
</div>	

<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	
