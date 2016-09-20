<?php
/**
 * Payments.
 *
 * Handles all shop related payments. 
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @package 	Models/Payment
 * @date 		10 July 2015
 * 
 */

class Payment extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		//$this->load->config('payments');
		$this->load->scope_model( $this , 'payment/transaction' , 'transaction');
	}

	public function load_gateway( )
	{
		$this->load->scope_model( $this , 'payment/payment_gateway' , 'gateway');
	}




}