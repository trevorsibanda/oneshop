<?php  foreach( $product['attributes'] as $attribute ): 
	$cart_option = cart_option( $cart_entry , $attribute['attribute_value'] );						
	if( $attribute['is_customize'] != True )
	{
		continue;
	}
		
?>
<div class="col-xs-12 col-sm-6 col-md-4 " >
	<label><?= $attribute['attribute_name'] ?></label>
	<select class="form-control" name="custom_<?= $attribute['attribute_id'] ?>" >
		<?php if( ! is_null($cart_option) ): ?>
		<option selected value="<?= $cart_option ?>" ><?= $attribute['attribute_value'] ?></option>
		<?php else: ?>	
		<option selected value="<?= $attribute['attribute_value'] ?>" ><?= $attribute['attribute_value'] ?></option>
		<?php endif;?>
		<?php 
			
			foreach( $attribute['options'] as $option )
			{
				if( $attribute['attribute_value'] == $option ['value'] or $attribute['attribute_value'] == $cart_option['value'] )
				{
					continue;
				}
				echo '<option value="' . $option['value'] . '" >' . $option['value'] . '</option>';
			}
		?>
	</select>
</div>
<?php endforeach; ?>
