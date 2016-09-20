
    <section class="to-top">
        <div class="container">
            <div class="row">
                <div class="to-top-wrap">
                    <a href="#top" class="top"><i class="fa fa-angle-up"></i></a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="footer-links">
                        <ul class="footer-group">
                            <li><a href="/home/features">Features</a></li>
                            <li><a href="/home/pricing">Pricing</a></li>
                            <li><a href="/#signup">Sign up</a></li>
                            <li><a href="/home/login">Login to your shop</a></li>
                            <li><a href="/home/terms_and_conditions">Terms of use</a></li>
                            <li><a href="/home/privacy">Privacy Policy</a></li>
                        </ul>
                        <p>Copyright &copy; <?= date('Y') ?> <a href="/#">263Shop</a><br>
                        <a href="/home/contact_us">Contact Us</a> | Created with <span class="fa fa-heart pulse2"></span> by <a href="http://www.base2theory.com/">Base2Theory</a>.</p>
                    </div>
                </div>
                <div class="social-share">
                    <p>Share 263Shop with your friends</p>
                    <a href="https://twitter.com/263_shop" class="twitter-share"><i class="fa fa-twitter"></i></a> <a href="http://facebook.com/263shop" class="facebook-share"><i class="fa fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?= public_resource('www/sedna/js/vendor/jquery-1.11.2.min.js'); ?>"></script>
    <script src="<?= public_resource('www/sedna/js/jquery.fancybox.pack.js'); ?>"></script>
    <script src="<?= public_resource('www/sedna/js/vendor/bootstrap.min.js'); ?>"></script>
    <script src="<?= public_resource('www/sedna/bower_components/jquery-waypoints/lib/jquery.waypoints.min.js'); ?>"></script>
    
    <?php $this->load->view('sedna/partials/analytics'); ?>
</body>
</html>
