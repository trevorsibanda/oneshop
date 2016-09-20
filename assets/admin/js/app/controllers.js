/** App Controllers **/
var controllers_module = angular.module
					('app.controllers' ,
						 [ 'app.services' , 'chart.js' , 'ui.bootstrap' ,
						  'ngRoute' , 'angularFileUpload'    ]
					 );

//////////////////////////////// MAIN CONTROLLERS ////////////////////////////////////
( function(module)
{
  	'use strict';
  	module.controller('NavBarCtrl' , NavBarCtrl )
	    .controller('MainContentCtrl' , MainContentCtrl )
    	.controller('SideBarCtrl' , SideBarCtrl )
    	.controller('FileManagerCtrl' , FileManagerCtrl)
    	.controller('PromptDialogCtrl' , PromptDialogCtrl );

  	NavBarCtrl.$inject = ['$scope' , '$rootScope' , '$timeout' , '$window' , '$location','userData'  ];
 	MainContentCtrl.$inject = ['$scope' , '$rootScope' , '$timeout' , 'userData' , '$modal'  ];
	SideBarCtrl.$inject = ['$scope' , '$interval' , 'shopData' , 'accountData' ,'userData'];
	/** File manager **/
	FileManagerCtrl.$inject = ['$scope' , '$modalInstance' , 'FileUploader' , 'shopData' , 'productData' , 'userData' , 'setup' ];
	PromptDialogCtrl.$inject =[ '$scope' , '$modalInstance' , 'heading' , 'prompt' , 'message'  , 'type' ];
	 

	

	/** Sidebar Ctrl **/
	function SideBarCtrl(  $rootScope  , $interval , shopData , accountData ,  userData )
	{
		var vm = this;
		vm.summary = {};



		activate();

		function activate()
		{

			//preferably cached to reduce http requests
			shopData.getShopSummary( true).then(function(data)
	 		{
	 			vm.summary = data;
	 			//trigger reload
	 			fnSummary();
	 		}).catch(function(){});

	 		

	 		//check for changes in summary every 120 seconds
	 		$interval(fnSummary , 120000 );			
		}

		

		function fnSummary( )
		{
			//no cache
			shopData.getShopSummary( false ).then(function(data)
	 		{
	 			$rootScope.$apply();
	 			if( data.pending_orders >  vm.summary.pending_orders )
	 			{
	 				$rootScope.$emit('notify.flash' , 'You have a new order.');
	 				$rootScope.$emit('storage:clear' , 'products');
	 			}
	 			vm.summary = data;

	 		}).catch(function(){});

	 		//ignore fails, in case its just bad network at the time
	 		//check every 2 mins
		}


	}

	/** NavBar **/
	function NavBarCtrl( $scope , $rootScope , $timeout , $window , $location ,userData )
	{
		var vm = this;
		
		//attrs
		vm.user = {};
		vm.isSupportOn = false;
		
		//methods
		vm.logout = logout;
		vm.filemanager = fileManager;
		vm.showProfile = showProfile;
		vm.doLogout = doLogout;
		vm.enableSupport = enableSupport;

		$rootScope.is_admin = is_admin;
		$rootScope.has_permission = hasPermission;
		$rootScope.to = to;
		
		
		activate();

		function activate()
		{
			
			userData.thisUser().then( function(data)
				{
					vm.user = data;
					
				}).catch(function(err){});
		}

		function is_admin()
		{
			return parseInt(vm.user.permission_admin) ;
		}

		function hasPermission( perm )
		{
			
			if( $rootScope.is_admin() )
				return true;
			var p = false;
			switch(perm)
			{
				//used !! for typecasting to bool
				case 'manage_products':{ p = vm.user.permission_manage_products; } break;
				case 'manage_stock': { p = vm.user.permission_manage_stock; } break;
				case 'manage_orders':{ p = vm.user.permission_manage_orders;} break;
				case 'pos': { p = vm.user.permission_pos; } break;
				case 'designer': { p = vm.user.permission_designer; } break;
				case 'blog': { p = vm.user.permission_blog; } break;
				
			}
			return parseInt(p);
		}

		function to(url)
		{
			$location.url('/' + url );
		}

		function fileManager()
		{
			var setup =
			{
				action: 'manage',
				upload: true
			};
			var unregisterFunc = $rootScope.$emit('file:manage' , setup );
			$scope.$on('$destroy' , unregisterFunc );
		}


		function logout()
		{
			$rootScope.prompt('Are you sure ?' , 'Do you want to logout ?' , '.' , 'warning')
			.then(function(resp)
			{
				if( resp == true )
				{
					$rootScope.$emit('notify.danger' , 'Logging you out in 3 seconds... ' );
					$rootScope.$emit('event:auth-loginRequired' , '' );
					$timeout( vm.doLogout , 3000 );	
				}
			});
			
			
		}

		function doLogout()
		{
			userData.logout();		
			$window.location = '/admin/logout';
		}

		function enableSupport( )
		{
			if( vm.isSupportOn )
			{
				$rootScope.$emit('notify.flash' , 'Support is already enabled');
				return;

			}
			$rootScope.$emit('notify.flash' , 'Live customer support enabled. Please wait a few seconds...');
			var script   = document.createElement("script");
			script.type  = "text/javascript";
			script.text  = 'var $_Tawk_API={};$_Tawk_LoadStart=new Date();(function(){ var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0]; s1.async=true; s1.src="https://embed.tawk.to/5640484f0717f593104a6fde/default"; s1.charset="UTF-8"; s1.setAttribute("crossorigin","*"); s0.parentNode.insertBefore(s1,s0); })();';               // use this for inline script
			document.body.appendChild(script);
			vm.isSupportOn = true;
		}

		function showProfile()
		{
				
			$rootScope.$emit('notify.flash' , 'This should disappear in 5 seconds ' );
		}
	}	

	/** Main Content Ctrl **/
	function MainContentCtrl( $scope , $rootScope , $timeout , userData , $modal )
	{
		var vm = this;

		vm.alerts = [];
		vm.closeAlert = closeAlert;
		vm.addAlert  = addAlert;
		vm.isLoggedIn = true; //assume logged in by default
		
		vm.submitLogin = submitLogin;
		vm.clearAlerts = clearAlerts;

		vm.login = {
			email: '',
			password: '',
			processing: false
		};

		activate();

		function activate()
		{
			//prompt dialog
			$rootScope.prompt = promptDialog;

			//change page title
			$rootScope.$on('head.title' , evtSetPageTitle  );
			
			//notifications
			$rootScope.$on('notify.danger' , evtNotifyDanger );
			$rootScope.$on('notify.warning' , evtNotifyWarning );
			$rootScope.$on('notify.info' , evtNotifyInfo );
			$rootScope.$on('notify.success' , evtNotifySuccess );
			$rootScope.$on('notify.flash' , evtNotifyFlash );

			//http interceptor login required
			$rootScope.$on('event:auth-loginRequired' , evtLoginRequired );
			//http interceptor not authorised
			$rootScope.$on('event:auth-forbidden' , evtAuthForbidden );
			//http interceptor logged in
			$rootScope.$on('event:auth-loginConfirmed' , evtLoginSuccess );

			//File manager
			$rootScope.$on('file:upload' , evtUploadFile );
			$rootScope.$on('file:select' , evtSelectFile);
			$rootScope.$on('file:manage' , evtFileManager );
			
			//check login status
			$timeout( checkLogin , 5000 );
				
		}

		function clearAlerts()
		{
			vm.alerts = [];
		}

		

		function promptDialog( heading  , prompt  , message  , type  )
		{
			if( null ==(heading))
				heading = '';
			if( null ==(prompt))
				prompt = '';
			if( null ==(message))
				message = '';
			if( null ==(type))
				type = 'info';
			var modalInstance = $modal.open({
		      templateUrl: 'prompt.html',
		      controller: 'PromptDialogCtrl',
		      controllerAs: 'vm',
		      size: 'sm',
		      resolve: {
		        heading: function () {
		          return heading;
		        },
		        prompt: function () {
		          return prompt;
		        },
		        message: function () {
		          return message;
		        },
		        type: function () {
		          return type;
		        },
		        
		      }
		    });

		    return modalInstance.result;
		}

		function checkLogin( )
		{
			userData.isLoggedIn().then(
				function(data)
				{
					$timeout( checkLogin , 5000 );
					vm.isLoggedIn = true;
				}).catch( function(data)
				{
					//show that you are now offline
					$timeout( checkLogin , 5000 );
					vm.isLoggedIn = false;
				} )
		}

		

		function closeAlert( index )
		{
			vm.alerts.splice(index , 1 );
		}

		function submitLogin()
		{
			var email = vm.login.email;
			var password = vm.login.password;
			vm.login.email = '';
			vm.login.password = '';
			vm.login.processing = true;
			userData.login( email , password );

		}

		function addAlert(  message ,type , is_flash )
		{
			if( null ==(type))
				type = 'info';
			if( null ==(is_flash))
				is_flash = false;
			var index = vm.alerts.push( {msg: message , type: type } );
			if( is_flash )
			{
				$timeout(  function()
				{
					//remove index
					vm.alerts.splice(index-1 , 1 );
				} , 5000 );
			}
		}

		function evtSetPageTitle( evt , title )
		{
			evt.stopPropagation();
			document.title = title + ' :: ' + appName + ' Admin ';
		}

		function evtLoginRequired( evt , data  )
		{
			if(!vm.isLoggedIn)
				return;
			$rootScope.$emit('notify.danger' , 'You are not logged in !');
			$rootScope.$emit('storage:clear' , null );
			vm.isLoggedIn = false;
		}	

		function evtLoginSuccess( evt , data )
		{
			vm.login.processing = false;
			vm.isLoggedIn = true;
			delete vm.alerts;
			vm.alerts = [];
			$rootScope.$emit('notify.flash' , 'You are now logged in !');
		}

		function evtAuthForbidden( evt , http_obj )
		{
			var msg = 'You are not authorised to access this resource';
			if( angular.isDefined(http_obj.data) && http_obj.data.length )
				msg = http_obj.data;
			else if( angular.isObject(http_obj.data) && angular.isDefined(http_obj.data.msg))
				msg = http_obj.data.msg;
			$rootScope.$emit('notify.warning' , msg);
		}

		function evtAddAlert( evt , alert , is_flash )
		{
			if( null ==(is_flash))
				is_flash = false;
			evt.stopPropagation();
			if(!vm.isLoggedIn)
				return;
			vm.addAlert(alert.msg , alert.type , is_flash  );
				
		}

		function evtNotifyDanger( evt , message )
		{
			var alert = 
			{
				msg: 	 message ,
				type: 	'danger'
			};
			evtAddAlert( evt , alert );
		}

		function evtNotifyWarning( evt , message )
		{
			var alert = 
			{
				msg: 	 message,
				type: 	'warning'
			};

			evtAddAlert( evt , alert );
		}

		function evtNotifyInfo( evt , message )
		{
			var alert = 
			{
				msg: 	 message,
				type: 	'info'
			};
			evtAddAlert( evt , alert );
		}

		function evtNotifySuccess( evt , message )
		{
			var alert = 
			{
				msg: 	 message ,
				type: 	'success'
			};
			evtAddAlert( evt , alert );
		}


		function evtNotifyFlash( evt , message )
		{
			var alert = 
			{
				msg: 	 message,
				type: 	'info'
			};
			evtAddAlert( evt , alert , true );
		}

		function evtFileManager(evt , setup)
		{
			evt.stopPropagation();
			if( angular.isUndefined(setup.size) )
			{
				setup.size = 'lg';
			}
			if( angular.isUndefined(setup.action) )
			{
				setup.action = 'manage';
			}
			if( angular.isUndefined(setup.type) )
			{
				setup.type = 'product_image';
			}

			var modalInstance = $modal.open({
		      templateUrl: 'filemanager.html',
		      controller: 'FileManagerCtrl',
		      controllerAs: 'vm',
		      size: setup.size,
		      resolve: {
		        setup: function () {
		          return setup;
		        }
		      }
		    });
			//call back data to controller
			var event = 'managed';
			if( setup.action == 'select' || setup.action == 'upload')
				event = setup.action + 'ed'; //uploaded|selected

			//wait for promise
			 modalInstance.result.then(
			 	//successfully selected or uploaded item
			 	function (item_id) 
			 	{
		   	   		$rootScope.$broadcast( 'file:' + event , { item: item_id , id: setup.id } );
		    	}, 
		    	//nothing selected - modal closed
		    	function () {
		      		$rootScope.$broadcast( 'file:' + event , { item: null , id: setup.id } );
		    	}
		    	);
		}

		function evtUploadFile( evt , setup )
		{

			setup.action = 'upload';
			evtFileManager( evt , setup );
		    
		}

		function evtSelectFile( evt , setup )
		{
			//expects a random integer to be set in setup
			setup.action = 'select';
			evtFileManager( evt , setup );
		    
		}
	}

	/** File Manager **/
	function FileManagerCtrl( $scope , $modalInstance ,  FileUploader , shopData , productData , userData , setup  )
	{
		var vm = this;

		//attr
		vm.file_search = ''; //search filter
		vm.selected = null; //select resource id
		vm.files = {}; //files
		vm.types = ['product_image' , 'shop_image' , 'user_image' , 'product_file'];
		vm.setup = setup;
		vm.type = ( setup.type != null ) ? setup.type : 'product_image';
		
		vm.action = ( setup.action != null) ? setup.action : 'manage';

		vm.uploadType = vm.type; //type of file to upload
		vm.close = $modalInstance.dismiss;
		//restrict the type of file the user can upload. if a product images dialog is opened images can only be uploaded into the product images folder
		vm.restrictUpload = true;
		vm.uploadFilter = ''; //file exts allowed during upload 
		vm.showProductImage = false;
		vm.showShopImage = false;
		vm.showUserImage = false;
		vm.showProductFile = false;

		vm.image_url = productData.imageUrl; //default to product images. see more code

		vm.selectFile = true;
		vm.uploadFile = true;
		vm.fileManager = false;



		//methods
		vm.close = doClose; //close a resource
		vm.select = doSelect; //select a resource 
		vm.deleteFile = deleteFile; //delete a file
		vm.editFile = editFile; //edit a file
		vm.isSelected = isSelected; //is a resource selected
		vm.load = getFiles; //load files/resources
		vm.id = resourceId; //get id to return from resource
		vm.isFile = isFile; 
		vm.humanType = humanType; //human type
		
		vm.uploader = {};

		activate();

		function activate()
		{
			vm.load();
			if( vm.action == 'select' )
			{
				vm.selectFile = true;
				if( angular.isDefined(setup.upload) && setup.upload == true )
				{
					vm.uploadFile = true;
				}
				vm.fileManager = false;
			}
			else if( vm.action == 'upload')
			{
				vm.selectFile = false;
				vm.fileManager = false;
			}else
			{
				vm.showProductImage = true;
				vm.showShopImage = true;
				vm.showUserImage = true;
				vm.showProductFile = true;
				vm.fileManager = true;
				vm.selectFile = false;
				vm.restrictUpload = false;
			}
			activateUploader();
			//change select folder type
			$scope.$watch( function(){ return vm.type;} , vm.load	);
			//change folder to upload into
			$scope.$watch( function(){ return vm.uploadType} , function()
			{
				//change folder to upload into
				vm.uploader.url = '/api/files/upload/' + vm.uploadType;
				//change filters
				setUploadFilter( vm.uploadType );

			} )
		}

		function setUploadFilter( type )
		{
			var image_filter = 'jpg|jpeg|png|gif|bmp';
			var file_filter  = 'any file type';
			vm.uploadFilter = null;
			if( type == 'product_image' || type == 'shop_image' || type == 'user_image')
			{
				vm.uploadFilter = image_filter;
			}
			else
			{
				vm.uploadFilter = file_filter;

				vm.uploader.filters = [];
				return;

			}	
			//set filter after clearing

			vm.uploader.filters.splice(1,1);


	        vm.uploader.filters.push({
	            name: 'uploadFilter',
	            fn: function(item /*{File|FileLikeObject}*/, options) {
	                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';
	                return vm.uploadFilter.indexOf(type) !== -1;
	            }
	        });

	        return vm.uploadFilter;
		}

		

		function activateUploader()
		{
			var uploader = vm.uploader = new FileUploader({
            	url: '/api/files/upload/' + vm.type
       		 });

	        // FILTERS
	        setUploadFilter( vm.uploadType );
	        // CALLBACKS
	        /*
	        uploader.onWhenAddingFileFailed = function(item , filter, options) {
	            console.info('onWhenAddingFileFailed', item, filter, options);
	        };
	        uploader.onAfterAddingFile = function(fileItem) {
	            console.info('onAfterAddingFile', fileItem);
	            fileItem.options = {username: 'trevor' , password: 'theOne'};
	        };
	        uploader.onAfterAddingAll = function(addedFileItems) {
	            console.info('onAfterAddingAll', addedFileItems);
	        };
	        uploader.onBeforeUploadItem = function(item) {
	            console.info('onBeforeUploadItem', item);
	        };
	        uploader.onProgressItem = function(fileItem, progress) {
	            console.info('onProgressItem', fileItem, progress);
	        };
	        uploader.onProgressAll = function(progress) {
	            console.info('onProgressAll', progress);
	        };
	        */
	        uploader.onSuccessItem = function(fileItem, response, status, headers) {
	            console.log( response );
	            if( ! angular.isObject(fileItem.savedFile) )
	            {

	            	fileItem.savedFile  = angular.fromJson( response );	
	            }
	            //clear local files
	            productData.clearCache();

	        };
	        /*
	        uploader.onErrorItem = function(fileItem, response, status, headers) {
	            console.info('onErrorItem', fileItem, response, status, headers);
	        };
	        uploader.onCancelItem = function(fileItem, response, status, headers) {
	            console.info('onCancelItem', fileItem, response, status, headers);
	        };
	        uploader.onCompleteItem = function(fileItem, response, status, headers) {
	            console.info('onCompleteItem', fileItem, response, status, headers);
	        };
	        uploader.onCompleteAll = function() {
	            console.info('onCompleteAll');
	        };
	        */
		}

		function humanType( curr)
		{
			switch(curr)
			{
				case 'product_image':
					return 'Product Image(s)';
				case 'product_file':
					return 'Product File(s)';
				case 'shop_image':
					return 'Shop/Blog Image(s)';
				case 'user_image':
					return 'Shop User Image(s)';
				default:
					return 'Generic';				
			}
		}

		function getFiles()
		{
			var fn = productData.getImages;
			if( vm.type == 'shop_image')
			{
				fn = shopData.getImages;
				vm.showShopImage =  true;
				vm.image_url = shopData.imageUrl;
			}
			else if( vm.type == 'user_image')
			{
				vm.showUserImage = true;
				fn = userData.getImages;
				vm.image_url = userData.imageUrl;
			}
			else if( vm.type == 'product_file')
			{
				vm.showProductFile = true;
				fn = productData.getFiles;
				vm.image_url = productData.fileUrl;
			}
			else
			{
				vm.showProductImage = true;
				vm.image_url = productData.imageUrl;
			}

			fn().then(
				function(data)
				{
					vm.files = data;
				},
				function()
				{
					alert('Failed to load files !');
					vm.close();
				});
		}

		function deleteFile( file )
		{
			var promise = $scope.prompt('' , 'Do you want to delete this file ?'  , 'This action is undo-able and once you delete a file any products, blog posts or pages which were using this image will have be updated and the image removed. If no image image is left in the specified blogpost or product, a random image is chosen to replace this image. Make sure you check all products afterwards. ', 'warning');
			promise.then(function(resp)
			{
				if(resp == true)
				{
					
					var new_promise = shopData.deleteFile( vm.type , resourceId(file) );
					new_promise.then(function(data)
					{
						alert('File was deleted ');
						getFiles();
					}).catch(function(err)
					{
						alert('Failed to delete file, already deleted ? ');
					})
				}
			}).catch(function(err){});
		}

		function editFile( file )
		{
			var p = prompt('Enter new file meta data and press ok to save' , file.meta);
			if( p === file.meta || p.length == 0 )
			{
				return;
			}
			file.meta = p;
			shopData.updateFile(vm.type ,file ).then(function(data)
			{
				alert('Successfully updated file meta data');
			}).catch(function(err)
			{
				alert('Failed to update file. Please try again.');
			});

		}

		function resourceId( res )
		{
			return ( vm.type == 'product_file' ? res.file_id : res.image_id );
		}

		function isFile( file )
		{
			return angular.isDefined(file.file_id) ? true : false;
		}


		function doClose( )
		{
			$modalInstance.dismiss( 0 );
		}

		function doSelect( index )
		{
			vm.selected = index;
			$modalInstance.close( vm.selected );
		}

		function isSelected(  index )
		{
			return (index == vm.selected );
		}	
	}   

	/** Prompt Dialog **/
	function PromptDialogCtrl( $scope , $modalInstance , heading , prompt , message  , type )
	{
		var vm = this;
		vm.heading = ( heading.length ) ? heading : 'Are you sure ?';
		vm.prompt = ( prompt.length ) ? prompt : 'Please confirm action';
		vm.message = message;
		vm.type = type.length  ? type : 'info';

		vm.select = doSelect;

		function doSelect( ret  )
		{
			$modalInstance.close( ret );
		}

	}
})(controllers_module);
/////////////////////////////// END MAIN CONTROLLERS ////////////////////////

