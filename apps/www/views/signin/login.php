<!DOCTYPE html>
<html>
<head>
<title>Create your shop -OneSHOP</title>
<link href="<?=  public_css('bootstrap.css'); ?>" rel='stylesheet' type='text/css' />
<!-- Landing page -->
<link href="<?= public_css('landing-style.css'); ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?= public_css('font-awesome.css'); ?>" rel="stylesheet" type="text/css" media="all" />

<!-- Custom Theme files -->
<script src="<?= public_js('jquery.min.js'); ?>"></script>
<!-- Custom Theme files -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Pie Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--webfont-->
<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Lato:100,300,400,700,900,100italic,300italic,400italic,700italic,900italic' rel='stylesheet' type='text/css'>
<!-- Owl Stylesheets -->
<link rel="stylesheet" href="<?= public_css('flexslider.css'); ?>" type="text/css" media="screen" />
 <script type="text/javascript" src="<?= public_js('move-top.js'); ?>"></script>
<script type="text/javascript" src="<?= public_js('easing.js'); ?>"></script>
<script type="text/javascript">
            jQuery(document).ready(function($) {
                $(".scroll").click(function(event){     
                    event.preventDefault();
                    $('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
                });
            });
        </script>
<!--Animation-->
<script src="<?= public_js('wow.min.js'); ?>"></script>
<link href="<?= public_css('animate.css'); ?>" rel='stylesheet' type='text/css' />
<script>
    new WOW().init();
</script>
</head>
<body>
    <!-- header-section-starts -->
    <div class="header" id="home">
        <div class="container">
            <div class="logo wow fadeInRight" data-wow-delay="0.4s">
                <a href="/#home"><img src="<?= public_img('landing/logo.png'); ?>" alt="" /></a>
            </div>
            <span class="menu"></span>
            <div class="top-menu fixed wow fadeInLeft" data-wow-delay="0.4s">
                <ul>
                    <li><a class="active hvr-shutter-out-horizontal" href="/auth/login">Login</a></li>
                    <li><a class=" hvr-shutter-out-horizontal" href="/create-shop">Create your online shop</a></li>
                </ul>
            </div>
                <!-- script for menu -->
                <script>
                $( "span.menu" ).click(function() {
                  $( ".top-menu" ).slideToggle( "slow", function() {
                    // Animation complete.
                  });
                });
            </script>
            <!-- script for menu -->

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="container" >

        
            <div class="row">
                <div class="col-md-8 col-md-offset-2" >   
                    <div  class="wow fadeInRight" style="color:#709dca;" >
                        <?php if( empty($site) ):  ?>
                        <h4 class="text-center color_bold_black">Login to access your store</h4>
                        <?php else: ?>
                        <h3 class="text-center color_bold_black"><?= $site['name'] . ' login' ?></h3>    
                        <?php endif; ?>
                        <form class="login-form" style="background-color: #fff;" action="/auth/login" method="POST">        
                            <div class="login-wrap">
                                
                                <p class="login-img"><i class="fa fa-lock"></i></p>
                                <?= validation_errors() ?>
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                  <input type="email" required class="form-control" name="email" placeholder="Your email address" autofocus>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="password" required class="form-control" placeholder="Your Password">
                                </div>
                                
                                <button class="btn btn-primary btn-lg  btn-block" type="submit">Login</button>
                                <?php if( ! empty($site) ): ?>
                                <br/>    
                                <a href="<?= $site['url'] ?>" style="background-color: purple;" class="btn btn-info btn-login btn-block" >Back to <?= $site['name'] ?></a>
                                <?php endif; ?>
                                <a href="/create-shop?referer=login" style="background-color: green;" class="btn  btn-block" >Create your online shop</a>
                                <label class="checkbox">
                                    
                                    <span class="pull-right"> <a href="#"> Forgot Password?</a></span>
                                </label>
                            </div>
                            <input type="hidden" name="challenge" value="<?= $form_challenge ?>" />
                            <input type="hidden" name="url" value="<?= $site['url'] ?>" />
                        </form>
                        <br/>

                    </div>
                    <div class="clearfix"></div>
                     
                </div>
                
        </div>
    </div>