<?php

require_once('Paynow.php');

 define('PAYNOW_ID' , '1586');
 define('PAYNOW_KEY' , '4114c8cc-676f-4ab9-ae53-ce6b58e7523f');
 
 $paynow = new PayNow( array('id' => PAYNOW_ID , 'key' => PAYNOW_KEY,'result_url'=>'') );
 $paynow->set_result_url('http://www.base2theory.com/paynow.php');
 $paynow->set_return_url('http://localhost/sellingmilk.aspx?action=return&order_id=2');
 
 $reference = 32;// 'Order Number: #Mil140'; //get reference from database. must be unique 
 
 $transaction = $paynow->make_transaction($reference , 1.00 , 'Some Info: milk tastes good.' );
 $transaction['authemail'] = 'fchiwunda@gmail.com';

$action = '';

if( $action == 'poll' )
{

$response = $paynow->poll_transaction("https://www.paynow.co.zw/Interface/CheckPayment/?guid=a81807b8-f0fb-4ee8-91e1-b86a7f5d310a");

if( $paynow->is_valid_poll_response( $response ) )
{
	print("This is a valid poll response from paynow");
}

print_r($response);
return;

}
else
{

$response = $paynow->init_transaction($transaction);
 
 if( isset($response['error']) ){  }
 
 if( $response['status'] != $paynow::ps_ok )
 {
      die('hashes mismatch');         
 }

if( $paynow->is_valid_init_response( $response ) )
{
	print("\n\nis valid init request worked :)");
}

echo "\n\n\n\n";
print_r( $response );
 //redirect user to paynow pay url if everything ok
// echo('Location: ' . $response['browserurl']); 
}
