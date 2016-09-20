<?php
/**
 * Analytics helper
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @package		Helpers/analytics_helper
 * @date 		10 October 2015
 * 
 *
 *
 */
 
 
 function analytics_enabled()
 {
 	return defined('ANALYTICS_ENABLE');
 }

 function analytics_code( )
 {
 	$CI =& get_instance();
 	return $CI->ui->analytics->generate_code();
 }
