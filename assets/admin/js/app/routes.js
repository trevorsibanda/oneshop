/** Routes **/
(function()
	{
		'use strict';

		angular.module('app.routes' , ['ngRoute'])
			   .constant( 'TEMPLATE_CFG' , {url: function(file){ return '/assets/admin/ui/'+ file;} } )
			   .config( ['$routeProvider' , 'TEMPLATE_CFG' , AppRoutes ] );


		function AppRoutes(  $routeProvider , cfg )
		{
			
			$routeProvider
			//welcome controller
			.when('/welcome' , 
			{
				templateUrl:	cfg.url('welcome.html') ,
				controller: 	'WelcomeCtrl',
				controllerAs: 	'vm'
			})
			//dashboard
			.when('/dashboard' , 
			{
				templateUrl:	cfg.url('dashboard.html') ,
				controller: 	'DashBoardCtrl',
				controllerAs: 	'vm'
			})
			//Point of Sale
			.when('/point-of-sale' ,
			{
				templateUrl: 	cfg.url('pos/index.html') ,
				controller: 	'PosIndexCtrl',
				controllerAs: 	'vm'	
			})
			//receipt maker
			.when('/pos/receipt-maker' ,
			{
				templateUrl: 	cfg.url('pos/receipt-maker.html') ,
				controller: 	'PosReceiptMakerCtrl',
				controllerAs: 	'vm'
			})
			//view receipt for order
			.when('/pos/receipt-maker/:order_id' ,
			{
				templateUrl: 	cfg.url('pos/receipt-maker.html') ,
				controller: 	'PosReceiptMakerCtrl',
				controllerAs: 	'vm'
			})
			//view receipt for order
			.when('/pos/print-receipt/:json' ,
			{
				templateUrl: 	cfg.url('pos/receipt-maker.html') ,
				controller: 	'PosReceiptMakerCtrl',
				controllerAs: 	'vm'
			})
			/////////////// BEGIN ORDERS /////////////////////
			//view an order
			.when('/orders/view/:order_id' , 
			{
				templateUrl: 	cfg.url('orders/view_order.html'),
				controller: 	'OrdersViewOrderCtrl' ,
				controllerAs: 	'vm'
			})
			//pending
			.when('/orders/pending' , 
			{
				templateUrl: 	cfg.url('orders/pending.html') ,
				controller: 	'OrdersPendingCtrl' ,
				controllerAs: 	'vm'
			})
			//browse
			.when('/orders/browse' ,
			{
				templateUrl: 	cfg.url('orders/browse.html') ,
				controller: 	'OrdersBrowseCtrl' ,
				controllerAs: 	'vm'
			})
			//shipping
			.when('/orders/shipping' ,
			{
				templateUrl: 	cfg.url('orders/shipping.html') ,
				controller: 	'OrdersShippingCtrl' ,
				controllerAs: 	'vm'
			})
			//gifts and vouchers
			.when('/orders/gifts' , 
			{
				templateUrl: 	cfg.url('orders/gifts.html') ,
				controller: 	'OrdersGiftsCtrl',
				controllerAs: 	'vm'
			})
			//customer support services
			.when('/orders/support' , 
			{
				templateUrl: 	cfg.url('orders/support.html') ,
				controller: 	'OrdersCustomerSupportCtrl',
				controllerAs: 	'vm'
			})
			////////////////////////// END OF ORDERS ///////////////////////////////
			////////////////////////// PRODUCTS ////////////////////////////////////
			//view a product
			.when('/product/view/:product_id' ,
			{
				templateUrl: 	cfg.url('products/view_product.html') ,
				controller: 	'ProductsViewProductCtrl' ,
				controllerAs: 	'vm'
			})
			//browse all products
			.when('/products/browse'  , 
			{
				templateUrl: 	cfg.url('products/browse.html') ,
				controller: 	'ProductsBrowseCtrl' ,
				controllerAs: 	'vm'
			})
			//add a new product
			.when('/products/add' , 
			{
				templateUrl: 	cfg.url('products/add_product.html') ,
				controller: 	'ProductsAddProductCtrl' ,
				controllerAs: 	'vm'
			})
			//edit product
			.when('/products/edit/:product_id' , 
			{
				templateUrl: 	cfg.url('products/add_product.html') ,
				controller: 	'ProductsAddProductCtrl' ,
				controllerAs: 	'vm'
			})
			//product categories
			.when('/products/categories' , 
			{
				templateUrl: 	cfg.url('products/categories.html') ,
				controller: 	'ProductsCategoriesCtrl' ,
				controllerAs: 	'vm'
			})
			//view a product category
			.when('/products/view_category/:category_id',
			{
				templateUrl: 	cfg.url('products/view_category.html') ,
				controller: 	'ProductsViewCategoryCtrl' ,
				controllerAs: 	'vm'
			})
			//stock manager
			.when('/products/stock-manager' ,
			{
				templateUrl: 	cfg.url('products/stock_manager.html') ,
				controller: 	'ProductsStockManagerCtrl' ,
				controllerAs: 	'vm'
			})
			////////////////////// END OF PRODUCTS /////////////////////
			////////////////////// REPORTS /////////////////////////////
			//main page
			.when('/reports' , 
			{
				templateUrl: 	cfg.url('reports/index.html') ,
				controller: 	'ReportsIndexCtrl' ,
				controllerAs: 	'vm'
			})
			//view a particular report
			.when('/reports/:report_type' , 
			{
				templateUrl: 	cfg.url('reports/view.html'),
				controller: 	'ReportsViewCtrl' ,
				controllerAs: 	'vm'
			})
			//view a particular report, abit more advanced
			.when('/reports/:report_type/:report_name' , 
			{
				templateUrl: 	cfg.url('reports/view.html'),
				controller: 	'ReportsViewCtrl' ,
				controllerAs: 	'vm'
			})
			
			/////////////////// END OF REPORTS /////////////////////////
			/////////////////// SHOP DESIGNER //////////////////////////
			//select theme
			.when('/designer/theme' ,
			{
				templateUrl: 	cfg.url('designer/theme.html'),
				controller: 	'DesignerThemeCtrl',
				controllerAs: 	'vm'
			})
			//edit layout
			.when('/designer/edit_layout' , 
			{
				redirectTo: 	'/designer/theme'
			})
			//edit vocabulary
			.when('/designer/edit_vocabulary' ,
			{
				redirectTo: 	'/designer/theme'
			})
			/////////////////////// END OF DESIGNER ////////////////////////
			/////////////////////// BLOG ///////////////////////////////////
			//write new article
			.when('/blog/write' ,
			{
				templateUrl: 	cfg.url('blog/editor.html') ,
				controller: 	'BlogEditorCtrl' ,
				controllerAs: 	'vm'
			})
			//edit article
			.when('/blog/edit/:post_id' ,
			{
				templateUrl: 	cfg.url('blog/editor.html') ,
				controller: 	'BlogEditorCtrl' ,
				controllerAs: 	'vm'
			})
			//browse articles
			.when('/blog/browse' ,
			{
				templateUrl: 	cfg.url('blog/browse.html') ,
				controller: 	'BlogBrowseCtrl',
				controllerAs: 	'vm'
			})
			////////////////////// END OF BLOG ///////////////////////////////////
			////////////////////// SETTINGS    //////////////////////////////////
			.when('/settings' , 
			{
				redirectTo: 	'/settings/general'
			})
			// General settings
			.when('/settings/general' ,
			{
				templateUrl: 	cfg.url('settings/general.html') ,
				controller: 	'SettingsGeneralCtrl' ,
				controllerAs: 	'vm'
			})
			// My account
			.when('/settings/account' ,
			{
				templateUrl: 	cfg.url('settings/account.html') ,
				controller: 	'SettingsAccountCtrl' ,
				controllerAs: 	'vm'
			})
			//account
			.when('/settings/account/:action' ,
			{
				templateUrl: 	cfg.url('settings/account.html') ,
				controller: 	'SettingsAccountCtrl' ,
				controllerAs: 	'vm'
			})
			// Upgrade account
			.when('/settings/upgrade_account' ,
			{
				templateUrl: 	cfg.url('settings/account.html') ,
				controller: 	'SettingsAccountCtrl' ,
				controllerAs: 	'vm'
			})
			
			//Shop users
			.when('/settings/users' ,
			{
				templateUrl: 	cfg.url('settings/users.html') ,
				controller: 	'SettingsUsersCtrl' ,
				controllerAs: 	'vm'
			})
			//Payments
			.when('/settings/payments' ,
			{
				templateUrl: 	cfg.url('settings/payments.html') ,
				controller: 	'SettingsPaymentsCtrl' ,
				controllerAs: 	'vm'
			})
			//Stats and analytics
			.when('/settings/analytics' , 
			{
				templateUrl: 	cfg.url('settings/analytics.html') ,
				controller: 	'SettingsAnalyticsCtrl' ,
				controllerAs: 	'vm'
			})
			//orders and shipping
			.when('/settings/orders' , 
			{
				templateUrl: 	cfg.url('settings/shipping.html') ,
				controller: 	'SettingsShippingCtrl' ,
				controllerAs: 	'vm'	
			})
			////////////////// END OF SETTINGS ///////////////////
			.otherwise(
			{
				redirectTo: 	'/dashboard'
			});
		}	

	}
)();