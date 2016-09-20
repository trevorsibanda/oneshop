<?php
	$this->load->theme($theme , 'partials/head' , array('page' => $page ) );
	$featured = random_element($featured_products);
    $this->load->theme( $theme , 'partials/headnav' , array('cart' => $cart) ); ?>
?>
<div class="container" >
	<div class="col-md-4 col-md-push-4">
		<form method="POST" >
			<div class="panel panel-success" >
				<div class="panel-body" >
					<h3 class="text-center">Login</h3>
					<?= validation_errors(); ?>
					<label>Email Address</label>
					<input type="email" required class="form-control" name="email" placeholder="youremail@site.com" />
					<label>Password</label>
					<input type="password" required class="form-control" name="password" placeholder="Your password" />
					<br/>
					<button type="submit" class="btn btn-success btn-lg btn-block ">Login </button>
					<hr/><br/>
					<a href="/admin/login/recover_password" class="pull-left">Forgot your password?</a>
					<a href="/admin/login/auth_help" class="pull-right" >Need Help ?</a>
					<br/> 
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