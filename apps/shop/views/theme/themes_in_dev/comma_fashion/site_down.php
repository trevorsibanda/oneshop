<?php
        $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
        $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );
?>
<div class="content" >
  <div class="content_box">
  <h1 class="text-center" >Site under maintanence</h1>
  <p style="margin-top: 100px; margin-bottom: 50px;" class="text-center">
  Our site is currently under maintanence and will be back online soon. We apologize for any inconvinience caused.<br/> Please visit us again soon. 
  </p>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>	  