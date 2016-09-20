<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>
<div class="main">
		<div class="container">
			<div class="content">	 	 
		 		<div class="section group">
					<div class="col-md-9 cont ">
						<?php foreach ($posts as $post ): $post_url = blog_post_url( $post ); ?>
						<div class="blog_grid">
							<h2 class="post_title"><a href="<?= $post_url ?>"><?= $post['title'] ?></a></h2>
							<a href="<?= $post_url ?>"><img style="max-height: 480px;width:100%;"src="<?= shop_image( $post['image'] ) ?>" class="img-responsive" alt="" /></a>
							<p><?= $post['extract'] ?></p>
								<div class="button1"><a class="more" href="<?= $post_url ?>">Read More</a></div>
							<ul class="links">
								<li><i class="date"> </i><span class="icon_text"><?= $post['date_published'] ?></span></li>
								<li><a href="#"><i class="admin"> </i><span class="icon_text"><?= $post['author'] ?></span></a></li>
							</ul>
							<ul class="links_middle">
								<li><i class="tags"> </i><span class="icon_text"><?php foreach($post['tags'] as $tag ): ?><label class="badge"><?= $tag ?></label><?php endforeach; ?></span></li>
								<li><i class="views"> </i><span class="icon_text"><?= $post['views'] ?></span></li>
								<li><i class="likes"> </i><span class="icon_text"><?= $post['shares'] ?></span></li>
							
							</ul>
							
						</div>
						<?php endforeach; ?>
						<div class="pagination pagination__posts">
							<ul>
								<li class="first"><a href="#">First</a></li>
								<li class="prev"><a href="#">Prev</a></li>
								<li class="active"><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">4</a></li>
								<li><a href="#">5</a></li>
								<li class="next"><a href="#">Next</a></li>
								<li class="last"><a href="#">Last</a></li>
									<div class="clearfix"></div>	
							</ul>
						</div>
					</div>
					<div class="col-md-3 services_list">
						<ul>
							<h3>Most shared posts</h3>
							<?php foreach ($top_shared_posts as $top_post):	?>
				    	 	<li><a href="<?= blog_post_url($top_post) ?>"><?= $post['title'] ?></a></li>
			             	<?php endforeach; ?>
						</ul>
						<ul class="archive-list">
							<h3>Popular Tags</h3>
							<?php foreach ($top_tags as $tag):	?>
				    	 	<li><a href="<?= blog_tag_url($tag) ?>"><?= ucwords( $tag ) ?></a></li>
			             	<?php endforeach; ?>		                 
					  </ul>				
					</div>
						<div class="clearfix"></div>					  
				</div>
			</div>
		</div>
	</div>