<div ng-if="vm.currentStep == 2"  class="" style="color:#709dca;" >
						<h3 class="text-center" style='color: black; font-weight: bold;'>{{ vm.shop.name }}&nbsp; <small><a href="#/step/1">edit</a></small></h3>
							<p class="text-center">
							 <small ><a href="#/step/1">Tagline</a>: &nbsp;&nbsp;</small>{{vm.shop.tagline}}</p>
							 <span ><label >Shop URL </label><span class="pull-right" style='color: black;'>You can change this later</span></span>
							 <div class="input-group">
	                                <span class="input-group-addon"><b style='color: green;'>HTTPS://</b></span>
	                                <input type="text" name="shop_name" ng-readonly="vm.isCheckingSubdomain"  ng-model="vm.shop.subdomain" class="form-control clear_input" placeholder="e.g Jamie's Fashion" />
	                                <span class="input-group-addon"><b style='color: green;'>.263shop.co.zw</b></span>
	                        </div>
	                        
	                        
	                        <br/>
	                        <p class="text-center" style="color: {{ vm.isSubDomainOk ? 'green' : 'red' }}; font-weight: bold;" >
	                        	<span ng-show="!vm.isCheckingSubdomain && vm.isSubDomainChecked" >{{ vm.shop_url }} is {{ vm.isSubDomainOk ? 'available !' : 'not available :(' }}</span>
	                        	<span ng-show="!vm.isSubDomainChecked && !vm.isCheckingSubdomain" style="color: orange;" > Check if {{ vm.shop_url }} is  available</span>
	                        	<span ng-show="vm.isCheckingSubdomain" style="color: purple;" >
	                        	
	                        	Checking if {{ vm.shop_url }} is available </span>
	                        	<button class="btn btn-default pull-right" ng-click="vm.checkSubDomain()" ng-disabled="vm.isCheckingSubdomain" ng-hide="vm.isSubDomainChecked" >
	                        		<span ng-hide="vm.isCheckingSubdomain">Check if available</span>
	                        		<span ng-show="vm.isCheckingSubdomain">Checking...</span>
	                        	</button>
	                        </p>
	                        <br/>
							<div class="row" >
								<div class="col-md-6" >
									<label>Select a Theme</label>
								</div>
								<div class="col-md-6" >
									<select class="form-control" ng-model="vm.shop.theme" >
										<option ng-repeat="theme in vm.themes" value="{{theme.info.dir}}" >{{theme.info.name}}</option>	
									</select>
								</div>
							</div>
							
								
								
								<div class="row"  style="margin-top: 20px; " ng-repeat="theme in vm.themes" ng-if="theme.info.dir == vm.shop.theme" >	
									<div class="col-md-8 col-xs-6 " >
										<carousel interval="3000" >
										      <slide ng-repeat="image in theme.screenshots" >
											<img ng-src="{{image}}" class="img-responsive" style="margin:auto; min-height: 240px; width: 100%; ">
											<div class="carousel-caption">
											  
			
											</div>
										      </slide>
										</carousel>
									</div>	
									<div class="col-md-4 col-xs-12" >
										<h4 class="text-center" style='color: black; font-weight: bold;' >{{ theme.info.name }}</h4>
										<hr/>
										<label>Description</label>
										<p style='color: black;'>
										{{ theme.info.description }}
										</p>

									</div>
								</div>	
								
								
							<br/>
							<br/>
							<a href="#/step/3" class="btn pull-right btn-success btn-lg" style="background-color:#709dca;" ng-disabled="! vm.isStepOk(2)" >Next Step >>> </a>

						
						<br/>
					</div>
					
