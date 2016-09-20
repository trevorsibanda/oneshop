<div id="featured">
	<div class="title">
		<h2>From our blog</h2>
		<span class="byline">Articles written by me and some friends.</span>
	</div>
	<ul class="style1">
		<?php foreach ($posts as $post ): $post_url = blog_post_url( $post ); ?>
		<li class="first">
			<div class="row" >
				<div class="col-sm-3" >
					<a href="<?= $post_url ?>" >
					<img src="<?= shop_image($post['image'] ) ?>"  class="img-responsive" />
					</a>
					
				</div>
				<div class="col-md-9" >
					<legend><a href="<?= $post_url ?>" ><?= $post['title'] ?></a></legend>
					<p><?= $post['extract'] ?></p>
					
					<small><b>Published <?= $post['date_published'] ?> by <?= $post['author'] ?></b></small>
				</div>
				<div class="col-xs-12" >
					
				</div>
			</div>
			
		</li>
		<?php endforeach; ?>
		
	</ul>
</div>