////////////////////////////// DASHBOARD ////////////////////////////////////
(function(module)
{
	module.controller('DashBoardCtrl' , DashBoardCtrl )
		  .controller('WelcomeCtrl' , WelcomeCtrl );

	DashBoardCtrl.$inject = ['$scope' , '$rootScope' , 'shopData' ];
	WelcomeCtrl.$inject = ['$scope' , '$rootScope' , 'shopData' ];
	
	/** Dashboard controller **/
	 function DashBoardCtrl(  $scope , $rootScope , shopData )
	 {
	 	var vm = this;

	 	vm.summary = {};

	 	activate();

	 	function activate()
	 	{
	 		$rootScope.$emit('head.title' , 'Dashboard');

	 		//we dont want cached data
	 		shopData.getShopSummary('all' , false).then(function(data)
	 		{

	 			vm.summary = data;
	 			console.log(vm.summary);
	 		}).catch( function(data)
	 		{
	 			$rootScope.$emit('notify.error' , 'Failed to load the shop summary. Make sure you are connected to the internet !');
	 		});	
	 	}

	 }


	 function WelcomeCtrl(  $scope , $rootScope , shopData )
	 {
	 	var vm = this;

	 	activate();

	 	function activate()
	 	{
	 		$rootScope.$emit('head.title' , 'Welcome to 263Shop, let\'s get you started!');
	 	}

	 	
	 }
})(controllers_module);
///////////////////////////// END DASHBOARD /////////////////////////////////


