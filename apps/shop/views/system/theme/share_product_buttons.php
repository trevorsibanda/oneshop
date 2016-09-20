<div class="row" >
	<div class="col-sm-4">
		<a href="<?= facebook_share() ?>" class="btn btn-primary btn-block btn-share" data-share='facebook' > Share Facebook</a>
	</div>
	<div class="col-sm-4">
		<a href="<?= whatsapp_share(Null , $product['name'] . ' by '. $product['brand'] . ' available at '. $shop['name'] . ' for ' . money($product['price']) ) ?>" class="btn btn-share btn-success btn-block " data-share='whatsapp' >Share Whatsapp</a>
	</div>
	<div class="col-sm-4">
		<a href="<?= twitter_share(Null , $shop['contact']['twitter_handle']) ?>" class="btn btn-share btn-info btn-block" data-share='twitter' >Share Twitter</a>
	</div>
</div>