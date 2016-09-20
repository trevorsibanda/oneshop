<?php
    $this->load->theme($theme , 'partials/head' , array('page' => $page ) );
    $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) );

	$featured_product = element( 0 , $featured_products , array() );
	$large_featured_product = array();
	
	//Large featured product
	if( sizeof( $featured_products ) > 1 )
	{
		$large_featured_product = random_element(  $featured_products );
	}
	else
	{
		$large_featured_product = $featured_product;
	}
		
		
?>

<div class="content">
  <div class="content_box">
	<div class="row" style="margin-top: 20px; margin-bottom: 150px;" >
		<div class="col-md-4 col-md-push-4 col-xs-12 well" style="background: #fbf;" >
			<h2 class="text-center" >User Login</h2>
			<p>
			Login to access your dashboard
			</p>
			<?= validation_errors(); ?>
			<form class="form" action="" method="post" style="margin-top: 10px;" >
				<label>* Email</label>
				<input type="email" name="email" required class="form-control" placeholder="Your email address" />
				<label>* Password</label>
				<input type="password" name="password" required class="form-control" placeholder="Your password." />
				<br/> 
				<button class="btn btn-lg btn-block btn-success" type="submit">Login</button>
				<br/>
				<div class="row" >
					<div class="col-md-6">
						<a href="/admin/recover_password" >Forgot your password?</a>
					</div>
					<div class="col-md-6" >
						<a href="/admin/auth_help" >Need Help ?</a>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php
	$data = array();
	$data['featured_products'] = $featured_products;
	$data['shop'] = $shop; 
	$this->load->theme( $theme , 'partials/footer' , $data );
?>		