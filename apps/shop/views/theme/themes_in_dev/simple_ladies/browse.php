<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
?>	
<body>
<?php $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>

<div class="product-model">	 
	 <div class="container">
		 <h2>OUR PRODUCTS</h2>
		 <ol class="breadcrumb">
		  <li><a href="index.html">Home</a></li>
		  <li class="active">Men</li>
		 </ol>
		
		 <div class="col-md-3" >
		 	<label>Sort By</label>
		 	<select class="form-control" id="sort_by" >
		 		<?php foreach( get_sort_filters() as $filter ): ?>
				<option value="<?= $filter['value'] ?>" ><?= $filter['name'] ?></option>
		        <?php endforeach; ?>	
		 	</select>
		 	<label>Items per page</label>
		 	<select class="form-control" id="items_page" >
		 		<option >5</option>
	            <option  selected >9</option>
                <option >10</option>
                <option >15</option>
                <option >20</option>
                <option>25</option>
                <option>50</option>
		 	</select>
		 	<label>Go to Page</label>
		 	<select class="form-control" id="page_nav" >
		 		<option>1</option>
		 	</select>
		 	<br/>
		 	<a href="#products" class="btn btn-block btn-default visible-xs visible-sm" >Jump to Products</a>
		 	<br/>
		 	<div class="panel panel-default" >
		        <div class="panel-heading">
		        	<h3>My Shopping Cart</h3>
		    	</div>
		    	<div class="panel-body">
			    	<ul class="list-unstyled" >
				    	<?php foreach ($cart as $cart_item): ?>
				    	<li > <?= $cart_item['qty'] ?> x <a href="<?= product_url( $cart_item['product'] ); ?>" ><?= $cart_item['product']['name'] ?></a><span class="pull-right"><?= money($cart_item['subtotal']) ?></span></li>
				    	<li><br/></li>
				    	<?php endforeach; ?>
			        	<li><br/></li>
			        	<li ><br/>Total:  <span class="pull-right"><?= money( $cart_total) ?></span></li>
			        </ul>
			        <a href="/cart/checkout" class="btn btn-block btn-info" >Checkout</a>
		    	</div>
			</div>

		 </div>				
		 <div class="col-md-9 product-model-sec" id="products">
		 	<?php $this->load->theme($theme , 'partials/products' , array('products' => $products) ); ?>
		 </div>
	 </div>
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
