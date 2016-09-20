<!DOCTYPE HTML>
<html>
	<head>
	<title><?=  $page['title'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="<?= $page['keywords']  ?>" />
	<meta name="description" content="<?= $page['description'] ?>" />
	
	<link href="<?= theme_css( $theme , 'bootstrap.css' ); ?>" rel='stylesheet' type='text/css' />
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<!-- Custom Theme files -->
	<link href="<?= theme_css( $theme , 'style.css' ); ?>" rel='stylesheet' type='text/css' />
	<!-- Custom Theme files -->
	<!--webfont-->
	<link href='http://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="<?= theme_js( $theme , 'jquery-1.11.1.min.js' ); ?>"></script>
	<!-- start menu -->
	<link href="<?= theme_css( $theme , 'flexslider.css'); ?>" rel='stylesheet' type='text/css' />
	<link href="<?= theme_css( $theme , 'megamenu.css' ); ?>" rel="stylesheet" type="text/css" media="all" />
	<script type="text/javascript" src="<?= theme_js( $theme , 'megamenu.js' ); ?>"></script>
	<script defer src="<?= theme_js($theme , 'jquery.flexslider.js'); ?>"></script>
	<script>$(document).ready(function(){$(".megamenu").megamenu();});</script>
	</head>
