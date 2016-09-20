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


 /** Escape string before sending to output, prevent XSS attacks **/	
 function _e( $string )
 {
 	return htmlspecialchars($string);
 }
 
function clean($string) 
{
	if( ! is_string($string))
		return $string;
   return preg_replace('/[^A-Za-z0-9\ \.\-]/', '', $string); // Removes special chars.
}

function seo_friendly_url($string)
{
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   $string = preg_replace('/[^A-Za-z0-9\-\/\?\[\]\{\}\=\+\&\_]/', '', $string); // Removes special chars.

   return strtolower( $string ); // Replaces multiple hyphens with single one.
}

 
 function shop_url( $page = '' )
 {
	 return seo_friendly_url('/shop/' . $page);
 }
 

function shop_image(  $shop_image )
{
	return ASSETS_BASE . 'files/shop_images/' . ( isset($shop_image['filename']) ? $shop_image['filename'] : 'file_not_found.jpg' );
}

function urlify_text( $text )
{
	return strtolower( str_replace(array(" ", ',' , '.') , array("-" , '' , '_') , $text ) );
}

function product_image(  $product_image )
{
	return ASSETS_BASE . 'files/product_images/' . ( isset($product_image['filename']) ? $product_image['filename'] : 'file_not_found.jpg' );
}

function random_product_image(  $product )
{
	return product_image( random_element(  $product['images'] ) );
}

function product_url(  $product )
{
	return shop_url( 'product/' . $product['product_id'] . '/' .  $product['name']   );
}


function product_category_url(  $category )
{
	return shop_url( 'category/' . $category['category_id'] . '/' . $category['name'] );
}

/**
 * Product File URL
 *
 * @param 		Array 	$	Product File
 * @param 		String 	$	URL action ( open , icon , ...)
 *
 */
function product_file_url( $file  )
{
	return '/analytics/download_file/' .  $file['hash'] . '.' . $file['ext'];
}

function in_cart( $product , $shopping_cart )
{
	if( is_array($shopping_cart) )
	{
		foreach( $shopping_cart as $cart_item )
		{
			if( $cart_item['product_id'] === $product['product_id'] )
				return $cart_item;
		}
	}
	else
	{
		return False;
	}

}

function cart_option( $cart_entry , $option )
{
	if(  isset($cart_entry['options']) )
	{
		foreach($cart_entry['options'] as $opt )
		{
			if( $opt['option'] === $option )
				return $opt['option'];
		}
	}
	return Null;
}

function remove_cart_url( $product )
{
	return "/cart/remove?product_id=" . $product['product_id'];
}

function money($amount)
{
    return OS_CURRENCY_SYMBOL . make_money($amount);
}

function make_money($price )
{
	return number_format(sprintf('%0.2f', preg_replace("/[^0-9.]/", "", $price ) ),2);
}

function oneshop_help( $entry )
{
	return OS_BASE_SITE . '/home/faq#' . urlify_text( $entry );
}

function browse_url( $filter , $page = 1 , $items_per_page = 10 , $sort = 'default' )
{
	return seo_friendly_url('/shop/browse/' . $filter . '?page=' . $page . '&items_per_page=' . $items_per_page . '&sort=' . $sort);
}


function admin_css( $file )
{
	return ASSETS_BASE . 'admin/css/' . $file;
}

function admin_js( $file  )
{
	return ASSETS_BASE . 'admin/js/' . $file;
}

function admin_img( $file )
{
	return ASSETS_BASE . 'admin/img/' . $file ;
}

function admin_asset(  $file )
{
	return ASSETS_BASE . 'admin/assets/' . $file;
}

function app_js( $file )
{
	return admin_js( 'app/' . $file );
}


function twitter_url($twitter_id)
{
	return 'https://www.twitter.com/' . $twitter_id;
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


function whatsapp_share(  $page = Null , $message = 'Checkout this page ! ' )
{
	if( is_null($page))
		$page = current_url();	
	$text = urlencode($message . " Visit " . $page . " for more info. ");
	return "whatsapp://send?text=$text";
}



function facebook_share( $page = Null  )
{
	if(  is_null($page))
		$page = current_url();
	return "http://www.facebook.com/sharer/sharer.php?u=" . urlencode($page);
}

function twitter_share(  $page = Null , $twitter_handle = '' , $hashtag = '' )
{
	if( is_null($page))
		$page = current_url();
	if( ! empty($twitter_handle) && $twitter_handle[0] != '@')
		$twitter_handle = '@' . $twitter_handle;
	return "http://twitter.com/share?text=". $twitter_handle ."&url=". urlencode($page) . "&hashtags=" . $hashtag;
}
