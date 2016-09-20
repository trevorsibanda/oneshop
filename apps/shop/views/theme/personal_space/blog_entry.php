<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'blog' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 
	
?>
<div class="title">
	<h2><?= $post['title'] ?></h2>
	<img src="<?=  shop_image( $post['image'] ) ?>" class="img-responsive" alt="<?= $post['image']['meta'] ?>" />
	<span class="byline"><?= $post['date_published'] . ' by ' . $post['author']  ?></span>
</div>
<div>
<?php $this->load->sys_theme('share_blog_buttons'); ?>

<?= $post['html'] ?>	
</div>
<small>
<br/>
<ul class="list-inline">
	<li><i class="tags"> </i><span class="icon_text"><?php foreach($post['tags'] as $tag ): ?><label class="badge"><?= $tag ?></label><?php endforeach; ?></span></li>
	<li><i class="views"> </i><span class="icon_text"><?= $post['views'] ?> Views</span></li>
	<li><i class="likes"> </i><span class="icon_text"><?= $post['shares'] ?> Shares</span></li>

</ul>
</small>
	
<?php
$this->load->theme($theme , 'partials/footer' );
$this->load->sys_theme('share_blog_buttons_js');
?>

