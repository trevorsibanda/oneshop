<?php
/**
 * Products cron job.
 *
 * 
 * 
 *
 * @author      Trevor Sibanda
 * @date        10 October 2015
 * @package     Controllers/cron/Orders
 */

class Products extends OS_Controller
{
    public function __construct()
    {
        parent::__construct();
       
        
    }
    
    
    public function index()
    {
        $this->autorun();
    }
    
    /**
     * Do not run more than once a day
     */
    public function autorun()
    {
       
    }
    
    /**
     * Send alert emails to shop admin letting them know of low stock on products.
     *
     * Run once a week
     */
    public function stock_check( )
    {
        
    }
    
    /**
     * Check for products containing broken images and fix them.
     *
     * Run once a week
     */
    public function check_broken_images( )
    {
        
    }
    
    /**
     * Check for products containing broken images and fix them.
     *
     * Run once a week
     */
    public function check_broken_files()
    {
        
    }
    
    /**
     * Suggestions for changes to product.
     *
     * Run once a fortnight
     */
    public function suggestions( )
    {
        
    }
    
}
    
