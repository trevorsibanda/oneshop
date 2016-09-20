<?php
/**
 * Css Engine
 *
 * @author 	Trevor Sibanda
 * @date 	12 October 2015
 */
class Css_Engine extends CI_Model
{
    
    private $_css_save_path = ASSETS_FILES . 'theme_css/';
    
    /**
     * Sanitize css code
     *
     * @param 		String 	$	Css code
     *
     * @return 		String 	$	Sanitized css
     */	
    public function sanitize_css( $css_code )
    {
        $sanitized = strip_tags( $css_code );
        return str_replace(array('javascript:','expression:') , '',$sanitized );
    }
    
    /**
     * Compress css code
     *
     * @param 		String 	$	Css code
     *
     * @return 		String 	$	Compressed css
     */
    public function compress_css( $css_code )
    {
        //@todo implement
        return $css_code;
    }
    
    /**
     * Save dynamic css to file on server.
     * The css is first sanitized and compressed before saving.
     *
     * @param 		Array 	$	Shop
     * @param 		String 	$	Theme directory
     * @param 		String 	$	Css Code to save
     *
     * @return 		String 	$	File path.
     */
    public function save_dynamic_css( $shop , $theme_dir , $css_code )
    {
        $css_code = $css_code . "\n\n\n /** Last saved at " . date('r') . " **/ \n";
        $fpath = $this->_css_save_path . 'theme-' . $theme_dir . $shop['shop_id'] . '.css';
        $fh = fopen( $fpath , 'w');
        fwrite( $fh ,  $this->compress_css( $this->sanitize_css( $css_code ) ) );
        fclose($fh);
        chmod( $fpath , 444 );
        return $fpath;
    }
    
    /**
     * Get url for dynamic css.
     *
     * @param 		Array 	$	Shop
     * @param 		String 	$	Theme directory
     *
     * @return 		String 	$	Url
     */
    public function dynamic_css_url( $shop , $theme_dir )
    {
        return ASSETS_BASE . 'files/theme_css/' . 'theme-' . $theme_dir . $shop['shop_id'] . '.css';
    }
    
    public function dynamic_css_file( $shop , $theme_dir )
    {
        return $this->_css_save_path . 'theme-' . $theme_dir . $shop['shop_id'] . '.css';
    }
    
    
        
}
