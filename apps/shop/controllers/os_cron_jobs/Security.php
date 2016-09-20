<?php

class Security extends OS_Controller
{
	 public function __construct()
    {
        ignore_user_abort(1);
        parent::__construct();
        
        $this->data = $this->load_shop();
        $this->load->model('payment');

    }

    /**
     *
     *
     * Run once daily
     */
    public function check_account_limits( )
    {

    }

    /**
     *
     *
     *Run once a week
     */
    public function warn_account_expiration( )
    {

    }

    /**
     * Run once a month
     *
     */
    public function inspire_account_subscription(  )
    {

    }

    

}