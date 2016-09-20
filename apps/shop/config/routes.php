<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site/to_index";
//Landii=ng page
$route['shop'] = 'site';
//About us
$route['shop/about-us'] = 'site/about_us';


//Browse products
$route['shop/browse/(:any)'] = 'site/browse/$1';
$route['shop/browse'] = 'site/browse';

//Shopping cart
$route['shop/cart'] = 'site/view_cart';

//Product
$route['shop/product/(:num)/(:any)'] = 'site/view_product/$1';
$route['shop/product/(:num)'] = 'site/view_product/$1';

//Category
$route['shop/category/(:num)/(:any)'] = 'site/view_category/$1/default';
$route['shop/category/(:num)'] = 'site/view_category/$1/default';

//Search

$route['shop/search'] = 'site/search';

//Featured products
$route['shop/featured'] = 'site/view_featured/';

//Sitemap
$route['sitemap' ] = 'site/sitemap';
//FAQ
$route['faq'] = 'site/faq';
//Verification info
$route['verification_info'] = 'site/verification_info';
//Contact us page
$route['contact-us'] = 'site/contact_us';
//About Us
$route['about-us'] = 'site/about_us';


$route['404_override'] = 'site/error_404';


/* End of file routes.php */
/* Location: ./application/config/routes.php */