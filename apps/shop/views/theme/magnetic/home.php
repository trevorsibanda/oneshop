<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$popular_products = theme_popular_products( 9 );
?>

<?php $this->load->theme(  $theme , 'partials/header'); ?>	

	<section class="main clearfix">
		<h4 class="text-right">Most popular products</h4>
		<div class="row" >
		<?php $x = 0; foreach( $popular_products as $product ): $x +=1; ?>
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
			<?php if( $x%6==0 && account_can('adverts') ): ?>
				<div class="text-center"><?= get_advert('728x90'); ?></div></div><div class="row">
			<?php endif; ?>
		<?php endforeach; ?>
		</div>
		<h4 class="text-right">Product categories</h4>
		<div class="row" >
		<?php foreach( $product_categories as $cat ):?>
			<div class="work">
				<a href="<?=  product_category_url( $cat ) ?>">
					<img src="<?=  product_image( $cat['image'] ) ?>" class="media" alt=""/>
					<div class="caption">
						<div class="work_title">
							<h1><?= $cat['name'] ?></h1>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach; ?>
		</div>
	</section><!-- end main -->
	

<?php $this->load->theme( $theme , 'partials/footer'); ?>