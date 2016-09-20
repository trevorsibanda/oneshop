<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$popular_products = theme_popular_products( 2 );

$cart_items = theme_cart_items();

$posts = theme_most_commented_blog_posts( 3);
?>

<?php $this->load->theme(  $theme , 'partials/header'); ?>	

	<section class="main clearfix">

    
    
    <div class="row">
                
                <div class="col-md-12">
                    <h2 class="text-center">404 _ page Not Found</h2>
                </div>                               
    		</div>
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        
    </div>		
	</section>

<?php 

$this->load->theme(  $theme , 'partials/footer'); ?>		