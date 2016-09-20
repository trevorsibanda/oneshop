<?php
/**
 * Email Template Engine.
 *
 * Loads email templates for a particular theme.
 * For example if a shop is using the comma_fashion theme
 * 
 * Inside the comma_fashion theme directory there should be
 * a subdirectory named email containing email.php
 *
 * The email template can contain php code, although it should be limited 
 * *Avoid function calls in the email template*
 *
 * Each template has the following params passed* 
 * header   	-> Header message ( For example on signup it might be "Welcome to OneShop") 
 * message 		-> Message to display (Your account has been created)
 * image_url 	-> Optional Image to show in the theme. (No required)			 
 * action_link  -> Required action link, call to action (Auto generated . i.e http://demo.oneshop.co.zw/callback/verify_email/2432432432)
 *
 * All templates have access to the following arrays.
 * shop 	-> Contains basic shop info
 * order  	-> *if available* All order info
 * products -> Similar products the customer might be interested in
 *
 * NB: When writing email templates all the css should be included in the template
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @date 		June 10 2015
 * @package 	Models/System/Email_template
 *
 */

class Email_template extends CI_model
{


	public function __construct()
	{
		parent::__construct();
		$tis->load->helper('directory');
	}

	

	/**
	 * Is the engine ready to parse the template.
	 *
	 * @return 		Bool
	 */
	public function is_ready( )
	{

	}

	/**
	 * Get all theme parameters
	 *
	 * @return 		Array 		$	Parameters	
	 */
	public function get_params(  )
	{

	}

	/**
	 * Set theme parameters
	 *
	 * @param 		Array 		$	Map array(0=>('key' => 'value')...
	 *
	 * @return 		None
	 */
	public function set_params( $array )
	{

	}

	/**
	 * Set a theme parameter 
	 *
	 * For example each template requires a header message 
	 * To set for a success message you might say set_param('header' ,'Success') 
	 *
	 * @param 		String 		$	Key
	 * @param 		String 		$	Value
	 *
	 * @return 		None
	 */
	public function set_param( $key , $value )
	{

	}

	/**
	 * Run the rendering process to generate the email message.
	 *
	 * 
	 * @return 		Bool 	$	Returns False when not ready 	
	 */
	public function render( )
	{

	}

	/**
	 * Get the stored output of rendering a theme
	 *
	 * @return 		String 		$	Returns Null if the class is not ready
	 *
	 */
	public function get_output()
	{

	}

	/**
	 * Reload the class.
	 * 
	 * Use when you want to load a different theme
	 *
	 */
	public function reload()
	{

	}

	/**
	 * Check a theme to see if it has valid
	 * email templates
	 *
	 * @param 		String 		$	Theme directory
	 *
	 * @return 		Bool
	 */
	private function _check_theme( $theme_directory )
	{

	}

	/**
	 * Load a theme template 
	 *
	 * Valid types are [info, warning, success , danger ]
	 *
	 * @param 		String 		$	Template type.
	 *
	 * @return 		Bool
	 */
	private function _load_template( $name )
	{

	}

	/**
	 * Runs the parsing process
	 *
	 * @return 		Bool 	$	False when not ready
	 */
	private function _run()
	{

	}

}

