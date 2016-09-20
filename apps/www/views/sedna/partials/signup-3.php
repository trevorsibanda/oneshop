<div ng-if="vm.currentStep == 3" class="" style="color:#709dca;" >
						<section id="pricing-table" >
            <div class="">
                <div class="row">
                	<h3 class="text-left">Select your plan</h3>
                	
                    <div class="pricing">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header" >
                                    <p class="pricing-title">Entrepreneur Plan</p>
                                    <p class="pricing-rate"><sup>$</sup> 0<br/><span>.00/Mo.</span></p>
                                    <a href="javascript:;" class="btn btn-custom">Ad Supported Plan</a>
                                </div>

                                <div class="pricing-list">
                                	<ul>
                                		<li><i class="fa fa-check"></i> 10 products max</li>
                                		<li><i class="fa fa-check"></i> Free to use</li>
                                		<li><br/></li>
                                		<li><br/></li>
                                	</ul>
                                    <button class="btn btn-block btn-lg {{ vm.selected_plan == 'entrepreneur' ? 'btn-success' : '' }}" ng-click="vm.selected_plan = 'entrepreneur'" ng-disabled="vm.selected_plan == 'entrepreneur'" ><span class="fa fa-check" ng-show="vm.selected_plan == 'entrepreneur'" ></span> Select Plan</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header" style="background-color: purple;" >
                                    <p class="pricing-title">Basic<br/> Plan</p>
                                    <p class="pricing-rate"><sup>$</sup> 4<br/><span>.99/Mo.</span></p>
                                    <a href="javascript:;" class="btn btn-custom">1 month free trial</a>
                                </div>

                                <div class="pricing-list">
                                	<ul>
                                		<li><i class="fa fa-shopping-cart"></i> 50 products max</li>
                                		<li><i class="fa fa-credit-card"></i> Point of Sale</li>
                                       	 	<li><i class="fa fa-bar-chart-o"></i> Analytics</li>
                                		<li><i class="fa fa-check"></i> Use your own domain</li>
                                		
                                	</ul>
                                    <button class="btn btn-block btn-lg {{ vm.selected_plan == 'basic' ? 'btn-success' : '' }}" ng-disabled="vm.selected_plan == 'basic'" ng-click="vm.selected_plan = 'basic'" ><span class="fa fa-check" ng-show="vm.selected_plan == 'basic'" ></span> Select Plan</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header">
                                    <p class="pricing-title">Premium<br/> Plan</p>
                                    <p class="pricing-rate"><sup>$</sup> 19<br/><span>.99/Mo.</span></p>
                                    <a href="javascript:;" class="btn btn-custom">1 month free trial</a>
                                </div>

                                <div class="pricing-list">
                                	<ul>
                                		<li><i class="fa fa-trophy"></i> Unlimited everything</li>
                                		<li><i class="fa fa-trophy"></i> Use your own domain</li>
                                		<li><i class="fa fa-trophy"></i> Free .co.zw domain</li>
                                       	 	<li><i class="fa fa-trophy"></i> Best deal </li>
                                	</ul>
                                    <button class="btn btn-block btn-lg  {{ vm.selected_plan == 'premium' ? 'btn-success' : '' }}" ng-click="vm.selected_plan = 'premium'" ng-disabled="vm.selected_plan == 'premium'" ><span class="fa fa-check" ng-show="vm.selected_plan == 'premium'" ></span> Select Plan</button>
                                </div>
                            </div>
                        </div>
                        <p class="text-center" ><small>You can subscribe, upgrade or downgrade to any plan at anytime</small></p>
                        <br/> 
                        <legend>Payment Gateway</legend>
                        <div class="row" >
                        	<div class="col-md-6" >
                        		<label>Do you have an account with <a href="http://www.pay4app.com/" target="_blank">Pay4App</a> or <a href="http://www.paynow.co.zw/" target="_blank">PayNow</a> ?</label>
                        		<select class="form-control" ng-model="vm.has_payment_gateway" >
                        			<option value=0 >No I do not have an account</option>
                        			<option value=1 >Yes, I already have an account</option>
                        		</select>
                        		<br/>
                        		<small style="color: black;" >A payment gateway is a service which processes and authorizes payments for ecommerce businesses.<br/>A payment gateway will settle money into your bank account or credit card when you receive a payment.</small>
                        		<img src="<?= public_resource('www/sedna/img/payment/payment_types_badge.png') ?>" class="img-responsive" style="width: 100%;" />		
                        
                        	</div>
                        	<div class="col-md-6" >
                        		<div ng-show="vm.has_payment_gateway" >
                        			<label>Select Your Payment Gateway</label>
                        			<select class="form-control" ng-model="vm.payment_gateway.name" >
                        				<option value="pay4app" >Pay4App Zimbabwe</option>
                        				<option value="paynow" >PayNow Zimbabwe</option>
                        			</select>
                        			<br/>
                        			<label>Api Key/ Merchant ID</label>
                        			<input type="text" placeholder="" ng-model="vm.payment_gateway.key" class="form-control" />
                        			<label>Api Secret/ Merchant Secret</label>
                        			<input type="text" placeholder="" ng-model="vm.payment_gateway.secret" class="form-control" />
                        			<br/>
                        			<small>You can change your details later....</small>
                        			
                        		</div>
                        		<div ng-hide="vm.has_payment_gateway" >
                        			<h4>You must signup for a Pay4App or PayNow account.</h4>
                        			<p>In order for your shop to work ( accept payments), you must have an account with a payment gateway. The payment gateway will allow your customers to pay for goods, and the money will be settled into your bank account.<br/>You can use <a href="https://www.pay4app.com/" target="_blank" >Pay4App</a> or <a href="https://www.paynow.com/" target="_blank" >PayNow</a> with your account.</p>  
                        		</div>
                        		
                        	</div>
                        </div>
                        <br/><br/>
                        <div class="text-center" style="margin-bottom: 50px;" >
                        	<button class=" btn-morph-style" ng-click="vm.build()" > Build your store </button>
                        </div>	
                    </div>
                </div>
            </div>
        </section>
	
						<br/>
					</div>
					<div class="clearfix"></div>
					
				</div>
