<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'browse' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 
	
?>
			
				

				<h2 class="text-center" >Browse my store</h2>
				<div class="row " style=" background-color: #337AB7; color: white; padding-bottom: 5px;" >
				<div class="browse-bar"  >
					
					<div class="col-md-3 col-sm-6" >
						<label>Sort by</label>
						<select class="form-control"  id="sort_by" >
						<?php foreach( get_sort_filters() as $filter ): ?>
							<option value="<?= $filter['value'] ?>" ><?= $filter['name'] ?></option>
					        <?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6" >
						<label>Filter</label>
						<select class="form-control" id="filter" >
							<option value="all" >No filter</option>
							<?php foreach($ranges as $range ): ?>
							<option value="price_range[<?= $range['low'] . '-' . $range['high'] ?>]"><?= money($range['low']) . ' - ' . money($range['high']) . '   (' . $range['count'] . ' )' ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6" >
						<label>Go to Page</label>
						<select class="form-control" id="page_number" >
							<?php for($x = 1; $x < $max_pages ; $x++ ): ?>
							<option value=<?= $x ?> ><?= $x ?></option>
							<?php endfor; ?>
						</select>
					</div>
					<div class="col-md-3 col-sm-6" >
						<label>Items per page</label>
						<select class="form-control" id="items_page" >
							<option>9</option>
							<option>12</option>
							<option>15</option>
						</select>
					</div>
				</div>
				</div>
				<br/>
<?php $this->load->theme($theme , 'partials/product_list'); ?>				
				<div class="row" >
					<div class="col-md-4 col-sm-2"></div>
					<div class="col-md-4 col-xs-12 col-sm-8">
						<?php if( $page['page']+1 <= $max_pages ): ?>
						<a href="<?= browse_url($page['filter'] , $page['page']+1 , $page['items_per_page'] , $page['sort'] ) ?>" class="btn btn-block btn-lg btn-success" >Next page</a>
						<?php endif;
						      if( $page['page']-1 > 0 ): ?>
						 <a href="<?= browse_url($page['filter'] , $page['page']-1 , $page['items_per_page'] , $page['sort'] ) ?>" class="btn btn-block btn-lg btn-success" >Previous page</a>
						 <?php endif; ?>     	
					</div>	
				</div>

				
<?php

$this->load->theme($theme , 'partials/footer' );
$this->load->sys_theme('browse_page_js');
?>
	
