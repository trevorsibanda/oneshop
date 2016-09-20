<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'blog' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 
	
?>
			
				<?php $this->load->theme($theme , 'partials/blog_list'); ?>				
				<?php if($has_next_page): ?>
				<div class="row" >
					<div class="col-md-4 col-sm-2"></div>
					<div class="col-md-4 col-xs-12 col-sm-8">
						 <a href="/blog/browse/<?= $page['page']+1; ?>" class="btn btn-block btn-lg btn-success" >Next page</a>
						   	
					</div>	
				</div>
				<?php endif; ?>

				
<?php
$this->load->theme($theme , 'partials/footer' );
?>