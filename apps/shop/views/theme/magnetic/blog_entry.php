<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$popular_products = theme_popular_products( 3 );

$next_post = theme_next_blogpost( $post );
$prev_post = theme_previous_blogpost( $post );

$this->load->theme( $theme , 'partials/header'); 
?>
<style> .top{  background: transparent url("<?=  shop_image( $post['image'] ) ?>") no-repeat scroll 50% 50% / cover; }</style>
	<section class="main clearfix">

		<section class="top">	
			<div class="wrapper content_header clearfix">
				<div class="work_nav">
							
					<ul class="btn clearfix">
						<li><a href="<?= blog_post_url( $prev_post ) ?>" class="previous" data-title="Previous ( <?= $prev_post['title'] ?> )"></a></li>
						<li><a href="/blog" class="grid" data-title="Browse blog posts"></a></li>
						<li><a href="<?= blog_post_url( $next_post ) ?>" class="next" data-title="Next ( <?= $next_post['title'] ?> )"></a></li>
					</ul>							
					
				</div><!-- end work_nav -->
				<h1 class="title"><?= $post['title'] ?></h1>
			</div>		
		</section><!-- end top -->

		<section class="wrapper">
			<div class="content">
				<?php $this->load->sys_theme('share_blog_buttons'); ?>	
				<?=  $post['html'] ?>
				<br/>
				<!--share section -->

			</div><!-- end content -->
		</section>
	</section><!-- end main -->

<?php $this->load->theme( $theme , 'partials/footer'); ?>
<?php $this->load->sys_theme('share_blog_buttons_js'); ?>	