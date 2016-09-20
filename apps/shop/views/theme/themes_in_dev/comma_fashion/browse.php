<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content">
  <div class="content_box">
	<div class="men">
	  <div class="col-md-3 sidebar">
	  	<div class="block block-layered-nav">
		    <div class="block-title">
		        <strong><span>Shop By</span></strong>
		    </div>
    <div class="block-content">
                                    
            <dl id="narrow-by-list">
<dt class="odd">Prices</dt>
                    <dd class="odd">
<ol>
	<?php
		$ranges = get_product_price_ranges(  $products   ,5);
		foreach( $ranges as $range ):
	?>
    <li>
        <a href="<?= browse_url('price_range[' . $range['low'] . '-' . $range['high'] . ']' , $page['page'] , $page['items_per_page'] , $page['sort'] ) ?>">
			<span class="price1">&nbsp;<?= money($range['low']); ?></span> - 
			<span class="price1">&nbsp;<?= money($range['high']) ?></span>
			<span class="pull-right">(<?= $range['count'] ?>)</span>
		</a>               
    </li>
    <?php endforeach; ?>
</ol>
</dd>
<dt class="even">Categories</dt>
                    <dd class="even">
<ol>
    <?php foreach( $product_categories as $category ): ?>
	<li>
        <a href="<?= category_url( $category ); ?>"><?= $category['name'] ?><span class="pull-right">(2)</span></a>
    </li>
	<?php endforeach; ?>
   
</ol>
</dd>
	</dl>
           
            </div>
</div>
<div class="block block-cart">
        <div class="block-title">
        <strong><span>My Shopping Cart</span></strong>
    </div>
    <div class="block-content">
    	<ul class="list-unstyled" >
	    	<?php foreach ($cart as $cart_item): ?>
	    	<li > <?= $cart_item['qty'] ?> x <a href="<?= product_url( $cart_item['product'] ); ?>" ><?= $cart_item['product']['name'] ?></a><span class="pull-right"><?= money($cart_item['subtotal']) ?></span></li>
	    	<?php endforeach; ?>
        	<li><br/></li>
        	<li ><br/>Total:  <span class="pull-right"><?= money( $cart_total) ?></span></li>
        </ul>
        <a href="/cart/checkout" class="btn btn-block btn-info" >Checkout</a>
    </div>
</div>
</div>
<div class="col-md-9">
	<div class="mens-toolbar">
          <div class="sort">
               	<div class="sort-by">
		            <label>Sort By</label>
		            <select id="sort_by">
		            <?php foreach( get_sort_filters() as $filter ): ?>
					<option value="<?= $filter['value'] ?>" ><?= $filter['name'] ?></option>
		            <?php endforeach; ?>
					</select>
		        </div>
    		</div>
	        <div class="pager">   
	           <div class="limiter visible-desktop">
	            <label>Show</label>
	            <select id="items_page" >
	            				<option >5</option>
	                            <option  selected >9</option>
	                            <option >10</option>
	                            <option >15</option>
	                            <option >20</option>
	                            <option>25</option>
	                            <option>50</option>
	                        </select> per page        
	             </div>
	       		<ul class="dc_pagination dc_paginationA dc_paginationA06">
				    <li><a href="#" class="previous">Pages</a></li>
				    
					<li><a href="#">1</a></li>
				    <li><a href="#">2</a></li>
					<li><a href="" >3</a></li>
			  	</ul>
		   		<div class="clearfix"></div>
	    	</div>
     	    <div class="clearfix"></div>
	     </div>
	          
			  <div class="row">
	          	 <?php $i = 0; foreach( $products as $product ): $i++;?>
				 <div class="col_1_of_single1 span_1_of_single1 col-md-4 ">
	          	    <a href="<?= product_url( $product ); ?>">
				     <img src="<?= product_image( random_element($product['images']) );  ?>" class="img-responsive" alt=""/>
				     <h3><?= $product['name'] ?></h3>
				   	 <p><?= $product['brand'] ?></p>
				   	 <h4><?= money($product['price']) ?></h4>

			         </a>
			         <input type="number" name=""  value="1" />
				   	 <button class="btn btn-default btn-block" >Add to Cart</button>  
				  </div>
				  <?php if($i == 3): ?>
				<div class="clearfix"></div>
				</div>
				<div class="row" >
				  <?php endif; endforeach; ?>
				  
				  <div class="clearfix"></div>
			  </div>
			</div>
          <div class="clearfix"> </div>
    </div>
    <script>
    	var current_page = <?= $page['page'] ?>;
    	var current_filter = "<?= $page['filter']?>";
    	var current_items_per_page = <?= $page['items_per_page'] ?>;
    	var current_sort = "<?= $page['sort'] ?>";

    	function browse_to( $_filter , $_page , $_items_per_page , $_sort )
    	{
    		var url = '/shop/browse/' + $_filter + '?page=' + $_page + '&items_per_page=' + $_items_per_page + "&sort=" + $_sort;
    		window.location = url;	
    	} 


    	$('#items_page').val( current_items_per_page );
    	$('#sort_by').val( current_sort );
    	//Change Sort
    	$('#sort_by').on('change' , function(evt)
    	{
    		browse_to( current_filter , current_page , current_items_per_page , $(this).val() );
    	});

    	//change items per page
    	$('#items_page').on('change' , function(evt)
    	{
    		browse_to( current_filter , current_page ,  $(this).val() , current_sort );
    	});



    </script>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	    