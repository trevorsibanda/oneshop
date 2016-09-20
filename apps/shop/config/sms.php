<?php
/**
 * Funerally Config File
 *
 *
 *
 */
 
 /**
  * Percent of SMS cost to add to cost
  *
  */
 $config['sms_percent_charge'] = 0.05; //5 percent
 
 /**
  * Amount to charge for each SMS session
  *
  */
 $config['sms_session_charge'] = 0.20;
 
 /**
  * Fallback cost per sms
  *
  * Fallback cost to charge for an SMS in case opertaor details are not available
  */
  $config['fallback_sms_cost'] = 0.05; // 5 cents
 
 /**
  * Registered SMS gateways
  *
  * List of sms gateways that are currently supported by Funerally
  *
  */
  $config['sms_gateways'] = array('nexmo' , 'debug'  , 'routesms');
 
 /**
  * Exception gateways
  *
  * Exception gateways override the gateway to use for sending to a particular country
  * The country code is provided along with the ateway to use instead
  * 
  */
  $config['exception_gateways'] = array('--' => 'routesms' );
 
 /**
  * Default SMS Gateway
  *
  * The default sms gateway
  */
  $config['default_sms_gateway'] = 'routesms';
  
  /**
   * Fallback SMS Gateway
   *
   * The fallback SMS gateway is the sms gateway to use when the selected 
   * gateway and default gateway are down
   */
   $config['fallback_sms_gateway'] = 'nexmo';


   $config['routesms_config'] = array('username' => '' ,'password' => '' ,'server_port' => 'smpp2.routesms.com:8080' , 'source_address' => '263SHOP' );

   
   /**
    * Should the sending of SMS messages be charged for ?
    *
    */
    $config['no_charge_sms'] = False;
