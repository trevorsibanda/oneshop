<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$popular_products = theme_popular_products( 3 );
?>

<?php $this->load->theme(  $theme , 'partials/header'); ?>	

	<section class="main clearfix">
		<legend><h1>Our blog</h1></legend>
		<div class="row">
        <div class="col-md-10 col-sm-12">
            <?php foreach( $posts as $post ): $url = blog_post_url($post); ?>
            <div class="row">
                <div class="col-md-12 post">
                    <div class="row">
                        <div class="col-md-12">
                            <h4>
                                <strong><a href="<?= $url ?>" class="post-title"><?= $post['title'] ?></a></strong></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 post-header-line">
                            by <a href="#"><?= $post['author'] ?></a> | 
                            <?= $post['date_published'] ?> | <?= $post['shares'] ?> Shares | <?= $post['views'] ?> Views | 
                                Tags : 
                                <?php foreach( $post['tags'] as $tag ): ?>
                                <span class="label label-info"><?= $tag ?></span>
                                <?php endforeach; ?> 
                                
                        </div>
                    </div>
                    <div class="row post-content">
                        <div class="col-sm-3 ">
                            <a href="<?= $url ?>">
                                <img src="<?= shop_image( $post['image'] ) ?>" alt="" class="img-responsive">
                            </a>
                        </div>
                        <div class="col-sm-9">
                            <p>
                                <?= $post['extract'] ?>
                            </p>
                            <p>
                                <a class="btn btn-read-more" href="<?= $url ?>">Read more</a></p>
                        </div>
                    </div>
                </div>
            </div>
	        <?php endforeach; ?>
            <div class="text-center" ><?= get_advert('728x90'); ?></div>
            <h4 class="text-right">Recommended for you</h4>
			<div class="row" >
			<?php foreach( $popular_products as $product ):  ?>
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
        <div class="col-md-2">
            
        </div>
    </div>

	</section><!-- end main -->
	

<?php $this->load->theme( $theme , 'partials/footer'); ?>