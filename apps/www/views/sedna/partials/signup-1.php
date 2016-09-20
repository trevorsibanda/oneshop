<div  class="" ng-if="vm.currentStep == 1" style="color:#709dca;" >
		<label>* Shop Name</label>
<div class="input-group">
        <input type="text" name="shop_name" value="<?= $signup['shop_name'] ?>" required class="form-control clear_input" placeholder="e.g Jamie's Fashion" />
        <span class="input-group-addon"><b ng-show="vm.isShopNameOk" style='color: green;'>OK !</b>
        <b ng-show="!vm.isShopNameOk" style='color: red;'>Not OK !</b></span>
</div>

			<label>* TagLine</label>
			<input type="text" class="form-control clear_input" name="shop_tagline" placeholder="Shop Tagline" ng-model="vm.shop.tagline" />
			<label>Shop Description</label>
			<textarea style="width: 100%;" class="form-control clear_input" name="shop_descr" ng-model="vm.shop.short_descr" ></textarea>
			<br/>
			<div class="row" >
	<div class="col-md-4" >
		<label>* Country</label>
                <select class="form-control" name="shop_country" ng-model="vm.shop.country" >
                	<option value='ZW' >Zimbabwe</option>
                </select>
	</div>
	<div class="col-md-4" >
		<label>* City</label>
                <select class="form-control" name="shop_city" ng-model="vm.shop.city" >
                	<?php foreach( $cities as $city ): ?>
                	<option value="<?= strtolower($city) ?>" ><?= $city ?></option>
                	<?php endforeach; ?>
                </select>	
                <br/>
	</div>
	<div class="col-md-4" >
		<label>* Category</label>
                <select class="form-control" ng-model="vm.shop.category" >
                	<?php foreach( $categories as $cat ): ?>
                	<option value="<?= strtolower($cat) ?>" ><?= $cat ?></option>
                	<?php endforeach; ?>
                </select>	
                <br/>
	</div>
</div>
			<a href="#/step/2" ng-disabled="! vm.isStepOk(1)"  class="btn pull-right btn-success btn-lg" style="background-color:#709dca;" type="submit">Next Step >>> </a>

		
		<br/>

	</div>
