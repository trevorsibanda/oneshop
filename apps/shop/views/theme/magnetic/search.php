<?php 

$this->load->theme( $theme , 'partials/head'); 
$product_categories = theme_product_categories( 3);

$this->load->theme( $theme , 'partials/header');
?>
<section class="main clearfix">
	<h4>Browse products</h4>
	
	<form class="row" method="get" action="<?= shop_url('search') ?>"  >
		
		<div class="col-md-9 col-sm-6" >
			<div class="input-group" >
				<input type="search" name="q"  class="form-control " placeholder="Search for products , categories here" value="<?= $query ?>" />
				<span class="input-group-addon" ></span>
			</div>
		</div>
		<div class="col-md-3 col-sm-6" >
			<button class="btn btn-default btn-block" type="submit" >Search</button>
		</div>
	</form>
	<hr style="border:  solid #337AB7 2px;"/>
	
	<div class="row" >	
		<div class="col-md-6 col-sm-6" >
			<strong>Found <?= $total_results_count ?> results for query</strong>
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
			<a href="/shop/search?q=<?= urlencode($query) ?>&page=<?= $page['page']+1 ?>" class="btn btn-block btn-lg btn-default" >Next page</a>
			<?php endif;
			      if( $page['page']-1 > 0 ): ?>
			 <a href="/shop/search?q=<?= urlencode($query) ?>&page=<?= $page['page']-1 ?>" class="btn btn-block btn-lg btn-default" >Previous page</a>
			 <?php endif; ?>     	
		</div>	
	</div>
	<div class="clearfix" ></div>
	<br/><br/>
<?php $this->load->theme( $theme , 'partials/footer'); ?>	
<?php $this->load->sys_theme('browse_page_js'); ?>