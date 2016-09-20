<?php
	$this->load->view('sedna/partials/head' );
?>
<!-- App -->
<script>
	var conf= {shop: {},user:{},themes: []};
	
	conf.shop = 
	{
		name: <?= json_encode($signup['shop_name']) ?>,
		country: 'ZW',
		city:    'bulawayo',
		short_descr: '',
		tagline:  '',
		suggested_subdomain: <?= json_encode( $suggested_subdomain ) ?>,
		subdomain: <?= json_encode( $suggested_subdomain ) ?>,
		alias: '',
		category: 'small business',
		theme: 'magnetic',
		selected_plan: 'basic'
	};
	
	conf.categories = <?=  json_encode( $categories ); ?>;
	
	conf.user = 
	{
		fullname: <?=  json_encode( $signup['fullname']) ?>,
		email: <?= json_encode( $signup['email']) ?>,
		phone_number: <?=  json_encode( $signup['phone_number']) ?>,
		gender: 'female'
	};
	
	conf.themes = <?= json_encode($themes) ?>;
</script>
<script src="<?=  public_js('angular.min.js'); ?>" ></script>
<script src="<?=  public_js('angular-route.min.js'); ?>" ></script>
<script src="<?=  public_js('ng-bootstrap.js'); ?>" ></script>
<script src="<?= public_resource('www/sedna/js/build_app.js'); ?>" ></script>

<!--Animation-->

<script src="<?= public_js('wow.min.js'); ?>"></script>
<link href="<?= public_css('animate.css'); ?>" rel='stylesheet' type='text/css' />
<script>
	new WOW().init();
</script>
<style>
.build-logo{ position: fixed; }

@media( max-width: 768px ){
	.build-logo{ position: relative; }	
};
</style>
<body ng-app="app" >
	<div class="container" ng-view >
		<div class="loader" ></div>
	</div>
	
	<script type="text/ng-template" id="build.html" >
	<div class="container" style="min-height: 768px;"   >
		<div class="row wow fadeIn">
			<img src="<?= public_resource('www/sedna/img/logo.png'); ?>" class="img-responsive build-logo"  />
					
		</div>
		<div >
			<div class="service-section-head text-center wow fadeInRight" ng-show="vm.currentStep == 1" data-wow-delay="0.4s">
				<h3>Create your store !</h3>
				<p>Welcome {{ vm.user.fullname }}, lets start building your shop :)</p>
				
           		 </div>
				
		</div>
			<div class="row"  >
				<div class="col-md-8 col-md-offset-2" >
					<div class="row bs-wizard" style="border-bottom:0;">
		                
		                <div class="col-xs-4 bs-wizard-step {{ vm.currentStep >= 1 ? 'complete' : '' }}">
		                  <div class="text-center bs-wizard-stepnum">Step 1</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#/step/1" class="bs-wizard-dot" ng-hide="vm.isBuilding"></a>
		                  <div class="bs-wizard-info text-center">Your shop details.</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step {{ vm.currentStep >= 2 ? 'complete' : '' }}"><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 2</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#/step/2" ng-hide="!vm.isStepOk(1) || vm.isBuilding" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Choose your design</div>
		                </div>
		                
		                <div class="col-xs-4 bs-wizard-step {{ vm.currentStep >= 3 ? 'complete' : '' }}"><!-- complete -->
		                  <div class="text-center bs-wizard-stepnum">Step 3</div>
		                  <div class="progress"><div class="progress-bar"></div></div>
		                  <a href="#/step/3" ng-hide="!vm.isStepOk(2)|| vm.isBuilding" class="bs-wizard-dot"></a>
		                  <div class="bs-wizard-info text-center">Create your shop</div>
		                </div>
		           </div>    
					<div ng-hide="vm.isBuilding" >
					<?php $this->load->view('sedna/partials/signup-1'); ?>
					<!-- Stage 2 -->
					<?php $this->load->view('sedna/partials/signup-2'); ?>
					<!-- Stage 3 -->
					<?php $this->load->view('sedna/partials/signup-3'); ?>
					</div>
					<!-- Building -->
					<?php $this->load->view('sedna/partials/signup-build'); ?>
					
				
		</div>
	</div>
	</script>
	<br/>
	<div class="clearfix" ></div>
<style>
		.loader {
  font-size: 10px;
  margin: 50px auto;
  text-indent: -9em;
  width: 11em;
  height: 11em;
  border-radius: 50%;
  background: #f00;
  background: -moz-linear-gradient(left, #0dc5c1 10%, rgba(0,0 , 0, 0) 42%);
  background: -webkit-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: -o-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: -ms-linear-gradient(left, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  background: linear-gradient(to right, #0dc5c1 10%, rgba(255, 255, 255, 0) 42%);
  position: relative;
  
  -webkit-animation: load3 1.4s infinite linear;
  animation: load3 1.4s infinite linear;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.loader:before {
  width: 50%;
  height: 50%;
  background: #0dc5c1;
  border-radius: 100% 0 0 0;
  position: absolute;
  top: 0;
  left: 0;
  content: '';
}
.loader:after {
  background: #fff;
  width: 75%;
  height: 75%;
  border-radius: 50%;
  content: '';
  margin: auto;
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}
@-webkit-keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes load3 {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

		</style>
		
 <?php
 	$this->load->view('sedna/partials/footer-build' ); 
 ?> 
 <script>
  $(document).ready(function(){
    $('.carousel').carousel();
  });
</script>		
