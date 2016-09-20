<?php
/**
 * Site helper
 *
 *
 *
 *
 *
 *
 */

 function theme_css( $theme , $filename)
 {
	return theme_resource($theme , 'css' , $filename );
 }
 
 function theme_js( $theme , $filename )
 {
	return theme_resource($theme , 'js' , $filename );
 }
 
 function theme_img(  $theme , $filename )
 {
	return theme_resource($theme , 'img' , $filename );
 }
 
 function theme_font( $theme , $filename )
 {
	return theme_resource($theme , 'font' , $filename );
 }
 
 function theme_resource( $theme , $folder , $filename )
 {
	return  ASSETS_BASE . 'theme/' . $theme . '/' . $folder . '/' . $filename;
 }
 

 function public_resource(  $path )
 {
 	return ASSETS_BASE . 'public/' . $path;
 }

 function public_css( $path )
 {
 	return public_resource('css/' . $path );
 }


 function public_js( $path )
 {
 	return public_resource('js/' . $path );
 }



 function public_font( $path )
 {
 	return public_resource('font/' . $path );
 }


 function public_img( $path )
 {
 	return public_resource('img/' . $path );
 }
 
 function public_video( $path )
 {
 	return public_resource('video/' . $path );
 }



 function shop_url( $page = '' )
 {
	 return '/shop/' . $page;
 }
 

function shop_image(  $shop_image )
{
	return '/assets/files/shop_images/' . $shop_image['filename'];
}

function urlify_text( $text )
{
	return strtolower( str_replace(array(" ", ',') , array("-" , '') , $text ) );
}

function product_image(  $product_image )
{
	return '/assets/files/product_images/' . $product_image['filename'];
}

function product_url(  $product )
{
	return shop_url( 'product/' . $product['product_id'] . '/' . urlify_text( $product['name'] )  );
}


function category_url(  $category )
{
	return shop_url( 'category/' . $category['category_id'] . '/' . urlify_text($category['name']) );
}

