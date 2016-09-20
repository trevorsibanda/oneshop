//263Shop signup build app
'use strict';

var app = angular.module('app',['ngRoute', 'ui.bootstrap']);

app.config( ['$routeProvider' , AppRoutes ] )
   .controller('BuildShopCtrl' , BuildShopCtrlFn )
   .controller('ErrorCtrl' , ErrorCtrlFn )
   .controller('BuildingCtrl' , BuildingCtrl );


function AppRoutes(  $routeProvider  )
{
			
	$routeProvider
	.when('/step/:step' , 
	{
		templateUrl:	'build.html' ,
		controller: 	'BuildShopCtrl',
		controllerAs: 	'vm'
	})
	.when('/error/:error_code' , 
	{
		templateUrl:	'error_code.html' ,
		controller: 	'ErrorCtrl',
		controllerAs: 	'vm'
	})
	.when('/building' , 
	{
		templateUrl:	'building.html' ,
		controller: 	'BuildingCtrl',
		controllerAs: 	'vm'
	})
	.otherwise(
	{
		redirectTo: '/step/1'
	});
}
	

BuildShopCtrlFn.$inject = ['$rootScope' , '$scope' , '$http' ,'$routeParams' , '$location' ];
ErrorCtrlFn.$inject = ['$rootScope' ,'$scope' ,'$http']; 
BuildingCtrl.$inject = ['$rootScope' , '$scope' , '$http' ]; 


