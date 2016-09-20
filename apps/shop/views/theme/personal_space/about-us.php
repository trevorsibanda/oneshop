<?php 
	$this->load->theme($theme , 'partials/head' , array('curr_page' => 'about' ) ); 
	$this->load->theme($theme , 'partials/mobile_nav' ); 
	
?>
<div class="title">
	<h2>About us</h2>
	<p>Who are we, what do we do ?</p>
</div>
<div class="row" >
<?= $shop['description'] ?>	
</div>

<div class="row" >
	<div class="col-sm-4">
		<a href="<?= facebook_share() ?>" class="btn btn-primary btn-block" > Share Facebook</a>
	</div>
	<div class="col-sm-4">
		<a href="<?= whatsapp_share( ) ?>" class="btn btn-success btn-block" >Share Whatsapp</a>
	</div>
	<div class="col-sm-4">
		<a href="<?= twitter_share() ?>" class="btn btn-info btn-block" >Share Twitter</a>
	</div>
</div>			
<?php
$this->load->theme($theme , 'partials/footer' );
?>

