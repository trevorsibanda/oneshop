<?php
/**
 * Shop Wallet.
 *
 * Each shop has a wallet which is used to keep record of the amount
 * made by a store using the OneSHop payment gateway.
 *
 * @author		Trevor Sibanda	<trevorsibb@gmail.com>
 * @module		Model/Shop/Shop_wallet
 * 
 * @date		20 Aug 2015
 */
 
 class Shop_wallet extends CI_Model
 {

 	 private $_table = 'shop_wallet';

	 public function __construct()
	 {
		 parent::__construct();
		 $this->load->database();
	 }
	 
	 
	 
	 /**
	  * Get a wallet given the shop ID
	  *
	  * @param		Int		$	Shop ID
	  *
	  * @return		Array	$	Empty object on fail
	  */
	 public function get( $shop_id )
	 {
		 $query = $this->db->where('shop_id' , $shop_id )->get( $this->_table  );
		 return $query->row_array();
	 }

	 /**
	  * Create a wallet for a shop
	  *
	  * @param 		Int 	$	Shop ID
	  * 	
	  */
	 public function create_wallet( $shop_id )
	 {
	 	$wallet = array();
	 	$wallet['shop_id'] = $shop_id;
	 	$wallet['amount'] = 0.00;
	 	$wallet['is_locked'] = False;
	 	$wallet['last_withdraw'] = '0000-00-00';
	 	$wallet['last_withdraw_amount'] = 0.00;
	 	$wallet['request_withdraw_time'] = '0000-00-00';

	 	$this->db->insert( $this->_table , $wallet );
	 	return True;
	 }
	 
	 /**
	  * Add money to the shop's wallet
	  *
	  * @param		Int		$	Shop ID
	  * @param		Float	$	Amount,Must be positive
	  *
	  * If a negative value is passed, nothing is done.
	  *
	  * @return		Bool 	$	Also returns new amount
	  */
	 public function add_money( $shop_id , $amount )
	 {
		 $wallet = $this->get( $shop_id );
		 if( empty($wallet) )
		 	return array();
		 if( $amount <= 0 )
		 	return False;
		 $wallet['amount'] += $amount;
		 $wallet['log'] = $wallet['log'] . "\nAdded money to wallet {$amount} at " . date('r') ;
		 $this->update( $wallet['shop_id'] , $wallet );
		 return $wallet['amount']; 
	 }
	 
	 /**
	  * Lock a wallet.
	  *
	  * A locked wallet means the shop can receive funds
	  * but cannot withdraw due to suspected ill activity on the account.
	  *
	  * @param		Int		$	Shop ID
	  * @param		Bool	$	Lock or unlock
	  *
	  * @return		Bool
	  */
	 public function lock_wallet( $shop_id , $do_lock = True )
	 {
		$wallet = $this->get( $shop_id );
		if( empty($wallet))
			return False;
		$wallet['is_locked'] = $do_lock;
		$wallet['log'] = $wallet['log'] . "\nChanged wallet lock status to " . ($do_lock ? 'Locked' : 'Unlocked') . " at " . date('r');
		$this->update( $wallet['shop_id'] , $wallet );
		return True;		 
	 }
	 
	 /**
	  * Check if a withdrawal request is in progress/being processed
	  *
	  * @param		Int 		$	Shop id
	  *
	  * @return		Bool
	  */
	  public function is_withdraw_active( $shop_id )
	  {
		  $wallet = $this->get( $shop_id );
		  if( empty($wallet))
		  	return False;
		  if( $wallet['request_withdraw'] )
		  {
		  	return True;
		  }
		  return False;
	  }
	 
	 /**
	  * Request to withdraw amount from a wallet.
	  * 
	  * @param		Int		$	Shop id
	  * @param		Float	$	Amount to withdraw
	  *
	  * @return		Bool	$	False on fail ( account locked or requested money is too high)
	  */
	 public function request_withdraw( $shop_id , $amount_to_withdraw )
	 {
		if( $amount_to_withdraw <= 0 )
			return False;
		$wallet = $this->get( $shop_id );
		if( empty($wallet))
			return False;
		if( $wallet['is_locked'] )
		{
			$wallet['log'] = $wallet['log'] . "\n Requested to withdraw {$amount_to_withdraw} from already locked wallet. " . date('r');
			$this->update($wallet['shop_id'] , $wallet );
			return False;
		}

		if( $wallet['request_withdraw'] )
		{
			$wallet['log'] = $wallet['log'] . "\n Requested to withdraw however another withdrawal request is pending. Requested to withdraw {$amount_to_withdraw} but {$wallet['request_withdraw_amount']} already pending. " . date('r') ;
			$this->update($wallet['shop_id'] , $wallet );
			return False;
		}
		if(  $amount_to_withdraw >= $this->calculate_commission( $wallet['amount']  ) )
		{
			$wallet['log'] = $wallet['log'] . "\n Requested to withdraw more money than in the wallet. Requested to withdraw {$amount_to_withdraw} but only {$wallet['amount']} exists. " . date('r') ;
			$this->update($wallet['shop_id'] , $wallet );
			return False;
		}

		$wallet['log'] = $wallet['log'] . "\n Requested to withdraw {$amount_to_withdraw} on  " . date('r') ;
		$wallet['request_withdraw'] = True;
		$wallet['request_withdraw_amount'] = $amount_to_withdraw;
		$wallet['request_withdraw_time'] = date('Y-m-d h:m:s');

		$this->update($wallet['shop_id'] , $wallet );
		return True;

	 }

	 public function calculate_commission( $amount )
	 {
	 	return $amount * 0.9;
	 }
	 
	 /**
	  * Cancel an attempt to withdraw money, only possible if withdraw in progress is false
	  *
	  * @param		Array	$	Wallet
	  *
	  * @return		Bool
	  */
	 public function deny_withdraw( $shop_id )
	 {
		 if( $amount_to_withdraw <= 0 )
			return False;
		$wallet = $this->get( $shop_id );
		if( empty($wallet))
			return False;

		if( $wallet['request_withdraw'] )
		{
			$wallet['amount'] += $wallet['request_withdraw_amount'];
			$wallet['request_withdraw'] =False;
			$wallet['request_withdraw_time'] = '';
			$wallet['request_withdraw_amount'] = 0.00;
			$wallet['log'] = $wallet['log'] . "\n Denied a request to withdraw {$wallet['request_withdraw_amount']} on  " . date('r') ;
			$this->update($wallet['shop_id'] , $wallet );
			return True;
		}
		return False;
	 }
	 
	 /**
	  * Alter the amount you wish to withdraw 
	  *
	  * @param		Array	$	Wallet
	  * @param		Float	$	New amount to request withdraw
	  *
	  * @return		Bool
	  */
	 public function update( $shop_id , $wallet )
	 {
	 	 unset( $wallet['shop_id'] );
	 	 unset( $wallet['last_accessed']);

		 $this->db->where('shop_id' , $shop_id )->update( $this->_table , $wallet );
	 	return True;
	 }
	 
	 /**
	  * Confirm that the withdraw request has been performed.
	  * This also removes the withdrawed amount from the wallet
	  * Only call this function when the funds have been sent to the user.
	  *
	  * @param		Array	$	Wallet
	  *
	  * @return		Bool
	  */
	 public function confirm_withdraw( $shop_id )
	 {
		
		$wallet = $this->get( $shop_id );
		if( empty($wallet))
			return False;

		if( $wallet['is_locked'] )
			return False; //must first unlock wallet

		$amount = $this->calculate_commission( $wallet['request_withdraw_amount']  );

		if( $wallet['amount'] <= $amount )
		{
			$wallet['is_locked'] = True; //suspicious behavior
			$wallet['log'] = $wallet['log'] . "\n Suspicious behavior, confirmed wallet withraw, but numbers do not add up.";
			$this->update( $wallet['shop_id'] , $wallet );
			return False;
		}
		$wallet['log'] = $wallet['log'] . "\n Confirmed withdrawal of {$amount}, amount requested was {$wallet['request_withdraw_amount']}. ";
		$wallet['amount'] -= $wallet['request_withdraw_amount'];
		$wallet['request_withdraw_amount'] = 0.00;
		$wallet['request_withdraw'] = False;
		$wallet['last_withdraw'] = date('Y-m-d');
		$wallet['last_withdraw_amount'] = $amount;

		$this->update(  $wallet['shop_id'] , $wallet );
		return True;
	 }
	 
	 
	
 };
 
 