function BuildShopCtrlFn($rootScope , $scope , $http , $routeParams , $location )
{
	var vm = this;
	
	$rootScope.shop = conf.shop;
	$rootScope.user = conf.user;
	
	//attrs
	vm.isShopNameOk = true;
	vm.isSubDomainOk = $rootScope.isSubDomainOk;
	vm.isSubDomainChecked = false;
	vm.isCheckingSubdomain = false;
	vm.build_msg = 'Building your shop...';
	vm.build_msg_small = 'Do not close this window, you will be automatically redirected to your show website once this process is complete';
	
	vm.checkedName = false;
	vm.shop =  $rootScope.shop;
	vm.user =  $rootScope.user;
	vm.themes = conf.themes;
	vm.payment_gateway = {name: 'paynow' , key: '',secret: '' };
	vm.has_payment_gateway = 0;
	vm.isBuilding = false;
	
	
	vm.use_alias = false;
	vm.selected_plan = 'basic';
	
	vm.shop_url = 'http://'+ vm.shop.suggested_subdomain + '.263shop.co.zw';
	
	vm.currentStep =1;
	
	//methods
	vm.checkSubDomain = check_sub;
	vm.isStepOk = isStepOk;
	vm.build = build;

	
	
	activate();
	
	function activate( )
	{
		
		if( angular.isDefined( $routeParams.step ) )
		{
			vm.currentStep = $routeParams.step;
		}else
		{
			vm.currentStep = 1;
		}
		if( vm.currentStep > 3 || vm.currentStep < 1 )
			vm.currentStep = 1;
		
		if( vm.currentStep != 1 )
		{
			if( ! vm.isStepOk( vm.currentStep - 1 ) )
			{
				$location.url('/step/' + vm.currentStep - 1  );
			}
		}
		
			
		
		if( vm.currentStep == 2 )
		{
			
			var fnDestroy = $scope.$watch(function(){ return vm.shop.subdomain } , function()
			{
				vm.shop_url = 'http://'+ vm.shop.subdomain + '.263shop.co.zw';
				vm.isSubDomainChecked =false;
				vm.isSubDomainOk = false;
				vm.isSubDomainChecked = false;
				$rootScope.isSubDomainOk = false;
			} );
			$scope.$on('$destroy' , fnDestroy );
			if( vm.shop.subdomain > 2 )
				check_sub();
		}
		
			
	}
	
	function check_shop_name()
	{
		//check if shopname ok
		$http.post('/build/check_shop_name_ok' , {name: vm.shop.name } )
		     .then(function(resp)
		     	{
		     		var data = resp.data;
		     		if( data.status == 'ok' )
		     			vm.isShopNameOk = true;
		     		else
		     			vm.isShopNameOk = false;
		     		
		     		vm.checkedName = true;		
		     	}).catch(function(resp)
		     	{
		     		vm.checkedName = true;
		     		alert('Failed to communicate with 263Shop server, make sure you are connected to the internet');
		     		return;
		     	});	
	}
	
	function check_sub( )
	{
		if( vm.shop.subdomain.length <= 2 )
		{
			alert('Subdomain should be at least three characters long and contain only letters and numbers');
			return;
		}
		//check if shopname ok
		vm.isCheckingSubdomain = true;
		$http.post('/build/check_subdomain_available' , {sub: vm.shop.subdomain } )
		     .then(function(resp)
		     	{
		     		
		     		var data = resp.data;
		     		
		     		if( data.status == 'ok' )
		     		{
		     			if( vm.shop.subdomain == data.sub )
		     			{
		     				vm.isCheckingSubdomain = false;
		     				vm.isSubDomainOk = true;
						vm.isSubDomainChecked = true;
						$rootScope.isSubDomainOk = true;
		     			}
		     		}
		     		if( data.status == 'fail' )
		     		{
		     			vm.isCheckingSubdomain = false;
	     				vm.isSubDomainOk = false;
					vm.isSubDomainChecked = true;
					$rootScope.isSubDomainOk = false;
		     		}
		     		
		     				
		     	}).catch(function(resp)
		     	{
		     		vm.isCheckingSubdomain = false;
		     		vm.isSubDomainChecked = true;
		     		alert('Failed to communicate with 263Shop server, make sure you are connected to the internet');
		     		return;
		     	});
	}
	
	function isStepOk( step )
	{
		switch(step)
		{
			case 1:
			{
				return ( vm.shop.name.length > 2 && vm.shop.city.length && vm.shop.country.length && vm.shop.tagline.length );
			}	
			case 2:
			{
				return (vm.shop.subdomain.length > 2 && vm.shop.theme.length && $rootScope.isSubDomainOk ); 
			}
			case 3:
			{
				return vm.selected_plan.length;
			}
		}
	}
	
	function build( )
	{
		if( ! isStepOk(1) && isStepOk(2) && isStepOk(3) )
		{
			alert('Please make sure you entered all steps correctly');
			return;
		}
		
		var data = 
		{
			shop_name: vm.shop.name,
			subdomain: vm.shop.subdomain,
			alias: vm.shop.alias,
			theme: vm.shop.theme,
			has_payment_gateway: vm.has_payment_gateway,
			gateway_name: vm.payment_gateway.name,
			gateway_key: vm.payment_gateway.key,
			gateway_secret: vm.payment_gateway.secret,
			plan: vm.selected_plan,
			tagline: vm.shop.tagline,
			city: vm.shop.city,
			country: vm.shop.country,
			short_descr: vm.shop.short_descr,
			category: vm.shop.category
		};
		
		vm.isBuilding = true;
		
		$http.post('/build/do_build_shop' , {data: data } )
		     .then(function(resp)
		     	{
		     		
		     		var data = resp.data;
		     		if( data.status == 'ok' )
		     		{
		     			//successfully built shop
		     			vm.build_msg = 'You shop has been built...';
		     			vm.build_msg_small = 'Redirecting your to your new shop';
		     			//regirect to token login url
		     			window.location = data.url;
		     		}
		     		
		     				
		     	}).catch(function(resp)
		     	{
		     		vm.isCheckingSubdomain = false;
		     		vm.isSubDomainChecked = true;
		     		alert('Failed to communicate with 263Shop server, make sure you are connected to the internet');
		     		return;
		     	});
		
		

		$('html, body').animate({ scrollTop: 0 }, 'fast');


		
		
	}

}

function ErrorCtrlFn($rootScope , $scope , $http )
{

}


function BuildingCtrlFn($rootScope , $scope , $http  )
{
	var vm =this;
	
	vm.msg = 'Building your shop...';
	
	
	activate();
	
	function activate( )
	{
	
	}

}
   	
