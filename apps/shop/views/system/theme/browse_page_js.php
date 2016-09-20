<script type="text/javascript" >	
	var current_page = <?= $page['page'] ?>;
    	var current_filter = "<?= urldecode($page['filter']) ?>";
    	var current_items_per_page = <?= $page['items_per_page'] ?>;
    	var current_sort = "<?= $page['sort'] ?>";

    	function browse_to( $_filter , $_page , $_items_per_page , $_sort )
    	{
    		var url = '/shop/browse/' + $_filter + '?page=' + $_page + '&items_per_page=' + $_items_per_page + "&sort=" + $_sort;
    		window.location = url;	
    	} 

	$('#filter').val( current_filter );
    	$('#items_page').val( current_items_per_page );
    	$('#sort_by').val( current_sort );
    	$('#page_number').val( current_page );
    	
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
    	
    	//change filter
    	$('#filter').on('change' , function(evt)
    	{
    		browse_to( $(this).val() , current_page ,  current_items_per_page , current_sort );
    	});
    	
    	//change page number
    	$('#page_number').on('change' , function(evt)
    	{
    		browse_to( current_filter , $(this).val() ,  current_items_per_page , current_sort );
    	});
 </script>   	
