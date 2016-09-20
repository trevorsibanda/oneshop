(function()
	{
		'use strict';
		angular.module('app.core' , [])
			   .factory('dataService' , dataService );

		dataService.$inject = ['$http' , 'localStorageModule'];

		function dataService( $http ,  $storage )
		{
			var backend = 'http://oneshop.co.zw/backend/';//backend url
			var service = {
				getUser:  			getUser , //get a user's details given id
				getAllUsers: 		getAllUsers, //get all of a shop's users

				getProduct: 		getProduct, //get a product given its id
				getAllProducts: 	getAllProducts, //get all products
				getAllCategories: 	getAllCategories, //get all product categories

				getTransaction: 	getTransaction, //get a transaction
				getAllTransactions: getAllTransactions, //get all transactions

				getSettings: 		getSettings, //get all shop settings

				getOrder: 			getOrder, //get an order
				getAllOrders: 		getAllOrders, //get all orders

				getShopper: 		getShopper, //get a shopper
				getSub: 			getSubscr, //get subscription given its id


			};

			return service;

			/** Get a user **/
			function getUser( $user_id )
			{

			}

			/**getall users **/
			function getAllUsers()
			{

			}

			function getProduct( $product_id )
			{

			}

			function getAllProducts(  )
			{

			}

			function getAllCategories( )
			{

			}

			function getTransaction( $transaction_id )
			{

			}

			function getAllTransaction()
			{

			}

			function getSettings( )
			{

			}

			function getOrder( $order_id )
			{

			}

			function getAllOrders(  )
			{

			}

			function getShopper()
			{
				
			}

		};	   

	}
)();