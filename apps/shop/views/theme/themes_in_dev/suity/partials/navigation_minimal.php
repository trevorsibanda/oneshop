<div class="sales">
    <div class="container">
	  <div class="header_top">
   		<div class="logo">
			<a href="<?= shop_url() ?>"><img class="img-responsive" style="max-height: 64px;" src="<?= shop_image($shop['logo']) ?>" alt="<?=  $shop['name'] ?>"/></a>
		</div>	
		<div class="header-bottom-right">
	       	<?php $this->load->theme($theme,'partials/nav_cart'); ?>
              <div class="clearfix"></div>
          </div>
		<?php $this->load->theme($theme ,'partials/nav_menu'); ?> 	
			         <div class="clearfix"></div>		
		      </div>
		</div>	
	    
                   	           	      
</div>
</div>
