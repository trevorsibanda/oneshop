<?php
/**
 * User security helper
 *
 *
 */


 /** does the user have a particular permission **/
 function has_permission( $perm )
 {
 	$perms =  array('manage_products' , 'manage_orders' , 'blog' , 'designer' , 'pos');
 	if( is_admin() )
 		return True;
 	
 	if( in_array($perm, $perms) )
 	{
 		return defined('USER_PERMISSION_' . strtoupper($perm) );
 	}
 	return False;
 }

 /** Is current logged in user an administrator **/
 function is_admin(  )
 {
 	return defined('USER_PERMISSION_ADMIN') ;
 }

 /** Apply global permissions given user **/
 function apply_global_perms(  $user )
 {
 	$perms =  array('admin', 'manage_products' , 'manage_orders' , 'blog' , 'designer' , 'pos');
 	
 	foreach( $perms as $perm )
 	{
 		if( $user['permission_admin'] or ( isset($user['permission_' . $perm ]) and $user['permission_' . $perm ] ) )
 		{
 			
 			define('USER_PERMISSION_' . strtoupper($perm) , 1);
 		}	
 	}

 	if( $user['is_suspended'] )
 		define('USER_ACCOUNT_SUSPENDED',1);

 }

 function user_account_suspended()
 {
 	return defined('USER_ACCOUNT_SUSPENDED');
 }