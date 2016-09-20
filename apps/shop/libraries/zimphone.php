<?php
/** 
 * ZimPhone Number Library.
 *
 * A helper class which can identify Zimbabwean phone numbers by network operator
 * and perform other tasks given a phone number.
 *
 * @author 		Trevor Sibanda
 * @date 		14 July 2015
 * @package 	ZimPhone
 *
 */

 class ZimPhone 
 {

 	private $_rules = array(
 		/* Econet */
 		'econet' => array('prefix' => '772,773,774,775,776,777,778,779,783,782') ,
 		'max_len' => 10 ,
 		'min_len' => 10 ) ,
 		/* Telecel */
 		'telecel' => array('prefix' => ''
 		'max_len' => 10,
 		'min_len' => 10
 		)
 		/* Netone */
 		'netone' => array( 'prefix' => '' ,
 		'max_len' => 10 , 
 		'min_len' => 10 );
 		/* Landline */
 		'landline' => array('prefix' => '' ,
 		'max_len' => 0 ,
 		'min_len' => 0)
 	);

 	private $_country_code = '263';


 	public function __construct()
 	{

 	}

 	public function is_econet( $phone_number )
 	{
 		if( strlen($phone_number) < 9 or strlen($phone_number) > 15 )
 			return False;

 		if( $phone_number[0] =='+' )
 			$phone_number = str_replace('+', '00', $phone_number );

 		$iso_code = substr($phone_number, 0 , 2 )
 	}

 	public function is_netone(  $phone_number )
 	{

 	}

 	public function is_telecel( $phone_number )
 	{

 	}

 	public function is_landline( $phone_number )
 	{

 	}

 	public function is_zim_number(  )
 	{

 	}

 	public function make_international( $phone_number )
 	{

 	}

 	public function is_international( $phone_number )
 	{

 	}



 }