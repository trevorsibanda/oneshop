<?php
/**
 * DNS Model
 *
 * @package 		Models/System/Dns.php
 * @author 			Trevor Sibanda
 * @date 			Jul-1-2015
 *
 * Queries dns servers and returns results. 
 * Mainly used to verify if a domain or subdomain
 * is actually pointing to the shop
 *
 */

require_once( APPPATH . '/libraries/Dns.php');

class Dns extends CI_Model
{
	//google nameserver
	private $_server = '8.8.8.8';
	private $_port = 53;
	private $_query;

	public $timeout=60;
	public $udp=true;
	public $debug=false;
	public $binarydebug=false;
	public $type="ALL";
	public $question="trevor.base2theory.com";


	public function __construct()
	{
		parent::__construct();
		if( ENVIRONMENT == 'debug')
		{
			$this->_server = '127.0.0.1';
		}
		$this->_query= new DNSQuery($this->_server,$this->_port,$this->timeout,$this->udp,$this->debug,$this->binarydebug);

	}

	public function get_query()
	{
		return $this->_query;
	}

	/**
	 * Query if a domain is registered
	 *
	 * @param 		$domain String 	Domain name (naked domain. - without www. )
	 *
	 * @return 		Bool
	 */
	public function is_registered(  )
	{

	}

	/**
	 * Set DNS nameserver
	 *
	 * @param 		$server 	String 		Server
	 * @param 		$port 		Int 		Port
	 */
	public function set_nameserver( $server , $port = 53 )
	{
		$this->_server = $server;
		$this->_port = $port;
	}

	/**
	 * Query DNS Server for a record
	 *
	 * @param 		$question 		String 	Question (domain)
	 * @param 		$type 			String 	Type (if not passed, this->type is used )
	 *
	 * @return 		Array
	 */
	public function query( $question , $type = Null )
	{
		if( $type != Null)
			$this->type = $type;
		return $this->_query->Query($question ,$this->type);	
	}


	/**
	 * Query for a CNAME record.
	 *
	 * @param 	$subdomain 		String CNAME record	
	 *
	 * @return 	Array
	 */
	public function query_cname( $subdomain )
	{
		return $this->query($subdomain, 'CNAME');
	}

	/**
	 * Check if a subdomain is actually an alias of a oneshop subdomain
	 *
	 * E.g shop.trevor.co.zw might be an alias of myshop.oneshop.co.zw 
	 * This function can be used to verify this.
	 *
	 * @param 		$alias 		String 		Alias ( myshop.oneshop.co.zw )
	 * @param 		$subdomain 	String  	Subdomain  shop.base2theory.com OR www.base2theory.com
	 *
	 * @return 		Bool	 		
	 */
	public function is_alias( $alias , $subdomain  )
	{
		$result = $this->query_cname( $subdomain , 'CNAME');
		
		if ($this->get_query()->error)
		{
			return False;
		}
		//too many cname records are bad
		if( $result->count > 1 )
		{
			//@todo WTF Am I doing ???
			return False; //assume error
		}
		if( ! isset($result->results[0]))
			return False;
		$entry = $result->results[  0 ];
		if( $entry->domain == $subdomain AND $entry->data == $alias )
		{
			return True;
		}
		return False;
	}

	/**
	 * Generate a CNAME DNS record for a subdomain making it an alias 
	 *
	 *
	 * @param 		$alias 		String 	Alias ( myshop.oneshop.co.zw )
	 * @param 		$subdomain 	String 
	 *
	 * @return 		String
	 */
	public function make_alias_record( $alias  , $subdomain )
	{
		return "CNAME\t{$alias}\t{$subdomain}";
	}

	/**
	 * Automatically register a domain name
	 *
	 * @todo 	Implement this. Maybe partner with Name.co.zw ?
	 *
	 * @param 		$domain 	String 	Domain name ( trevor.co.zw )
	 * @param 		$data 		Array 	Data to pass to the domain registar
	 *
	 * @return 		Array
	 */
	public function register_domain(  $domain , $data )
	{

	}


}

