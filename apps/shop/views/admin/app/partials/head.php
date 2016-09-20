<!DOCTYPE html>
<html lang="en"  ng-app="app" moznomarginboxes mozdisallowselectionprint>
  <head  >
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $page['description'] ?>">
    <meta name="author" content="<?=  OS_SITE_NAME ?>">
    <meta name="keyword" content="<?=  OS_SITE_NAME ?> admin, login to oneshop">
    <link rel="shortcut icon" href="<?= public_img('favicon.png') ?>">

    <title  ><?=  OS_SITE_NAME ?> Admin</title>

    <!-- Bootstrap CSS -->    
    <link href="<?= admin_css('bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- bootstrap theme -->
    <link href="<?= admin_css('bootstrap-theme.css'); ?>" rel="stylesheet">
    <!--external css-->
    <!-- font icon -->
    <link href="<?= admin_css('font-awesome.min.css'); ?>" rel="stylesheet" />    

    <!-- Custom styles -->
    <link href="<?= admin_css('style.css'); ?>" rel="stylesheet">
    <link href="<?= admin_css('style-responsive.css'); ?>" rel="stylesheet" />
    <link href="<?= admin_css('loading-bar.min.css'); ?>" rel="stylesheet" />
    <link href="<?= admin_css('angular-wizard.min.css'); ?>" rel="stylesheet" />
    <link href="<?= admin_css('ng-tags-input.min.css'); ?>" rel="stylesheet" type="text/css" />

    
	

    <!-- Angular JS -->
    <script src="<?= admin_asset('angularjs/angular.min.js'); ?>" ></script>
    <script src="<?= admin_asset('angularjs/angular-route.min.js'); ?>" ></script>
    <script src="<?= admin_js('loading-bar.min.js'); ?>" ></script>
    <script src="<?= admin_js('ng-wysiwyg.js'); ?>" ></script>
    <script src="<?=  admin_js('angular-wizard.min.js') ?>" ></script>
    <script src="<?=  admin_js('http-auth-interceptor.js') ?>" ></script>
    <script src="<?=  admin_js('angular-webstorage.min.js') ?>" ></script>
    <script src="<?=  admin_js('angular-file-upload.min.js'); ?>"></script>
    <script src="<?=  admin_js('ng-tags-input.min.js'); ?>"></script>
    <script src="<?=  admin_js('Chart.min.js'); ?>"></script>
    <script src="<?=  admin_js('angular-chart.min.js'); ?>"></script>
    
    <!-- App JS -->
    <script src="<?= admin_js('ng-bootstrap.js') ?>" ></script> 
    <?php if( ENVIRONMENT == 'development' ): ?>
    
    <script src="<?= app_js('config.js'); ?>" ></script>
    <script src="<?= app_js('directives.js'); ?>" ></script>
    <script src="<?= app_js('routes.js'); ?>" ></script>    
    <script src="<?= app_js('services.js'); ?>" ></script>    
    <script src="<?= app_js('controllers.js'); ?>" ></script>
    <script src="<?= app_js('app.js'); ?>" ></script>
    
    <?php else: ?>
    <script src="<?= app_js('minified.js'); ?>" ></script>
    <?php endif; ?>
    
    
    

    
    <!-- App -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 -->
    <!--[if lt IE 9]>
      <script src="<?= admin_js('html5shiv.js'); ?>"></script>
      <script src="<?= admin_js('respond.min.js'); ?>"></script>
      <script src="<?= admin_js('lte-ie7.js'); ?>"></script>
    <![endif]-->
  </head>