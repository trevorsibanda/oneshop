<?php
    $this->load->view('sedna/partials/head' );
?>
<body id="top">
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    
    <div class="text-center" >
        <h2>263Shop</h2>
        <br/>
    </div>
    <section class="sign-up  text-center" id="login">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <h3>Sign in to access your store</h3>
                    <p>Enter your email address and password to access your store.</p>
                    <form class="signup-form" action="/action/login" method="POST" role="form">
                        <div class="form-input-group">
                            <i class="fa fa-envelope"></i><input type="email" class="" placeholder="Enter your email" name="user_email" required>
                        </div>
                        <div class="form-input-group">
                            <i class="fa fa-lock"></i><input type="password" class="" placeholder="Enter your password" name="user_password" required>
                        </div>
                        <input type="hidden" name="csrf_token" value="<?= $csrf_login_token ?>" />
                        <button type="submit" class="btn-fill sign-up-btn">Login</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
    <br/><br/>
    <section class="to-top">
        <div class="container">
            <div class="row">
                <div class="to-top-wrap">
                    <a href="#top" class="top"><i class="fa fa-angle-up"></i></a>
                </div>
            </div>
        </div>
    </section>
<?php $this->load->view('sedna/partials/footer' ); ?>    