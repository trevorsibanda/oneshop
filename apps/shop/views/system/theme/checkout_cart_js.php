<script>
	var shipping_cost = "<?= money( $shipping_cost) ?>";
	function shipping( option )
	{
		var so = document.querySelector('#shipping_options');
		if( option == 'deliver')
		{
			document.querySelector('.shipping_cost').innerHTML = shipping_cost;
			so.className = '';
		}
		else
		{
			if( document.jQuery )
			{
				
				$('.shipping_cost').html('$0.00');
			}
				
			else
			{
				document.querySelector('.shipping_cost').innerHTML = '$0.00';
				
			}	
			so.className = 'hidden';
		}
	}

	shipping('collect_instore');
	</script>