/////////////////////////////// ORDERS /////////////////////////////////////
(function(module)
{
    module
    .controller('OrdersViewOrderCtrl' , OrdersViewOrderCtrl )
    .controller('OrdersBrowseCtrl' , OrdersBrowseCtrl )
    .controller('OrdersGiftsCtrl' , OrdersGiftsCtrl )
    .controller('OrdersShippingCtrl' , OrdersShippingCtrl )
    .controller('OrdersCustomerSupportCtrl' , OrdersCustomerSupportCtrl )
    .controller('OrdersViewShipmentCtrl' , OrdersViewShipmentCtrl )
    .controller('OrdersContactCustomerModalCtrl' , OrdersContactCustomerModalCtrl )
    .controller('OrdersViewOrderedProductsModalCtrl' , OrdersViewOrderedProductsModalCtrl )
    .controller('OrdersVerifyOrderModalCtrl' , OrdersVerifyOrderModalCtrl )
    .controller('OrdersCollectOrderModalCtrl' , OrdersCollectOrderModalCtrl)
    .controller('OrdersDispatchModalCtrl' , OrdersDispatchModalCtrl);

	/** Orders **/
	OrdersViewOrderCtrl.$inject = ['$rootScope' , '$modal' , 'orderData' , '$routeParams' ];
	OrdersBrowseCtrl.$inject = ['$rootScope' , '$scope' , '$modal'  ,'orderData'  ];
	OrdersGiftsCtrl.$inject = ['$rootScope' , 'orderData' , 'productData'];
	OrdersContactCustomerModalCtrl.$inject = ['$modalInstance' , '$rootScope' , 'notifyData' , 'order'];
	OrdersViewOrderedProductsModalCtrl.$inject = ['$rootScope' , '$modalInstance' , 'productData' , 'order' ];
	OrdersShippingCtrl.$inject = ['$rootScope' ,  '$routeParams' , '$modal' , 'orderData'  ];
	OrdersCustomerSupportCtrl.$inject = ['$rootScope' , '$routeParams','$modal','orderData'];
	OrdersViewShipmentCtrl  = ['$rootScope' ,  '$routeParams' , '$modal' , 'orderData'  ];
	OrdersVerifyOrderModalCtrl.$inject = ['$rootScope' , '$location' , '$modalInstance' , 'orderData' ];
  	OrdersCollectOrderModalCtrl.$inject = ['$rootScope' , '$location' , '$modalInstance' , 'orderData' ];
  	OrdersDispatchModalCtrl.$inject = ['$rootScope' , '$location' , '$modalInstance' , 'shopData' , 'orderData' , 'order' ];

	/** View Order **/
	function OrdersViewOrderCtrl( $rootScope , $modal , orderData , $routeParams )
	{
	  	var vm = this;
	  	vm.is_loaded = false; //is the order loaded
	  	vm.order = {}; //order

	  	//ui
	  	vm.closeOtherAccordions = true;
	  	vm.showProductAccordion = true;
	  	vm.expandProductsOrdered= false;
	  	vm.showShippingAccordion= false;
	  	vm.showShopperAccordion = false;
	  	vm.showTransactionAccordion= false;
	  	vm.url 	= orderData.orderUrl; //view live order in shop
	  	vm.permissionEdit = false;

	  	//methods
	  	vm.showProducts = showProducts;
	  	vm.contactCustomer = contactCustomer;
		vm.cancelOrder = cancelOrder;
		vm.deleteOrder = deleteOrder;
		vm.completeOrder = completeOrder;
		vm.dispatchOrder = dispatchOrder;
		vm.archiveOrder = archiveOrder;
		vm.setOrderShipped = setOrderShipped;


	  	activate();

	  	function activate()
	  	{
	  		$rootScope.$emit('head.title' , 'Loading Order #' + $routeParams.order_id );
	  		orderData.getOrder( $routeParams.order_id ).then( function(data)
	  		{
	  			vm.permissionEdit = $rootScope.has_permission('manage_orders');

	  			vm.order = data;
	  			vm.order_type = orderData.OrderType( vm.order );
	  			if( vm.order.status == 'paid' )
	  			{
				  	vm.closeOtherAccordions = false;
				  	vm.showProductsAccordion= true;
				  	vm.showShippingAccordion= true;
				  	//vm.showShopperAccordion = true;
				  	vm.showTransactionAccordion= true;
	  			}
	  			$rootScope.$emit('head.title' , 'View Order #' + vm.order.order_id );
	  			vm.is_loaded = true;
	  		});
	  	}

	  	function setOrderShipped( )
	  	{
	  		orderData.setShipped( vm.order.order_id ).then(function(data)
	  		{
	  			$rootScope.$emit('notify.success' , 'The order has been set as shipped.');
	  			activate();
	  		}).catch(function(err)
	  		{
	  			$rootScope.$emit('notify.warning' , 'Failed to set order as shipped');
	  		});
	  	}

	  	function showProducts(  )
	  	{
	  		var modalInstance = $modal.open({
		      templateUrl: 'viewOrderedProducts.html',
		      controller: 'OrdersViewOrderedProductsModalCtrl',
		      controllerAs: 'vm',
		      size: 'lg',
		      resolve: 
		      {
		      	order: function(){ return vm.order }
		      }
		    });
	  	}

	  	function cancelOrder()
		{
			var promise = $rootScope.prompt('Are you sure ?' , 'Cancel this order ?' , 'If you choose to cancel this order, the customer will be sent an email notifying him/her that their order has been cancelled. They can however still view the order and proceed to checkout. If you do not want this, then use Delete Order instead.');
			promise.then( function(response)
			{
				if(response == true)
				{
					orderData.cancelOrder( vm.order.order_id ).then(function(data)
					{
						vm.order = data;
					}).catch(function(err)
					{
						$rootScope.$emit('notify.warning' , 'Failed to cancel order #' + vm.order.order_id  + ' Try again.');
					});
				}
			}).catch(function(){});
		}

		function deleteOrder(  )
		{
			if(vm.order.status != 'cancelled')
				return;
			var promise = $rootScope.prompt('','Delete this order?','Once an order is deleted, the customer will no longer be able to access the order and all records of the order on ' + appName + ' will be deleted. This action cannot be undone and we highly discourage its use.');

			promise.then(function(resp)
			{
				if( resp != true )
					return;
				orderData.deleteOrder( vm.order.order_id ).then(function(data)
				{
					if( data.status == 'ok' )
					{
						$rootScope.$emit('notify.success' , 'Order #' + vm.order.order_id + ' by '+ vm.shipping.fullname + '( ' + vm.shipping.email + ' ) was successfully deleted.' );
						$rootScope.to('orders/browse');
					}
				} ).catch(function(err)
				{
					alert('Failed to delete this order !');
					$rootScope.to('orders/browse');
				});
			})
		}

		function contactCustomer( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'sendCustomerMessage.html',
		      controller: 'OrdersContactCustomerModalCtrl',
		      controllerAs: 'vm',
		      size: 'lg',
		      resolve: 
		      {
		      	order: function(){ return vm.order }
		      }
		    });
		}

		function completeOrder()
		{

		}

		function dispatchOrder()
		{
			var modalInstance = $modal.open({
		      templateUrl: 'dispatchOrder.html',
		      controller: 'OrdersDispatchModalCtrl',
		      controllerAs: 'vm',
		      size: 'lg',
		      resolve: 
		      {
		      	order: function(){ return vm.order }
		      }
		    });
		    modalInstance.result.then(function(){} , function(){});
		}

		function archiveOrder(  )
		{
			var promise = $rootScope.prompt('Are you sure ?', 'Archive this order.' ,'Archiving an order is usually done automatically, two weeks after the payment is received to keep things tidy. You can still view the order once its archived, but it will no longer be listed under paid orders, and will be listed under archived orders','info-circle');
			promise.then(function(resp)
			{
				if( resp == true)
				{
					orderData.archiveOrder( vm.order.order_id ).then(function(data)
					{
						if(data.status == 'ok')
						{
							$rootScope.$emit('notify.flash' ,'Moved this order to archive.');
							vm.order.status = 'archived';
						}
						else
							alert('Unknown error occured!');
					}).catch(function(err){ $rootScope.$emit('notify.warning' ,'Could not archive order #'+ vm.order.order_id ); })
				}
			});
		}




	  }

	  function OrdersContactCustomerModalCtrl(  $modalInstance , $rootScope , notifyData , order )
	  {
	  	var vm= this;

	  	//attrs
	  	vm.type = 'info';
	  	vm.message = 'email';
	  	vm.title = '';
	  	vm.text = '';
	  	vm.isProcessing = false;
	  	vm.order = order;

	  	//methods
	  	vm.close = $modalInstance.dismiss;
	  	vm.is_ready = is_ready;
	  	vm.send =send;

	  	activate();

	  	function activate(){ vm.isProcessing = false; }

	  	function is_ready(  )
	  	{
	  		var b = vm.text.length && vm.message;
	  		if( vm.message != 'sms' )
	  			b = b && vm.title.length;
	  		return b;
	  	}

	  	function send( )
	  	{
	  		if( ! vm.is_ready() )
	  			return false;
	  		if( vm.message == 'sms' && vm.text.length > 450 )
	  		{
	  			alert('Your message is too long and will be shortened to 450 characters when being sent.');
	  		}
	  		var promise = $rootScope.prompt('','Send ' + vm.message + ' message ?' , '');
	  		promise.then(function(resp)
	  		{
	  			if( resp === true)
	  			{
	  				vm.isProcessing = true;
			  		if( vm.message == 'sms' )
			  		{
			  			notifyData.sendCustomerSms( vm.order.order_id , vm.text  )
			  					  .then(onSendComplete)
			  					  .catch(onSendFail);
			  		}
			  		else if( vm.message == 'email' )
			  		{
			  			notifyData.sendCustomerEmail( vm.order.order_id , vm.title , vm.text , vm.type )
			  					  .then(onSendComplete)
			  					  .catch(onSendFail);
			  		}
	  			}
	  		}).catch(function(err){});
	  		
	  	}

	  	function onSendComplete( data )
	  	{
	  		if(angular.isDefined(data.status) && data.status == 'ok')
	  		{
	  			$rootScope.$emit('notify.flash' , 'Successfully sent ' + vm.message + ' message to customer. ');
	  			vm.isProcessing = false;
	  			$modalInstance.dismiss();
	  		}
	  		else
	  		{
	  			vm.isProcessing = false;
	  			alert('An unknown error occured, message not sent');
	  		}
	  	}

	  	function onSendFail( data )
	  	{
	  		vm.isProcessing = false;
	  		alert('Failed to send message, please try again.');
	  	}



	  }

	  function OrdersBrowseCtrl( $rootScope , $scope , $modal , orderData )
	  {
	  		var vm = this;

	  		//attrs
	  		vm.orders = [];
	  		vm.orderInfo = {paid: 0 ,pending: 0, cancelled: 0, archived: 0 , delivered: 0};
	  		vm.isWorking = false;
	  		vm.filter ='pending';
	  		vm.perPage = 5;
	  		vm.orderBy = 'DESC';


	  		//methods
	  		vm.orderType = orderData.OrderType;
	  		vm.loadMore = loadMore; //load more orders
	  		vm.doLoad = doLoad;
	  		vm.canLoadMore = canLoadMore;
	  		vm.setFilter = setFilter;
	  		vm.verifyOrder = verifyOrder;
	  		vm.collectOrder = collectOrder;


	  		activate();

	  		function activate()
	  		{
	  			
	  			//vm.setFilter('pending');

	  			
	  			//get summary
	  			orderData.getOrderSummary().then(function(data)
	  			{
	  				vm.orderInfo = data;
	  			}).catch(function(err){$rootScope.$emit('notify.warning' , 'Failed to get order information')});

	  			
	  		}

	  		function setFilter( filter )
	  		{
	  			if( filter != 'current')
	  				vm.filter = filter;
	  			vm.orders = [];
	  			$rootScope.$emit('head.title' , 'Loading Orders...' );
	  			doLoad();
	  			
	  			
	  		}

	  		function doLoad()
	  		{
	  			vm.isWorking = true;
	  			orderData.getAllOrders(vm.filter , 0 , vm.perPage , vm.orderBy ).then( function(data)
	  			{
	  				$rootScope.$emit('head.title' , 'Browse orders' );
	  				vm.isWorking = false;
	  				vm.orders = data;
	  			}).catch(function(err)
	  			{
	  				alert('Failed to load the orders in the specified filter. Try again');
	  			});
	  		}

	  		function canLoadMore()
	  		{
	  			if( vm.orders.length == 0)
	  				return false;
	  			var count = vm.orderInfo.pending;
	  			switch( vm.filter )
	  			{
	  				case 'paid':
	  				{
	  					count = vm.orderInfo.paid;
	  				}
	  				break;
	  				case 'cancelled':
	  				{
	  					count = vm.orderInfo.cancelled;
	  				}
	  				break;
	  				case 'refunded':
	  				{
	  					count = vm.orderInfo.refunded;
	  				}
	  				break;
	  				case 'archived':
	  				{
	  					count = vm.orderInfo.archived;
	  				}
	  				break;
	  				case 'delivered':
	  				{
	  					count += vm.orderInfo.delivered;
	  				}

	  			}

	  			return !( vm.orders.length >= count );
	  		}

	  		

	  		function loadMore()
	  		{
	  			vm.perPage = parseInt(vm.perPage) + parseInt(vm.perPage); //increment items to return
	  			vm.doLoad();
	  		}

	  		function verifyOrder( )
	  		{
	  		
		  		var modalInstance = $modal.open({
			      templateUrl: 'verifyOrderCode.html',
			      controller: 'OrdersVerifyOrderModalCtrl',
			      controllerAs: 'vm',
			      size: 'sm'
			    });

	  		}

	  		function collectOrder( )
	  		{
	  		
		  		var modalInstance = $modal.open({
			      templateUrl: 'collectOrderCode.html',
			      controller: 'OrdersCollectOrderModalCtrl',
			      controllerAs: 'vm',
			      size: 'sm'
			    });

	  		}
	  }

	  function OrdersGiftsCtrl(  $rootScope , orderData , productData )
	  {
	  		var vm = this;

	  		activate();

	  		function activate()
	  		{
	  			$rootScope.$emit('head.title' , 'Gifts and Voucher Codes');
	  		}

	  }

	/** Orders shipping **/
	function OrdersShippingCtrl( $rootScope , $routeParams , $modal , orderData )
	{
		var vm = this;

		//attrs
		vm.search = '';
		vm.summary = {};
		vm.isWorking = false;
		vm.type = 'deliver';
		vm.items = [];

		//methods
		vm.getDeliveries = getDeliveries;
		vm.setFilter = setFilter;
		

		activate();

		function activate()
		{
			$rootScope.$emit('head.title' , 'Deliveries - Orders '); 
			$rootScope.$emit('notify.flash' , 'Please note that '+ appName+ ' does not handle the actual delivery of goods but instead provides a platform for you to easily coordinate with third party delivery companies');
			orderData.getDeliveriesSummary().then(function(data)
			{
				vm.summary = data;
			}).catch(function(){alert('Failed to get deliveries summary. Make sure you are connected to the internet.')});
			vm.setFilter('deliver');
		}

		function getDeliveries(  )
		{
			vm.isWorking = true;
			orderData.getDeliveries(vm.type).then(function(data)
			{
				vm.isWorking = false;
				vm.items = data;
			}).catch(function(){vm.isWorking=false; alert('Failed to get the deliveries. please try again !')});
		}

		function setFilter( filter )
		{
			vm.type = filter;
			getDeliveries();
		} 
	}  

	/** View shipment **/
	function OrdersViewShipmentCtrl(  $rootScope , $routeParams , $modal , orderData )
	{

	}

	function OrdersCustomerSupportCtrl($rootScope,$routeParams,$modal,orderData)
	{
		var vm =this;

	}

	/**  View ordered products modal **/
	function OrdersViewOrderedProductsModalCtrl(  $rootScope , $modalInstance ,productData ,  order  )
	{
		var vm =this;

		//attribs
		vm.order = order;
		vm.file_search = '';

		//methods
		vm.cancel = $modalInstance.dismiss;
		vm.image_url = productData.imageUrl;


		activate();
		function activate()
		{
			
		}	
	}

	function OrdersVerifyOrderModalCtrl(  $rootScope , $location , $modalInstance , orderData )
	{
		var vm = this;
		//attrs
		vm.v_code = '';
		vm.isProcessing = false;
		vm.isBackFromServer = false;
		vm.orderExists = false;
		vm.order = null;

		//methods
		vm.cancel = cancel;
		vm.verify = verify;
		vm.viewOrder = viewOrder;
		

		activate();

		function activate()
		{

		}

		function verify()
		{
			vm.isProcessing = true;
			orderData.verifyOrder( vm.v_code ).then(function(data)
			{
				vm.isProcessing = false;
				if( angular.isUndefined(data.order_id) )
				{
					vm.isBackFromServer = true;
					vm.orderExists = false;
				}
				else
				{
					orderData.getOrder( data.order_id ).then(function(order)
					{
						vm.order = order;
						vm.isBackFromServer = true;
						vm.orderExists = true;
					}).catch(function(err)
					{
						alert('Problem fetching order info. Make sure you are connected and logged in');
					})
				}
			}).catch(function(err)
			{
				alert('Problem fetching order info. Make sure you are connected and logged in');
			});
		}

		function viewOrder()
		{
			$location.url('/orders/view/' + vm.order.order_id );
			vm.cancel();
		}

		function cancel()
		{
			$modalInstance.close(null);
		}
	}

	function OrdersCollectOrderModalCtrl(  $rootScope , $location , $modalInstance , orderData )
	{
		var vm = this;
		//attrs
		vm.c_code = '';
		vm.isProcessing = false;
		vm.isBackFromServer = false;
		vm.orderExists = false;
		vm.order = null;

		//methods
		vm.cancel = cancel;
		vm.verify = verify;
		vm.viewOrder = viewOrder;
		

		activate();

		function activate()
		{

		}

		function verify()
		{
			vm.isProcessing = true;
			orderData.collectOrder( vm.c_code ).then(function(data)
			{
				vm.isProcessing = false;
				if( angular.isUndefined(data.order_id) )
				{
					vm.isBackFromServer = true;
					vm.orderExists = false;
				}
				else
				{

					orderData.getOrder( data.order_id ).then(function(order)
					{
						vm.order = order;
						vm.isBackFromServer = true;
						vm.orderExists = true;
						vm.viewOrder();
					
					}).catch(function(err)
					{
						alert('Problem fetching order info. Make sure you are connected and logged in');
					})
				}
			}).catch(function(err)
			{
				alert('Problem fetching order info. Make sure you are connected and logged in');
			});
		}

		function viewOrder()
		{
			$location.url('/orders/view/' + vm.order.order_id );
			vm.cancel();
		}

		function cancel()
		{
			$modalInstance.close(null);
		}
	}

	function OrdersDispatchModalCtrl($rootScope , $location , $modalInstance , shopData , orderData , order )
	{
		var vm = this;

		//attrs
		vm.order = order;
		vm.consignment = 
		{

		    "package": 
		    {
		        "weight": 0,
		        "dimensions": {
		            "l": 0,
		            "w": 0,
		            "h": 0
		        },
		        "contents": 
		        {
		            "fragile": 0,
		            "medical": 0,
		            "liquid": 0,
		            "type": "electronics"
		        },
		        "packaging": 
		        {
		            "type": "cardboard"
		        },
		        "comments": ""
		    },
		    "route": 
		    {
		        "source": {
		            "country": "ZW",
		            "city": "",
		            "suburb": "",
		            "address": "",
		            "is_cbd": true
		        },
		        "destination": {
		            "country": "ZW",
		            "city": "",
		            "suburb": "",
		            "address": "",
		            "is_cbd": true
		        },
		        "comments": ""
		    },
		    "contacts": 
		    {
		        "sender": {
		            "fullname": "",
		            "email": "",
		            "phone": "",
		            "address": "",

		        },
		        "receiver": {
		            "fullname": "",
		            "email": "",
		            "phone": "",
		            "address": ""
		        },
		        "comments": ""
		    },
		    "security": 
		    {
		        "strict_delivery": false,
		        "collection_code": "",
		        "alt_rcvr_phone_number": "",
		        "alt_rcvr_email": ""
		    }
		};
		vm.isProcessing = false;
		vm.currentStep = 1;
		vm.changeShopAddress = false; //by default we use the shops address
		vm.isGettingPrices = false;
		vm.quotations = {}; //actual quotations


		//methods
		vm.cancel = $modalInstance.dismiss;
		vm.isStepReady = isStepReady;
		vm.quoteMe = getPrices;
		vm.dispatch = dispatch;
		vm.boolToEnglish = function(b){ return b ? 'Yes':'No';};


		activate();

		function activate()
		{
			
			shopData.getShop().then(
				function(shop)
				{
							//set values based on order
					vm.consignment = 
						{

						    "package": 
						    {
						        "weight": 0,
						        "dimensions": {
						            "l": 0,
						            "w": 0,
						            "h": 0
						        },
						        "contents": 
						        {
						            "fragile": 0,
						            "medical": 0,
						            "liquid": 0,
						            "type": "electronics"
						        },
						        "packaging": 
						        {
						            "type": "cardboard"
						        },
						        "comments": ""
						    },
						    "route": 
						    {
						        "source": {
						            "country": shop.country,
						            "city": shop.city,
						            "suburb": '',
						            "address": shop.address
						        },
						        "destination": {
						            "country": vm.order.shipping.country,
						            "city": vm.order.shipping.city,
						            "suburb": vm.order.shipping.suburb,
						            "address": vm.order.shipping.address
						        },
						        "comments": ""
						    },
						    "contacts": 
						    {
						        "sender": {
						            "fullname": shop.name,
						            "email": shop.contact_email,
						            "phone": shop.contact_phone,
						            "address": shop.address,

						        },
						        "receiver": {
						            "fullname": vm.order.shipping.fullname,
						            "email": vm.order.shipping.email,
						            "phone": vm.order.shipping.phone_number,
						            "address": vm.order.shipping.address
						        },
						        "comments": ""
						    },
						    "security": 
						    {
						        "strict_delivery": false,
						        "collection_code": "",
						        "alt_rcvr_phone_number": vm.order.shipping.alt_phone_number
						    }
						};
				});
				
		}

		function getPrices()
		{
			vm.isGettingPrices  = true;
			
			orderData.getShippingQuotation( vm.order.order_id , vm.consignment ).then(
				function(resp)
				{
					if( resp.length == 0 )
					{
						alert('Sorry, none of the delivery agents which work with '+ appName+ ' ship to or from the specified region.');
					}
					vm.quotations = resp;
					vm.isGettingPrices = false;
				}).catch(function(err)
				{
					alert('Failed to get prices for your shipping. Make sure you are connected to the internet.');
					vm.isGettingPrices = false;
				});
		}

		function dispatch(provider)
		{
			var promise = $rootScope.prompt('Please read carefully' , 'Important' , appName +' does nt handle deliveries. We simply provide a platform for you to easily find a shipping provider/courier, calculate the costs and pay on the provider\'s website. You may be asked to provide more information on the providers website. Once you make the payment on the provider\'s website an email will be automatically sent to the customer with the tracker code and other info. Once you pay with a provider you cannot choose another provider using '+ appName +'. Do you wish to continue ?' , 'warning');
			promise.then(function(resp)
			{
				if(resp == true)
				{
					//continue 
					vm.consignment.provider = provider;
					doDispatch();
				}
			}).catch(function(){});
		}

		function doDispatch()
		{
			//open new window and post values
		    var mapForm = document.createElement("form");

		    mapForm.target = "Map";
		    mapForm.method = "POST"; // or "post" if appropriate
		    mapForm.action = "/api/shipments/dispatch/"+ vm.order.order_id + '?post';

		    var mapInput = document.createElement("input");
		    mapInput.type = "text";
		    mapInput.name = "consignment";
		    mapInput.value = angular.toJson(vm.consignment);
		    mapForm.appendChild(mapInput);

		    document.body.appendChild(mapForm);

		    map = window.open("Dispatching delivery...", "Map", "status=0,title=1,height=600,width=800,scrollbars=1");

			if (map) {
			    mapForm.submit();
			} else {
			    alert('You must allow popups for this to work.');
			}
		}

		function isStepReady(step)
		{
			switch(step)
			{
				case 1:
				{
					return ( vm.consignment.package.weight && vm.consignment.package.dimensions.l && vm.consignment.package.dimensions.w && vm.consignment.package.dimensions.h   );
				}
				break;
				case 2:
				{
					return ( vm.consignment.route.destination.address.length && vm.consignment.route.destination.city.length && vm.consignment.route.destination.country.length );
				}
				break;

			}
		}


	}
	  

})(controllers_module);
//////////////////////////// END ORDERS //////////////////////////////////////

