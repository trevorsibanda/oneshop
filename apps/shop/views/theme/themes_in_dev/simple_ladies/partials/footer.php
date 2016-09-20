<!-- footer -->
<div class="footer">
	<div class="container">
		<div class="col-md-4 social">
			<h4>Feel free to contact us!</h4>
			<p><?= nl2br($shop['address']) ?> </p>
						<ul>
							<li><?= $shop['telephone'] ?></li>
							<li><a href="#"><i class="facebok"> </i></a></li>
							<li><a href="#"><i class="twiter"> </i></a></li>
							<li><a href="#"><i class="rss"> </i></a></li>
							<div class="clearfix"></div>	
						</ul>
		</div>
		<div class="col-md-4 information">
			<h4>About us</h4>
			<p><?= $shop['short_description'] ?></p>
			<button class="btn btn-block btn-block">About Us</button>
		</div>
		<div class="col-md-4 searby">
			<h4>Featured products</h4>
				<div class="col-md-12 by1">
					<?php foreach ($featured_products as $product): ?>
					<li><a href="#"><?= $product['name'] ?><span class="pull-right" ><b><?= money($product['price']) ?></b> - <?= $product['stock_left']  ?> left</span></a></li>

					<?php endforeach; ?>
					
				</div>
				
					<div class="clearfix"> </div>
		</div>
			<div class="clearfix"></div>
			<div class="bottom">
					<p>Copyrights © <?= date('Y') . ' - '. $shop['name'] ?>  | Powered by <a href="http://www.oneshop.co.zw">OneShop</a></p>
				</div>
	</div>
</div>
<!-- footer -->
</body>
</html>