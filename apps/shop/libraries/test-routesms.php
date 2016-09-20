<?php
include('Routesms.php');

$config = array('username' => 'francis10' ,'password' => 'k8f5m3a7' ,'server_port' => 'smsplus1.routesms.com:8080' );
$rsms = new RouteSms($config);

$rsms->source_address('263SHOP');
$targets = array( '+263783102754');
foreach( $targets as $t )
{
$msg = "Follow http://myshop.263shop.co.zw/continue/81 to continue your order. An email with more info was also sent to you.
This message is intended for Trevor Sibanda if this is not you, ignore it ";

$r = $rsms->send_unicode( $t , $msg );

var_dump($r);
}

