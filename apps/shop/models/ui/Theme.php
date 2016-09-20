<?php
/**
 * User Interface Theme 
 *
 * Loads a shop theme and parses it returning the available options 
 * and values.
 *
 * A theme has the following components
 * -> Global Options [global]
 * -> Page [page]
 * -> Vocabulary [vocabulary]
 * -> System Settings [system]
 * -> Info            [info]
 * 
 *
 * Global settings are theme-wide settings, which may vary from background image
 * to text to theme color
 * *All types are valid*
 *
 * Page settings are settings which apply for a particluar page e.g (landing, product, browse).
 * They override global settings
 * An example would be a background image for a page
 * *All types are valid*
 * 
 * Vocabulary settings are to customize the site wording, for example one might want to replace the words
 * "Out of Stock" with the words "Available in store only". For words undefined the default vocabulary is used
 * *All types are text*
 *
 * Info is the theme info including name, description, folder_name...etc
 * *All types are text and cannot be updated *
 *
 * An entry is generally of the type:
 * {
 *		name: 'Shop Logo' , //display name
 *		type: 'shop_image' , //type
 * 		value: '', //value
 *		default: '', //default value
 *		description:  "The shop's logo image" //description 	
 * }
 *
 * All Valid types are [ text , shop_image , product_image , product , product_category , blog_page , shop_page ]
 * 
 *
 *General layout (in JSON)
 *
 *{
 *	global: 	{
 *					logo: Option
 *
 *				},
 *	page: 		{
 *					landing:	{
 *									slideshow: {},
 *									text1: Option
 *
 *								},
 *
 *				}	
 *	system:		{
 *					pages: ''
 *				}	
 *	info:       {
 *					name: 'My theme',
 * 					description: '..............'
 *				}							
 *}
 *
 * NB:			Most of functions to access theme data are defined in the helper
 *
 * @author 		Trevor Sibanda<trevorsibb@gmail.com>
 * @date 		4 June 2015 		
 * @package 	Models/ui/Theme
 *
 * @see 		Helpers/theme_helper
 */


class Theme extends CI_Model
{
	/** Shop id **/
	private $_shop_id;

	/** Theme data **/
	private $_theme_data;

	/** Theme options **/
	private $_theme_options;

	private $_is_loaded;

