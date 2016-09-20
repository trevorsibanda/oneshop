<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content" >
	<div class="content_box">
		<div class="men">
		  <div class="error-404 text-center">
				<h1>404</h1>
				<p>You ' ve Failed</p>
				<a class="b-home" href="<?= shop_url('') ?>">Back to Home</a>
			  </div>
		</div>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>			