/////////////////////////// BLOG        //////////////////////////////////////
(function(module)	    
{
	module
	     .controller('BlogEditorCtrl' , BlogEditorCtrl)
	     .controller('BlogBrowseCtrl' , BlogBrowseCtrl );

	BlogEditorCtrl.$inject = ['$scope' , '$rootScope' , '$location' , '$routeParams' , 'storage' , 'shopData' , 'userData' , 'blogData' ];
	BlogBrowseCtrl.$inject = ['$scope' , '$rootScope' , '$route' , 'blogData' , 'shopData' ];

	function BlogEditorCtrl( $scope , $rootScope , $location , $routeParams , storage , shopData , userData , blogData )
	{
		var vm = this;
		vm.post = 
		{
			title: '',
			html: '',
			author: '',
			tags: '',
			image_id: null
		};

        
        vm.image_url = shopData.imageUrl;
        vm.select_file_id = 0;    
        vm.isProcessing = false;
        vm.editMode = false;

        //methods
        vm.selectImage = selectImage;
        vm.isReady = isReady;
        vm.addArticle  = saveArticle;
        vm.saveDraft = saveDraft;
        vm.updateArticle = updateArticle;

		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('blog') )
			{
				$rootScope.to('');
				$rootScope.$emit('notify.warning' , 'You are not allowed to write blog articles.');
				return;
			}
			$rootScope.$emit('head.title' , 'Blog Editor');

			
			//check if editting
			if( angular.isDefined($routeParams.post_id) )
			{
				blogData.getPost($routeParams.post_id).then(function(data)
				{
					vm.post = data;
					
					$rootScope.$emit('notify.flash' ,'Loaded post published by - ' + vm.post.author + ' @ ' + vm.post.date_published  );
					$rootScope.$emit('head.title' ,'Edit article - ' + vm.post.title );
					vm.editMode = true;
				}).catch(function(err)
				{
					$rootScope.$emit('notify.danger' ,'Failed to load blopost make sure you are connected to the internet.' );
					$location.url('/blog/browse');
				});

			}
			else
			{
				
				//load user info
				userData.thisUser().then( function(data)
				{
					vm.post.author = data.fullname;
					vm.post.html = '<h1>[HEADER]</h1><br/><br/><p></p><hr/><b>By ' + vm.post.author + '</b>';
				}).catch(function(err){});

				//attempt to load draft article
				storage.get('draft_article').then(function(data)
				{
					vm.post = data;
					shopData.getImage( vm.post.image_id ).then(function(img)
					{
						vm.image = img;
					}).catch(function(err){vm.post.image_id = null;});

					$rootScope.$emit('notify.flash' ,'Loaded automatically saved draft post' );
				}).catch(function(err){/*not saved draft*/});


			}
			
			var unregisterFn = $rootScope.$on('file:selected' , function( evt ,resp)
			{
				if( resp.id == vm.select_file_id )
				{
					//get actual image to display
					
					shopData.getImage( resp.item ).then( function(data)
					{
						vm.post.image = data;
						vm.post.image_id = vm.post.image.image_id;
					} , function(data)
					{
						$rootScope.$emit('notify.warning' , 'Failed to select image !');
					});
				}
			});

			//save draft article
			$scope.$on('$destroy' , function(){ vm.saveDraft(); unregisterFn(); }  );

			
		}

		function saveArticle( )
		{
			$rootScope.prompt('Confirm action', 'Publish this blog post ?' , 'As soon as you publish it will become publicly available to your clients and search engines. ' , 'question-circle').then
			(function(resp)
			{
				if(resp == true)
				{
					vm.isProcessing = true;
					blogData.addPost( vm.post ).then(function(data)
					{
						vm.isProcessing = false;
						vm.post = data;
						//show all articles
						storage.clear('draft_article');
						vm.post = {};
						$rootScope.$emit('notify.flash' , 'Successfully published article .');
						$location.url('/blog/browse');
					}).catch(function(err)
					{
						vm.isProcessing = false;
						$rootScope.$emit('notify.danger' , 'Failed to publish the article ! Make sure you\'re connected online ');
					});
				}
			}).catch(function(){});
		}

		function updateArticle( )
		{
			$rootScope.prompt('Confirm action', 'Save updated blog post ?' , 'As soon as you publish it will become publicly available to your clients and search engines. ' , 'question-circle').then
			(function(resp)
			{
				if(resp == true)
				{
					vm.isProcessing = true;
					vm.post.image_id = vm.post.image.image_id;
					blogData.updatePost( vm.post ).then(function(data)
					{
						vm.isProcessing = false;
						vm.post = data;
						//show all articles
						storage.clear('draft_article');
						vm.post = {};
						$rootScope.$emit('notify.flash' , 'Successfully updated article - ' + vm.post.title );
						$location.url('/blog/browse');
					}).catch(function(err)
					{
						vm.isProcessing = false;
						$rootScope.$emit('notify.danger' , 'Failed to save the article ! Make sure you\'re connected online ');
					});
				}
			}).catch(function(){});
		}

		//dont save if not ready and dont save if in edit mode
		function saveDraft( )
		{
			if( ! vm.isReady() || vm.editMode )
				return;
			storage.set('draft_article' , vm.post );
			$rootScope.$emit('notify.flash' , 'Draft article saved !' );
		}

		function selectImage( )
		{
			vm.select_file_id = Math.random();
			var setup = 
			{
				type: 	'shop_image',
				upload: true,
				id: 	vm.select_file_id
			};				
			$rootScope.$emit('file:select' , setup );

		}

		function isReady()
		{
			return (  vm.post.title.length && vm.post.html.length &&
					  vm.post.author.length && vm.post.tags.length &&
					  vm.post.image_id );
		}
	}   

	function BlogBrowseCtrl($scope , $rootScope , $route ,  blogData , shopData )
	{
		var vm = this;

		//attrs
		vm.posts = [];
		vm.post_search = '';

		//methods
		vm.image_url = shopData.imageUrl;
		vm.reloadPosts = reloadPosts; 
		vm.deleteArticle = deleteArticle;

		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('blog') )
			{
				$rootScope.to('');
				$rootScope.$emit('notify.warning' , 'You are not allowed to write blog articles.');
				return;
			}
			$rootScope.$emit('head.title' , 'Loading blog posts');
			blogData.getAll().then(function(data)
			{
				vm.posts = data;
				$rootScope.$emit('head.title' , 'Browse blog posts');
			}).catch(function(err)
			{
				$rootScope.$emit('notify.danger' , 'Failed to load blog posts, make sure you are connected to the internet');
			});
			
		}

		function reloadPosts()
		{
			vm.posts = [];
			$rootScope.$emit('storage:clear' , 'blogposts');
			$rootScope.$emit('head.title' , 'Loading blog posts');
			
			$route.reload();
		}

		function deleteArticle(post)
		{
			var promise = $rootScope.prompt('Are you sure?' , 'Delete article ' + post.title + ' ? ' , 'This action cannot be undone and once deleted, all links pointing to that page will be broken.' , 'warning');
			promise.then(function(resp)
			{
				if(resp == true )
				{
					blogData.removePost( post.post_id ).then( function(data)
					{
						$rootScope.$emit('notify.flash' , 'Successfully deleted article - ' + post.title );
						vm.reloadPosts();
					}).catch(function(err)
					{
						$rootScope.$emit('notify.danger' , 'Failed to remove article ! Please try again');
						vm.reloadPosts();
					});
				}
			}).catch(function(err){});
		}
	}  

})(controllers_module);
////////////////////// END BLOG ///////////////////////////

