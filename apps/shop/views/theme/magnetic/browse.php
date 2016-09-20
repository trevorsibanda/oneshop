<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$this->load->theme( $theme , 'partials/header');
?>
<section class="main clearfix">
	<h4>Browse products</h4>
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
					<option value="free_text" >No filter</option>
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
	<div class="row" >
	<?php for( $i = 0; $i < count($products ); ++$i ): ?>
		<?php if($i & $i%5 == 0 & account_can('adverts') ): ?>
			<div class="work"><?= get_advert('300x250'); ?></div>
		<?php endif; ?>
		<div class="work">
			<a href="<?=  product_url( $products[$i] ) ?>">
				<img src="<?=  random_product_image( $products[$i] ) ?>" class="media" alt=""/>
				<div class="caption">
					<div class="work_title">
						<h1><?= $products[$i]['name'] ?><br/><small><?= money( $products[$i]['price'] ) ?></small></h1>
					</div>
				</div>
			</a>
		</div>
	<?php endfor; ?>
	</div>
	<div class="clearfix" ></div>
	<br/>
	<div class="row" >
		<div class="col-md-4 col-sm-2"></div>
		<div class="col-md-4 col-xs-12 col-sm-8">
			<?php if( $page['page']+1 < $max_pages ): ?>
			<a href="<?= browse_url($page['filter'] , $page['page']+1 , $page['items_per_page'] , $page['sort'] ) ?>" class="btn btn-block btn-lg btn-default" >Next page</a>
			<?php endif;
			      if( $page['page']-1 > 0 ): ?>
			 <a href="<?= browse_url($page['filter'] , $page['page']-1 , $page['items_per_page'] , $page['sort'] ) ?>" class="btn btn-block btn-lg btn-default" >Previous page</a>
			 <?php endif; ?>     	
		</div>	
	</div>
	<div class="clearfix" ></div>
	<br/><br/>
<?php $this->load->theme( $theme , 'partials/footer'); ?>	
<?php $this->load->sys_theme('browse_page_js'); ?>