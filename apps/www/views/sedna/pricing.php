<?php
    $this->load->view('sedna/partials/head' );
?>
<body id="top">
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <section class="">
        <section class="navigation">
            <header>
                <div class="header-content">
                    <div class="logo"><a href="/"><img src="<?= public_resource('www/sedna/img/logo.png'); ?>" style="max-height: 30px;" alt="Sedna logo"></a></div>
                    <div class="header-nav">
                        <nav>
                            <ul class="primary-nav">
                                <li><a href="/#features">Features</a></li>
                                <li><a href="/#pricing-table">Pricing</a></li>
                                <li><a href="/#demos">Demos</a></li>
                                
                                <li><a href="/#blog">Blog</a></li>
                                
                                <li><a href="/#signup">Get started</a></li>
                            </ul>
                            <ul class="member-actions">
                                <li><a href="/#login" class="login">Log in</a></li>
                                <li><a href="/#signup" class="btn-white btn-small">Sign up</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="navicon">
                        <a class="nav-toggle" href="/#"><span></span></a>
                    </div>
                </div>
            </header>
        </section>
       </section>
       <section class="section-padding"></section>
    <?php $this->load->view('sedna/partials/pricing-section'); ?>
    <?php $this->load->view('sedna/partials/signup-section'); ?>

<?php $this->load->view('sedna/partials/footer'); ?>    