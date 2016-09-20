<?php
/**
 * Pay4App Integration
 *
 *  @author     Trevor Sibanda  <trevorsibb@gmail.com>
 *  @date       18 June 2015
 *  @package    Pay4App
 *
 *
 *
 *
 *
 */
class Pay4App
{
  
    /** Test mode **/
    private $_test_mode = False;
    
    /** Merchant ID **/
    private $_merchant_id;
    
    /** Api Secret **/
    private $_api_secret;
    
    /** Endpoint URL **/
    private $_endpoint_url = 'https://pay4app.com/v1/';
    
    /** Success checkout url **/
    private $_checkout_url;
    
    /** Transfer pending url **/
    private $_transfer_pending_url;
    
    /** Json return format Object or Array **/
    private $_json_format = 'array'; //'object';
    
    const ERR_REQUEST         = 'err_request';
    const ERR_AUTH            = 'err_auth';
    const ERR_ORDER_NOT_FOUND = 'order_not_found';
    const ERR_SERVER          = 'err_server';
    const SUCCESS             = '1';
    const ERROR               = '2';
    const FATAL_ERROR         = '0'; 
    
    
    /**
     *  Constructor
     * 
     * @param   Array   $   Pay4app config. if not passed. default used
     * 
     */ 
    public function __construct($config = array() )
    {
        
        $this->_checkout_url = isset(  $config['checkout_url'] ) ? $config['checkout_url'] : Null;
        $this->_transfer_pending_url = isset(  $config['transfer_pending_url']) ? $config['transfer_pending_url'] : Null;
        $this->_api_secret = isset( $config['api_secret'] ) ? $config['api_secret'] : NUll;
        $this->_merchant_id = isset( $config['merchant_id']) ? $config['merchant_id'] : Null;
        $this->_test_mode =  isset( $config['test_mode'] ) ? $config['test_mode'] : False;
     }
    
    /**
     * API Secret key getter/setter
     * 
     * @param   String  $   API Secret key.
     *
     * @return  String  
     */ 
    public function api_secret(  $new_api_key = Null )
    {

      if( ! is_null($new_api_key))
        $this->_api_secret = $new_api_key; 
      return $this->_api_secret;  
    }
    
    /**
     * Merchant Key, getter or setter
     * 
     * @param   String  $   Merchant Key
     * 
     * @return  String
     */
     public function merchant_id(  $merchant_id = Null )
     {
       if( ! is_null($merchant_id))
        $this->_merchant_id = $merchant_id;
       return $this->_merchant_id;  
     }
   
   /**
    * Setter/getter for checkout url
    * 
    * @param    String  $ Checkout url
    * 
    * @return   String  $ Checkout url 
    */
    public function checkout_url(  $checkout_url = Null )
    {
      if( ! is_null($checkout_url))
        $this->_checkout_url = $checkout_url;
      return $this->_checkout_url;  
    }
    
    /**
     *  Setter/getter for transfer pending url
     * 
     * @param   String    $ Transfer pending url
     * 
     * @return  String
     */
     public function transfer_pending_url(  $transfer_pending_url = Null )
     {
       if( ! is_null($transfer_pending_url) )
        $this->_transfer_pending_url = $transfer_pending_url;
       return $this->_transfer_pending_url;  
     }
     
     /**
      * Set the structure to return. Array or Object
      * 
      * @param    String  $ Type array or object
      * 
      * @return   None
      */ 
     public function set_json_format(  $format )
     {
       if( ! in_array($format , array('object' , 'array')))
        $format = 'array';
       $this->_json_format = $format;
     }
     
    /**
     * Set test mode
     * 
     * Marks this a test checkout form. 
     * 
     * @param     Bool  $ Test Checkout ?
     * 
     * @return    None
     */
     public function set_test_mode(  $test_mode )
     {
       $this->_test_mode = $test_mode;
     }
     
     
     
     /**
      * Generates an array to be used for the checkout form
      * 
      * @param    String    $   Order ID
      * @param    Float     $   Amount
      * 
      * @return   Array
      */ 
     public function make_form( $order_id , $amount )
     {
      
       if( ! $this->_is_ready() )
        die('Pay4App: Make sure api_secret and merchant_id are passed ');
        $form = array();
        $form['form_action'] = 'https://pay4app.com/checkout.php';
        $form['amount'] = $amount;
        $form['orderid'] = $order_id;
        $form['merchantid'] = $this->merchant_id();
        $form['signature'] = $this->_signature( $form['orderid'] , $form['amount'] );
        if( ! is_null($this->_checkout_url) )
          $form['redirect'] = $this->checkout_url();
        if( ! is_null($this->_transfer_pending_url) )
          $form['transferpending'] = $this->transfer_pending_url();
        //test mode
        if( $this->_test_mode )
          $form['test'] = True;
        return $form;
     }
     
