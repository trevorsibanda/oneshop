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
				<div class="col-md-9 cont span_2_of_3">
				  <div class="blog_grid">
		  	   	   <h2 class="post_title"><?= $post['title'] ?></h2>
		  	   	   <a href="<?= shop_image( $post['image'] ) ?>" ><img src="<?= shop_image( $post['image'] ) ?>" class="img-responsive" alt="" /></a>
		  	   	   <div>
		  	   	   <?= $post['html'] ?>
		  	   	   </div>
		  	   	   <ul class="links">
						<li><i class="date"> </i><span class="icon_text"><?= $post['date_published'] ?></span></li>
						<li><a href="#"><i class="admin"> </i><span class="icon_text"><?= $post['author'] ?></span></a></li>
					</ul>
					<ul class="links_middle">
						<li><i class="tags"> </i><span class="icon_text"><?php foreach($post['tags'] as $tag ): ?><label class="badge"><?= $tag ?></label><?php endforeach; ?></span></li>
						<li><i class="views"> </i><span class="icon_text"><?= $post['views'] ?></span></li>
						<li><i class="likes"> </i><span class="icon_text"><?= $post['shares'] ?></span></li>
					
					</ul>
					<br/>
		  		    <div class="row" >
		  		    	<div class="col-sm-4">
		  		    		<a href="<?= facebook_share() ?>" class="btn btn-primary btn-block" > Share Facebook</a>
		  		    	</div>
		  		    	<div class="col-sm-4">
		  		    		<a href="<?= whatsapp_share(Null , 'Read this article ! - ' . $post['title'] ) ?>" class="btn btn-success btn-block" >Share Whatsapp</a>
		  		    	</div>
		  		    	<div class="col-sm-4">
		  		    		<a href="<?= twitter_share() ?>" class="btn btn-info btn-block" >Share Twitter</a>
		  		    	</div>
		  		    </div>	
		  		     <div class="comments-area">
		  		        	<h3>Leave A Comment</h3>
							<form>
								<p>
									<label>Name</label>
									<span>*</span>
									<input type="text" value="">
								</p>
								<p>
									<label>Email</label>
									<span>*</span>
									<input type="text" value="">
								</p>
								<p>
									<label>Website</label>
									<input type="text" value="">
								</p>
								<p>
									<label>Subject</label>
									<span>*</span>
									<textarea></textarea>
								</p>
								<p>
									<input type="submit" value="Submit Comment">
								</p>
							</form>
					  </div>
		  	     </div>
		  	     
				</div>
				<div class="col-md-3 rsidebar span_1_of_3 services_list">
				    <ul>
				    	 <h3><span>Top Articles</span></h3>
				    	 <?php foreach ($top_posts as $top_post):	?>
				    	 <li><a href="<?= blog_post_url($top_post) ?>"><?= $post['title'] ?></a></li>
			             <?php endforeach; ?>
				    </ul>

					<ul class="archive-list">
						<h3><span>Most Shared</span></h3>
						<?php foreach ($top_shared_posts as $top_post):	?>
				    	 <li><a href="<?= blog_post_url($top_post) ?>"><?= $post['title'] ?></a></li>
			             <?php endforeach; ?>		                 
					</ul>				
				  </div>	
					<div class="clearfix"> </div>				  
		      </div>
			</div>
		</div>
	</div>