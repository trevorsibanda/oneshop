<?php
/**
 * Theme helper
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @package		Helpers/theme_helper
 * @date 		4 June 2015
 * 
 *
 *
 */


 function include_system_header()
 {
 	$CI =& get_instance();
 	$CI->load->view('system/theme/header');
 }

 function include_system_footer(  )
 {
 	$CI =& get_instance();
 	$CI->load->view('system/theme/footer');
 }


 function theme_featured_products(  $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_featured_products( $count );
 }

 function theme_suggested_products( $count = 3 , $product = Null )
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_recommended_products( $product , $count );
 }

 function theme_popular_products( $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_popular_products( $count );
 }

 function theme_recently_viewed_products(  $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_last_viewed_products($count );
 }

 function theme_product_categories( $count = 10)
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_product_categories(  $count );
 }


 function theme_cheap_products(  $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_cheap_products( $count );
 }

 function theme_expensive_products( $count = 3)
 {
 	$CI =& get_instance();
 	return $CI->product->search->get_expensive_products( $count );
 }

 function theme_popular_blog_posts( $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->blogpost->get_top_posts(  $count , 'views' );
 }

 function theme_next_blogpost( $post )
 {
 	$CI =& get_instance();
 	$np = $CI->blogpost->get_next_post( $post );
 	return empty($np) ? $post : $np;
 }

 function theme_previous_blogpost( $post )
 {
 	$CI =& get_instance();
 	$np = $CI->blogpost->get_prev_post( $post );
 	return empty($np) ? $post : $np;
 }

 function theme_most_commented_blog_posts(  $count = 3 )
 {
 	$CI =& get_instance();
 	return $CI->blogpost->get_top_posts(  $count , 'shares' );
 }

 function theme_recent_blog_posts(  $count = 3 , $order = 'DESC' )
 {
 	$CI =& get_instance();
 	return $CI->blogpost->get_latest_posts(  $count , $order );
 }

 function theme_suggested_blog_posts(  $count = 3  )
 {
 	$CI =& get_instance();
 	return $CI->blogpost->get_latest_posts(  $count );
 } 

 function theme_random_shop_images(  $count = 5 )
 {
 	//@todo
 }

 function theme_cart_items( )
 {
 	$CI =& get_instance();
 	return $CI->system->cart->items();
 }

 function theme_cart_total()
 {
 	$CI =& get_instance();
 	return $CI->system->cart->get_total();
 }

 function theme_cart_total_items()
 {
 	$CI =& get_instance();
 	return $CI->system->cart->count_items();
 }

 function theme_cart_order_id()
 {
 	$CI =& get_instance();
 	return $CI->system->cart->order_number();
 }

 function theme_cart_is_ordered()
 {
 	$CI =& get_instance();
 	return $CI->system->cart->is_cart_ordered();
 }

 function theme_custom_css( $shop , $theme )
 {
 	$CI =& get_instance();
 	$css_url = $CI->ui->css->dynamic_css_url(  $shop , $theme['info']['dir'] );
 	return '<link rel="stylesheet" id="os_custom_css" href="' . $css_url . '" />'; 
 }

 function theme_global( $theme , $option )
 {
	if( isset($theme['global'][ $option ])  )
	{
		if( is_array($theme['global'][ $option ]) )
		{
			return $theme['global'][ $option ]['value'];
		}
		return $theme['global'][ $option ];
	}
	return Null;
 }

 function theme_page( $theme , $page , $option )
 {
	if( isset($theme['page'][$page][ $option ])  )
	{
		if( is_array($theme['page'][$page][ $option ]) )
		{
			return $theme['page'][$page][ $option ]['value'];
		}
		return $theme['page'][$page][ $option ];
	}
	return Null;
 }

 function theme_info_option( $theme , $name )
 {
	return  ( isset($theme['info'][ $option ]) ?  $theme['info'][$option] : '' );
 }

 function theme_vocabulary( $theme , $name )
 {
 	return  ( isset($theme['vocabulary'][ $name ]) ?  $theme['vocabulary'][$name] : '' );
 }

 function theme_img( $theme , $path )
 {
 	return theme_resource( $theme , 'img/' . $path );
 }


/** JS **/
 function theme_js( $theme , $path )
 {
 	return theme_resource( $theme , 'js/' . $path );
 }

 function theme_css( $theme , $path )
 {
 	return theme_resource( $theme , 'css/' . $path );
 }

 function theme_font( $theme , $filename )
 {
	return theme_resource($theme , 'font/' .  $filename );
 }

 function theme_resource( $theme , $path )
 {
 	return ASSETS_BASE . 'theme/' . $theme['info']['dir'] . '/' . $path;
 }


 function open_add_cart_form($product)
 {
 	echo '<form method="POST" action="/cart/add" name="add_to_cart" >
 	      <input type="hidden" name="product_id" value="' . $product['product_id'] . '" />
 	      ';
 }

 function close_add_cart_form( )
 {
 	echo '</form>';
 }

 
