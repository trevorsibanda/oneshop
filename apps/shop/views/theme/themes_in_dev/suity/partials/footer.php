<div class="footer">
	<div class="container">
		<img src="<?= shop_image( $shop['logo'] ) ?>" alt="<?=  $shop['logo']['meta'] ?>"/>
		<p><a href="mailto:<?=  $shop['contact_email'] ?>"><?= $shop['contact_email'] ?></a></p>
		<div class="copy">
			<p>&copy; <?= date('Y') . ' ' . $shop['name'] ?> All Rights Reserved  :: <a href="http://www.oneshop.co.zw/" target="_blank" >Create your own online store</a> </p>
		</div>
		<ul class="social">
		  <li><a href="<?= $shop['contact']['facebook_page'] ?>"> <i class="fb"> </i> </a></li>
		  <li><a href="<?= twitter_url($shop['contact']['twitter_handle'] ) ?>"> <i class="tw"> </i> </a></li>
		</ul>
	</div>
</div>
<?= include_system_footer(); ?>
