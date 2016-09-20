<?php

//ini_set('session.save_path' , '/tmp');
define('CI_ENV' , 'production');

/**Config directory */
define('CONFIG_DIR' , dirname(__FILE__) );

/** OneShop Naked Domain Name **/
define('OS_DOMAIN' , '263shop.co.zw');

/** Base Domain **/
define('OS_BASE_DOMAIN' , '.263shop.co.zw');

define('OS_SECURE_DOMAIN' , 'secure' . OS_BASE_DOMAIN );

define('OS_BASE_SITE' , 'https://www' . OS_BASE_DOMAIN );

define('OS_SITE_NAME' , '263Shop ');

define('OS_CURRENCY_SYMBOL' , '$');

/** Apps Path **/
define('OS_APPS_PATH'  , CONFIG_DIR . '/apps/' );

define('OS_SHOP_APP_PATH'  , OS_APPS_PATH . 'shop' );

/** Default app **/
define('OS_DEFAULT_APP' , OS_APPS_PATH . 'www');

/** Shop site. Handles all *.domain.com **/
define('SHOP_SITE_APP' , 'shop' );

/** System **/
define('OS_SYS_PATH'  , CONFIG_DIR .'/system/');


//////////////////////////////////////////////////////
// Shared resources
//////////////////////////////////////////////////////

/** Models Path **/
define('OS_SHARED_PATH' , OS_APPS_PATH .'shop/' );

/** Helpers Path **/
define('OS_SHARED_HELPERS_PATH'  , OS_APPS_PATH . 'shop/helpers');

/**Views Path **/
define('OS_SHARED_VIEWS_PATH'  , OS_APPS_PATH . 'shop/views');

/**Views Path **/
define('OS_SHARED_LIBRARIES_PATH'  , OS_APPS_PATH . 'shop/libraries');

///////////////////////////////////////////////////////////

/** Assets dir */
define('ASSETS_DIR' , CONFIG_DIR . '/assets/' );

/**Assets base files */
define('ASSETS_FILES' , ASSETS_DIR . 'files/');

/**Public assets */
define('ASSETS_PUBLIC_FILES' , ASSETS_DIR . 'public/');

/**Assets themes */
define('ASSETS_THEME_FILES' , ASSETS_DIR . 'theme/'); 

/** Assets Base */
define('ASSETS_BASE' , 'https://assets' . OS_BASE_DOMAIN .'/' );

/** Static Sites. Defined in apps **/

define('OS_STATIC_SITES' , 'www;user;admin;assets;dev;secure;hosted' );




/**
 * Get shop subdomain
 *
 * Extract the shop subdomain name.
 *
 * @return 	String
 *
 * @todo	Add support for custom domains
 */
function os_subdomain( &$is_domain = False )
{
	//lets explode the domain 
	$_domainParts = explode(".",$_SERVER["HTTP_HOST"]);

	if( count($_domainParts) >= 2 )
	{
		if( $_domainParts[0] == '263shop' )
			return 'www';
		if( $_domainParts[1] !== '263shop' )
		{
			$is_domain = True;
			return $_SERVER['HTTP_HOST'];
		}
		
		return $_domainParts[0];
	}

	return 'www';

}


/**
 * Check if the subdomain is a static site
 *
 *
 *
 */
function is_static_site( $subdomain )
{
	if( $subdomain != 'hosted' && in_array($subdomain, explode(';' , OS_STATIC_SITES) ) )
		return True;
	return False;
}


