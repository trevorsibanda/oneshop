<?php
/**
 * Cron security.
 *
 * @author      Trevor Sibanda
 * @date        10 October 2015
 * @package     Models/system/cron_security
 */

class Cron_security extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('oneshop/cron_security');
    }
    
    /**
     * Check if the cron request
     *
     *
     */
    public function check( $shop )
    {
        $config = $this->config->item('cron_security');
        
        $challenge = $this->input->get_post('os_challenge');
        $salt = $this->input->get_post('os_salt');
        
        $local = md5( $shop['shop_id'] . $salt . $config['secret' ] );
        if( $local !== $challenge )
            return False;
        return True;
    }
    
    
}
 
 