///////////////////// PRODUCTS  ///////////////////////////
(function(module)
{
	module
	.controller('ProductsBrowseCtrl' , ProductsBrowseCtrl )
    .controller('ProductsViewProductCtrl' , ProductsViewProductCtrl )
    .controller('ProductsAddProductCtrl' , ProductsAddProductCtrl )
    .controller('ProductsCategoriesCtrl' , ProductsCategoriesCtrl )
    .controller('ProductsAddCategoryModalCtrl' , ProductsAddCategoryModalCtrl)
    .controller('ProductsStockManagerCtrl' , ProductsStockManagerCtrl )
    .controller('ProductsViewCategoryCtrl' , ProductsViewCategoryCtrl);

	ProductsBrowseCtrl.$inject = ['$rootScope' , 'shopData' , 'productData'];
	ProductsViewProductCtrl.$inject = ['$rootScope' , '$routeParams' , '$location' , 'orderData' , 'productData'];
	ProductsAddProductCtrl.$inject = [ '$scope' , '$rootScope' , '$location' , '$routeParams' , 'productData'  ];
	ProductsCategoriesCtrl.$inject= ['$scope' , '$rootScope' , '$modal' , '$location' ,  'productData'];
	ProductsViewCategoryCtrl.$inject =[ '$scope' , '$rootScope' , '$routeParams' , '$location' , 'productData' ];
	ProductsStockManagerCtrl.$inject = ['$scope' , '$rootScope' , '$modal' , 'productData' , 'orderData'];
	ProductsAddCategoryModalCtrl.$inject = ['$scope' , '$rootScope' , '$location',  '$modalInstance' , 'productData' ]; 
  	
	function ProductsBrowseCtrl($rootScope , shopData , productData )
	 {
	 	var vm = this;
	 	vm.totalItems = 150;
	 	vm.currentPage = 12;
		vm.products = {};
	 	vm.product_search = '';
	 	vm.permissionEdit = false;

	 	vm.image_url = productData.imageUrl;
	 	vm.product_url = productData.productUrl;
	 	vm.reloadProducts = reloadProducts;


	 	activate();

	 	function activate()
	 	{
	 		$rootScope.$emit('head.title' , 'Loading products ');
	 		vm.permissionEdit = $rootScope.has_permission('manage_products');
	 		
	 		productData.getAll().then(function(data)
	 		{
	 			vm.products = data;
	 			$rootScope.$emit('head.title' , 'Browse Products');
	 		}).catch(function(data)
	 		{
	 			$rootScope.$emit('notify.warning' , 'Failed to load products !');
	 		});
	 	}

	 	function reloadProducts( )
	 	{
	 		$rootScope.$emit('storage:clear' , 'products');
	 		activate();
	 	}
	 } 

	function ProductsViewProductCtrl(  $rootScope , $routeParams , $location , orderData , productData )
	 {
	 	var vm = this;
	 	
	 	vm.product = {};
	 	vm.permissionEdit = false;
	 	vm.categories = [];

	 	vm.image_url = productData.imageUrl;
	 	vm.file_url = productData.fileUrl;
	 	vm.product_url = productData.productUrl;
	 	vm.order_url = orderData.orderUrl;
	 	vm.deleteProduct = deleteProduct;


	 	activate();

	 	function activate()
	 	{
	 		vm.permissionEdit = $rootScope.has_permission('manage_products');

	 		productData.get( $routeParams.product_id ).then( function(data)
	 		{
	 			vm.product = data;
	 			//categories
		 		productData.getCategories().then(function(data2)
		 		{
		 			vm.categories = data2;
		 		}).catch(function(err)
		 		{
		 			alert('Failed to load product categories');
		 		});
				 		
	 			$rootScope.$emit('head.title' , 'View Product: ' + vm.product.name + ' #' + vm.product.product_id );
	 		}).catch( function(data)
	 		{
	 			$rootScope.$emit('notify.warning' , 'Failed to load product');
	 		});

	 		productData.getCategories().then(function(data)
	 		{
	 			vm.categories = data;
	 		}).catch(function(err)
	 		{
	 			alert('Failed to load product categories');
	 		});

	 		$rootScope.$emit('head.title' , 'Loading product...');

	 	}

	 	function deleteProduct(  )
	 	{
	 		var msg ='Deleting this product is an undoable action and will affect all orders involving this product. All pending orders with this product will be altered and an email will be sent to telling your customers of the change. Please understand this before you do something you will regret';
	 		$rootScope.prompt( 'Are you really sure ?' , 'Permanently delete ' + vm.product.name + ' ? ' , msg , 'times' ).then
	 		(function(resp)
	 		{
	 			if( resp == true )
	 			{
	 				//@todo delete the product
	 				productData.deleteProduct( vm.product.product_id ).then(
	 				function(response)
	 				{
	 					//deleted 
	 					$rootScope.$emit('notify.success' , 'Successfully deleted product (  ' + vm.product.name + ' ) notifying customers of the change. ');
	 					$location.url('/products/browse');
	 				}).catch(
	 				function(response)
	 				{
	 					$rootScope.$emit('notify.warning' , 'Failed to delete the product. Try again :( ');
	 				}
	 				)
	 			}
	 		});

	 	}

	 }

	function ProductsAddProductCtrl( $scope , $rootScope , $location , $routeParams , productData , product )
	 {
	 	var vm = this;

	 	//attr
	 	vm.product = {
	 		name: '',
	 		brand: '',
	 		price: '',
	 		tags: '',
	 		type: 'physical',
	 		category_id: 1,
	 		images: [],
	 		file: {},
	 		attributes: [
		 	],
	 		description: '<h3>Product description here</h3>',
	 		min_orders: 1,
	 		max_orders: 10,
	 		is_featured: "0",
	 		stock_sold: 0,
	 		stock_left: 20,
	 		file_id: -1,
	 		weight_kg: ''
	 	};
	 	vm.isOrdered = false; //is the product currently ordered
	 	vm.editMode = false;

	 	
	 	vm.image_id = 0; //id used to track and verify image uploads
	 	vm.file_id = 0; //id to check and verify file uploads/selects
	 	vm.isProcessing = false;
	 	vm.addProductFailed = false;
	 	vm.addProductFailMsg = 'Check that you did not make a mistake !';

	 	//methods
	 	vm.selectFile = selectFile;
	 	vm.selectImage = selectImage;
	 	vm.removeImage = removeImage;
	 	vm.addAttribute = addAttribute;
	 	vm.removeAttribute = removeAttribute;
	 	vm.addOption = addOption;
	 	vm.isReady = isReady; //ready to add product ?
	 	vm.removeAttributeOption = removeAttributeOption;
	 	vm.addProduct = addProduct;
	 	vm.updateProduct = updateProduct;

	 	vm.image_url = productData.imageUrl;
	 	vm.file_url = productData.fileUrl;
	 	vm.product_url = productData.productUrl;
	 	


	 	activate();

	 	function activate()
	 	{
	 		if( ! $rootScope.has_permission('manage_products') )
	 		{
	 			$rootScope.to('products/browse');
	 			$rootScope.$emit('notify.warning' , 'You are not allowed to manage products.');
	 			return;
	 		}
	 		if( angular.isDefined($routeParams.product_id))
	 		{
	 			$rootScope.$emit('head.title' , 'Loading product #' + $routeParams.product_id );
	 			productData.get( $routeParams.product_id ).then(
	 				function(product)
	 				{
	 					vm.product = product;
	 					$rootScope.$emit('head.title' , 'Edit Product - ' + product.name );
	 					vm.editMode = true;
	 					//check if product is currently ordered
	 					productData.isOrdered( vm.product.product_id ).then
	 					(function(data)
	 					{
	 						vm.isOrdered = data.count;
	 					}, function(data)
	 					{
	 						$rootScope.$emit('notify.warning' , 'WARNING: Could not determine how many orders this product is part of. Edit this product if you are sure it wont affect any other orders ! ');
	 					}
	 					).catch(function(err){ alert('An unknown error occured')});

	 					//load product if specified
	 					if( vm.product.file_id > 0)
	 					{
	 						productData.getFile( vm.product.file_id ).then( function(data)
				 			{
				 				vm.product.file =  data;
				 				vm.product.file_id = data.file_id;
				 			}).catch(function(err){alert('Failed to get the file.');});
	 					}


	 				}).catch( function()
	 				{
	 					$rootScope.$emit('notify.warning' , 'Failed to load product for editting !');
	 					vm.editMode = false;
	 				});
	 		}
	 		else
	 		{
	 			$rootScope.$emit('head.title' , 'Add a new product');
	 		}

	 		//categories
	 		productData.getCategories().then(function(data)
	 		{
	 			vm.categories = data;
	 		}).catch(function(err)
	 		{
	 			alert('Failed to load product categories');
	 		});

	 		//listen for file selected events
	 		var unregisterFn = $scope.$on('file:selected' , function(evt , resp)
	 		{
	 			
	 			if( resp.item < 0 )
	 				return;
	 			//upload/select image
	 			if( resp.id == vm.image_id )
	 			{
	 				productData.getImage( resp.item ).then( function(data)
		 			{
		 				vm.product.images.push( data );
		 			}).catch(function(err){alert('Failed to get the image.');});;
	 					
	 			}
	 			//upload/select file
	 			if(  resp.id == vm.file_id)
	 			{
	 				
	 				productData.getFile( resp.item ).then( function(data)
		 			{
		 				vm.product.file =  data;
		 				vm.product.file_id = data.file_id;
		 			}).catch(function(err){alert('Failed to get the file.');});
	 					
	 			}
	 			
	 			
	 		} );

	 		

	 		//unregister filemanager listeener on destroy
	 		$scope.$on('$destroy' , unregisterFn );

	 	}

	 	function isReady( )
	 	{
	 		if( vm.product.type == 'virtual')
	 		{
	 			if( vm.product.file_id == -1 )
	 				return false;
	 		}	
	 		else
	 			if( ! vm.product.weight_kg.length )
	 				return false;	

	 		return ( vm.product.name.length  
	 			&& vm.product.brand.length 
	 			 && vm.product.images.length);
	 	}

	 	function addProduct()
	 	{
	 		if( ! vm.isReady() )
	 		{
	 			alert('Cannot add product just yet !');
	 			return;
	 		}
	 		vm.isProcessing = true;
	 		productData.addProduct( vm.product ).then( function(product)
	 		{
	 			vm.isProcessing = false;
	 			if( angular.isDefined(product.error))
	 			{
	 				vm.addProductFailed = true;
	 				vm.addProductFailMsg = product.msg;
	 				return;
	 			}
	 			vm.addProductFailed = false;
	 			$scope.$emit('storage:clear' , 'products');
	 			$scope.$emit('notify.flash' , 'Successfully added product ' + vm.product.name );
	 			$location.url('/products/browse');
	 		} , function(data)
	 		{
	 			vm.isProcessing = false;
	 			vm.addProductFailed = true;
	 			vm.addProductFailMsg = "Failed to add product, make sure you're connected to the internet";
	 		} );

	 	}

	 	function updateProduct()
	 	{
	 		if( ! vm.isReady() )
	 		{
	 			alert('Cannot add product just yet !');
	 			return;
	 		}
	 		var promise = $rootScope.prompt('','This action may have side-effects','If you change the product price. All orders in which the product is listed, will be cancelled and an email sent to the customer.','warning');

	 		promise.then(function(resp)
	 		{
	 			if(resp == true)
	 			{
	 				vm.isProcessing = true;
			 		productData.updateProduct( vm.product ).then( function(product)
			 		{
			 			vm.isProcessing = false;
			 			if( angular.isDefined(product.error))
			 			{
			 				vm.addProductFailed = true;
			 				vm.addProductFailMsg = product.msg;
			 				return;
			 			}
			 			vm.addProductFailed = false;
			 			$scope.$emit('storage:clear' , 'products');
			 			$scope.$emit('notify.flash' , 'Successfully updated product ' + vm.product.name );
			 			$location.url('/product/view/' + vm.product.product_id );
			 		} , function(data)
			 		{
			 			vm.isProcessing = false;
			 			vm.addProductFailed = true;
			 			vm.addProductFailMsg = "Failed to update product, make sure you're connected to the internet";
			 		} );
	 			}
	 		}).catch(function(err){});

	 	}



	 	function selectImage()
	 	{
	 		vm.image_id = Math.random();
	 		var setup =
	 		{
	 			type: 'product_image',
	 			id: vm.image_id,
	 			upload: true //can upload new file
	 		};
	 		$scope.$emit('file:select' , setup );
	 		//listen for file uploaded evt
	 		
	 	}

	 	function selectFile()
	 	{
	 		vm.file_id = Math.random();
	 		var setup =
	 		{
	 			type: 'product_file',
	 			id: vm.file_id,
	 			upload: true
	 		};
	 		$scope.$emit('file:select' , setup );
	 		//listen for file uploaded evt
	 	}

	 	function selectProductFile()
	 	{
	 		vm.product_file_id = Math.random();
	 		var setup =
	 		{
	 			type: 'product_file',
	 			id: vm.product_file_id,
	 			upload: true
	 		};
	 		$scope.$emit('file:upload' , setup );
	 		//listen for file uploaded evt
	 	}

	 	function removeImage(index )
	 	{
	 		vm.product.images.splice(index , 1); 
	 	}

	 	function removeFile( index )
	 	{
	 		vm.product.files.splice(index , 1);
	 	}

	 	function addAttribute(  )
	 	{
	 		vm.product.attributes.push( {attribute_name: '' , attribute_value: '' , options: []} );
	 	}

	 	function removeAttribute(index)
	 	{
	 		vm.product.attributes.splice(index , 1 );
	 	}

	 	function addOption( parent_index )
	 	{
	 		
	 		var attr = vm.product.attributes[parent_index];
	 		attr.options.push( {value: ''} );
	 	}

	 	function removeAttributeOption(  parent_index , index )
	 	{
	 		
	 		var attr = vm.product.attributes[parent_index ];
	 		attr.options.splice( index , 1 );
	 	}
	}

	function ProductsCategoriesCtrl( $scope , $rootScope ,  $modal , $location , productData  )
	{
		var vm = this;

		//attrs
		vm.categories = [];
		vm.category = {};
		vm.permissionEdit = false;

		//methods
		vm.addCategory = addCategory;
		vm.categoryUrl = productData.categoryUrl;
		vm.imageUrl = productData.imageUrl;

		activate();

		function activate( )
		{
			$rootScope.$emit('head.title' , 'Loading product categories');
			vm.permissionEdit = $rootScope.has_permission('manage_products');

			productData.getCategories().then( function(data)
			{
				$rootScope.$emit('head.title' , 'Product Categories');
				vm.categories = data;
			}).catch( function(data)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load the product categories !');
			});

		}

		function addCategory( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'addProductCategory.html',
		      controller: 'ProductsAddCategoryModalCtrl',
		      controllerAs: 'vm',
		      size: 'sm'
		      
		    });

		    
		}
	}

	function ProductsAddCategoryModalCtrl( $scope , $rootScope , $location , $modalInstance , productData )
	{
		var vm = this;

		vm.isProcessing = false;
		vm.categories = [];
		vm.cat_image = null;
		vm.imgUrl = null;
		vm.isFailedAdd = false;

		vm.category = 
		{
			name: 		'',
			description:'',
			is_menu: 	false,
			parent_id: 	0,
			image_id:   0 
		};



		//method
		vm.isReady = isReady;
		vm.addCategory = addCategory;
		vm.selectImage = selectImage;

		activate();

		function activate()
		{
			vm.permissionEdit = $rootScope.has_permission('manage_products');
			if( ! vm.permissionEdit ) 
			{
				$rootScope.$emit('notify.warning' , 'You are not allowed to create or edit product categories.');
				$rootScope.to('');
				return;
			}
		}

		function addCategory( )
		{
			vm.isProcessing = true;
			vm.isFailedAdd = false;
			productData.addCategory( vm.category ).then(
				function(data)
				{
					vm.isFailedAdd = false;
					//returns category,
					$rootScope.$emit('storage:clear' , 'product_categories');
		    		$location.url('#/products/view_category/' + data.category_id );
					$modalInstance.close( data.category_id );
				}).catch( function(data)
				{
					vm.isProcessing = false;
					vm.isFailedAdd = true;
				})
		}

		function selectImage( )
		{
			var id = Math.random();
			var setup = 
			{
				type: 'product_image',
				id:   id,
				upload: true
			};

			$rootScope.$emit('file:select' , setup );

			var unregisterFn = $rootScope.$on('file:selected' , function(evt , data)
			{
				if( data.id == id)
				{
					//evt.stopPropagation();
					productData.getImage( data.item ).then
					(function(img)
						{ 
							vm.cat_image = img;
							vm.category.image_id = img.image_id;  
							vm.imgUrl = productData.imageUrl( vm.cat_image );
						}
					);
				}
			});

			$scope.$on('$destroy' , unregisterFn );
		}

		function isReady()
		{
			return ( vm.category.name.length && vm.category.description.length && (vm.category.image_id > 0 ) );

		}
	}

	function ProductsViewCategoryCtrl( $scope , $rootScope , $routeParams , $location , productData )
	{
		var vm = this;

		vm.category = {};
		vm.products = [];
		vm.imageUrl = productData.imageUrl;
		vm.deleteCategory = deleteCategory;
		vm.product_search = '';


		activate();

		function activate()
		{
			$rootScope.$emit('head.title' , 'Loading Category ... ');
			productData.getCategory( $routeParams.category_id ).then(
				function(data)
				{
					vm.category = data;
					$rootScope.$emit('head.title' , vm.category.name + ' - Product Category ');

					//load products
					productData.getAll( ).then(function(products)
					{
						products.every(function(element, index, array) {
							if( element.category_id == vm.category.category_id )
							{
								vm.products.push( element );
							}
						});
						//@todo filter products and get only this category
						
					});
				}).catch( function(err)
				{
					$rootScope.$emit('notify.warning' , 'Failed to load the category.');
					$location.url('#/products/categories');
				});
		}

		function deleteCategory(  )
		{
			$rootScope.prompt('Are you really really sure ?' , 'Delete category - ' + vm.category.name , 'You cannot undo this action. Products with this category will be left and "orphaned". You will have to manually delete all of them.' , 'warning' ).then
			(function(response)
			{
				if( response == true )
				{
					//@todo delete this category
					alert('@todo delete category');
					productData.removeCategory( vm.category.category_id ).then(function(data)
						{

						});
				}
			});
		}
	}

	function ProductsStockManagerCtrl( $scope , $rootScope , $modal ,  productData , orderData)
	{
		var vm = this;

		//attrs
		vm.product_search = '';
		vm.products = [];
		vm.isEditting = false;
		vm.edittingProduct = null;
		vm.editOriginalValue = null;
		vm.permissionEdit = false;


		//methods
		vm.image_url = productData.imageUrl;
		vm.addStock = addStock;
		vm.showProduct = showProduct;
		vm.clearChanges = clearChanges;
		vm.cancelOrders = cancelOrders;
		vm.cacheReload = cacheReload;
		vm.saveStock = saveStock;

		activate();

		function activate()
		{
			vm.permissionEdit = $rootScope.has_permission('manage_products');
			if( ! vm.permissionEdit )
			{
				$rootScope.$emit('notify.warning' , 'You are not allowed to manage product stock.');
				$rootScope.to('');
				return;
			}

			$rootScope.$emit('head.title' , 'Stock Manager');
			vm.isEditting = false;
			
			productData.clearCache();
			productData.getAll().then(function(data)
			{
				vm.products = data;
			}).catch( function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load products !');
			});
		}

		function saveStock( product )
		{
			var promise = $rootScope.prompt('','Save changes ?','Confirm action');
			promise.then(function(resp)
			{
				if(resp == true )
				{
					productData.setStock( product.product_id , product.stock_left ).then(function(data)
					{
						if(data.status == 'ok')
						{
							$rootScope.$emit('notify.flash', 'Updated ' + product.name + ' stock. ' + product.stock_left + ' items now in stock ');
							vm.isEditting = false;
							vm.editOriginalValue = product.stock_left;
							activate();
						}
						else
						{
							alert(data.msg);
							activate();
						}
					}).catch(function(data)
					{
						alert('Failed to change the product\'s stock. Make sure you are connected to the internet');
					});
				}
			}).catch(function(){});
		}

		function addStock( num , product )
		{
			
			var index = vm.products.indexOf( product );
			vm.products[ index ].stock_left = parseInt( product.stock_left );
			if( vm.editOriginalValue == null )
			{
				vm.isEditting = true;
				vm.edittingProduct = product.product_id;
				vm.editOriginalValue = vm.products[ index ].stock_left;
			}
			vm.products[ index ].stock_left += num;
		}

		function showProduct(product )
		{
			if( ! vm.isEditting )
				return true;
			if(product.product_id == vm.edittingProduct )
				return true;
			return false;
		}

		function clearChanges(product)
		{
			product.stock_left = vm.editOriginalValue;
			vm.editOriginalValue = null;
			vm.isEditting = false;

		}

		function cancelOrders( product )
		{
			var promise = $rootScope.prompt('Are you really really sure ?' ,'Cancel all orders containing '+ product.name + '?', 'This will DELETE ' + product.stock_ordered + 'orders which contain ' + product.name + '! Please make sure you know exactly what you are doing before continuing, emails will be sent to customers notifying them that their orders have been cancelled. This action cannot be undone', 'remove');
			promise.then(function(resp)
			{
				if( resp == true)
				{
					orderData.cancelWhereOrdered( product.product_id ).then(function(data)
					{
						$rootScope.$emit('notify.flash' , 'Cancelled all pending orders containing ' + product.name );
						vm.cacheReload();
					}).catch(function(err)
					{
						$rootScope.$emit('notify.warning' , "Failed to cancel orders containing " + product.name );
					});
				}
			}).catch(function(){});
		}

		function cacheReload( )
		{
			$rootScope.$emit('storage:clear' , 'products');
			activate();
		}

	}
}
)(controllers_module);
///////////////////// END PRODUCTS /////////////////////////	    

