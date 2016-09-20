<?php
/**
 * Ad Network
 *
 * Advertising network. Show advertising banners and popups
 * in free OneShop account
 *
 * @author 		Trevor Sibanda <trevorsibb@gmail.com>
 * @date 		6 Sept 2015
 * @package 	Models/Ui/Ad_network 		
 */

class Ad_engine extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('oneshop/ad_engine');
		$this->load->helper('ad_engine');
	}

	/**
	 * Initialise the Ad network and select
	 * ads
	 *
	 * @param 		Array 		$	Shop
	 *
	 * @return 		Bool
	 */
	public function init( $shop )
	{
		//@todo determine ad network to choose based on shop
		if( ! defined('OS_AD_ENGINE_ON') )
			define('OS_AD_ENGINE_ON' , true );
	}

	/**
	 * Get a list of all advertisers which are available
	 *
	 * @return 		Array
	 */
	public function available_advertisers(  )
	{
		return $this->config->item('ad_networks');
	}

	//@todo implement
	public function activate_advertiser( $advertiser )
	{

	}

	//@todo implement
	public function deactivate_advertiser( $advertiser )
	{

	}

	/**
	 * Get active advertising networks
	 *
	 * @return 		Array
	 */
	public function get_active_advertisers(  )
	{
		return $this->config->item('active_ad_networks');
	}


	/**
	 * Get the initialization html code
	 * Ad engine must be initialized.
	 * 
	 * 
	 * @return 		String 	$	Html code
	 */
	public function get_init_html(  )
	{
		$active = $this->get_active_advertisers();
		$code = '<!-- Start Ads Init -->';
		foreach ($active as $name) 
		{
			$network = $this->config->item($name .'_ad_network');
			if( ! empty($network) )
			{
				$code .= $network['init_html'];
			}
		}
		$code .= '<!--End Ads Init -->';
		return $code;
	}

	/**
	 * Get the html closing tags
	 * Ad engine must be initialized
	 *
	 * @return 		String 	$	Html code
	 */
	public function get_close_html( )
	{
		$active = $this->get_active_advertisers();
		$code = '<!-- Start Ads End -->';
		foreach ($active as $name) 
		{
			$network = $this->config->item($name .'_ad_network');
			if( ! empty($network) )
			{
				$code .= $network['close_html'];
			}
		}
		$code .= '<!--End Ads  -->';
		return $code;
	}

	/**
	 * Get advert html code
	 *
	 * Get the html code for an advert. 
	 *
	 * @param 		String 		$	Resolution widthxheight i.e 729x90
	 * @param 		String 		$	Advert type ( banner, popup, popunder  )
	 * @param 		String 		$	Advertsing network to selet any use random ( Adsense, PropeelerAds.. etc)
	 *
	 */
	public function get_advert_html( $resolution , $type , $network = 'random' )
	{
		$active = $this->get_active_advertisers();
		if( ! in_array($type, array('banner','popup','popunder')))
		{
			$type = 'banner';
		}

		if( $network == 'random')
		{

			$network = $active[ rand()%count($active) ];

		}
		$name = $network;

		$network = $this->config->item($network .'_ad_network');
		if( empty($network) )
			return "<!-- {$resolution} {$type} {$network} ad unit not found -->";
		$code = '';
		if( in_array($resolution, $network['ad_units'][$type]) )
		{
			$code = $this->load->view('system/adverts/' . $name . '/' . $resolution . '_unit' ,Null , True );
		}
		else
		{
			$code = "<!-- {$resolution} {$type} {$network} ad unit not supported -->";
		}
		return $code;
	}



	




}
