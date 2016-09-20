<?php
/**
 * Oneshop advertising engine.
 *
 * 
 *
 */

 /** Available ad networks **/ 
 $config['ad_networks'] = array('adsense'=>'Google Adsense');
 
 /** Active ad networks **/
 $config['active_ad_networks'] = array('adsense'); 

 /** Google adsense ad details **/
 $config['adsense_ad_network'] = array( 'name' => 'Google Adsense' ,
 										'init_html' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>',//initiliaztion code, added in head
 										'close_html' =>'<script>
[].forEach.call(document.querySelectorAll(".adsbygoogle"), function(){
    (adsbygoogle = window.adsbygoogle || []).push({});
});
</script>',//close html added at end of page
 										'ad_units' => array
 															( 'banner' =>
 																array('728x90','300x250','336x280','300x600','160x600','120x600','250x250','200x200'),//banner ads
 															   'popup' => array() ,//no poup ads
 															   'popunder'=>array(),//no pop unders
 															), //supported ad units 
 										'max_units' => 5//recommended max number of ad units
 										);
 
/** Maximum ad units per page **/
$config['ad_engine_max_units'] = 5;

															