///////////////////// SETTINGS /////////////////////////////
(function(module)
{
	module
		.controller('SettingsUsersCtrl' , SettingsUsersCtrl )
	    .controller('SettingsGeneralCtrl' , SettingsGeneralCtrl)
	    .controller('SettingsAccountCtrl', SettingsAccountCtrl )
	    .controller('SettingsPaymentsCtrl' , SettingsPaymentsCtrl)
	    .controller('SettingsAnalyticsCtrl' , SettingsAnalyticsCtrl )	    
	    .controller('SettingsShippingCtrl' , SettingsShippingCtrl )
	    .controller('SettingsShippingRulesModalCtrl' , SettingsShippingRulesModalCtrl )
	    .controller('SettingsAddUserModalCtrl' , SettingsAddUserModalCtrl )
	    .controller('SettingsChangePasswordModalCtrl' , SettingsChangePasswordModalCtrl );

	SettingsUsersCtrl.$inject = ['$rootScope' , '$modal' , 'userData'];
	SettingsGeneralCtrl.$inject = ['$rootScope' , '$scope' , 'userData' , 'shopData' , 'accountData' ];
	SettingsAccountCtrl.$inject = ['$rootScope','$scope','$routeParams','accountData','shopData','userData'];
	SettingsPaymentsCtrl.$inject = ['$rootScope' , 'shopData' , 'accountData'];
	SettingsAnalyticsCtrl.$inject = ['$rootScope' , 'shopData' , 'accountData'];
	SettingsShippingCtrl.$inject = ['$rootScope' , '$scope' , '$modal' ,'shopData' , 'accountData'];
	SettingsShippingRulesModalCtrl.$inject = ['$rootScope' , '$modalInstance' , 'shopData' , 'rules'];
	SettingsAddUserModalCtrl.$inject = ['$rootScope' , '$modalInstance' , 'userData'];
	SettingsChangePasswordModalCtrl.$inject = ['$rootScope' , '$modalInstance' , 'userData' ,'user'];

	//general
	function SettingsGeneralCtrl( $rootScope ,$scope , userData , shopData , accountData)
	{
		var vm = this;

		//atrtrs
		vm.shop = {logo: {}};
		vm.domain = { subdomain:  'www' , domain: 'example' , tld: '.co.zw' };
		vm.subscription = {};
		vm.settings = {};
		vm.isProcessing = false;
		vm.isChanged = false;
		vm.isLoaded = false;
		vm.savedShop = false;
		vm.savedSettings = false;
		vm.userPassword = '';
		vm.change_subdomain = false;
		vm.subdomain_available = true;
		vm.checking_subdomain = false;
		vm.check_domain_err =null;
		vm.checking_dns =false;
		vm.dns_ok = false;
		vm.oneshop_ip_addr = hostedDomain;


		//methods
		vm.image_url = shopData.imageUrl;
		vm.changeLogo = changeLogo;
		vm.saveSettings = saveSettings;
		vm.unpublishShop = unpublishShop;
		vm.changeSubDomain = changeSubDomain;
		vm.isSubdomainOk = isSubdomainOk;
		vm.isCustomSubdomainOk = isCustomSubdomainOk;
		vm.checkDNS = checkDNS;
		vm.saveCustomDomain =saveCustomDomain;
		

		activate();

		function activate()
		{
			if( ! $rootScope.is_admin() )
			{
				
				$rootScope.to('dashboard');
				return;
			}

			$rootScope.$emit('head.title' , 'General Settings');
			//shop
			shopData.getShop().then(function(data)
			{
				vm.shop = data;
				vm.shop.is_active = parseInt(  vm.shop.is_active );
				vm.shop.is_suspended = parseInt(  vm.shop.is_suspended );
				
				vm.change_subdomain = false;
				vm.shop.is_suspended = parseInt( vm.shop.is_suspended );
				createDomainFromAlias();
			
				
			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to connect to internet. ');
			});
			//subscription
			accountData.getSub().then(function(data)
			{
				vm.subscription = data;
			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load your current subscription. Try Again');
			});

			//change logo file select
			var unregisterFn = $rootScope.$on('file:selected' , function(evt , data)
			{
				if( data.id == vm.id && data.item )
				{
					//prompt save
					$rootScope.prompt('Confirm action' , 'Change your shop logo' , 'The changes will be saved immediately. The old logo will not be deleted.' , 'photo').then
					(function(resp)
					{	
						if( resp == true )
						{
							//save changes
							shopData.changeLogo( data.item ).then(function(data2)
							{
								shopData.getImage( data.item ).then(function(data3)
								{
									vm.shop.logo = data3;
									$rootScope.$emit('notify.flash' , 'Successfully updated your logo!');
								}).catch(function(err){alert('Failed to load logo image'); });
							}).catch(function(err)
							{
								$rootScope.$emit('notify.warning' , 'Failed to update your logo. Please try again');
							});
						}
						
					}).catch(function(err){});
						
				}
				
			});

			//get shop settings
			shopData.getSettings('all').then(function(data)
			{
				vm.settings = data;
			}).catch(function(err){ $rootScope.$emit('notify.warning' , 'Failed to load shop settings !')});


			//watch changes
			$scope.$on('$destroy' , unregisterFn);
			var unregisterFn1 = $scope.$watch( function(){ return vm.domain.subdomain + '.' + vm.domain.domain  + vm.domain.tld } , createAlias   );
			//var unregisterFn2 = $scope.$watch(function(){ return vm.shop.alias} , checkDNS );
			$scope.$on('$destroy' , unregisterFn1);
			//$scope.$on('$destroy' , unregisterFn2);



		}

		function isSubdomainOk()
		{
			return (vm.shop.subdomain.length >= 4) && (vm.shop.subdomain.length <= 25);
		}

		//@todo checkif subdomain is ok
		function isCustomSubdomainOk()
		{
			return true;
		}

		function createAlias()
		{
			vm.shop.alias = vm.domain.subdomain + '.' + vm.domain.domain + vm.domain.tld;
		}

		function createDomainFromAlias()
		{
			if( vm.shop.alias.length < 6 )
				return vm.domain = { subdomain: '', domain: '',tld: ''};
			var x=0, tmp='';
			x = vm.shop.alias.indexOf('.');
			if( x < 0 )
				return;
			tmp = vm.shop.alias.slice( x +1, vm.shop.alias.length );
			vm.domain.subdomain = vm.shop.alias.slice(0,x);
			x = tmp.indexOf('.');
			if ( x < 0)
				return;
			vm.domain.domain = tmp.slice(0,x);
			vm.domain.tld = tmp.slice(x,tmp.length);
		}

		function changeSubDomain()
		{
			
			if( ! vm.change_subdomain )
			{
					vm.change_subdomain = true;
					checkSubDomainAvailable();
					var unregisterFn =$scope.$watch( function(){ return vm.shop.subdomain} , checkSubDomainAvailable );
					$scope.$on('$destroy' , unregisterFn);
					return;
			}

			var promise = $rootScope.prompt('','Change your subdomain ?' , 'Changing your subdomain has serious side-effects. All previous links you had shared using the old subdomain will become invalid and your search engine rankings will fall because of this. Traffic from your old subdomain will not be forwarded to your new subdomain. We recommend you only change your subdomain whilst your shop is not popular and remember to update your payment gateway settings as well as your analytics tracker info. More details to help you will be sent to your email. ' ,'warning');

			promise.then(function(resp)
			{
				if(resp === true)
				{
					//request to change subdomain
					shopData.changeSubdomain( vm.shop.subdomain ).then(function(data)
					{
						if(data.status == 'ok')
						{
							var msg = 'Your shop\'s subdomain has been changed. You will be logged out and redirected to your new subdomain, where you will login the same login details as before. ';
							$rootScope.$emit('notify.info', msg );
							alert( msg);
							window.location = '/admin/logout';
						}
						else
						{
							alert(data.err);
						}
						activate();
					})
				}
			}).catch(function(err)
			{});
		}

		function checkDNS()
		{
			if( ( ! vm.shop.alias.length ) || ! vm.isCustomSubdomainOk())
				return;
			vm.checking_dns = true;
			vm.dns_ok = false;

			shopData.checkDNSOk( vm.shop.alias ).then(function(data)
			{
				vm.checking_dns =false;
				if( data.alias != vm.shop.alias )
					return false;
				if(data.status == 'ok')
				{
					
					vm.dns_ok = true;

				}
				else
				{
					vm.dns_ok = false;

					alert(data.err);
				}
			}).catch(function(err){});

		}

		function saveCustomDomain()
		{
			var promise = $rootScope.prompt('','Change your subdomain ?' , 'Changing your subdomain has serious side-effects. All previous links you had shared using the old subdomain will become invalid and your search engine rankings will fall because of this. Traffic from your old subdomain will not be forwarded to your new subdomain. We recommend you only change your subdomain whilst your shop is not popular and you know exactly what you are doing! Remember to update your payment gateway settings as well as your analytics tracker info. More details to help you will be sent to your email. ' ,'warning');

			promise.then(function(resp)
			{
				if(resp === true)
				{
					shopData.changeAlias( vm.shop.alias , vm.shop.use_alias ).then(function(data)
					{
						if(data.status == 'ok')
						{
							var msg = 'Your shop\'s subdomain has been changed. You will be logged out and redirected to your new subdomain, where you will login the same login details as before. ';
							$rootScope.$emit('notify.info', msg );
							alert( msg);
							window.location = data.shop.url + '/admin/login';
						}else
						{
							alert('An unknown error occured');
						}
					}).catch(function(err){ alert('An error occured whilst saving your custom domain'); });
				}
			}).catch(function(err){});
		}

		function checkSubDomainAvailable( )
		{
			var l =vm.shop.subdomain.length;
			if( l <4 || l > 25 )
				return;


			vm.check_domain_err = null;
			vm.subdomain_available = null;
			vm.checking_subdomain =true;
			shopData.subdomainAvailable( vm.shop.subdomain ).then(function(data)
			{
				if(data.subdomain == vm.shop.subdomain)
				{
					vm.checking_subdomain = false;
					if( angular.isDefined(data.err) )
					{
						vm.check_domain_err = data.err;

					}
					
					vm.subdomain_available = !data.registered;
				}
			}).catch(function(err)
			{
				vm.subdomain_available =false;
				$rootScope.$emit('notify.warning','Failed to check if subdomain available due to bad internet connection');
			});
		}

		function changeLogo( )
		{
			var setup = 
			{
				id: Math.random() , 
				upload: true,
				select: true,
				type: 'shop_image'
			};

			vm.id = setup.id;

			$rootScope.$emit('file:select' , setup );
		}

		function saveSettings( )
		{
			$rootScope.prompt('Update shop settings ?' , 'Save these settings ?' , 'The changes will take effect emmediately in your website, please make sure you have entered the correct details' , 'question-circle').then
			(function(resp)
			{
				if( resp == true)
				{
					vm.isProcessing = true;
					vm.savedSettings = false;
					vm.savedShop = false;
					//save contact details
					shopData.saveSettings( 'contact' , vm.settings.contact ).then(function(data)
					{
						vm.settings.contact = data;
						vm.savedSettings = true;
						$rootScope.$emit('notify.flash' , 'Successfully updated your shop contact and social details ');

						if(  vm.savedShop )
							vm.isProcessing = false;
					}).catch(function(err)
					{
						$rootScope.$emit('notify.danger' , 'Failed to save your shop settings. Make sure you are connected to the internet.');
					});
					//save core settings
					shopData.saveSettings( 'core' , vm.shop ).then(function(data)
					{
						if(  data.url != vm.shop.url )
						{
							alert('Your shop\'s url has been changed and you will now be redirected to your new shop url login page. ' );
							window.location = data.url + 'admin#/settings/general';
						}
						vm.shop = data;
						vm.savedShop = true;
						$rootScope.$emit('notify.flash' , 'Successfully updated your shop details ');
						if(  vm.savedSettings )
							vm.isProcessing = false;
					}).catch(function(err)
					{
						$rootScope.$emit('notify.danger' , 'Failed to update your shop settings. Make sure you are connected to the internet.');	
					}
					);
				}else
				{
					$rootScope.$emit('notify.flash' , 'Settings not saved !');
				}
			}).catch(function(err){});
		}

		

		function unpublishShop( )
		{
			//if published, unpublished
			var action_p = vm.shop.is_suspended ;
				
			if( ! vm.userPassword.length )
				return alert('You must enter your password to perform change');
			var title = (action_p ) ? "Your shop will be made publicly available" : "Your shop will no longer be available to the public";
			var msg = ( action_p ) ? "Everyone who visits your shop website will be able to see your shop online. " : "Only you and other logged in user will be able to see the shop, this is useful if for example you are implementing alot of changes";
			var icon = ( action_p  ) ? 'save' : 'remove';
			var promise = $rootScope.prompt('',title,msg,icon);

			var password = angular.copy( vm.userPassword );
			vm.userPassword = '';
			
			promise.then( function(resp)
			{
				if( resp != true )
					return;
				//unpublish shop
				shopData.publish( action_p , password ).then(function(data)
				{
					vm.shop = data;
					var msg = ( vm.is_published == '1') ? ' published. It is now availableto the public' : ' unpublished. ';
					$rootScope.$emit('notify.success' , 'Your shop has been successfully ' + msg );
					activate();
				}).catch(function(err)
				{
					$rootScope.$emit('notify.danger' , 'Failed to publish/unpublish your shop - please make sure you have a working internet connection.');
					activate();
				});


			}).catch(function(err)
			{

			});
		}
	}

	//account
	function SettingsAccountCtrl($rootScope,$scope, $routeParams, accountData,shopData,userData)
	{
		var vm = this;

		//attrs
		vm.plans = [];
		vm.plan = {};
		vm.sub = {};
		vm.all_subs = [];
		vm.selected_sub = {};
		vm.gettingLink = false;
		vm.load_plans_fail = 0;


		//methods
		vm.subscribe = subscribe;
		vm.select = select;
		//hack so that account names are not reordered
		vm.notSorted = function(obj)
		{
	        if (!obj) {
	            return [];
	        }
	        return Object.keys(obj);
	    };

		activate();

		function activate()
		{
			$rootScope.$emit('head.title','Account and subscriptions');
			if( ! $rootScope.is_admin() )
			{
				$rootScope.to('dashboard');
			}


			accountData.getAllSubs().then(function(data)
			{
				vm.all_subs = data;
				vm.select(0);
			}).catch(function(err){vm.all_subs = []; }); 

			accountData.planTypes().then(function(data)
			{
				vm.plans = data;
				
				accountData.getSub().then(function(data2)
				{
					vm.sub = data2;
					vm.plan = vm.plans[ vm.sub.type ];
				}).catch(function(err)
				{
					vm.load_plans_fail +=1;
					console.log('Failed to load subscription, please try again later.');
					if(vm.load_plans_fail < 5)
						activate();
				});
			}).catch(function(err)
			{
				$rootScope.$emit('notify.danger',"Failed to load account subscription types");
			});
		}

		function select(index)
		{
			vm.selected_sub = vm.all_subs[index];
		}

		function subscribe( plan  , period )
		{
			var word = 'Upgrade ';	
			var promise = $rootScope.prompt('',word + ' plan' , 'Subscribe to ' + vm.plans[plan].name + ' for ' + period + 'months','question');
			promise.then( function(resp)
			{
				if(resp == true )
				{
					vm.gettingLink = true;
					accountData.getUpgradeLink( plan , period ).then(function(data)
					{
						if( angular.isDefined(data.status) && data.status == 'ok')
						{
							popupwindow(data.url,data.title,720,360);
							vm.gettingLink = false;
						}
						else
						{
							alert('An unknown error occured !');
						}
					});
				}
			}).catch(function(err){});
		}

		function popupwindow(url, title, w, h) 
		{
		  var left = (screen.width/2)-(w/2);
		  var top = (screen.height/2)-(h/2);
		  return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
		} 

		
	}

	//user settings
	function SettingsUsersCtrl( $rootScope , $modal,  userData )
	{
		var vm = this;

		//attrs
		vm.users = [];
		vm.me = {};
		vm.user = {};
		vm.search = '';
		vm.log_max = 25;
		vm.log_filter = 'all';
		vm.contact_type = 'email';
		vm.contact_user_msg = '';


		//methods
		vm.select = selectUser;
		vm.is_admin = $rootScope.is_admin();
		vm.isMe = isMe;
		vm.addUser = addUser;
		vm.deleteUser = deleteUser;
		vm.changePassword = changePassword;
		vm.updateUser = updateUser;
		vm.loadActivity = loadActivity;
		vm.contactUser = contactUser;

		activate();

		function activate()
		{
			$rootScope.$emit('head.title' , 'User Settings');
			userData.thisUser().then(function(data)
			{
				vm.me = data;

			}).catch(function(err){});

			userData.getUsers().then(function(data)
			{
				vm.users = data;
				for(var i=0; i < vm.users.length ; ++i)
				{
					if( vm.users[i].user_id == vm.me.user_id )
					{
						vm.select(i);
						break;
					}
				}
			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load shop users.');

			});
		}

		function selectUser( index )
		{
			vm.user = vm.users[ index ];
			vm.user.logs = null;
		}

		function updateUser(  )
		{
			var promise = $rootScope.prompt('' , 'Update ' + vm.user.fullname + ' profile ?' , 'If you changed the email or phone number, user will not be able to login until they verify the new details. ');
			promise.then(function(data)
			{
				if(data == true)
				{
					userData.updateUser(vm.user).then(function(data)
					{
						vm.user = data;
						$rootScope.$emit('notify.flash' , 'Successfully updated user settings.');
					}).catch(function(err)
					{
						$rootScope.$emit('notify.danger' , 'Failed to update user account settings');
					});
				}
			}).catch(function(err){});
		}

		function loadActivity(  )
		{
			userData.getUserLog(vm.user.user_id , vm.log_filter , vm.log_max ).then(function(data)
			{
				vm.user.logs = data;
			}).catch(function(err){
				$rootScope.$emit('notify.warning' , 'Failed to load activity log');
			});

		}


		function isMe( user )
		{
			return (vm.me.user_id == user.user_id);
		}

		function deleteUser( )
		{
			var promise = $rootScope.prompt('' , 'Delete user - ' + vm.user.fullname , 'This action cannot be undone. The user\'s activity log will not be deleted.' , 'warning' );
			promise.then(function(resp)
			{
				if(resp == true )
				{
					userData.deleteUser( vm.user.user_id ).then(function(resp)
					{
						$rootScope.$emit('notify.warning' , 'Successfully deleted user - ' + vm.user.fullname );
						activate();
					}).catch(function(err)
					{
						alert('Failed to delete user. Try again later');
						activate();
					});

				}
			}).catch(function(err){});
		}

		function addUser( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'addUser.html',
		      controller: 'SettingsAddUserModalCtrl',
		      controllerAs: 'vm',
		      size: 'sm'
		    });

		    modalInstance.result.then( function(data)
		    {
		    	$rootScope.$emit('storage:clear' , 'users');
		    	activate();
		    }).catch(function(){});
		}

		function changePassword( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'changeUserPassword.html',
		      controller: 'SettingsChangePasswordModalCtrl',
		      controllerAs: 'vm',
		      size: 'sm',
		      resolve: {user: function(){ return vm.user;} }
		    });

		    modalInstance.result.then( function(data)
		    {
		    	$rootScope.$emit('storage:clear' , 'users');
		    	activate();
		    }).catch(function(){});
		}

		function contactUser( type )
		{
			if(  vm.isMe(vm.user) )
				return alert('You cannot send a message to yourself');
			var promise = $rootScope.prompt('','Send ' + vm.contact_type + ' message to ' + vm.user.fullname + ' ?' , 'This message will be delivered within a ten minute period.');
			promise.then(function(resp)
			{
				if(resp == true)
				{
					var http_promise;
					if( vm.contact_type == 'email' )
						http_promise = notifyData.sendUserEmail( vm.user.user_id , 'Message from ' + vm.me.fullname , vm.contact_user_msg , 'info' );
					else
						http_promise = notifyData.sendUserSms(  vm.user.user_id , vm.contact_user_msg );

					http_promise.then(function(data)
					{
						if( data.status == 'ok')
							alert('Successfully sent ' + vm.contact_type + ' message');
					}).catch(function(err)
					{
						alert('Failed to send message, try again');
					});

				}
			}).catch(function(err){});
		}
	}

	function SettingsAddUserModalCtrl( $rootScope , $modalInstance , userData )
	{
		var vm = this;

		//attrs
		vm.isProcessing = false;
		vm.user = {fullname: '', email: '',gender: 'Female', phone_number: '', national_id: '',is_suspended: "0", permission_manage_orders: "1", permission_manage_products:"0", permission_blog: "1",permission_pos:"0",permission_designer:"0"};

		//methods 
		vm.add = addUser;
		vm.close = $modalInstance.close;
		vm.isReady = isReady;

		activate();

		function activate()
		{
			if( ! $rootScope.is_admin() )
			{
				$modalInstance.dismiss();
			}


		}

		function isReady( )
		{
			return vm.user.fullname.length && vm.user.phone_number.length && vm.user.email.length && vm.user.national_id.length && vm.user.gender ;
		}

		function addUser( )
		{
			var promise = $rootScope.prompt('' ,'Create user ' + vm.user.fullname , 'The user must first verify either the email address or phone number before they can login.');
			promise.then(function(resp)
			{
				if( resp == true)
				{
					userData.addUser( vm.user ).then(function(data)
					{
						$rootScope.$emit('notify.success', 'Created new user ' + vm.user.fullname + '- User will have to verify account to login.');
						$modalInstance.close(true);
						//must reload users
					}).catch(function(err)
					{
						alert('Failed to add new user. Please try again. ');
						vm.isProcessing = false;
					})
				}
			}).catch(function(err){});
		}
	}

	function SettingsChangePasswordModalCtrl( $rootScope , $modalInstance , userData , user )
	{
		var vm = this;

		//attrs
		vm.user = user;
		vm.is_admin = $rootScope.is_admin();
		vm.current_pass = '';
		vm.new_password = '';
		vm.confirm_password = '';
		vm.isProcessing = false;

		//methods
		vm.cancel = $modalInstance.dismiss;
		vm.passwordsMatch = function(){ return vm.new_password == vm.confirm_password };
		vm.changePassword = changePassword;


		activate();

		function activate()
		{

		}

		function changePassword( )
		{
			var promise = $rootScope.prompt('' ,'Change password?','An email will be sent with the new login details.');
			promise.then(function(resp)
			{
				if(resp == true )
				{
					vm.isProcessing = true;
					userData.updatePassword( vm.user.user_id , vm.current_pass , vm.new_password ).then(function(resp)
					{
						if( angular.isDefined(resp.error))
						{
							alert(resp.msg);
							vm.isProcessing = false;
							return;
						}
						$rootScope.$emit('notify.flash','Password was successfully changed for user ' + vm.user.fullname + ' - ' + vm.user.email );
						vm.isProcessing = false;
						$modalInstance.dismiss();
					} , function(err)
					{
						
						alert('Failed to change password for user' );

					});
				}
			}).catch(function(err)
			{

			});
		}
	}

	//payment settings
	function SettingsPaymentsCtrl(  $rootScope , shopData , accountData )
	{
		var vm = this;
		
		//attrs
		vm.payment = { use_custom: false}
		vm.wallet = {amount: 0.00 , is_locked: false};
		vm.showPayNowKey = false;
		vm.showPay4AppKey = false;


		//methods
		vm.requestWithdraw = requestWithdraw;
		vm.summary = {};
		vm.saveChanges = saveChanges;



		activate();

		function activate()
		{
			if( ! $rootScope.is_admin() )
			{
				$rootScope.to('dashboard');
			}
			$rootScope.$emit('head.title' , 'Payment Settings');
			shopData.getShopSummary().then(function(data)
			{
				vm.summary = data;

			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to communicate with remote server.');	
			});

			shopData.getWallet().then(function(data)
			{
				vm.wallet = data;
			}).catch(function(data)
			{
				alert('Failed to load wallet info');
			});
			
			shopData.getSettings('payment').then(function(data)
			{
				vm.payment = data;
			} ).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load the payment settings');
			});
		}

		function saveChanges( )
		{
			var promise = $rootScope.prompt('Are you really sure?','Change your payment gateway settings?','Changing these settings will affect all future and current pending orders. Please make sure you entered the correct details, an email will be sent to the administrator\'s email and an sms will also be sent to reflect the changes. ','remove');
			promise.then(function(resp)
			{
				if( resp == true )
				{
					shopData.saveSettings('payment', vm.payment).then(function(data)
					{
						vm.payment = data;
						$rootScope.$emit('notfy.flash','Saved shop settings and sent out email and sms to administrator');
						activate();
					}).catch(function(err)
					{
						$rootScope.$emit('notify.danger','Failed to save shop settings, please try again');
						activate();
					});
				}
			}).catch(function(err){});
		}

		function requestWithdraw( )
		{
			var promise = $rootScope.prompt('Are you sure?' , 'Request cash withdrawal ?' , 'A '+ appName+ ' employee will review your transaction history and send the amount left in your wallet after '+ appName +' has deducted its 10% cost. This usually takes 2 to 5 days and is done to counter fraud. We will first send you an SMS then send the amount to the Econet number listed in your settings.' , 'credit-card' );
			promise.then(function(resp)
			{
				if(resp == true )
				{
					alert('@todo request withdraw');
				}
			}).catch(function(err){});
		}
	}	

	function SettingsAnalyticsCtrl( $rootScope , shopData , accountData )
	{
		var vm = this;

		//attrs
		vm.allowed = false;
		vm.analytics = {};

		//methods
		vm.save = save;

		activate();

		function activate()
		{
			if( ! $rootScope.is_admin()   )
			{
				alert('You are not allowed to access this resource');
				$rootScope.to('/');
				return;
			}
			$rootScope.$emit('head.title' ,'Loading analytics...');
			shopData.getSettings('analytics').then(function(data)
			{
				vm.analytics = data;

				$rootScope.$emit('head.title' ,'Analytics and stats');
			}).catch(function(err)
			{
				alert('Analytics failed to load !');
			});
		}

		function save()
		{
			var promise = $rootScope.prompt('' ,'Save Google Analytics Key' , 'You can only use your own Gogle Analytics key if you are on a custom domain. i.e Not using a .263shop.co.zw . ');
			promise.then(function(resp)
			{
				if( resp == true)
				{
					var obj = {
						google_analytics_key: vm.analytics.google_analytics_key
					};
					
					shopData.saveSettings('analytics' , obj).then(function(data)
					{
						$rootScope.$emit('notify.flash' , 'Successfully saved the analytics settings.');
					}).catch(function(err)
					{
						alert('There was an error saving your analytics settings');
					});
				}
			})
		}

	} 

	//Orders and shipping settings
	function SettingsShippingCtrl( $rootScope , $scope , $modal , shopData , accountData )
	{
		var vm = this;

		//attrs
		vm.isWorking = false;
		vm.order_settings = {order_expire_days: 14, sms_notify_on_order: "0", sms_notify_on_pay: "1" , email_notify_on_order: "0" , allow_pos_order: "0" , allow_pos_complete_order: "0" };
		vm.shipping_settings = {handling_fee: 0.00 , last_modified: Date() , rules: [] };


		//methods
		vm.saveOrderSettings = saveOrderSettings;
		vm.saveShippingSettings = saveShippingSettings;
		vm.editShippingRules = editShippingRules;
		

		activate();

		function activate( )
		{
			if( ! $rootScope.is_admin() )
			{
				$rootScope.to('dashboard');
			}
			$rootScope.$emit('head.title' , 'Loading settings 0/2...');
			shopData.getSettings('order').then(function(data)
			{
				$rootScope.$emit('head.title' , 'Loading settings 1/2...');
				vm.order_settings = data;

				shopData.getSettings('shipping').then(function(data)
				{
					vm.shipping_settings = data;
					$rootScope.$emit('head.title' , 'Orders and Shipping Settings');
				}).catch(function(err)
				{
					alert('Failed to load the shipping settings, please reload the page');
				});
			}).catch(function(err)
			{
				alert('Failed to load the order settings, please reload the page');
			});
			
		}

		

		function saveOrderSettings()
		{
			var promise = $rootScope.prompt('' , 'Save and apply new order settings ?' , 'This settings will be applied immediately and will take effect, please make sure you entered correct details' ,'warning' );
			promise.then(function(resp)
			{
				if(resp == true)
				{
					shopData.saveSettings( 'order' , vm.order_settings ).then(function(data)
					{
						vm.order_settings = data;
						$rootScope.$emit('notify.flash','Successfully updated your order settings.');
					}).catch(function(data)
					{
						$rootScope.$emit('notify.warning','Failed to save order settings, please try again.');
					});
				}
			}).catch(function(){})
		}

		function saveShippingSettings()
		{
			var promise = $rootScope.prompt('' , 'Save and apply new shipping settings ?' , 'This settings will be applied immediately and will take effect, please make sure you entered correct details.' ,'warning' );
			promise.then(function(resp)
			{
				if(resp == true)
				{
					shopData.saveSettings( 'shipping' , vm.shipping_settings ).then(function(data)
					{
						vm.shipping_settings = data;
						$rootScope.$emit('notify.flash','Successfully updated your shipping settings.');
					}).catch(function(data)
					{
						$rootScope.$emit('notify.warning','Failed to save order settings, please try again.');
					});
				}
			}).catch(function(){})
		}

		function editShippingRules( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'editShippingRules.html',
		      controller: 'SettingsShippingRulesModalCtrl',
		      controllerAs: 'vm',
		      resolve: 		{rules: function(){return vm.shipping_settings.rules; } },
		      size: 'lg'
		    });

		    modalInstance.result.then( function(data)
		    {
		    	vm.shipping_settings.rules = angular.copy(data);
		    }).catch(function(){});
		}
	}  

	function SettingsShippingRulesModalCtrl(  $rootScope , $modalInstance , shopData , rules )
	{
		var vm =this;

		if( ! $rootScope.is_admin() )
		{
			$rootScope.to('dashboard');
		}

		//attrs
		
		vm.rules = rules;
		var rule = {price: 0.00 , low: 0.00, high: 0.00 , conditions:[], type: 'weight'};
		if( ! angular.isDefined(rules) )
		{
			vm.rules = [ ];
			vm.rules.push( angular.copy(rule ) );
		}
		else
		{
			vm.rules = angular.copy( rules );	
		}
		
		

		//methods
		vm.addRule = addRule;
		vm.removeRule = removeRule;
		vm.addCondition = addCondition;
		vm.saveRules = saveRules;
		vm.dismiss = dismiss;

		function addRule()
		{
			vm.rules.push( angular.copy( rule ) );
		}

		function removeRule( index )
		{
			$rootScope.prompt('' ,'Remove this rule ?' , 'Confirm action.' , 'warning').then(function(resp)
			{
				if( resp == true)
				{
					vm.rules.splice( index , 1 );

				}
					
			}).catch(function(){});
			
		}

		function addCondition(rule )
		{
			alert('Feature not yet active');
		}

		function saveRules()
		{
			$modalInstance.close( vm.rules );
		}

		function dismiss( )
		{
			//dont prompt save
			$modalInstance.dismiss();
		}

	}



})(controllers_module);
	    
