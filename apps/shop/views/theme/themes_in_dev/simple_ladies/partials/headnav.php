<!-- header -->
	<div class="header">
		<div class="container">
			<div class="logo">
				<a href="/"><img src="/assets/files/shop_images/<?= $shop['logo']['filename']; ?>" style="max-height: 64px;" class="img-responsive" alt="<?= $shop['name'] ?>"></a>
			</div>
			<div class="header-bottom">
				<div class="head-nav">
					<span class="menu"> </span>
						<ul class="cl-effect-3">
							<li class="active"><a href="/">Home</a></li>
							<li><a href="<?= shop_url('browse') ?>">Browse </a></li>
							<li><a href="<?= shop_url('featured') ?>">Featured Products</a></li>
							<li><a href="/blog">Blog</a></li>
							<li><a href="/about-us">About us</a></li>

							<div class="clearfix"></div>
						</ul>
				</div>
				<!-- script-for-nav -->
					<script>
						$( "span.menu" ).click(function() {
						  $( ".head-nav ul" ).slideToggle(300, function() {
							// Animation complete.
						  });
						});
					</script>
				<!-- script-for-nav -->

				<div class="search2">
					
					<form method="get" action="<?= shop_url('search') ?>">
							
						<input type="text"  name="q" placeholder="Search here...">
						<input type="submit" value="">

					</form>
					<br/>
				
				</div>
				
					<div class="clearfix"></div>
			</div>
		</div>
	</div>