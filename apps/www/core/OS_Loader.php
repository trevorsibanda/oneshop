<?php

class OS_Loader extends CI_Loader
{

	
	public function __construct()
	{
		parent::__construct();	
		
		
		
		//add routes to shared models, libraries and 
		array_push(  $this->_ci_view_paths , OS_SHARED_PATH );
		
		array_push( $this->_ci_library_paths , OS_SHARED_LIBRARIES_PATH  );
		
		array_push( $this->_ci_model_paths , OS_SHARED_PATH );
		
		array_push( $this->_ci_helper_paths , OS_SHARED_PATH );
	}

	// --------------------------------------------------------------------

	/**
	 * Model Loader
	 *
	 * Loads and instantiates models.
	 * Instead of 'tying' models to $CI, the models are tied to the particular
	 * object only. Allowing for a much neater hierachy.
	 *
	 * NB: Take care to not load the same model several times over. Only use this for 
	 * a certain hierachy structure
	 *
	 * E.g ( In model )
	 * $this->scope_model( $this , 'shop/Shop_Image' , 'image' )
	 * Produces $this->shop_image-> and $this->shop->image->
	 *
	 * @param	string	$model		Model name
	 * @param	string	$name		Object name to assign to
	 * 
	 *
	 * @return	object
	 */
	public function scope_model(  &$_this , $model , $name , $db_conn = False )
	{
		$global_name = explode('/', $model );
		
		$global_name = strtolower( $global_name[1] );
		$this->model($model ,  $global_name , $db_conn );
		$_this->$name = get_instance()->$global_name;
		return $_this;
	}

	public function theme( $theme , $file , $data = array() )
	{
		$this->load->view('theme/' . $theme['info']['dir'] . '/' . $file , $data );
	}

	public function sys_theme(  $file , $data = array() )
	{
		$this->load->view('system/theme/' . $file , $data );
	}

	
	
}