//////////////////// END SETTINGS /////////////////////////
/////////////////  REPORTS        ///////////////////////////
(function(module)
{
	module.controller('ReportsIndexCtrl' , ReportsIndexCtrl )
		  .controller('ReportsViewCtrl' , ReportsViewCtrl );

	ReportsIndexCtrl.$inject = ['$rootScope' , '$scope' , 'shopData' , 'reportData'];
	ReportsViewCtrl.$inject = ['$rootScope' , '$scope' , '$routeParams' ,'$location', 'shopData' , 'reportData'  ];

	function ReportsIndexCtrl( $rootScope , $scope , shopData , reportData  )
	{
		var vm = this;

		//attrs

		//methods

		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('mnage_orders') )
			{
				$rootScope.to('dashboard');
			}
			$rootScope.$emit('head.title' , 'Reports');	
			
		}
		
	}

	function ReportsViewCtrl( $rootScope , $scope , $routeParams , $location , shopData , reportData )
	{
		var vm = this;

		//attrs
		vm.valid_report_types = ['products','payments','orders' , 'shipping'];
		vm.report_type = $routeParams.report_type;
		vm.reports = []; //reports for the current type

		//methods
		vm.iconType = iconType;


		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('mnage_orders') )
			{
				$rootScope.to('dashboard');
			}
			var b = false;
			for( var i=0; i < vm.valid_report_types.length ; ++i)
			{
				if( vm.valid_report_types[i] ==  $routeParams.report_type )
					b = true;
			}
			if(  b == false )
			{
				alert(   $routeParams.report_type );
				$location.url('/reports');
			}
			
			$rootScope.$emit('Loading reports... ');
			reportData.get( $routeParams.report_type ).then(function(data)
			{
				var str = $routeParams.report_type.replace($routeParams.report_type[0] , $routeParams.report_type[0].toUpperCase() );
				vm.reportTypeName = str;
				$rootScope.$emit('head.title' , str + ' reports');
			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load the reports. make sure you are connected to the internet.');
			});

			$scope.$watch(function(){return vm.report_type;} , function()
			{
				$location.url('/reports/' + vm.report_type );
			});
		}

		function iconType( )
		{
			switch(vm.report_type)
			{
				case 'products': { return 'cubes'; }break;
				case 'orders': { return 'shopping-cart'; }break;
				case 'payments': { return 'credit-card'; }break;
				case 'shipping': { return 'truck'; } break;
				default: return 'question-circle'; break;
			}
		}
	}

})(controllers_module);
///////////////// END REPORTS     ///////////////////////////
/////////////////// DESIGNER      /////////////////////////
(function(module)
{
	module.controller('DesignerThemeCtrl' , DesignerThemeCtrl)
		  .controller('DesignerThemeCustomCSSCtrl' , DesignerThemeCustomCSSCtrl )
		  .controller('DesignerThemeDictionaryCtrl' , DesignerThemeDictionaryCtrl )
		  .controller('DesignerEditCtrl' , DesignerEditCtrl );

	DesignerThemeCtrl.$inject = ['$rootScope' , '$scope' , '$modal' ,'shopData' , 'themeData'];
	DesignerThemeCustomCSSCtrl.$inject = ['$rootScope' ,'$modalInstance' , 'themeData' ];
	DesignerThemeDictionaryCtrl.$inject = ['$rootScope' ,'$modalInstance' , 'themeData' ];
	DesignerEditCtrl.$inject = ['$rootScope' , '$scope' , '$routeParams' , '$modal' , 'shopData' , 'themeData'];

	function DesignerThemeCtrl($rootScope , $scope , $modal, shopData , themeData )
	{
		var vm = this;

		//attrs
		vm.hideAll = false;
		vm.themes = [];
		vm.theme = {info:{}}; //current theme
		vm.selectedThemeName = 'default';
		vm.selectedTheme = {}; 
		vm.defaultPage = 'shop';

		//methods
		vm.image_url = image_url;
		vm.applyTheme = applyTheme;
		vm.customCSS = customCSS;
		vm.editWording = editWording;


		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('designer') )
			{
				$rootScope.$emit('notify.warning' , 'You are not allowed to manage the shop design');
				$rootScope.to('');
				return;
			}
			$rootScope.$emit('head.title' , 'Loading available themes...');
			themeData.theme().then(function(data)
			{
				
				vm.theme = data;
				vm.selectedThemeName = vm.theme.info.dir;

				shopData.getSettings('theme').then(function(_data)
				{
					vm.defaultPage = _data.default_page;
				}).catch(function(){});

				themeData.allThemes().then(function(data)
				{
					$rootScope.$emit('head.title' , 'Select a theme ');
					vm.themes = data;
					
				}).catch(function(err)
				{
					alert('Failed to load the available themes !');
				});	
			}).catch(function(err)
			{
				alert('Failed to load the current theme, please try again !');
				vm.hideAll = true;
			});	
		}

		function customCSS()
		{
			var modalInstance = $modal.open({
		      templateUrl: 'editCustomCSS.html',
		      controller: 'DesignerThemeCustomCSSCtrl',
		      controllerAs: 'vm',
		      size: 'sm'
		    });

		    modalInstance.result.then( function(data)
		    {
		    	
		    }).catch(function(Err){});
		}

		function editWording( )
		{
			var modalInstance = $modal.open({
		      templateUrl: 'editWording.html',
		      controller: 'DesignerThemeDictionaryCtrl',
		      controllerAs: 'vm',
		      size: 'sm'
		    });

		    modalInstance.result.then( function(data)
		    {
		    	
		    }).catch(function(Err){});
		}

		function applyTheme( theme_name )
		{
			var promise = $rootScope.prompt('' , 'Apply this theme ?' , 'The theme will be applied to your shop, anyone who accesses the site will see the new theme -including yourself. Note that your settings for this theme will be saved and you can quickly change back to it. The new theme will have default settings and it will be up to you to  change its settings' , 'question-circle' );
			promise.then( function(resp)
			{
				if( resp == true )
				{
					themeData.apply( theme_name , vm.defaultPage ).then(function(resp)
					{
						activate();
						$rootScope.$emit('notify.success' , 'New theme successfully applied, visit your shop to see changes');	
						return;
					}).catch(function(err)
					{
						$rootScope.$emit('notify.warning' , 'Failed to apply theme ,  please try again' );
					});
				}
				
			}).catch(function(){});
		}

		function image_url( filename )
		{
			return assetsBase + 'theme/' + vm.selectedThemeName + '/screenshots/' + filename;
		}
	}

	function DesignerThemeCustomCSSCtrl( $rootScope , $modalInstance , themeData )
	{
		var vm = this;

		//attrs
		vm.css = '';
		vm.isLoading = true;

		//methods
		vm.cancel = $modalInstance.dismiss;
		vm.save = save;

		activate();

		function activate()
		{
			themeData.loadCSS(  ).then(function(data)
			{
				vm.isLoading = false;
				vm.css = data;
			}).catch(function(err)
			{
				alert('Failed to load custom css. Try again later.');
				$modalInstance.dismiss();
			});
		}

		function save()
		{
			var promise = $rootScope.prompt('' ,'Save custom CSS' ,'The css you provided will be applied immediately to your shop. Please note that this CSS will apply only to this particular theme. ');
			promise.then(function(resp)
			{
				if( resp === true)
				{
					vm.isProcessing = true;
					themeData.saveCSS(vm.css).then(function(data)
					{
						if(data.status == 'ok')
						{
							alert('Successfully saved custom css');
							$modalInstance.dismiss();
						}else
						{
							alert('An error occured, failed to save custom css');
							$modalInstance.dismiss();
						}
					}).catch(function(err)
					{
						alert('An error occured whilst trying to save your custom css');
						$modalInstance.dismiss();
					});
				}
			}).catch(function(err)
			{

			});
		}


	}

	function DesignerThemeDictionaryCtrl( $rootScope , $modalInstance , themeData )
	{
		var vm =this;

		//attrs
		vm.vocabulary = [];
		vm.isLoading = true;

		//methods
		vm.cancel = $modalInstance.dismiss;
		vm.save = save;

		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('designer') )
			{
				alert('You are not allowed to manage the shop design');
				$modalInstance.dismiss();
				$rootScope.to('');
				return;
			}
			vm.isLoading = true;
			themeData.theme().then(function(data)
			{
				var words = data.vocabulary;
				vm.vocabulary = words; //_(words).toArray(); 
				vm.isLoading = false;
				
			}).catch(function(err)
			{
				$rootScope.$emit('notify.warning' , 'Failed to load the theme vocabulary. Make sure you are connected to the internet.');
			});
			
		}

		function save( )
		{
			var promise = $rootScope.prompt('','Update your site dictionary ?' ,'Applicable instances of the words will be immediately replaced in your site. Make sure there are no mis-spellings');
			promise.then(function(resp)
			{
				if(resp === true )
				{
					alert('@todo save dictionary');
				}
				else
				{

				}
			}).catch(function(err){});
		}

	}


	function DesignerEditCtrl( $rootScope , $scope , $routeParams , $modal , shopData , themeData )
	{
		
		activate();

		function activate()
		{
			if( ! $rootScope.has_permission('designer') )
			{
				$rootScope.$emit('notify.warning' , 'You are not allowed to manage the shop design');
				$rootScope.to('');
				return;
			}
			$rootScope.$emit('head.title' , 'Edit a page');
		}
	}

})(controllers_module);

