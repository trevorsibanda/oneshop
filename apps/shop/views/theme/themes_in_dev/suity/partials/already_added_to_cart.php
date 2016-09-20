<?php if( is_array($cart_entry) ): ?>
	<div class="alert alert-info" >
		<p>
			<b>Added to your shopping cart</b>
			<br/>
			<?= $cart_entry['qty'] ?> items ordered totalling <?= money($cart_entry['subtotal']) ?>.
		</p>
		<table class="table table-responsive" >
			<?php foreach( $cart_entry['options'] as $opt ): ?>
				<tr>
					<td><?=  $opt['option'] ?></td>
					<td><?= $opt['value'] ?></td>
				</tr>
			<?php endforeach; ?>	
		</table>
		<a href="/cart/remove?product_id=<?=  $product['product_id'] ?>" class="btn btn-default" ><i class="fa fa-remove"></i> Remove from Cart</a>
	</div>

<?php endif; ?>
