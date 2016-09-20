<?php

require_once('./cron_config.php');

define('CRON_JOB' , '10-minute-job');

cron_start_session();

if(  cron_session_locked() )
{
	cron_log('The cron session is already running. Please try again later');
}

$list_of_sites = cron_get_site_list( );

if( ! is_array( $list_of_sites ) )
{
	cron_error('Failed to get list of sites. No use running cron job. ');
	cron_stop_session();
	return;
	
}

cron_log('Found ' . count( $list_of_sites ) . ' sites to run in queue');
cron_log('Creating logs folder');
$base = 'logs/' . date('Y-m-d') . '/';

cron_log('Started queue');

foreach( $list_of_sites as $site )
{
	//run site email queue
	
	cron_log('Running ' . $site['name'] . ' email queue ');
	$data = cron_run( $site['url'] . 'os_cron_jobs/notifications/run_email_queue' );
	cron_log('Finished running SMS queue for site. Saving log');
	
	
	cron_log('Running ' . $site['name'] . ' sms queue - 10 SMS Max');
	$data = cron_run( $site['url'] . 'os_cron_jobs/notifications/run_sms_queue' );
	cron_log('Finished running SMS queue for site. Saving log');
	
	
	
} 
