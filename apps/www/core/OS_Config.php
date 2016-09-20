<?php

class OS_Config extends CI_Config
{
	public function __construct()
	{
		parent::__construct();
		
		array_push(  $this->_config_paths , OS_SHARED_PATH );
	}
}	
