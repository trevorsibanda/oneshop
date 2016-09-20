<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_job_263shop_restricted extends CI_Controller {

	//file handle
	private $fh=Null;
	
	//cached shop lists
	private $_cached_shop_list=Null;
	
	//cache shop list database file
	private $_cache_shop_list_file = '/tmp/cron_shop_cache_list.db';
	
	//base folder to store logs
	private $_base_logs_path = APPPATH . 'logs/cron/';
	
	public function __construct()
	{
		ignore_user_abort(1);
		parent::__construct();
		$this->load->database();
		
		$this->load->model('shop');
		$this->load->model('ui');
		$this->load->model('product');
		$this->load->library('email');
		
		$this->db->db_select('oneshop');
		
		set_time_limit(0);
		
		
	}
	
	public function index()
	{
	
	}
	
	//@todo add app queue
	public function run_cron_minute( )
	{
		$this->_start_cron_log('cron_minute_logs'  , time() );
		$lock_file = '/tmp/minute-job-notifications.lock';
		
		$this->_log("Started Run Cron Jobs Notifications Queue ");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron minute notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			$has_sms = $this->_has_queue( $shop['shop_id'] , 'sms' );
			$has_email = $this->_has_queue( $shop['shop_id'] , 'email' );
			if( ! $has_sms and ! $has_email )
			{
				$this->_log('Site does not have any items in sms or email queues. Continuing');
				continue;
			}
			
			
			if( $has_email )
			{
				$this->_log('Starting shop Email queue');
				$email_queue_data = $this->_run_cron_task( $shop , 'notifications/run_email_queue' );
				$this->_log('Email queue run completed. Returned data : ');
				$this->_log('');
				$this->_log( "\n" .  $email_queue_data );
				$this->_log('');
				$this->_log('Ended email queue');
			}
			if( $has_sms )
			{
				$this->_log('Starting shop sms queue');
				$email_queue_data = $this->_run_cron_task( $shop , 'notifications/run_sms_queue' );
				$this->_log('Email queue run completed. Returned data : ');
				$this->_log('');
				$this->_log( "\n" . $email_queue_data );
				$this->_log('');
				$this->_log('Ended email queue');
			}
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			$this->_log('');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
		
		  
	}
	
	public function run_cron_15_minutes( )
	{
		$this->_start_cron_log('cron_15_minutes_logs'  , time() );
		$lock_file = '/tmp/15-minute-job-notifications.lock';
		
		$this->_log("Started Run Cron  15 minute Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron minute notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_30_minutes( )
	{
		$this->_start_cron_log('cron_30_minutes_logs'  , time() );
		$lock_file = '/tmp/30-minute-job-notifications.lock';
		
		$this->_log("Started Run Cron 30 Minute Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron 30 minute job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	
	public function run_cron_hourly( )
	{
	
		$this->_start_cron_log('cron_hourly_logs'  , time() );
		$lock_file = '/tmp/hourly-job-notifications.lock';
		
		$this->_log("Started Run Cron Hourly Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron hourly job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_6_hours( )
	{
		$this->_start_cron_log('cron_6_hour_logs'  , time() );
		$lock_file = '/tmp/6-hour-job-notifications.lock';
		
		$this->_log("Started Run Cron 6 Hours Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron six hour notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_12_hours( )
	{
		$this->_start_cron_log('cron_12_hour_logs'  , time() );
		$lock_file = '/tmp/12-hour-job-notifications.lock';
		
		$this->_log("Started Run Cron 12 Hour Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron minute notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_daily( )
	{
		$this->_start_cron_log('cron_daily_logs'  , time() );
		$lock_file = '/tmp/daily-job-notifications.lock';
		
		$this->_log("Started Run Cron Daily jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron daily notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			$this->_log('');
			$this->_log('Running expire old orders');
			$response = $this->_run_cron_task( $shop , 'orders/expire_overdue_orders' );
			$this->_log('');
			$this->_log( "\n" .  $response );
			$this->_log('');
			$this->_log('Running delete expired orders');
			$response = $this->_run_cron_task( $shop , 'orders/delete_expired_orders' );
			$this->_log('');
			$this->_log( "\n" .  $response );
			$this->_log('');
			$this->_log('Running check all pending orders for payment');
			$response = $this->_run_cron_task( $shop , 'orders/check_all_pending_transactions' );
			$this->_log('');
			$this->_log( "\n" .  $response );
			$this->_log('');
			$this->_log('Running send order reminders');
			$response = $this->_run_cron_task( $shop , 'orders/check_all_pending_transactions' );
			$this->_log('');
			$this->_log( "\n" .  $response );
			$this->_log('');
			$this->_log('Running check account limits');
			$response = $this->_run_cron_task( $shop , 'security/check_account_limits' );
			$this->_log('');
			$this->_log( "\n" .  $response );
			$this->_log('');
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_2_days( )
	{
		$this->_start_cron_log('cron_2_day_logs'  , time() );
		$lock_file = '/tmp/2-day-job-notifications.lock';
		
		$this->_log("Started Run Cron 2 day Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron 2 day notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_weekly( )
	{
		$this->_start_cron_log('cron_weekly_logs'  , time() );
		$lock_file = '/tmp/weekly-job-notifications.lock';
		
		$this->_log("Started Run Cron Hourly Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron weekly notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_fortnight( )
	{
		$this->_start_cron_log('cron_fortnight_logs'  , time() );
		$lock_file = '/tmp/fortnight-job-notifications.lock';
		
		$this->_log("Started Run Cron Hourly Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron fortnight notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_monthly( )
	{
		$this->_start_cron_log('cron_monthly_logs'  , time() );
		$lock_file = '/tmp/monthly-job-notifications.lock';
		
		$this->_log("Started Run Cron Hourly Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron monthly notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_3_months( )
	{
		$this->_start_cron_log('cron_3_month_logs'  , time() );
		$lock_file = '/tmp/3-month-job-notifications.lock';
		
		$this->_log("Started Run Cron 3 month Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron 3 month notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_6_months( )
	{
		$this->_start_cron_log('cron_6_month_logs'  , time() );
		$lock_file = '/tmp/6-month-job-notifications.lock';
		
		$this->_log("Started Run Cron 6 month Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron 6 month notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function run_cron_annually( )
	{
		$this->_start_cron_log('cron_annuall_logs'  , time() );
		$lock_file = '/tmp/annuall-job-notifications.lock';
		
		$this->_log("Started Run Cron Anual Jobs");
		
		//check if another queue is running
		if( file_exists( $lock_file  ) )
		{
			$this->_log('Another cron anual notifications job is already running. Will terminate');
			$this->_log('Terminating now');
			return;
		}
		
		//lock until session complete
		file_put_contents($lock_file , '1');
		
		$list_of_shops = $this->_get_priority_site_list();
		$this->_log('Found ' . count( $list_of_shops ) . ' to run queue for');
		
		foreach( $list_of_shops as $shop )
		{
			
			$this->_log('Started queue for shop[ ' . $shop['shop_id'] . '  ] - ' . $shop['url'] . ' -> ' . $shop['name'] ); 
			
			
			
			$this->_log('Ended queue for shop[ ' . $shop['shop_id'] . ' ] ');
			
		}
		
		//remove lock file
		unlink($lock_file); 
		$this->_end_cron_log( );
	}
	
	public function _read_input() 
	{
		return json_decode($this->input->raw_input_stream,true);
	}
	
	public function _start_cron_log( $folder , $filename )
	{
		@mkdir( $this->_base_logs_path . $folder );
		@mkdir( $this->_base_logs_path . $folder . '/' . date('Y-m-d') );
		
		$log_file = $this->_base_logs_path . $folder . '/' . date('Y-m-d') . '/' . $filename;
		
		$this->fh = fopen(  $log_file , 'w');
		
		if( ! $this->fh )
		{
			echo "[!] Failed to open log file. The cron log will only be written to stdout \n";
		}
		else
		{
			$this->_log('Created session log file ' . $log_file );
		}
		return True; 
	}
	
	public function _end_cron_log( )
	{
		if( $this->fh != False )
			$this->_log('Ended cron log and closed file');
		fclose( $this->fh );
		$this->fh = False;	
	}
	
	//get list of shops, starting with premium account onto basic then free
	public function _get_priority_site_list()
	{
		
		if( file_exists( $this->_cache_shop_list_file ) )
		{
			//if not older than 5 minutes
			if( time() - filemtime( $this->_cache_shop_list_file ) <= (60*5 ) )
			{ 
				$data = file_get_contents( $this->_cache_shop_list_file );
				$shops = json_decode( $data , True );
				if(  is_array( $shops ) )
				{
					return $shops;
				}
			}
		}
		
		$shops = array();
		
		foreach( array('free','basic','premium') as $plan )
		{
			$query = $this->db->get_where('shop_account_subscription' , array('type' => $plan , 'year' => date('Y') , 'month' => date('m') ) );
			foreach( $query->result_array() as $row )
			{
				$shop = $this->shop->get_by_id(  $row['shop_id'] );
				if( empty($shop) )
					continue;
				array_push( $shops , $shop );	
			} 
		}
		return $shops;
	}
	
	//run a cron job
	public function _run_cron_task( $shop , $job_name )
	{
		//run .263shop.co.zw domain so that we are sure that cron wont fail
		$url = 'http://' . $shop['subdomain'] . OS_BASE_DOMAIN . '/os_cron_jobs/' . $job_name . '?os_cron_job_no_redirect=';
		$this->_log('Initiated HTTP Request to ' . $url );
		$arrContextOptions=array(
			    "ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			    ),
			); 
			
		$response = file_get_contents( $url , false, stream_context_create($arrContextOptions) );
		$this->_log('Finished HTTP Request ');
		return $response; 
		
	}
	
	//does the shop have messages that need to be sent
	public function _has_queue( $shop_id , $type )
	{
		$conds = array('is_sent' => False , 'type' => $type , 'shop_id' => $shop_id );
		foreach( $conds as $k => $v )
		{
			$this->db->where( $k , $v );
		}
		return $this->db->count_all_results('push_notification');
		
	}
	
	
	public function _log( $msg = '' )
	{	
		if( $msg == '' )
			$msg = '-------------------------------------------';
		$msg = '[=]{' . date('r') . '}  ' . $msg . "\n";   
		if( $this->fh !== False )
			fwrite( $this->fh , $msg );
		echo $msg;		
	}
	
	/**
	 * Rest helper
	 *
	 * @param 		String 	$url 		Url
	 * @param 		Mixed 	$params 	Parameters, string or array
	 * @param 		String 	$verb 		HTTP VERB
	 * @param 		String 	$format 	Format
	 *
	 * @return 		Mixed 	$			Depending on format, returns array, object or string. returns False on Fail
	 */
	private function rest_helper($url, $params = null, $verb = 'GET', $format = 'json')
	{
	  $cparams = array(
	    'http' => array(
	      'method' => $verb,
	      'ignore_errors' => true
	    )
	  );
	  if ($params !== null) {
	    $params = http_build_query($params);
	    if ($verb == 'POST') {
	      $cparams['http']['content'] = $params;
	    } else {
	      $url .= '?' . $params;
	    }
	  }

	  $context = stream_context_create($cparams);
	  $fp = @fopen($url, 'rb', false, $context);
	  if (!$fp) {
	    $res = false;
	  } else {
	    // If you're trying to troubleshoot problems, try uncommenting the
	    // next two lines; it will show you the HTTP response headers across
	    // all the redirects:
	    // $meta = stream_get_meta_data($fp);
	    // var_dump($meta['wrapper_data']);
	    $res = stream_get_contents($fp);
	  }

	  if ($res === false) {
	    return False;
	  }

	  switch ($format) {
	    case 'json':
	      $r = json_decode($res);
	      if ($r === null) {
	        return $r;
	      }
	      return $r;

	    case 'xml':
	      $r = simplexml_load_string($res);
	      if ($r === null) {
	        return $r;
	      }
	      return $r;
	  }
	  return $res;
	}
	
	
}
