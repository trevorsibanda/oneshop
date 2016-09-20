<?php
/**
 * Advertising network.
 *
 * Used within pages.
 */


/**
 * Check if the ad network is activated for the current shop
 *
 * @return 		Bool
 */
function is_ad_engine( )
{
	return defined('OS_AD_ENGINE_ON'); 
}

/**
 * HTML Ad network initialisation code.
 * 
 * @return 		String 		$	Html code
 */
function ad_engine_init()
{
	if( ! is_ad_engine() )
		return '';
	$CI =& get_instance();

	return $CI->ui->ad_engine->get_init_html( ); 
}

/**
 * Html Ad finish code.
 *
 * @return 		String 		$	Html code
 */
function ad_engine_end()
{
	if( ! is_ad_engine() )
		return False;
	$CI =& get_instance();

	return $CI->ui->ad_engine->get_close_html( );
}

/**
 * Get advert html code.
 * 
 * @param 		String 		$	Type of advert - banner, text , popup, popunder 
 * @param 		String 		$	Resolution of ad, can be false if popup
 * @param  		String 		$	Network to use ( adsense, propellerads ?...etc)
 *
 * @return 		String 		$	Html code
 */
function get_advert(  $res = '729x90' , $type = 'banner' , $network = 'random'  )
{
	if( ! is_ad_engine() )
		return False;
	$CI =& get_instance();

	$html = $CI->ui->ad_engine->get_advert_html( $res , $type , $network );
	return $html;
}