   /**
    * Query the  pay4app backend for the status of an order
    * 
    * @param    Int   $   Pay4App checkout ID
    * 
    * @return   Array
    */
    public function query_checkout( $checkout_id  )
    {
        $this->_is_ready() or die('Pay4App: Query checkout failed because api_secret or merchant_id nt set');
        $digest = hash('sha256' , $checkout_id . $this->merchant_id() . $this->api_secret() );
        $params = array();
        $params['checkout'] = $checkout_id;
        $params['merchant'] = $this->merchant_id();
        $params['digest'] = $digest;
        $data = $this->_query_endpoint( $params  );
        return $this->_from_json( $data  );
    }
    
    /**
     * Query the pay4app backend for the status of an order
     * 
     * @param     String    $   Order ID *Local order id*
     * 
     * @return    Array
     */
     public function query_order(  $order_id )
     {
       $this->_is_ready() or die('Pay4App: Query order failed because api_secret or merchant_id is not set');
       $digest = hash('sha256' , $order_id . $this->merchant_id() . $this->api_secret() );
       $params = array();
       $params['order'] = $order_id;
       $params['merchant'] = $this->merchant_id();
       $params['digest'] = $digest;
       $data = $this->_query_endpoint( $params );
       return $this->_from_json( $data );
     }
     
     /**
      * Verify if a request is in fact a notification from pay4app
      * 
      * @param    Array   $   Pass global $_GET variable. Not accesed directly here for security and testing
      * 
      * @return   Bool    $   True if its a notification request
      */ 
     public function is_notify_request(  $_get_  )
     {
          $this->_is_ready() or die('Pay4App: Not initialised properly !'. __LINE__);
          $expected_keys = array('merchant' , 'checkout' , 'order' , 'amount' , 'email' , 'phone' , 'timestamp' , 'digest');
          foreach( $expected_keys as $key  )
          {
            if( ! isset($_get_[ $key ]  ))
              return False; //fail
          }

          //for readability the concatenation is split over two lines   
          $digest = $_get_['merchant'].$_get_['checkout'].$_get_['order'].$_get_['amount'];
          $digest .= $_get_['email'].$_get_['phone'].$_get_['timestamp'].$this->api_secret();
          
          $digesthash = hash("sha256", $digest);
          if ($_get_['digest'] !== $digesthash)
          {
              return False;
          }

          return True;
     }
     
     /**
      * Check if its a valid transfer pending notification
      * 
      * @param    Array   $   Pass global $_GET variable. Not accesed directly here for security and testing
      * 
      * @return   Bool    
      */
      function is_transfer_pending_redirect(  $_get_  )
      {
        $this->_is_ready() or die('Pay4App:  Not  intialized properly !');
        $expected_keys = array( 'merchant' , 'order' , 'digest'  );
        foreach( $expected_keys as $key )
        {
          if( ! isset($_get_[ $key ]) )
            return False;
        }
        $expecteddigest = $_get_['merchant'].$_get_['order'].$this->api_secret();
        $expecteddigest = hash("sha256", $expecteddigest);
        if ($_get_['digest'] !== $expecteddigest){
            return False;
        }
        
        return True;
             
      }
  
     /**
     * Call at the end of a notification callback to tell pay4app what happened
     * Best you exit soon after this call
     * 
     * @param     Bool    $   Did everything go well ?
     * 
     * @return    None
     */ 
     public function close_notify( $all_well = True  )
     {
       $status = intval( $all_well  );
       echo json_encode(array("status"=> $status  )); 
     }
     
     /**
      * Generate the signature
      * 
      * @param    String  $   Order ID
      * @param    Float   $   Amount
      * 
      * @return   String  $   Sha256 hash
      */ 
       private function _signature( $order_id , $amount )
       {
         $data = $this->merchant_id() . $order_id . $amount .  $this->api_secret();
         
         return hash('sha256'  ,  $data );
       }
     
     /**
      * Check if the api is ready
      * 
      * @return   Bool
      */ 
       private function _is_ready(  )
       {
         if( is_null($this->api_secret()) or is_null($this->merchant_id())  )
            return False;
         return True;    
       }
     
       /** Convert data from json to array/object **/
       private function _from_json( $data )
       {
         $format = ( $this->_json_format == 'array'  ) ? True : False;
         return json_decode(  $data , $format );
       }
       
       /**
        * Query the endpoint providing post parameters
        * 
        * @param    Array     $ Post parameters
        * 
        * @return   String    $ Data or Null
        */ 
       private function _query_endpoint(   $post_params )
       {
          $delim = "";
          $fields_string = "";
          foreach($post_params as $key=>$value) {
            $fields_string .= $delim . $key . '=' . $value;
            $delim = "&";
          }
          
          $ch = curl_init();    
          curl_setopt($ch, CURLOPT_URL, $this->_endpoint_url );
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        
          //execute post  
          $result = curl_exec($ch);  
        
          if($result)  
          {  
            $data = $result;
            curl_close($ch);
            return $data;
          }
          else
          {
            if(  $this->_test_mode )
              die( curl_error($ch) );
            return Null;
          } 
       }
   
}

/********* End of Pay4App ************/