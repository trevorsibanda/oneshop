<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define('INTEGRETION_ID', '1586'); //integration key here
define('INTEGRATION_KEY', '4114c8cc-676f-4ab9-ae53-ce6b58e7523f'); //integration ID here
define('base_url', 'https://www.paynow.co.zw/interface/initiatetransaction');
define('ps_error', 'Error');
define('ps_ok','Ok');
define('ps_created_but_not_paid','created but not paid');
define('ps_cancelled','cancelled');
define('ps_failed','failed');
define('ps_paid','paid');
define('ps_awaiting_delivery','awaiting delivery');
define('ps_delivered','delivered');
define('ps_awaiting_redirect','awaiting redirect');

class Paynow extends CI_Controller {
public function createTransaction(){
$authemail = '';
$data = "";

$info = json_encode($data);
$list = array('steve@sans.co.zw', 'samantha@sans.co.zw', 'simukai@sans.co.zw');

$paynowValues = array(
'id' => INTEGRETION_ID,
'reference' => '25',
'amount' => 20,
'additionalinfo' => $info,
'returnurl' => base_url."paynow/transactionConfirmed",
'resulturl' => base_url."paynow/result",
'authemail' => $authemail,
'status' =>'Message'
);
//foreach ($paynowValues as $key => $value) {
// var_dump($key ." : ".$value);
// echo "<br/>";
//}
$paynowFields = $this->_createMsg($paynowValues);
//open connection
$ch = curl_init();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, base_url );
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $paynowFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXY, 'socks5://localhost:8082');
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
//execute post
try {
$result = curl_exec($ch);
echo $result;
} catch (Exception $e) {
$error = curl_error($ch);
var_dump($result);
echo "<br/>";
var_dump($error);
echo "<br/>";
var_dump($e);
//die();
}
if($result){
$msg = $this->_parseMsg($result);
//close connection
curl_close($ch);
//first check status, take appropriate action
switch ($msg["status"]) {
case ps_error:
$error = $msg["error"];
$this->_resultError($error);
exit;
break;
case ps_ok:
$this->_resultOK($msg,$paynowValues);
break;
default:
$error = "Invalid status in from Paynow, cannot continue.";
$this->_resultError($error);
break;
}
}
}
public function transactionConfirmed(){
$table_name = "paynow";
$condition = array('reference' => $this->session->userdata('transaction'));
$query = $this->app_model->get_all_where($table_name, $condition, $limit=1, $offset=null);
$order_data = $query->row_array();
$ch = curl_init();
//var_dump($order_data);
//die();
//set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $order_data['pollurl']);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, '');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//execute post
$result = curl_exec($ch);
if($result) {
//close connection
$msg = $this->_parseMsg($result);
$MerchantKey = INTEGRATION_KEY;
$validateHash = $this->_createHash($msg, $MerchantKey);
if($validateHash != $msg["hash"]){
$error = "Invalid order confirmation code from Paynow, cannot continue.";
$this->_resutError($error);
}
else
{
/***** IMPORTANT ****
On Paynow, payment status has changed, say from Awaiting Delivery to Delivered
Here is where you
1. Update your local shopping cart of Payment Status etc and do appropriate actions here, Save data to DB
2. Email, SMS Notifications to customer, merchant etc
3. Any other thing
*** END OF IMPORTANT ****/
switch ($msg["status"]) {
case 'Cancelled':
$this->_customerHasCancelled($msg);
break;
case 'Paid':
$this->_customerHasPaid($msg);
break;
case 'Created':
$this->_customerHasCreated($msg);
break;
case 'Disputed':
$this->_customerHasDisputed($msg);
break;
case 'Refunded':
$this->_customerHasRefunded($msg);
break;
case 'Delivered':
$this->_customerHasDelivered($msg);
break;
case 'Awaiting Delivery':
$this->_customerIsAwaitingDelivery($msg);
break;
}
}
}
//Thank your customer
}
public function result(){
echo "the result is here";
}
private function _resultOK($msg,$paynowValues,$integration_key=INTEGRATION_KEY){
//$this->_notifyCustomerViaEmailOrder($paynowValues['reference']);
//second, check hash
$validateHash = $this->_createHash($msg, $integration_key);
if($validateHash != $msg["hash"]){
$error = "Paynow reply hashes do not match : " . $validateHash . " - " . $msg["hash"];
}
else
{
$processPaymentOnPayNow = $msg["browserurl"];
header("Location: $processPaymentOnPayNow");
/***** IMPORTANT ****
On User has approved paying you, maybe they are awaiting delivery etc
Here is where you
1. Save the PollURL that we will ALWAYS use to VERIFY any further incoming Paynow Notifications FOR THIS PARTICULAR ORDER
1. Update your local shopping cart of Payment status etc and do appropriate actions here, Save any other relavant data to DB
2. Email, SMS Notifications to customer, merchant etc
3. Any other thing
*** END OF IMPORTANT ****/
$table_name = "paynow";
$condition = array('reference' => $this->session->userdata('transaction'));
$query = $this->app_model->get_all_where($table_name, $condition, $limit=1, $offset=null);
$data = array('reference' =>$paynowValues['reference'],
'amount' =>$paynowValues['amount'],
'additionalinfo' =>$paynowValues['additionalinfo'],
'authemail' =>$paynowValues['authemail'],
'status' =>$msg['status'],
'pollurl' =>$msg['pollurl'],
'customer' =>$this->session->userdata('user'),
'date_of_order' => get_time(),
'method' => 'PayNow',
'url' => $msg['browserurl']);
if($query->num_rows() != 1){
$this->app_model->insert($table_name,$data);
}else{
$this->app_model->update($table_name, $data, $condition);
}
$this->_notifyCustomerViaEmailOrder($this->session->userdata('transaction'));
//send email
//send sms
}
}
private function _notifyUs($transactionRefference){
$table_name = "paynow";
$condition = array('reference' => $transactionRefference);
$limit = 1;
$data = $this->app_model->get_all_where($table_name,$condition,$limit);
$row = $data->row_array();
$person = $this->app_model->get_all_where("customers",array('email'=>$row['customer']),1);
$info = $person->row_array();
$message = "
SANS Exposure.<br/>
This is a payment notification for Invoice #". $row['id'] ." sent on ". $row['date_of_payment']." to ".$info['fname']." ".$info['sname'].". <br/>
============================================<br/>
Order Details: ".$row['additionalinfo']."<br/>
Total Ammount: $".$row['amount']." <br/>
Payment Status: ".$row['status']." <br/><br/>
This order is pending delivery to customer". $row['customer'] ."
SANS Exposure Sales Team | sales@sans.co.zw <br/>
https://www.sans.co.zw/
";
$list = array('steve@sans.co.zw', 'samantha@sans.co.zw', 'simukai@sans.co.zw');
$sendGrid = new Email_Assistant();
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$list,
'PayNow Transaction Notification',
$message);
}
private function _notifyCustomerViaEmail($transactionRefference){
$table_name = "paynow";
$condition = array('reference' => $transactionRefference);
$limit = 1;
$data = $this->app_model->get_all_where($table_name,$condition,$limit);
$row = $data->row_array();
$person = $this->app_model->get_all_where("customers",array('email'=>$row['customer']),1);
$info = $person->row_array();
$message = "
<img src='http://sans.co.zw/uiux/img/logo.png' alt='SANS Exposure Logo'/><br/>
Dear ".$info['fname']." ".$info['sname']." <br/>
This is a payment receipt for Invoice #". $row['id'] ." sent on ". $row['date_of_payment'] .". <br/><br/>
============================================<br/>
Order Details: ".$row['additionalinfo']."<br/>
Total Ammount: $".$row['amount']." <br/>
Payment Status: ".$row['status']." <br/><br/>
============================================<br/>
You may review your invoice history at any time by logging in to your client area.<br/>
<a href='http://sans.co.zw/auth/'>Click here to go to your account</a><br/>
Note: This email will serve as an official receipt for this payment.<br/>
Regards.<br/><br/>
SANS Exposure Sales Team | sales@sans.co.zw <br/>
www.sans.co.zw
";
$list = array('steve@sans.co.zw', 'samantha@sans.co.zw', 'simukai@sans.co.zw');
$sendGrid = new Email_Assistant();
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$row['customer'],
'Payment Receipt',
$message);
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$list,
'Payment Receipt',
$message);
}
private function _notifyCustomerViaEmailCancelled($transactionRefference){
$table_name = "paynow";
$condition = array('reference' => $transactionRefference);
$limit = 1;
$data = $this->app_model->get_all_where($table_name,$condition,$limit);
$row = $data->row_array();
$person = $this->app_model->get_all_where("customers",array('email'=>$row['customer']),1);
$info = $person->row_array();
$message = "
<img src='http://sans.co.zw/uiux/img/logo.png' alt='SANS Exposure Logo'/><br/>
Dear ".$info['fname']." ".$info['sname']." <br/>
This is a notification that Invoice #". $row['id'] ." sent on ". $row['date_of_payment'] ." was Cancelled. <br/><br/>
============================================<br/>
Order Details: ".$row['additionalinfo']."<br/>
Total Ammount: $".$row['amount']." <br/>
Payment Status: ".$row['status']." <br/>
============================================<br/>
You may review your invoice history at any time by logging in to your client area.<br/>
<a href='http://sans.co.zw/auth/'>Click here to go to your account</a><br/>
Note: This email will serve as an official record for this transaction.<br/>
Regards.<br/><br/>
SANS Exposure Sales Team | sales@sans.co.zw <br/>
www.sans.co.zw
";
$list = array('steve@sans.co.zw', 'samantha@sans.co.zw', 'simukai@sans.co.zw');
$sendGrid = new Email_Assistant();
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$row['customer'],
'Payment Cancelled',
$message);
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$list,
'Payment Cancelled',
$message);
}
private function _notifyCustomerViaEmailOrder($transactionRefference){
$table_name = "paynow";
$condition = array('reference'=>$transactionRefference);
$limit = 1;
$data = $this->app_model->get_all_where($table_name,$condition,$limit);
$row = $data->row_array();
$person = $this->app_model->get_all_where("customers",array('email'=>$row['customer']),1);
$info = $person->row_array();
$message = "
<img src='http://sans.co.zw/uiux/img/logo.png' alt='SANS Exposure Logo'/><br/>
Dear ".$info['fname']." ".$info['sname']." <br/>
This is to notify that Invoice #". $row['id'] ." has been created on ". $row['date_of_order'] ." and is awaiting payment. <br/>
============================================<br/>
Order Details: ".$row['additionalinfo']."<br/>
Total Ammount: $".$row['amount']." <br/>
Payment Status: Pending <br/>
============================================<br/>
You may review this invoice at any time by logging in to your client area to complete payment.<br/>
<a href='http://sans.co.zw/auth/'>Click here to go to your account</a><br/>
Note: This email will serve as an official invoice for this order.<br/>
Regards.<br/><br/>
SANS Exposure Sales Team | sales@sans.co.zw <br/>
www.sans.co.zw
";
$list = array('steve@sans.co.zw', 'samantha@sans.co.zw', 'simukai@sans.co.zw');
$sendGrid = new Email_Assistant();
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$row['customer'],
'Invoice Created',
$message);
$sendGrid->send_email('sales@sans.co.zw',
'Sans Exposure Sales Team',
$list,
'Invoice Created',
$message);
}
private function _resultError($msg){
var_dump($this->session->all_userdata());
echo "<br/>";
var_dump($msg);
//die();
}
private function _parseMsg($msg) {
//echo $this->session->userdata('transaction');
//var_dump($this->session->all_userdata());
//echo "<br/>";
//var_dump($msg);
//die();
$parts = explode("&",$msg);
$result = array();
foreach($parts as $i => $value) {
$bits = explode("=", $value, 2);
$result[$bits[0]] = urldecode($bits[1]);
}
return $result;
}
private function _createHash($values, $MerchantKey){
$string = "";
foreach($values as $key=>$value) {
if( strtoupper($key) != "HASH" ){
$string .= $value;
}
}
$string .= $MerchantKey;
$hash = hash("sha512", $string);
return strtoupper($hash);
}
private function _urlIfy($fields) {
$delim = "";
$fields_string = "";
foreach($fields as $key=>$value) {
$fields_string .= $delim . $key . '=' . $value;
$delim = "&";
}
return $fields_string;
}
private function _createMsg($values, $MerchantKey=INTEGRATION_KEY){
$fields = array();
foreach($values as $key=>$value) {
$fields[$key] = urlencode($value);
}
$fields["hash"] = urlencode($this->_createHash($values, $MerchantKey));
$fields_string = $this->_urlIfy($fields);
return $fields_string;
}
/*-------------------------------------------------*/
// Customer Order Status Methods
/*--------------------------------------------------*/
private function _orderStatusCache($msg){
$table_name = "paynow";
$condition = array('reference' => $msg["reference"]);
$data = array(
'status' =>$msg["status"],
'pollurl' => $msg["pollurl"],
'paynowreference' => $msg["paynowreference"],
'amount' => $msg["amount"],
'date_of_payment' => get_time()
);
$this->app_model->update($table_name, $data, $condition);
//$this->_notifyCustomerViaEmail($this->session->userdata('transaction'));
//$this->cart->destroy();
//--$this->session->userdata('transaction') = "";
//$this->session->unset_userdata('transaction');
//$this->load->view('paynow/thankyou');
}
private function _customerHasCancelled($msg){
$this->_orderStatusCache($msg);
$this->_notifyCustomerViaEmailCancelled($this->session->userdata('transaction'));
$this->cart->destroy();
$this->session->unset_userdata('transaction');
$this->session->set_userdata('paynow_transaction_message',"Your Order has been succesffully CANCELLED, thank you for shopping with SANS Exposure. You may place another order at any time or continue shopping with us.");
redirect('customers');
}
private function _customerHasPaid($msg){
$this->_orderStatusCache($msg);
$this->_notifyCustomerViaEmail($this->session->userdata('transaction'));
$this->_notifyUs($this->session->userdata('transaction'));
$this->cart->destroy();
$this->session->unset_userdata('transaction');
$this->session->set_userdata('paynow_transaction_message',"Your Order has been succesffully PAID, thank you for shopping with SANS Exposure. You may place another order at any time or continue shopping with us.");
redirect('customers');
}
private function _customerHasCreated($msg){
}
private function _customerHasDisputed($msg){
}
private function _customerHasRefunded($msg){
}
private function _customerHasDelivered($msg){
}
private function _customerIsAwaitingDelivery($msg){
}
}

