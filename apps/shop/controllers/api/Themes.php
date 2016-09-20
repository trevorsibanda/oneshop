<?php
/**
 * OneSHOP Admin Theme API
 *
 * Shop themes
 *
 * @author 		Trevor Sibanda	<trevorsibb@gmail.com>
 * @packages	Controllers/Admin_API
 *
 * @todo 	Check user permissions
 */

require_once( APPPATH . 'core/OS_AdminController.php');


class Themes extends OS_AdminController
{

	/** Constructor **/
	public function __construct()
 	{
 		parent::__construct();
 		$this->set_app_mode('json');
 		$this->data = $this->load_shop();
 		$this->shop->logger->shop_id( $this->data['shop']['shop_id'] );
 		$this->shop->logger->user_id( isset($_SESSION['user']['user_id'])? $_SESSION['user']['user_id'] : Null );
 		//set ui theme shop id
 		$this->ui->theme->set_shop_id( $this->data['shop']['shop_id'] );
		$this->load->model('blogpost'); 		
 	}

 	/** Get current theme settings  **/
 	public function current( )
 	{
 		$theme = $this->data['theme'];
 		$this->render('' , $theme );
 	}

 	/** Update the current theme.  **/
 	public function update( $type = 'page' )
 	{

 	}

 	/** Apply the specified theme **/
 	public function apply( $theme_name )
 	{	
 		if(empty($theme_name) or is_null($theme_name))
 			$this->forbidden();

 		$json = $this->read_input();
 		$page = isset($json['page']) ? $json['page'] : 'shop';
 		if( ! in_array($page, array('shop','blog')))
 			$page = 'shop';

 		//remve chance of directory traversal
 		$theme_name = str_replace(array(' ' , '.' , '/' , '\\'), '', $theme_name);
 		$theme = array();
 		if( empty($theme_name))
 		{
 			$theme['status'] = 'Theme name cannot be empty';
 			$theme['error'] = 'ui';
 		}
 		else
	 	{
	 		

	 		if( ! $this->ui->theme->load_theme( $theme_name  ) )
	 		{
	 			$this->error('The specified theme does not exist');
	 		}else
	 		{
	 			//apply the theme
	 			$this->ui->theme->apply_theme( $theme_name , $page );
	 			$theme = $this->ui->theme->theme_data();
	 			//reload theme and clear cache
	 			$data = array();
	 			$this->_load_theme( $this->data['shop'] , $this->data['settings']['theme'] , $data , True );
	 		}
	 	}
 		$this->render('theme' , $theme );
 	}

 	/** Get all themes in Oneshop **/
 	public function all()
 	{
 		
 		$this->ui->theme->set_shop_id( $this->data['shop']['shop_id'] );
 		$themenames = $this->ui->theme->all_themes();
 		$themes = array();
 		foreach( $themenames as $name )
 		{
 			$theme = $this->ui->theme->get_theme_info( $name );
 			$themes[$name] = $theme;	
 		}
 		$this->render('themes' , $themes );
 	}

 	//get current theme custom css
 	public function current_css( )
 	{
 		$this->ui->load_css_engine();
 		$_template_css = "/** ". OS_SITE_NAME . " Custom CSS **/";
 		$fpath = $this->ui->css->dynamic_css_file( $this->data['shop'] , $this->data['theme']['info']['dir'] );
 		$data = file_get_contents($fpath);
 		if( $data === False or is_null($data) )
 		{
 			//add css file

 			$fpath = $this->ui->css->save_dynamic_css( $this->data['shop'] , $this->data['theme']['info']['dir'] , $_template_css );
 			echo file_get_contents( $fpath );
 			return;
 		}
 		echo $data;
 	}

 	//save new custom css
 	public function save_custom_css()
 	{
 		$this->ui->load_css_engine();
 		$status = array('status'=>'ok');
 		$json = $this->read_input();
 		if( empty($json) or ! isset($json['css'])  or ! is_string($json['css']))
 			$this->error('Invalid css code passed to server. Please try again');
 		//save custom css
 		$this->shop->logger->action('Saved new custom css for theme ' . $this->data['theme']['info']['name'] );
 		$fpath = $this->ui->css->save_dynamic_css( $this->data['shop'] , $this->data['theme']['info']['dir'] , $json['css'] );
 			
 		$this->render('status' , $status);
 	}

 	//update theme wording
 	public function update_theme_wording( )
 	{
 		$status = array('status'=>'ok');
 		$json = $this->read_input();
 		$this->render('status' , $status);
 	}

 	/** Change the current shop logo **/
 	public function change_shop_logo( )
 	{
 		//@todo check user permissions

 		$json = $this->read_input();

 		if( $json == False || empty($json) || (! isset($json['image_id'])))
 			$this->forbidden();

 		//check image
 		$image = $this->shop->image->get($json['image_id']);
 		if( empty($json) or ($image['shop_id']  != $this->data['shop']['shop_id'] ))
 		{
 			$this->error('Image does not exist or is invalid');
 		};

 		//update settings
 		unset($this->data['shop']['logo']);
 		$to_update = array('logo_id' => $image['image_id']);
        
 		$this->shop->update( $this->data['shop']['shop_id'] , $to_update );

 		$this->shop->logger->action('Changed shop logo  ');


 		$this->data['shop']['logo'] = $image;
 		$this->render('shop' , $this->data['shop']);
 	}
}