/////////////////   END DESIGNER  /////////////////////////// 

///////////////////////////// POINT OF SALE ///////////////////////////////
(function(module)
{
	module.controller('PosIndexCtrl' , PosIndexCtrl )
		  .controller('PosReceiptMakerCtrl' , PosReceiptMakerCtrl )
		  .controller('PosSaveSettingsCtrl' , PosSaveSettingsCtrl )
		  .controller('PosCheckoutCtrl' , PosCheckoutCtrl);

	/** Point of Sale **/
	PosIndexCtrl.$inject = ['$rootScope' , '$modal' , 'shopData' , 'productData'];
	PosReceiptMakerCtrl.$inject = ['$rootScope' ,  '$routeParams' , 'storage' , 'orderData' , 'productData' , 'shopData'  ];
	PosSaveSettingsCtrl.$inject = ['$rootScope' , '$modalInstance' , 'settings'];
	PosCheckoutCtrl.$inject = ['$rootScope' , '$location' , '$modalInstance' , 'orderData' , 'storage' , 'order' ];


	function PosIndexCtrl(  $rootScope , $modal ,shopData , productData )
	{

	  	var vm = this;

	  	vm.order_search = '';
	  	vm.product_search = '';
	  	vm.products = [];
	    vm.itemCount = 1;

	    vm.order = {
	    	items: [],
	    	total: 0.00,
	    	shipping: { fullname: '' , phone_number: '' , email: '' , address: '' }
	    };

	    vm.pos_settings = 
	    {
	    	images: 'images' //display text instead of images
	    };

	    vm.isDisabled = true;

	    //methods
	    vm.addItem = addItem;
	    vm.getSum = getSum;
	    vm.removeItem = removeItem;
	    vm.subtractQty = subtractQty;
	    vm.checkout =  checkout;
	    vm.clearOrder = clearOrder;
	    vm.image_url = productData.imageUrl;

	    vm.settings = settings;



	    activate();

	    function activate()
	    {
	    	if( ! $rootScope.has_permission('pos') )
	    	{
	    		$rootScope.$emit('notify.warning' , 'You do not have access to the point of sale.');
	    		$rootScope.to('');
	    	}
	    	$rootScope.$emit('head.title' , 'Loading Point of Sale....');
	    	productData.getAll().then(function(products)
	    	{
	    		$rootScope.$emit('head.title' , 'Point of Sale Software');
	    		vm.products = products;
	    	}).catch(function(err)
	    	{
	    		$rootScope.$emit('notify.warning' , 'Failed to load Point of Sale products. Make sure you are connected to the internet');
	    	});

	    }

		function isEmpty(obj) 
		{
		    return Object.keys(obj).length === 0;
		}

	    function addItem(product) 
	    {
 			//is produc already added ?
 			var isAdded = false;

 			for(var i =0; i < vm.order.items.length ; ++i )
 			{
 				if( vm.order.items[i].product.product_id == product.product_id )
 				{
 					//increment number of items
 					vm.order.items[ i ].qty += 1;
 					isAdded = true;
 					return;
 				}
 			}
 			

 			if( isAdded )
 				return;
 			//add item instead
	      	var item = {
		        price_per_item: product.price,
		        price: product.price,
		        qty: 1,
		        product_id: product.product_id,
		        product: product
		    };

		    vm.order.items.push(  item );
		    vm.itemCount = vm.order.items.length;
		};

	    function getSum() 
	    {
	      var sum = 0;
	      for(var i=0; i < vm.order.items.length; ++i ) 
	      {
	        vm.order.items[i].price = vm.order.items[i].qty * vm.order.items[i].price_per_item;
	        sum += vm.order.items[i].price;
	      }
	      return sum;
	    };

	    function subtractQty(item)
	    {
	      var index = vm.order.items.indexOf( item );
	      if( vm.order.items[ index ].qty > 0 )
	      {
	      	vm.order.items[ index ].qty -= 1;	
	      }
	    }

	    function removeItem(item) 
	    {
	      var index = vm.order.items.indexOf( item );
	      vm.order.items.splice(index, 1);
	      vm.order_search = '';
	    };
	    
	    function checkout(index) 
	    {
	      var modalInstance = $modal.open({
		      templateUrl: 'checkoutPos.html',
		      controller: 'PosCheckoutCtrl',
		      controllerAs: 'vm',
		      size: 'sm',
		      resolve: 
		      {
		      	order: function(){ return vm.order; }
		      }
		    });

		    modalInstance.result.then( function(data)
		    {
		    	vm.pos_settings = data;
		    });
	    };
	    
	    function clearOrder()
	    {
	      vm.order.items = [];
	    };

	    function settings( )
	    {
	    	
	    	var modalInstance = $modal.open({
		      templateUrl: 'savePosSettings.html',
		      controller: 'PosSaveSettingsCtrl',
		      controllerAs: 'vm',
		      size: 'sm',
		      resolve: 
		      {
		      	settings: function(){ return vm.pos_settings }
		      }
		    });

		    modalInstance.result.then( function(data)
		    {
		    	vm.pos_settings = data;
		    });
	    }
	}

	function PosReceiptMakerCtrl( $rootScope , $routeParams , storage , orderData , productData , shopData  )
	{

	  	var vm = this;
	  	
	  	vm.allowPrintMode = true;
	  	vm.printMode = false;
	  	vm.allowEditMode = false;
	  	vm.orderMode = false;
	  	vm.siteName = 'OneShop';
	  	vm.siteLogo = '';
	  	vm.invoice  = {
	  		order: 		{order_id: Math.random() , items: [] , shipping_amount: 0.00 },//actual order, can be empty
	  		customer: 	{name: '' , address: '', phone: '', email: '' },//customer
	  		company: 	{name: 'OneShop' , address: '', url: 'http://www.oneshop.co.zw/', phone: '+263000000000', email: 's@humun.com'  }
	  	};


	  	vm.calculate_grand_total = calculate_grand_total;
	  	vm.invoice_sub_total  = invoice_sub_total;
	  	vm.addItem = addItem;
	  	vm.removeItem = removeItem;
	  	vm.print = printPage;
	  	vm.resetInvoice = resetInvoice;


	  	activate();

	  	function activate()
	  	{
	  		$rootScope.$emit('head.title' , 'Invoice Maker');
	  		
	  		if( angular.isDefined($routeParams.order_id) )
	  		{
	  			if( $routeParams.order_id == 'storage')
	  			{
	  				storage.get('invoice').then(function(data)
	  				{
	  					vm.invoice = data;
	  					
	  				}).catch(function(data)
	  				{
	  					$rootScope.$emit('notify.flash' , 'No invoice stored in local memory !');
	  				});
	  			}
	  			else
	  			{
	  				orderData.getOrder( $routeParams.order_id ).then(function(data)
	  				{
		  				vm.invoice.order = data;
		  				$rootScope.$emit('head.title' , 'Invoice for order #' + vm.invoice.order.order_id );
		  				vm.invoice.customer.name = data.shipping.fullname;
		  				vm.invoice.customer.email = data.shipping.email;
		  				vm.invoice.customer.phone = data.shipping.phone_number;
		  				vm.invoice.customer.address = data.shipping.address;
		  				vm.orderMode = true;
		  				vm.printMode = true;
		  			}).catch( function(data)
		  			{
		  				$rootScope.$emit('notify.warning' , 'Failed to load the order. Make sure you are connected online');
		  			});
	  			}
	  			
	  			//get shop
	  			shopData.getShop().then( function(shop)
	  			{
	  				vm.invoice.company = shop;
	  				vm.siteName = shop.name;
	  				//set image logo
	  				vm.siteLogo = shopData.imageUrl( shop.logo );

	  			}).catch( function(err)
	  			{
	  				$rootScope.$emit('notify.flash' , 'Failed to load shop information !');
	  			});
	  		}

	  		if( (! vm.orderMode) && ( ! $rootScope.has_permission('pos') ) )
	  		{
	  			//not allowed to print or edit
	  			vm.allowEditMode = false;
	  			vm.printMode = true;
	  			vm.allowPrintMode = $rootScope.has_permission('manage_orders');
	  		}

	  	}


	  
	    function addItem() 
	    {
	        vm.invoice.order.items.push({qty:0, price_per_item:0, name:'' });    
	    }

	    function removeItem(item) 
	    {
	        vm.invoice.order.items.splice(vm.invoice.order.items.indexOf(item), 1);    
	    }
	    
	    function invoice_sub_total() 
	    {
	        var total = 0.00;
	        angular.forEach(vm.invoice.order.items, function(item, key){
	          total += (item.qty * item.price_per_item);
	        });
	        return total;
	    }

	    function calculate_grand_total() 
	    {
	        return vm.invoice.order.shipping_amount + vm.invoice_sub_total();
	    } 

	    function printPage() 
	    {
	      
	      window.print();
	    }

	    function resetInvoice()
	    {
	      $rootScope.prompt('Are you sure' , 'Restart the order ?' ,"Are you sure you would like to clear the invoice?").then
	      (function(data)
	      {
	      	vm.invoice.order.items = [];
	      }).catch(function(){} );
	    }
	}

	function PosSaveSettingsCtrl( $rootScope , $modalInstance , settings )
	{
		var vm = this;

		//attrs
		vm.settings = settings;

		vm.close = close;

		function close()
		{
			$modalInstance.close( vm.settings );
		}

		function save( )
		{
			$modalInstance.close( vm.settings )
		}
	}

	function PosCheckoutCtrl( $rootScope , $location , $modalInstance , orderData , storage , order )
	{
		var vm =this;

		vm.notify_type = 'all'; //send sms + email
		vm.invoice  = {
	  		order: 		{order_id: Math.random() , items: [] , shipping_amount: 0.00 , status: 'pending' },//actual order, can be empty
	  		customer: 	{name: '' , address: '', phone: '', email: '' },//customer
	  		company: 	{name: 'OneShop' , address: '', url: 'http://www.oneshop.co.zw/', phone: '+263000000000', email: 's@humun.com'  }
	  	};
	  	vm.isProcessing = false;

	  	//methods
	  	vm.offlineOrder = offlineOrder;
	  	vm.posOrder = posOrder; //place an order.
	  	vm.isReady = isReady; //all set to place order ?

	  	activate();

	  	function activate()
	  	{
	  		vm.invoice.order.items = order.items;
	  	}

	  	function offlineOrder()
	  	{
	  		storage.set('invoice' , vm.invoice );
	  		$location.url('/pos/receipt-maker/storage');
	  		$modalInstance.close(1);
	  	}

	  	function isReady()
	  	{
	  		return (  vm.invoice.customer.name.length && vm.invoice.customer.phone.length 
	  			&& vm.invoice.customer.email.length && vm.invoice.customer.address.length );
	  	}

	  	function posOrder( )
	  	{
	  		$rootScope.prompt('Please confirm action' , 'Place order on behalf of customer ?' , 'The customer will receive a link via email and/or sms that will take him to your site to review, edit and then complete the payment securely without having to share any confidential info. Are you sure you want to continue ? ' , 'question' ).then(
	  			function(resp)
	  			{
	  				if( resp )
	  				{
	  					vm.isProcessing = true;
	  		
				  		orderData.placePosOrder( vm.invoice ).then(function(data)
				  		{
				  			vm.isProcessing = false;
				  			vm.invoice.order = data;
				  			$location.url('/order/view/' + data.order_id );
				  			$rootScope.$emit('notify.success' , 'Successfully created order #' + data.order_id + 'for customer and sent out email/sms. Customer can now complete payment...etc');
				  			$modalInstance.close(1);
				  		}).catch( function(resp)
				  		{
				  			vm.isProcessing = false;
				  			alert('Failed to create your order. Please try again ! Make sure you\'re connected to the internet');
				  		});	
	  				}
	  			}).catch(function(){});
	  		
	  	}


	}
})(controllers_module);
/* END POINT OF SALE */
