<header>
		<div class="logo">
			<a href="/"><img src="<?=  shop_image( $shop['logo'] ) ?>" title="<?= $shop['name'] ?>" alt="<?= $shop['name'] ?>"/></a>
		</div><!-- end logo -->

		<div id="menu_icon"></div>
		<nav>
			<ul>
				<li><a href="/" class="selected">Home</a></li>
				<li><a href="<?= shop_url('browse') ?>">Browse products</a></li>
				<li><a href="/blog">Our blog</a></li>
				<li><a href="/about-us">About Us</a></li>
				<li><a href="/cart/checkout">Checkout <?= money( theme_cart_total() ) ?></a></li>
			</ul>
		</nav><!-- end navigation menu -->

		<div class="footer clearfix">
			<ul class="social clearfix">
				<li><a href="<?= $shop['contact']['facebook_page'] ?>" class="fb" data-title="Facebook"></a></li>
				<li><a href="<?= $shop['contact']['linkedin_url'] ?>" class="linkedin" data-title="Linked In"></a></li>
				<li><a href="https://www.twitter.com/<?= $shop['contact']['twitter_handle'] ?>" class="twitter" data-title="Twitter"></a></li>
				
				<li><a href="/shop/rss_feed" class="rss" data-title="RSS"></a></li>
			</ul><!-- end social -->

			<div class="rights">
				<p>Copyright © <?=  date('Y') . ' ' . $shop['name'] ?></p>
				<p>Powered by <a href="<?= OS_BASE_SITE ?>" target="_blank" >263Shop</a></p>
			</div><!-- end rights -->
		</div ><!-- end footer -->
	</header><!-- end header -->