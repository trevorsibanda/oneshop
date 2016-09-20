<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	