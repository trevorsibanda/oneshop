<!DOCTYPE HTML>
<html>
  <head>
    <title><?=  $page['title'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<?=  $page['keywords'] ?>" />
    <!-- Begin CSS -->
    <link href="<?= theme_css( $theme , 'bootstrap.css'); ?>" rel="stylesheet" type="text/css" media="all">
    <link href="<?= theme_css( $theme , 'style.css'); ?>" rel="stylesheet" type="text/css" media="all" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?= theme_css( $theme , 'flexslider.css'); ?>" type="text/css" media="screen" />
    <!-- End CSS -->
    <!-- Begin JS -->
    <script src="<?= theme_js( $theme , 'jquery.min.js'); ?>"></script>
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <script src="<?=  theme_js( $theme , 'responsiveslides.min.js'); ?>"></script>
    <!-- End JS -->
   
    <script type="text/javascript">
		
    //slider
    $(function () {
      $("#slider").responsiveSlides({
      	auto: true,
      	nav: true,
      	speed: 500,
        namespace: "callbacks",
        pager: true,
      });
    });
    </script>
  </head>