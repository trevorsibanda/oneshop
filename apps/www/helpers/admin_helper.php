<?php
/**
 * Shop Admin helper
 *
 *
 *
 */

 function admin_css( $filename)
 {
	return admin_resource( 'css' , $filename );
 }
 
 function admin_js(  $filename )
 {
	return admin_resource( 'js' , $filename );
 }
 
 function admin_img( $filename )
 {
	return admin_resource( 'img' , $filename );
 }
 
 function admin_font(  $filename )
 {
	return admin_resource( 'font' , $filename );
 }
 
 function admin_resource(  $folder , $filename )
 {
	return  ASSETS_BASE . 'admin/'  . $folder . '/' . $filename;
 }
 

