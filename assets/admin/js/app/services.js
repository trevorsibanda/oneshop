/**
 * Shop Account 
 *
 *
 */
(function()
{
	angular.module('app.services' , [ 'ngRoute' , 'http-auth-interceptor' , 'webStorageModule' ])
		   .factory('storage' , DataStorageFactory )
		   .factory('accountData' , accountData )
		   .factory('productData' , productData )
		   .factory('orderData' , orderData )
		   .factory('shopperData' , shopperData )
		   .factory('userData' , userData )
		   .factory('transactionData' , transactionData )
		   .factory('shopData' , shopData )
		   .factory('notifyData' , notifyData )
		   .factory('blogData' , blogData)
		   .factory('themeData' , themeData )
		   .factory('reportData' , reportData );
		   


	DataStorageFactory.$inject = ['webStorage' , '$q' , '$rootScope' ];
	accountData.$inject = ['$http' ,   'storage' ];
	shopData.$inject = ['$http' ,  'storage'];
	userData.$inject = ['$http' , 'storage' , 'authService'  ];
	productData.$inject = ['$http' , 'storage' ]
	blogData.$inject = ['$http' , 'storage'];
	orderData.$inject = ['$http' , 'storage'];
	themeData.$inject = ['$http' , 'storage' ];
	reportData.$inject = ['$http' , 'storage'];
	notifyData.$inject = ['$http' , 'storage'];

	/**
	 * DataFactory - All results are returned as promises
	 */
	function DataStorageFactory(  webStorage , $q , $rootScope )
	{

		var service = 
		{
			get: 			getStorage, //get local storage
			get_index: 		getStorageIndex, //get an item from a stored array
			set: 			setStorage, //set local storage
			set_index: 		setStorageIndex,//set an item in an array e.g storage['orders']['order_1']
			has: 			hasStorage, //is an element set
			has_index: 		hasStorageIndex, //index set
			remove: 		removeStorage, //remove
			remove_index: 	removeStorageIndex,
			clear: 			clearStorage, //clear storage
			clearAll: 		clearAllStorage //clear all storage
		};

		//listen for event to clear storage
		$rootScope.$on('storage:clear' , service.clear );

		return service;


		function getStorage (index)
		{
			var defered = $q.defer();
			if( webStorage.has(index)  )
			{
				defered.resolve( webStorage.get(index) );
			}
			else
			{
				defered.reject(false);
			}
			return defered.promise;
		}

		function getStorageIndex( key , index )
		{
			var defered = $q.defer();
			if( ! webStorage.has(key ) )
				defered.reject( key );
			var array = webStorage.get(  key );
			if( angular.isUndefined( array[ index ] ) )
				defered.reject( index );
			else
				defered.resolve(  array[ index ] );
			return defered.promise;
		}

		function setStorage( index , value  )
		{
			var defered = $q.defer();
			//only saves if its an object or array
			if( angular.isObject(value) || angular.isArray(value) )
			{
				//if array and empty don't save
				if( angular.isArray(value) && value.length == 0 )
				{

				}
				else
				{
					webStorage.add( index , value );
				}
				defered.resolve( value );	
			}
			else
			{
				defered.reject( value );
			}
			
			return defered.promise;
		}

		function setStorageIndex( key , index , value )
		{
			var defered = $q.defer();
			if( ! webStorage.has(key ) )
				defered.reject( key );
			else
			{
				var array = webStorage.get(  key );
				array[ index ] = value;
				webStorage.add( key ,  array );
				defered.resolve(  array[ index ] );
			}
			return defered.promise;
		}

		function hasStorage( index )
		{
			var defered = $q.defer();
			if( webStorage.has(index) )
				defered.resolve(index);
			else
				defered.reject(index);
			return defered.promise;

		}

		function hasStorageIndex( key , index )
		{
			var defered = $q.defer();
			if( ! webStorage.has(key) )
				defered.reject(key);
			else
			{
				var array = webStorage.get(key);
				if( angular.isUndefined( array[ index ]  ) )
					defered.reject( index );
				defered.resolve( array[ val ] );
			}
			return defered.promise;
		}

		function removeStorage( index )
		{
			var defered = $q.defer();
			defered.resolve(index);
			webStorage.remove( index );
			return defered.promise;			
		}

		function removeStorageIndex( key , index )
		{
			var defered = $q.defer();
			if( ! webStorage.has(key ) )
				defered.reject( key );
			var array = webStorage.get(  key );
			if( angular.isDefined( array[ index ] ) )
			{
				delete array[ index ];
				webStorage.add(key , array );
				
			}
			defered.resolve( index );
			return defered.promise;
		}

		function clearStorage( evt , data )
		{
			if( data != null )
				webStorage.remove( data );
			else
				webStorage.clear();
		}

		function clearAllStorage(  )
		{
			clearStorage(null , null );
		}
	}

	function productData( $http , storage )
	{
		var service = 
		{
			get: 			getProduct, //get a product gven its id
			getProduct: 	getProduct, //alias for get
			productUrl: 	productUrl, //get url of product
			getAll: 		getAllProducts,//get all products
			addProduct: 	addProduct, //add a new product
			updateProduct: 		updateProduct, //update a product
			deleteProduct: 	removeProduct, //remove a product 
			getCategory: 	getCategory ,//get product category given id
			addCategory: 	addCategory, //add a category
			categoryUrl: 	categoryUrl,//category url
			getImage: 		getImage, //get an image
			imageUrl: 		imageUrl, //get url of image.
			getImages: 		getImages, //get all images
			getFile:     	getFile, //get a file
			fileUrl: 		fileUrl, //et file url
			getFiles: 		getFiles, //get all product files
			getCategories:  getCategories, //get all product categories
			removeCategory: removeCategory, //remove a product category
			updateCategory: updateCategory, //update product categry
			isOrdered: 		isOrdered, //check if a product is ordered
			setStock: 		setStock,//set items in stock
			clearCache:     clearCache //clear items in local storage
		};

		return service;

		//@todo fix this
		function getProduct( product_id )
		{
			var index = 'product_' + product_id;
			return storage.get(index  )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/products/get/' + product_id )
									 .then( function(response){ return storage.set( index  , response.data); });
						  }); 
		}

		function productUrl( product )
		{
			return '/shop/product/' + product.product_id + '/' + product.name;
		}



		function getAllProducts( offset , max , order_by )
		{
			if( null ==(offset) )
				offset = 0;
			if( null ==(max))
				max = 999;
			if( null ==(order_by))
				order_by = 'name_a-z';

			//if getting from a different offset and max number , do not save 
			if( offset == 0  && max == 9999 )
			{
				return storage.get('products' )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/products/all?order_by=' + order_by )
									 .then( function(response){ return storage.set('products'  , response.data); });
						  }); 	
			}
			else
			{
				return $http.get('/api/products/all?offset=' + offset + '&max=' + max + '&order_by=' + order_by ).then
							(function(response){  return response.data; }	).catch
							(function(err){return err;});
			}
			
		}

		function addProduct( product )
		{
			return $http.post('/api/products/add' , product )
						.then(function(response)
						{
							return response.data;
						}, function(err)
						{
							return err;
						});
		}

		function removeProduct( product_id )
		{
			//clear local orders and products in case it actually deleted the product
			storage.clear(null , 'products');
			storage.clear(null , 'orders');
			storage.clear(null , 'product_' + product_id );
			return $http.get('/api/products/delete/' +  product_id ).then(
				function(response)
				{
					return response.data;
				}).catch(function(data){return data;});
		}

		function updateProduct(  product )
		{
			storage.clear('product_' + product.product_id );
			return $http.post('/api/products/update' , product )
						.then(function(response)
						{
							return response.data;
						}, function(err)
						{
							return err;
						});
		}

		function getCategory( category_id )
		{
			var index = 'product_category_' + category_id;
			return storage.get(index )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/products/get_category/' + category_id )
									 .then( function(response){ return storage.set(index  , response.data); });
						  }); 
		
		}

		function categoryUrl( category )
		{
			return '/shop/category/' + category.category_id + '/' + category.name;
		}

		function addCategory(  category )
		{
			return $http.post('/api/products/add_category' , category )
						.then(function(response)
						{
							return response.data;
						}, function(err)
						{
							return err;
						});
		}

		function getImage( image_id )
		{	
			var index = 'product_image_' + image_id;
			return storage.get( index )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/files/get/product_image/' + image_id  )
									 .then( function(response){ return storage.set( index , response.data); });
						  }); 
		}

		function imageUrl( image  , thumbnail_width  )
		{
			if( angular.isUndefined(image.filename))
				return assetsBase + 'files/product_images/not_found.png';
			if( thumbnail_width != null)
				return '/api/files/thumbnail/product_image/' + image.image_id + '/' + thumbnail_width;
			return assetsBase + 'files/product_images/' + image.filename;
		}


		function getImages( max  , offset , order_by , use_cache )
		{
			if( null ==(max))
				max = 99999;
			if( null ==(offset))
				offset = 0;
			if( null ==(order_by))
				order_by = 'DESC';
			if( null ==(use_cache))
				use_cache = true;
			if( use_cache && offset == 0 && max == 9999 )
			{
				return storage.get('product_images')
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/files/all/product_images'  )
									 .then( function(response){ return storage.set( 'product_images' , response.data); });
						  });	
			}
			else
			{
				return $http.get('/api/files/all/product_images?offset=' + offset + '&max=' + max + '&order_by=' + order_by   )
							.then( function(response){ return storage.set( 'product_images' , response.data); })
							.catch(function(err){ return err;} );
						 
			}
			 
		}

		function getFile( file_id )
		{
			return storage.get( 'product_files' )
						  .then( 
						  	function(data)
						  	{
						  		for( var x = 0; x < data.length ; x++)
						  		{
						  			if( data[x].file_id == file_id )
						  				return data[x];
						  		}
						  		//failed perform http request
						  		return $http.get('/api/files/get/product_file/' + file_id  )
									 .then( function(response){ return  response.data; });
						  	} 
						  ).catch( function(data)
						  {
						 	return $http.get('/api/files/get/product_file/' + file_id  )
										.then( function(response){ return  response.data; });
						  });
		}

		function fileUrl( file )
		{
			if( thumbnail_width != null)
				return '/file/product_file/' + image.filename + '/' + thumbnail_width;
			return assetsBase + 'files/product_files/' + image.filename;	
		}

		function getFiles( max  , offset , order_by  , use_cache  )
		{
			if( null ==(max))
				max = 99999;
			if( null ==(offset))
				offset = 0;
			if( null ==(order_by))
				order_by = 'DESC';
			if( null ==(use_cache))
				use_cache = true;
			if( use_cache && offset == 0 && max == 9999 )
			{
				return storage.get('product_files')
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/files/all/product_files'  )
									 .then( function(response){ return storage.set( 'product_files' , response.data); });
						  });	
			}
			else
			{
				return $http.get('/api/files/all/product_files?offset=' + offset + '&max=' + max + '&order_by=' + order_by   )
							.then( function(response){ return storage.set( 'product_files' , response.data); })
							.catch(function(err){ return err;} );
						 
			}
		}


		function getCategories()
		{
			return storage.get('product_categories' )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/products/categories' )
									 .then( function(response){ return storage.set('product_categories'  , response.data); });
						  }); 
		}
		

		function updateCategory( category )
		{

		}

		function removeCategory(  category_id )
		{
			//clear local orders and products in case it actually deleted the product
			storage.clear(null , 'product_categories');
			storage.clear(null , 'products' );
			return $http.get('/api/products/delete_category/' + category_id ).then(
				function(response)
				{
					return response.data;
				}).catch(function(data){return data;});	
		}

		function isOrdered(  product_id )
		{
			return $http.get('/api/products/count_ordered/' + product_id ).then(function(response)
				{
					return response.data;
				}).catch( function(data){ return data; });
		}

		function setStock(product_id , stock_count )
		{
			return $http.get('/api/products/set_stock/' + product_id + '/' + stock_count ).then(function(response)
				{
					return response.data;
				}).catch( function(data){ return data; });
		}

		function clearCache( )
		{
			storage.remove('product_images');
			storage.remove('product_categories');
			storage.remove('product_files');
			storage.remove('products');
		}

	}

	function orderData(  $http , storage  )
	{

		

		var service = 
		{
			getOrder: 		getOrder,
			getAllOrders: 	getAllOrders,
			getOrderSummary:getOrderSummary, 
			cancelOrder: 	cancelOrder,
			deleteOrder: 	deleteOrder,
			archiveOrder: 	archiveOrder,
			cancelWhereOrdered: cancelWhereOrdered, //cancel all orders where a product is ordered.
			OrderType: 		OrderType,
			orderUrl: 		orderUrl,
			verifyOrder: 	verifyOrder, //verify order given verification code
			collectOrder: 	collectOrder, //take me to an order given collection code
			placePosOrder: 	placePosOrder, //place a point of sale order.

			//////SHIPPING 
			getShippingQuotation: getShippingQuotation,
			getDeliveriesSummary: getDeliveriesSummary,
			getDeliveries:        getDeliveries,
			setShipped: 		  setShipped

		};

		return service;


		function getOrder(   order_id )
		{
			//query bckend by default, no cacheing orders
			return $http.get('/api/orders/get/' + order_id )
						.then
						( function(response)
						{
							return response.data;		  
						})
						.catch( function(err)
						{
						 	return err;
						 } );	
			
		} 	

		function setShipped( order_id )
		{
			return $http.get('/api/shipments/set_shipped/' + order_id ).then(function(resp)
			{
				return resp.data;
			}).catch(function(err){return err;});
		}

		function getAllOrders( filter  , offset , max  , order_by )
		{
			if( null ==(filter))
				filter = 'all';
			if( null ==(offset))
				offset = 0;
			if( null ==(max))
				max = 10;
			if( null ==(order_by))
				order_by = 'DESC';
			return $http.get('/api/orders/all/' + filter + '?offset=' + offset + '&max=' + max + '&order_by=' + order_by  )
						.then( function(response){ storage.set('orders' , response.data); return response.data; })
						.catch( function(){ return storage.get('orders'); } );
		}

		function getOrderSummary()
		{
			return $http.get('/api/orders/summary')
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function cancelOrder( order_id )
		{
			return $http.get('/api/orders/cancel/' + order_id )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function deleteOrder( order_id )
		{
			
			return $http.get('/api/orders/delete/' + order_id )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function archiveOrder(  order_id )
		{
			return $http.get('/api/orders/move_to_archive/' + order_id )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});	
		}

		function cancelWhereOrdered( product_id )
		{

			return $http.get('/api/orders/cancel_where_ordered/' + product_id )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function OrderType( order )
	  	{
	  		var otype = 'Collect in Store';
	  		if( angular.isUndefined(order ) )
	  			return otype;
	  		if( order.shipping.type === 'deliver')
	  			otype = 'Delivery'
	  		else if( order.shipping.type === 'book')
	  			otype = 'Booking';
	  		return otype;
	  	}

	  	function orderUrl(  order )
	  	{
	  		return '/shop/view_order/' + order.order_id;
	  	}

	  	function verifyOrder( v_code )
	  	{
	  		return $http.get('/api/orders/verify_code/' + v_code ).then(function(response){return response.data;})
	  					.catch(function(err){return err;});		
	  	}

	  	function collectOrder( c_code )
	  	{
	  		return $http.get('/api/orders/collection_code/' + c_code ).then(function(response){return response.data;})
	  					.catch(function(err){return err;});		
	  	}

	  	function placePosOrder( invoice )
	  	{
	  		return $http.post('/api/orders/create_pos_order' , invoice).then
	  		(function(response)
	  		{
	  			return response.data;
	  		}).catch(function(data){ return data;} ); 
	  	}

	  	function getShippingQuotation(order_id , consignment)
	  	{
	  		return $http.post('/api/shipments/quotations/' + order_id , consignment ).then
	  		(function(response)
	  		{
	  			return response.data;
	  		}).catch(function(data){ return data;});
	  	}

	  	function getDeliveries( filter )
	  	{
	  		return $http.get('/api/shipments/all/' + filter  ).then(function(response){return response.data;})
	  					.catch(function(err){return err;});	
	  	}

	  	function getDeliveriesSummary(  )
	  	{
	  		return $http.get('/api/shipments/pending_summary'  ).then(function(response){return response.data;})
	  					.catch(function(err){return err;});	
	  	}
	}

	function shopperData( $http , storage )
	{

	}

	function userData( $http , storage , authService )
	{
		var service =
		{
			thisUser: 			thisUser, //returns currently logged in user
			isLoggedIn: 		isLoggedIn, //is user logged in
			getUser: 			getUser,  //get a user given the user id
			userImageUrl: 		userImageUrl, //url to user's image
			getUserLog: 		getUserLog, //get a user's log diven user id
			getUsersLog: 		getUsersLog, //get users log
			getUsers: 			getUsers, //get all users of the shop
			hasPermission:      hasPermission, //check if a user has a required permission
			isAdmin: 			isAdmin, //checks if current user is an administrator
			updateUser: 		updateUser, //update a user's details
			updatePassword: 	updatePassword, //update a user's password
			addUser: 			addUser, //add a user to db
			deleteUser: 		deleteUser, //delete a  user
			login: 				login, //login if service terminated
			logout: 			logout //logout 
		};

		return service;

		function thisUser( )
		{
			return storage.get('user' )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/users/me' )
									 .then( function(response){ return storage.set('user'  , response.data); });
						  }); 
		}

		function isLoggedIn( )
		{
			return service.thisUser();
		}


		function login( email , password )
		{
			return $http.post('/api/users/login' , {email: email, password: password} )
				 		.catch( function(){ authService.loginCancelled(); } )
				 		.then( function(response){
								authService.loginConfirmed(response.data);
								return response.data;
						});

		}

		function logout( )
		{
			storage.clearAll();
		}

		function getUser( user_id )
		{
			return $http.get('/api/users/get/' + user_id )
						.then( function(response){ return storage.set('user_' + user_id , response.data); })
						.catch( function(){ return storage.get('user_' + user_id ); } );
		}

		function deleteUser( user_id )
		{
			storage.clear(null , 'users');
			return $http.get('/api/users/delete/'+ user_id ).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err;});
		}

		function getUserLog( user_id , filter , count )
		{
			if( null ==(count))
				count = 50;
			if( null ==(filter))
				filter = 'all';
			
			return $http.get('/api/users/logs/' + user_id + '/' + filter + '?max=' + count )
						.then( function(response){ return storage.set('user_'+ user_id +'log'  , response.data); })
						.catch( function(){ return storage.get('user_'+ user_id +'log' ); } );
		}

		function getUsersLog( filter , count )
		{
			if( null ==(count))
				count = 50;
			if( null ==(filter))
				filter = 'all';
			return $http.get('/api/users/logs/everyone/'+ filter + '?max=' + count )
						.then( function(response){ return storage.set('users_log'  , response.data); })
						.catch( function(){ return storage.get('users_log' ); } );
		}

		/**Check if  a user has a permission **/
		function hasPermission( permission )
		{
			var b = false;
			var user = 0;
			service.thisUser().then(function(data)
				{
					user = data;
				} , function(){  return false; });

			if( isAdmin() )
			{
				return true;
			}
			switch( permission )
			{
				
				case 'operator':
					b = user.permission_operator;
				break;
				case 'refund':
					b = user.permission_refund;
				break;
				case 'manage_stock':
					
					b = user.permission_manage_stock;
				break;
				case 'manage_products':

					b = user.permission_manage_stock;
				break;
				case 'view_logs':
					b = user.permission_view_logs;
				break;	
			}
			return b;
		}

		/** Check if user has admin permissions **/
		function isAdmin()
		{
			var user = storage.get('user');
			return ( user.permission_admin ) ? true : false;
		}

		function userImageUrl( image )
		{
			if( angular.isUndefined(image.filename))
				return assetsBase + 'files/user_images/not_found.png';
			return assetsBase + 'files/user_images/' + image.filename;
		}

		function getUsers( )
		{
			return storage.get('users' )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/users/all' )
									 .then( function(response){ return storage.set('users'  , response.data); });
						  }); 
		}

		function updateUser( user )
		{
			storage.clear(null , 'users');
			return $http.post('/api/users/update' , {user: user } ).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err;});
		}

		function updatePassword( user_id , old_password , new_password )
		{
			storage.clear(null, 'users');
			return $http.post('/api/users/update_password' , { user_id: user_id, current_pass: old_password, new_pass: new_password}).then(function(response)
			{
				return response.data;
			} , function(err){return err;});
		}

		function addUser( user )
		{
			storage.clear(null, 'users');
			return $http.post('/api/users/add' , { user: user }).then(function(response)
			{
				return response.data;
			} , function(err){return err;});
		}

	}


	function shopData( $http , storage )
	{
		var shop = {};
		var service = 
		{
			getShop: 			getShop,
			getSettings: 		getSettings, //
			getWallet: 			getWallet, 
			requestWalletWithdraw: requestWalletWithdraw,//request to wothdraw money from wallet
			getShopSummary: 	getSummary, //summary of the important stuff
			getImages: 			getImages, //get shop images 
			imageUrl: 			imageUrl ,//url of shop image
			getImage: 			getImage, //get shop image
			deleteFile: 		deleteFile, //delete a file given type and id
			updateFile: 		updateFile, //update a file
			changeLogo: 		changeLogo, //quickly change the logo.
			updateShop: 		updateShop ,
			subdomainAvailable: subdomainAvailable,//check if subdomain is available
			changeSubdomain: 	changeSubdomain,
			changeAlias: 		changeAlias,
			publish: 			publish, //publish or unpublish a shop
			saveSettings: 		saveSettings, //save settings
			checkDNSOk: 		checkDNSOk, //check if dns setup for subdomain
		};

		return service;


		function getShop(  )
		{
		
			return storage.get('shop' )
						  .then( function(data){ return data; } )
						  .catch( function(data)
						  {
						 return $http.get('/api/core/shop' )
									 .then( function(response){ return storage.set('shop'  , response.data); });
						  }); 		
		}

		function getSettings( type  , get_cached )
		{
			if( null ==(type))
				type = 'all';
			if( null ==(get_cached))
				get_cached = true;
			if( get_cached )
				return storage.get('shop_settings').catch(function(err)
					{
						return $http.get('/api/settings/get/' + type ).then(
						function(response)
						{
							return storage.set('shop_summary' , response.data);
						}).catch(function(err){return err;});
					});

			return $http.get('/api/settings/get/' + type ).then(
				function(response)
				{
					return storage.set('shop_summary' , response.data);
				}).catch(function(err){return err;});
		}

		function getWallet(  )
		{
			return $http.get('/api/wallet/get').then(function(resp)
			{
				return resp.data;
			}).catch(function(err)
			{
				return err;
			});
		}

		function requestWalletWithdraw( amount )
		{
			return $http.get('/api/wallet/request_withdraw/' + amount ).then(function(resp)
			{
				return resp.data;
			}).catch(function(err)
			{
				return err;
			});	
		}

		function getSummary( use_cache )
		{

			if( ! use_cache )
			{
				return $http.get('/api/core/summary')
						.then(function(response)
						{
							return storage.set('summary',response.data);
						})
						.catch(function(data)
						{
							return data;
						});	
			}
			return storage.get('summary')
						.then(function(data)
						{
							return data;
						})
						.catch(function(data)
						{
							return getSummary( false );
						});
			
		}

		function imageUrl(  image )
		{
			if( angular.isUndefined(image.filename))
				return assetsBase + 'files/shop_images/not_found.png';
			return assetsBase + 'files/shop_images/' + image.filename;
		}

		function updateShop( new_shop )
		{

		}

		function getImage( image_id )
		{	
			var index = 'shop_image_' + image_id;
			return storage.get( index )
						  .then( function(data){ return storage.set(index ,data); } )
						  .catch( function(data)
						  {
						 return $http.get('/api/files/get/shop_image/' + image_id  )
									 .then( function(response){ return storage.set( index , response.data); });
						  }); 
		}

		function deleteFile( type , id )
		{
			storage.clear(null, type + 's');
			return $http.get('/api/files/delete/'+type + '/'+id).then(function(response)
			{
				return response.data;
			});

		}

		function updateFile( type , file )
		{
			storage.clear(null, type + 's');
			return $http.post('/api/files/update/' + type , {file: file } ).then(function(response)
			{
				return response.data;
			});

		}

		function changeLogo( image_id )
		{
			return $http.post('/api/themes/change_shop_logo' , {image_id: image_id } ).then
			(function(response)
			{
				return storage.set('shop' , response.data);
			}).catch(function(err)
			{
				return err;
			});
		}

		function getImages(  )
		{	
			var index = 'shop_images';
			return storage.get( index )
						  .then( function(data){ return storage.set(index,data); } )
						  .catch( function(data)
						  {
						 return $http.get('/api/files/all/shop_images/'  )
									 .then( function(response){ return storage.set( index , response.data); });
						  }); 
		}

		
		function updateShop( shop )
		{
			return saveSettings('core' , shop);
		}

		function subdomainAvailable(subdomain )
		{
			return $http.post('/api/settings/subdomain_available' , {subdomain: subdomain}).then(function(resp)
			{
				return resp.data;
			});	
		}

		function changeSubdomain( new_subdomain )
		{
			storage.clear('shop');
			return $http.post('/api/settings/change_subdomain' , {subdomain: new_subdomain}).then(function(resp)
			{
				return resp.data;
			});	
		}

		function changeAlias( new_alias, use_alias )
		{
			storage.clear('shop');
			return $http.post('/api/settings/change_alias' , {alias: new_alias,use_alias: use_alias}).then(function(resp)
			{
				return resp.data;
			});	
		}

		function publish( b_publish  , admin_password )
		{
			storage.clear('shop');
			storage.clear('shop_settings');
			return $http.post('/api/settings/publish' , { publish: b_publish , password: admin_password }).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err});
		}

		function saveSettings( type , settings )
		{
			return $http.post('/api/settings/update/'+ type , settings ).then(function(response)
			{
				storage.clearAll();
				return response.data;
			}).catch(function(err){return err;});
		}

		function checkDNSOk( alias )
		{
			return $http.post('/api/settings/check_dns_setup' , {alias: alias}).then(function(resp)
			{
				return resp.data;
			});
		}

	}

	function notifyData( $http , storage )
	{
		var service = 
		{
			sendCustomerEmail:  sendCustomerEmail,
			sendCustomerSms: 	sendCustomerSms,
			sendUserEmail: 		sendUserEmail,
			sendUserSms: 		sendUserSms
		};

		return service;

		function _send( target , id  , data )
		{

			return $http.post('/api/contact/' + target + '/' + id , data ).then(function(resp)
			{
				return resp.data;
			}).catch(function(resp)
			{
				return resp.data;
			});
		}

		function sendCustomerEmail( order_id , title , text , type  )
		{
			if( type == null )
				type = 'info';
			var data = {
				title: title,
				message: 'email',
				type: type,
				text: text
			};
			return _send( 'customer' , order_id , data  );
		}

		function sendCustomerSms( order_id , text  )
		{
			var data = {
				title: '',
				message: 'sms',
				type: 'info',
				text: text
			};
			return _send( 'customer' , order_id , data  );
		}

		function sendUserEmail( user_id , title , text , type  )
		{
			if( type == null)
				type = 'info';
			var data = {
				title: title,
				message: 'email',
				type: type,
				text: text
			};
			return _send( 'user' , user_id , data  );
		}

		function sendUserSms(  user_id , text )
		{
			var data = {
				title: title,
				message: 'sms',
				type: 'info',
				text: text
			};
			return _send( 'user' , user_id , data  );
		}
	}

	function transactionData( $http , $q , storage )
	{
		
	}

	function accountData( $http ,  storage )
	{

		var service = 
		{
			load: 			load, //load account data
			update: 		update, //update the account details with new ones - saves to server
			expires: 		expires, //get when the current subscription expires
			getSub: 		getSub, //get current subscription 
			getAllSubs:		getAllSubs, //get all subscriptions
			planTypes: 		planTypes, //get all available plan types
			getUpgradeLink: getUpgradeLink,//link to upgrade to certain plan
		};

		return service;

	

		function load( )
		{

		}

		function update(  _account_data )
		{

		}

		function expires(  )
		{

		}

		function getSub( )
		{

			return $http.get('/api/core/subscription').then(function(resp)
				{
					return storage.set('current_subscription' , resp.data);
				}).catch(function(err)
				{
					return storage.get('current_subscription');
				});
		}

		function getAllSubs( )
		{
			return $http.get('/api/core/all_subscriptions').then(function(resp)
			{
				return resp.data;
			}).catch(function(err){});
			
		}

		function planTypes(  )
		{
			return storage.get('plan_types').then(function(data)
				{
					return data;
				}).catch(function(err)
				{
					$http.get('/api/core/subscription_types').then(function(resp)
					{
						return storage.set('plan_types' , resp.data);
					});
				});	
		}

		function getUpgradeLink( plan,period )
		{
			return $http.post('/api/settings/get_upgrade_link' , {plan: plan,months: period}).then(function(resp)
			{
				return resp.data;
			}).catch(function(err){return err;});

		}

	}

	function blogData( $http , storage )
	{
		var service =
		{
			getPost: 		getPost, //get a blog post
			getTopPosts: 	getTopPosts, //
			getAll: 		getAllPosts,
			updatePost: 	updatePost, //update post
			addPost: 		addPost,
			removePost: 	removePost,
			postUrl: 		postUrl,
			blogUrl: 		blogUrl 		
		};

		return service;

		function getPost( post_id  )
		{
			return $http.get('/api/blog/get/' + post_id )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function getTopPosts( count  )
		{
			if( null ==(count))
				count = 3;
			var index = 'topblogposts';
			return storage.get(index).then(
				function(data)
				{
					return data;
				} , function(data)
				{
					return $http.get('/api/blog/top/' + count ).then
							(function(response){ storage.set(index , response.data); });
				});	
		}

		function updatePost( post )
		{
			return $http.post('/api/blog/update' , post ).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err;});
		}

		function getAllPosts( )
		{
			var index = 'blogposts';
			return storage.get(index).then(
				function(data)
				{
					return data;
				} , function(data)
				{
					return $http.get('/api/blog/all').then
							(function(response){ return storage.set(index , response.data); });
				});
		}

		function addPost( post )
		{
			return $http.post('/api/blog/add' , post ).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err;});
		}

		function removePost(  post_id )
		{
			return $http.post('/api/blog/remove/' +  post_id ).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err;});
		}

		function postUrl( post )
		{
			return '/blog/post/' + post.post_id ;
		}

		function blogUrl(  )
		{
			return '/blog/';
		}
	}

	function themeData( $http , storage )
	{
		var service = {
			theme: 		getCurrent, //get current theme settings
			allThemes: 	allThemes, //get all themes in oneshop
			update: 	update, //update current theme
			apply: 		apply, //apply a different theme
			loadCSS: 	loadCSS,//load current theme css
			saveCSS: 	saveCSS,//save current theme custom css
			saveDict: 	saveDictionary,//save new dictionary
			preview: 	preview //request a theme preview url
		};

		return service;

		function getCurrent(  )
		{
			return storage.get('current_theme').catch(function(data)
			{
				return $http.get('/api/themes/current').then(function(response)
				{
					return storage.set('current_theme' , response.data );
				});
			});
		}

		function allThemes(  )
		{
			//not saved trying to use less localStorage :)
			return $http.get('/api/themes/all').then(function(response)
			{
				return  response.data ;
			});
			
		}

		function update(type , value )
		{

		}

		function apply( theme_name , page )
		{
			storage.clear(null , 'current_theme');
			return $http.post('/api/themes/apply/' + theme_name , {page: page} ).then(
				function( response )
				{
					service.theme().then(function(){}).catch(function(){});
					return response.data;
				}).catch(
				function(err)
				{
					return err;
				});
		}

		function loadCSS( )
		{
			return $http.get('/api/themes/current_css').then(function(response)
			{
				return response.data;
			}).catch(function(err)
			{
				return err;
			})
		}

		function saveCSS(css )
		{
			return $http.post('/api/themes/save_custom_css' , {css: css}).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err});
		}

		function saveDictionary( words )
		{
			return $http.post('/api/themes/update_theme_wording' , {words: words}).then(function(response)
			{
				return response.data;
			}).catch(function(err){return err});
		}

		function preview( theme_name )
		{
			//@todo open new window with custom applied theme
		}

	}

	function reportData( $http , storage )
	{
		var service = {
			get: 			getReport, //get a particuar report
			getDocument: 	getDocument,//get a document report
		};

		return service;

		function getReport( report_type )
		{
			return $http.get('/api/reports/get/' + report_type )
						.then(function(response){ return response.data; })
						.catch(function(err){return err;});
		}

		function getDocument( report_type , format  )
		{
			alert('@todo service get report document');
		}
	}


})();		   
