<?php
/**
 * Shop Helpers
 *
 * @author		Trevor Sibanda <trevorsibb@gmail.com>
 *
 * @date 		6 May 2015
 */
 



/**
 * Simple chack to see if passed array is a shop
 *
 * @param		Array	$	Shop
 *
 * @return		Bool
 */
function is_shop_valid( $shop )
{
	
}

/**
 * Reads an image from the file and returns the data
 *
 * @param		Array	$	Image
 *
 * @return		Byte	$	Image data
 */
function read_image( $shop_image )
{
	
}



function shop_operating_days( $type )
{
	$days = 'Monday to Sunday';
	switch( $type )
	{
		case 'everyday':
		break;
		case 'business_days':
			$days = 'Monday to Friday';
		break;
		case 'business_and_sat':
			$days = 'Monday to Saturday';
		break;
		case 'business_and_sunday':
			$days = 'Sunday to Friday. (Not open on Saturday )';
		break;
		case 'weekends':
			$days = 'Saturday and Sunday only';
		break;
	}
	return $days;
}

 /**
  * Check if a particular shop is still open, given 24hr time
  *
  * @param		Array	$	Shop
  * @param		Int		$	Time in 24 Hour notation, default is now
  *
  * @return		Bool
  */

function is_shop_open( $shop_operating_days , $open_time , $close_time )
{
	$o_time = explode( ":" , $open_time );
	$o_int = $o_time[0];
	
	$c_time = explode( ":" , $close_time );
	$c_int = $c_time[0];
	
	$hour = strftime('HH');
	
	
	if( $o_int < $hour || $hour >= $c_int  )
		return False;
	
	return True;
}

/************************************************* Product Categories ****************************************************************/

/**
 * Get product children,
 *
 * @param		Array		$	Product Categories
 * @param		Array		$	Category to get children for
 *
 * @return		Array		$	Empty on fail
 */
 function get_category_children( $product_categories , $category )
 {
	$children = array(); 
	if( $category['is_menu'] == False)
		return array();
	foreach( $product_categories as $category_tmp )
	{
		if(  $category['category_id'] == $category_tmp['parent_id'] and  $category_tmp['is_menu'] == False )
		{
			array_push( $children , $category_tmp );
		}
	}
	return $children;
 }
 
/**
 * Get menu categories
 *
 * @param		Array		$	Product categories
 *
 * @return		Array		$	Empty on fail
 */ 
 function get_category_menus(  $product_categories )
 {
	 $menus = array();
	 foreach( $product_categories as $cat)
	 {
		 if( $cat['is_menu'] )
			 array_push( $menus , $cat);
	 }
	 return $menus;
 }
 
 
 function get_product_price_ranges( $products , $range = 5)
 {
	 $max = 0;
	 foreach( $products as $product)
	 {
		if( $product['price'] > $max ) 
			$max = $product['price']; 
	 }
	 $step = $max/$range;
	 $ranges = array();
	 for($x = 0; $x < $max ; $x += $step)
	 {
		 $r = array('low' => $x ,'high' => $x + $step  );
		 $count = 0;
		 foreach( $products as $product )
		 {
			 if( $product['price'] >= $r['low'] and $product['price'] <= $r['high']  )
				 $count++;
		 }
		 $r['count'] = $count; 
		 array_push( $ranges , $r );
	 }
	 return $ranges;
 }
 
 function get_sort_filters()
 {
	 return array(
	 array('name' => 'Popularity' , 'value' => 'popularity' ) ,
	 array('name' => 'Name A-Z' , 'value' => 'name_a-z' ) ,
	 array('name' => 'Name Z-A' , 'value' => 'name_z-a' ) ,
	 array('name' => 'Price High-Low' , 'value' => 'price_high-to-low' ) ,
	 array( 'name' => 'Price Low-High' , 'value' => 'price_low-to-high' ) 
	 
	 );
 }