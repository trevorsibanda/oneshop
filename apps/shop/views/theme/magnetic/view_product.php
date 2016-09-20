<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$suggested_products = theme_suggested_products( 3  , $product );
?>

<?php $this->load->theme(  $theme , 'partials/header'); ?>	
<link rel="stylesheet" href="<?= theme_css($theme, 'etalage.css'); ?>">

	<section class="main clearfix">
		<div style="margin-top: 50px;" ></div>
		<div class="row" >
			<div class="col-md-4" >
				<ul id="etalage">
		     		<?php foreach($product['images'] as $img ):
		     				$url = product_image($img);
		     			?>
					<li>
						<a href="<?= $url ?>" target="_blank" >
							<img class="etalage_thumb_image" src="<?= $url  ?>" class="img-responsive" />
							<img class="etalage_source_image" src="<?= $url ?>" class="img-responsive" />
						</a>
					</li>
					<?php endforeach;  ?>
					<div class="clearfix"> </div>
				</ul>
				<h5><?= $product['stock_left'] ?> in stock</h5>
				<?php foreach( $product['tags'] as $tag ): ?>
					<label class="badge" ><?= $tag ?></label>
				<?php endforeach; ?>
				<?=  get_advert('300x250')  ?>
			</div>
			<div class="col-md-8" >
				<h1><?= $product['name'] ?></h1>
				
			    <div class="price_single">
				  <span class="actual"><?= money($product['price']) ?></span>
				</div>
				<h2 class="quick">Quick Overview:</h2>
				<p class="quick_desc"><?= $product['stock_left'] ?> left in stock</p>
			    
				<?php open_add_cart_form($product); ?>
						<?php $this->load->theme( $theme , 'partials/already_added_to_cart') ?>
						
						<div class="row" >
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<label>Items</label>
								<select class="form-control" name="items" >
									<?php for($x=$product['min_orders'];$x <= $product['max_orders'];$x++): ?>
									<option value=<?= $x ?> ><?= $x ?> items</option>
									<?php endfor; ?>
								</select>
							</div>
							<?php $this->load->sys_theme( 'product_customize_select' );?>
							<div class="col-xs-12 col-sm-6 col-md-4 " >
								<br/>
								<button style="margin-top:5px;" class="btn btn_form btn-block "><i class="fa fa-shopping-cart"></i> Add to cart</button>
							</div>
							
						</div>
				<?php close_add_cart_form(); ?>
			    <legend class="text-right"><small >Product description</small></legend>
				<table class="table table-striped" >
					<tbody>
						<tr>
							<td>Brand</td>
							<td><?= $product['brand'] ?></td>
						</tr>
						<tr>
							<td>Weight</td>
							<td><?= $product['weight_kg'] ?> Kg</td>
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
			<div class="clearfix"></div>
		   </div>
			</div>	
		</div>
		<h4 class="text-right">You might also like</h4>
		<?php foreach( $suggested_products as $product ): ?>
		<div class="work">
			<a href="<?=  product_url( $product ) ?>">
				<img src="<?=  random_product_image( $product ) ?>" class="media" alt=""/>
				<div class="caption">
					<div class="work_title">
						<h1><?= $product['name'] ?><br/><small><?= money( $product['price'] ) ?></small></h1>
					</div>
				</div>
			</a>
		</div>
		<?php endforeach; ?>
	</section><!-- end main -->
	

<?php $this->load->theme( $theme , 'partials/footer'); ?>
<script src="<?= theme_js($theme, 'jquery.etalage.min.js'); ?>"></script>
<script>
	$('#etalage').etalage();
</script>