	<div class="footer_top">
	  <div class="span_of_4">
		<div class="col-md-3 box_4">
			<h4>Shop</h4>
			<ul class="f_nav">
				<li><a href="#">new arrivals</a></li>
				<li><a href="#">men</a></li>
				<li><a href="#">women</a></li>
				<li><a href="#">accessories</a></li>
				<li><a href="#">kids</a></li>
				<li><a href="#">brands</a></li>
				<li><a href="#">trends</a></li>
				<li><a href="#">sale</a></li>
				<li><a href="#">style videos</a></li>
			</ul>	
		</div>
		<div class="col-md-3 box_4">
			<h4>help</h4>
			<ul class="f_nav">
				<li><a href="#">frequently asked  questions</a></li>
				<li><a href="#">men</a></li>
				<li><a href="#">women</a></li>
				<li><a href="#">accessories</a></li>
				<li><a href="#">kids</a></li>
				<li><a href="#">brands</a></li>
			</ul>				
		</div>
		<div class="col-md-3 box_4">
			<h4>Featured Products</h4>
			<ul class="f_nav">
				<?php foreach( $featured_products as $product ): ?>
				<li><a href="<?= product_url( $product ); ?>"><?= $product['name'] ?></a></li>
				<?php endforeach; ?>
			</ul>			
		</div>
		<div class="col-md-3 box_4">
			<h4>One Shop</h4>
			<ul class="f_nav">
				
				<li><a href="<?= oneshop_help('what'); ?>">What is OneShop?</a></li>
				<li><a href="<?= oneshop_help('how_do_i_pay'); ?>">How do I pay ?</a></li>
				<li><a href="<?= oneshop_help('payment_safe'); ?>">Is my payment safe?</a></li>
				<li><hr/></li>
				<li><a href="<?= oneshop_help('my_account') ?>">My OneShop Account</a></li>
				<li><a href="<?= oneshop_help('getstarted'); ?>">Create OneShop account</a></li>
				
				<li><a href="<?= oneshop_help('getstarted') ?>">Create your own Store</a></li>
				
			</ul>
		</div>
		<div class="clearfix"></div>
	</div>
		<!-- start span_of_2 -->
		<div class="span_of_2">
			<div class="span1_of_2">
				<h5>need help? <a href="/shop/contact-us">contact us <span> &gt;</span> </a> </h5>
				<p>(or) Call us: <?=  $settings['contact_phone'] ?></p>
			</div>
			<div class="span1_of_2">
				<h5>follow us </h5>
				<div class="social-icons">
					     <ul>
					        <li><a href="<?=  $settings['facebook_page_url'] ?>" target="_blank"></a></li>
					        <li><a href="<?=  $settings['twitter_url'] ?>" target="_blank"></a></li>
					        
						</ul>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="copy">
		   <p>© <?= date('Y'); ?> <?= $shop['trading_name'] ?> Powered by <a href="http://oneshop.co.zw/">OneShop</a> </p>
		</div>
     </div>
   </div>
</div>	

							  
							  <script type="text/javascript">
								$(function(){
								  SyntaxHighlighter.all();
								});
								$(window).load(function(){
								  $('.flexslider').flexslider({
									animation: "slide",
									start: function(slider){
									  $('body').removeClass('loading');
									}
								  });
								});
							  </script>
</body>
</html>		
