<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?= include_system_header(); ?>
<!--
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
-->
<link href="<?=  theme_css($theme , 'default.css?'); ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?=  theme_css($theme , 'bootstrap.css'); ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?=  theme_css($theme , 'fonts.css'); ?>" rel="stylesheet" type="text/css" media="all" />

<!--[if IE 6]><link href="<?=  theme_css($theme , 'default_ie6.css'); ?>" rel="stylesheet" type="text/css" /><![endif]-->
<?=  theme_custom_css($shop , $theme); //custom theme css ?>
</head>
<body>
<div  class="container-fluid">
	<div class="row" >
		<div id="page" style="position: fixed;" class="col-md-3 col-sm-3 visible-md visible-lg visible-sm" id="header" >
			<div id="logo">
				<img src="<?= shop_image($shop['logo'] ); ?>" class="img-responsive" style="max-width: 128px;" alt="" />
				<h1><a href="<?= shop_url('') ?>"><?= $shop['name'] ?></a></h1>
				<span><?= $shop['tagline'] ?></span>
			</div>
			<div id="menu">
				<ul>
					<li <?= $curr_page == 'home' ? 'class="current_page_item"' : ''  ?> ><a href="<?= shop_url('') ?>" accesskey="1" title="">Homepage</a></li>
					<li <?= $curr_page == 'browse' ? 'class="current_page_item"' : ''  ?>><a href="<?= shop_url('browse') ?>" accesskey="2" title="">Browse store</a></li>
					<li <?= $curr_page == 'blog' ? 'class="current_page_item"' : ''  ?>><a href="/blog" accesskey="3" title="">My Blog</a></li>
					<li <?= $curr_page == 'about' ? 'class="current_page_item"' : ''  ?>><a href="/about-us" accesskey="4" title="">About Me</a></li>
					<li <?= $curr_page == 'checkout' ? 'class="current_page_item"' : ''  ?>><a href="/cart/checkout" accesskey="5" title="">Checkout <?= money( $cart_total ) ?></a></li>
				</ul>
			
			</div>
		</div>
		<div class="col-md-9" id="main">
			<div class="cd-container" >
				<div class="shopping-cart">
					<a title="Checkout" href="/cart/checkout">
						
						<img src="<?=  theme_img($theme , 'cd-cart.svg'); ?>" style="" />

					</a>

				</div>
				