	private $_is_parsed;


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('file');
	}

	public function is_ready()
	{
		return ( $this->_is_loaded and $this->_is_parsed );
	}

	/**
	 * Set the shop id
	 *
	 * @param 		Int 	$	Shop ID
	 * 
	 * @return 		None
	 */
	public function set_shop_id( $shop_id )
	{
		$this->_shop_id = $shop_id;
	}

	/**
	 * Load a theme using its name
	 *
	 * @param 		String 		$	Theme Name e.g comma_fashion
	 *
	 * @return 		Bool 		$	False on fail
	 */
	public function load_theme( $theme_name )
	{
			//try to load theme schema
			$theme_file = APPPATH . 'views/theme/' . $theme_name . '/theme.json';
			if(  file_exists($theme_file) )
			{
				$theme_data = read_file( $theme_file );
				if( $theme_data != False)
				{
					$theme_json = json_decode($theme_data , True);
					if(  is_array($theme_json ) )
					{
						//all checks passed
						$this->_theme_data = $theme_json;
						$this->_is_loaded = True;
						//get theme  from database
						$theme = $this->get_db_theme( $theme_name );
						if( empty($theme) )
						{
							//not available. apply this theme 
							$this->apply_theme( $theme_name );
							$theme = $this->get_db_theme($theme_name);
						}
						else
						{
							//theme available :)
							//set theme name
							$theme['name'] = $theme_name;
							$this->parse( $theme );
							$this->_is_parsed = True;
						}
						return True;
					}
				}
			}
			else
			{
				$this->_is_parsed = False;
				$this->_is_loaded = False;
				$this->_theme_data = Null;
				$this->_theme_options = Null;
				return False;
			}

	}

	public function get_theme_info( $theme_name )
	{
		$this->load->helper('directory');
		//try to load theme schema
		$theme_file = OS_SHARED_PATH . 'views/theme/' . $theme_name . '/theme.json';
		$theme_data = read_file( $theme_file );
		$json = json_decode($theme_data , True );
		$theme = array();
		$theme['info'] = $json['info'];
		$theme['screenshots'] = array();


		$dir = ASSETS_THEME_FILES . $theme_name . '/screenshots/';
		$dirs = directory_map( $dir , 1 );
		if ( $dirs == False )
			return $theme;
		foreach( $dirs as $key=>$file)
		{
			if( ! is_array($file) ) //not a directory
			{
				array_push($theme['screenshots'], $file );
			}
		}
		return $theme;				
	}

	/**
	 * Parse the theme options against the theme template file.
	 *
	 * To avoid broken themes due to mising parameters
	 *
	 * @param 	Array 		$	Theme Options
	 *
	 * @return Array
	 */
	public function parse( $theme_options )
	{
		if( ! $this->_is_loaded )
			return false;
		//theme data must have page, global,info,vocabulary
		foreach( array('page' , 'global' , 'info' , 'vocabulary') as $key )
			if( ! isset($this->_theme_options[$key]) )
				$this->_theme_options[$key] = array();

		foreach( $this->_theme_data as $key=>$category )
		{
			switch ($key) {
				//page
				case 'page':
					{
						foreach(  $category as $page_name => $page_attributes )
						{
							foreach( $page_attributes as $key=>$entry )
							{

								if( ! isset($this->_theme_options['page'][$page_name][$key] ))
								{
									if( ! isset($entry['value']))
										$entry['value'] = $entry['default'];
									$this->_theme_options['page'][$page_name][$key] = $entry;
								}

							}
						}
					}
				break;		
				default:
					{
						if( is_string($category) )
						{
							if( ! isset($this->_theme_options[$key]))
							{
								$this->_theme_options[$key] = $category;
							} 
						}
						foreach($category as $name=>$value)
						{
							if( ! isset($this->_theme_options[$key][$name]))
							{
								$this->_theme_options[$key][ $name ] = $value;
							}
						}
					}
				break;
			}
		}
	}


	/**
	 * Retrieve theme properties from database
	 *
	 * @param 		String 		$	Theme Name 
	 *
	 * @param 		Array 		$	Empty on fail
	 */
	public function get_db_theme( $theme_name )
	{
		$query = $this->db->get_where('shop_settings_theme' , array('theme' => $theme_name));
		return $query->row_array();
	}

	/**
	 * Save a theme.
	 *
	 * This saves the theme settings into the database.
	 *
	 *
	 *
	 */
	public function save_theme( $is_active = False )
	{
		if( ! $this->is_ready())
			return False;
		$data = Array();
		$data['shop_id'] = $this->_shop_id;
		$data['theme'] = $this->_theme_name;
		$data['settings_json'] = $this->_theme_options;
		$data['is_active'] = $is_active;
		$this->db->update('shop_settings_theme' , $data);
		return True;
	}

	/**
	 * Get all themes in the app
	 *
	 *
	 *
	 */
	public function all_themes(  )
	{
		$this->load->helper('directory');
		$themes = array();
		$themes_directory = OS_SHARED_PATH . 'views/theme/';
		$dirs = directory_map( $themes_directory , 2 );
		if ( $dirs == False )
			return array();
		foreach( $dirs as $key=>$file)
		{
			if( is_array($file) ) //directory
			{
				foreach( $file as $f )
				{
					if( $f == 'theme.json')
						array_push($themes, substr($key ,  0 , strlen($key)-1) ); //remove trailling slash
				}
			}
		}
		return $themes; 
	}	

	/**
	 * Get theme data
	 *
	 * Returns theme data as an associative array to be passed
	 * to each view
	 *
	 * @return 		Array 	$	Theme data
	 */
	public function theme_data()
	{
		return $this->_theme_data;
	}



	/**
	 * Get Theme options
	 *
	 * These are defined by the user.
	 *
	 * @return 		Array 	$	Theme options
	 */
	public function theme_options(  )
	{
		return $this->_theme_options;
	}


	public function update_page_option( $page_name , $option_name , $new_value )
	{
		if( ! isset($this->_theme_options['page'][$page_name][$option_name] ) )
			return False;
		$this->_theme_options['page'][$page_name][$option_name] = json_encode( $new_value );
		return True;
	}

	public function update_system_option( $option_name , $new_value)
	{
		if( ! isset($this->_theme_options['system'][$option_name] ) )
			return False;
		$this->_theme_options['system'][$option_name] = json_encode( $new_value );
		return True;
	}

	public function update_vocabulary_option( $option_name , $new_value )
	{
		if( ! isset($this->_theme_options['vocabulary'][$option_name] ) )
			return False;
		$this->_theme_options['vocabulary'][$option_name] = json_encode( $new_value );
		return True;
	}



	/**
	 * Apply a new theme. 
	 *
	 * Copies the current theme settings to a new theme and adds the template for
	 * that new theme to database.
	 *
	 * @param 		String 		$	Theme name
	 *
	 */
	public function apply_theme( $new_theme_name , $default_page = 'shop' )
	{
		$data = array();
		$data['shop_id'] = $this->_shop_id;
		$data['theme'] = $new_theme_name;
		$data['settings_json'] = json_encode(array());
		$data['is_active'] = True;
		$data['default_page'] = in_array($default_page, array('shop','blog')) ? $default_page : 'shop';

		//set all other themes as not active
		$inactive = array('is_active' => False );
		$query = $this->db->where('shop_id' , $data['shop_id']  )->update('shop_settings_theme' , $inactive );
		

		//check if it already exists
		$conditions = array('shop_id' => $data['shop_id'] , 'theme' => $data['theme']);
		$query = $this->db->get_where('shop_settings_theme' ,  $conditions );
		if( $query->num_rows() )
		{
			
			$query = $this->db->where( $conditions )->update('shop_settings_theme' , $data );
			return;
		}
		$this->db->insert('shop_settings_theme' , $data );

		return True; //$this->db->insert_id();
	}



}


