<?php
/**
 * Shop account helper
 *
 *
 *
 */

$_shop_account = array('pos'=>False,'max_users'=>0,'max_products'=>0,'coupons'=>0,'sms'=>0,'app'=>0,'analytics'=>0,'custom_payments'=>0,'custom_domain'=>0,'adverts'=>0);

/**
 * Check if an account can perform a particular action 
 *
 * Actions are shortened version of the db table columns. 
 * Because I am lazy.. yes
 *
 * @param 		String 	$	Action
 *
 * @return 		Int
 */
function account_can( $action )
{
	global $_shop_account;
	return isset( $_shop_account[$action] ) ? $_shop_account[ $action ] : False;
}



/**
 * Apply a current account subscription and set limits
 *
 * @param 		Array 	$	Current subscription
 *
 * @return 		Null
 */
function apply_account( $current_subscription )
{
	global $_shop_account;

	if( ! is_array($current_subscription) )
	{
		die('Failed to apply shop subscription limits');
	}

	$_shop_account['adverts'] = $current_subscription['ad_supported'];
	$_shop_account['pos'] = $current_subscription['allow_pos'];
	$_shop_account['max_users'] = $current_subscription['max_users'];
	$_shop_account['max_products'] = $current_subscription['max_products'];
	$_shop_account['coupons'] = $current_subscription['allow_coupons'];
	$_shop_account['analytics'] = $current_subscription['allow_analytics'];
	$_shop_account['custom_payments'] = $current_subscription['custom_payment_details'];
	$_shop_account['sms'] = $current_subscription['sms_credit'];
	$_shop_account['app'] = $current_subscription['app_credit'];
	$_shop_account['custom_domain'] = $current_subscription['custom_domain'];
	$_shop_account['type'] = $current_subscription['type'];

}


/**
 * Alias for account_can('ad')
 *
 * 
 */
function ad_supported_account()
{
	return account_can('ad');
}

/**
 * Return the current shop account type
 * 
 * @return 		String 		
 */
function account( )
{
	global $_shop_account;
	return $_shop_account['type'];
}