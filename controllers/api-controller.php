<?php

/**
 * Wp controller vincod api
 *
 * A simple interface of vincod API Restful
 *
 * Note : I wanted use the API lib created by Thomas, but the lib
 * created many conflict with Wordpress (i searched the problem 2hours, and i didn't find ...)
 * so i crafted this minimalist lib.
 *
 * @author       Vinternet
 * @category     Controller
 * @copyright    2016 VINTERNET
 *
 */
class wp_vincod_controller_api {
	
	/**
	 * The key customer API
	 *
	 * @var string
	 */
	private $_customer_api = '';
	
	/**
	 * The id of customer API
	 *
	 * @var string
	 */
	private $_customer_id = '';
	
	public function __construct() {
	
	}
	
	
	/**
	 * Config
	 *
	 * Config the api by array params
	 *
	 * @access    public
	 * @return    mixed (bool/array)
	 *
	 */
	public function config(array $config = array()) {
		
		foreach($config as $key => $value) {
			
			if(isset($this->$key)) {
				
				$this->$key = $value;
				
			}
			
		}
		
	}
	
	
	/**
	 * Request API
	 *
	 * Simple interface for API requests
	 *
	 * @access    public
	 * @return    bool
	 *
	 */
	public function request_api(array $params = array()) {
		
		if(isset($params['method']) && !empty($params['method']) && isset($params['action']) && !empty($params['action'])) {
			
			$additional_params = (isset($params['params'])) ? $params['params'] . '&' : '';
			
			// Create url to request
			$url = 'http://mboucher.apivincod.vinternet-redmine.reseaux.info/2/json/%method/%action/%lang/%id?' . $additional_params . 'apiKey=' . $this->_customer_api;
//			$url = 'http://api.vincod.com/2/json/%method/%action/%lang/%id?' . $additional_params . 'apiKey=' . $this->_customer_api;
			
			
			$url = $this->parse_url($url, $params);
			// Check in the cache
			$already_cached = $this->already_cached($url);

			if(WP_DEBUG == true) {
				echo($url.'<br/>');
			}
			
			if(!$already_cached) {
				
				// File get (located in plugin-vincod/functions.php)
				$datas = wp_vincod_file_get_contents($url, false);
				
				// Create cache if user option different 0 or empty
				$option = (int)get_option('vincod_setting_cache_api');
				
				if($option != 0) {
					
					$this->cache($url, $datas);
					
				}
				
				// Decode datas
				$datas = json_decode($datas, true);
				
				
			}
			else {
				
				$datas = json_decode($already_cached, true);
				
			}
			
			// Return the result
			return $datas;
			
		}
		else {
			
			return false;
			
		}
		
	}
	
	
	/**
	 * Parse Url
	 *
	 * Replace some things in the url
	 *
	 * @access    private
	 * @return    string
	 *
	 */
	private function parse_url($url, $params) {
		
		// Method
		$url = str_replace('%method', $params['method'], $url);
		
		// Action
		$url = str_replace('%action', $params['action'], $url);
		
		// Id
		if(isset($params['id'])) {
			
			$url = str_replace('%id', $params['id'], $url);
			
		}
		elseif(isset($params['vincod'])) {
			
			$url = str_replace('%id', $params['vincod'], $url);
			
		}
		else {
			
			$url = str_replace('%id', $this->_customer_id, $url);
			
		}
		
		// Lang
		if(isset($params['lang'])) {
			
			$url = str_replace('%lang', $params['lang'], $url);
			
		}
		else {
			
			$lang = wp_vincod_detect_lang();
			
			if(empty($lang)) {
				
				$lang = 'fr';
				
			}
			
			$url = str_replace('%lang', $lang, $url);
			
		}
		
		return $url;
		
	}
	
	/**
	 * Already Cached
	 *
	 * Check if the request already cached
	 *
	 * @access    private
	 *
	 * @param    string  Url request to check
	 *
	 * @return    mixed (json / false)
	 *
	 */
	private function already_cached($url) {
		
		// Encode url in md5
		$file = md5($url);
		
		$path = WP_VINCOD_PLUGIN_PATH . 'cache/api/' . $file . '.json';
		
		// Check if file exists in the cache folder
		if(file_exists($path)) {
			
			// Check if you can use this file (Time cache)
			// So get the timestamp of file
			$file_timestamp = filemtime($path);
			
			// Get duration cache
			// and convert days into seconds
			$duration_cache = (int)get_option('vincod_setting_cache_api');
			$duration_cache = 86400 * $duration_cache;
			
			if($file_timestamp + $duration_cache > time()) {
				
				// Cache not expired
				$loaded = file_get_contents($path);
				
				return $loaded;
				
			}
			else {
				
				// Delete file
				unlink($path);
				
				return false;
				
			}
			
		}
		
		return false;
		
	}
	
	/**
	 * Cache
	 *
	 * Write new file for cache
	 *
	 * @access    private
	 *
	 * @param    string    Url to cache
	 * @param    json    Json to stock
	 *
	 * @return    string
	 *
	 */
	private function cache($url, $datas) {
		
		$file = md5($url);
		
		file_put_contents(WP_VINCOD_PLUGIN_PATH . 'cache/api/' . $file . '.json', $datas);
		
		return true;
		
	}
	
	
	/* ---------------------------------------------------------------- */
	// Owner
	/* ---------------------------------------------------------------- */
	
	public function get_owner_by_id() {
		
		$owner = $this->request_api(array(
			
			'method' => 'owner',
			'action' => 'GetOwnerById'
		
		));
		
		
		// Check error
		if(isset($owner['owners']['error']) || empty($owner)) {
			
			return false;
			
		}
		
		return $owner['owners']['owner'];
	}
	
	public function get_catalogue_by_vincod($vincod = null) {
		
		$params = array(
			
			'method' => 'owner',
			'action' => 'GetCatalogueByVincod'
		
		);
		
		if($vincod !== null) {
			$params['params'] = 'vincod=' . $vincod;
		}
		
		$menu = $this->request_api($params);
		
		
		// Check error
		if(isset($menu['owners']['error']) || empty($menu)) {
			
			return false;
			
		}
		
		return $menu['owners'];
	}
	
	
	/* ---------------------------------------------------------------- */
	// Family
	/* ---------------------------------------------------------------- */
	
	public function get_families_by_owner_id() {
		
		$families = $this->request_api(array(
			
			'method' => 'family',
			'action' => 'GetFamiliesByOwnerId'
		
		));
		
		if(isset($families['families']['error']) || empty($families) || !isset($families['families']['family'])) {
			
			return false;
			
		}
		
		$families = $this->_prevent_api($families, 'families', 'family');
		
		return $families['families']['family'];
		
	}
	
	public function get_family_by_id($id) {
		
		
		$family = $this->request_api(array(
			
			'method' => 'family',
			'action' => 'GetFamilyById',
			'id' => $id
		
		));
		
		if(isset($family['families']['error']) || empty($family) || empty($family['families']['family'])) {
			
			return false;
			
		}
		
		return $family['families']['family'];
	}
	
	public function get_family_by_winery_id($id) {
		
		
		$family = $this->request_api(array(
			
			'method' => 'family',
			'action' => 'GetFamilyByWineryId',
			'id' => $id
		
		));
		
		if(isset($family['family']['error']) || empty($family) || empty($family['families']['family'])) {
			
			return false;
			
		}
		
		return $family['families']['family'];
	}
	
	
	/* ---------------------------------------------------------------- */
	// Winery
	/* ---------------------------------------------------------------- */
	
	public function get_wineries_by_owner_id() {
		
		$wineries = $this->request_api(array(
			
			'method' => 'winery',
			'action' => 'GetWineriesByOwnerId'
		
		));
		
		if(isset($wineries['wineries']['error']) || empty($wineries)) {
			
			return false;
			
		}
		
		$wineries = $this->_prevent_api($wineries, 'wineries', 'winery');
		
		return $wineries['wineries']['winery'];
		
	}
	
	public function get_wineries_by_family_id($id) {
		
		$wineries = $this->request_api(array(
			
			'method' => 'winery',
			'action' => 'GetWineriesByFamilyId',
			'id' => $id
		
		));
		
		if(isset($wineries['wineries']['error']) || empty($wineries)) {
			
			return false;
			
		}
		
		$wineries = $this->_prevent_api($wineries, 'wineries', 'winery');
		
		return $wineries['wineries']['winery'];
		
	}
	
	public function get_winery_by_id($id) {
		
		$winery = $this->request_api(array(
			
			'method' => 'winery',
			'action' => 'GetWineryById',
			'id' => $id
		
		));
		
		// Check error
		if(isset($winery['wineries']['error']) || empty($winery)) {
			
			return false;
			
		}
		
		return $winery['wineries']['winery'];
	}
	
	public function get_winery_by_vincod($id) {
		
		$winery = $this->request_api(array(
			
			'method' => 'winery',
			'action' => 'GetWineryByVincod',
			'id' => $id
		
		));
		
		// Check error
		if(isset($winery['wineries']['error']) || empty($winery)) {
			
			return false;
			
		}
		
		return $winery['wineries']['winery'];
		
		
	}
	
	public function get_winery_by_range_id($id) {
		
		$winery = $this->request_api(array(
			
			'method' => 'winery',
			'action' => 'GetWineryByRangeId',
			'id' => $id
		
		));
		
		// Check error
		if(isset($winery['wineries']['error']) || empty($winery)) {
			
			return false;
			
		}
		
		return $winery['wineries']['winery'];
		
	}
	
	
	/* ---------------------------------------------------------------- */
	// Range
	/* ---------------------------------------------------------------- */
	
	public function get_ranges_by_owner_id() {
		
		$ranges = $this->request_api(array(
			
			'method' => 'range',
			'action' => 'GetRangesByOwnerId',
		
		));
		
		if(isset($ranges['wineries']['error']) || empty($ranges)) {
			
			return false;
			
		}
		
		$ranges = $this->_prevent_api($ranges, 'wineries', 'winery');
		
		return $ranges;
	}
	
	public function get_ranges_by_winery_id($id) {
		
		
		$ranges = $this->request_api(array(
			
			'method' => 'range',
			'action' => 'GetRangesByWineryId',
			'id' => $id
		
		));
		
		if(empty($ranges)) {
			
			return false;
			
		}
		elseif(isset($ranges['wineries']['error'])) {
			
			// String returned, so just only for a single result
			if(!is_array($ranges['wineries']['error'])) {
				
				return false;
				
			}
			
			// We get many results, we must check one per one if no error
			
			$ranges_success = array();
			
			foreach($ranges['wineries']['winery'] as $key => $winery) {
				
				if(!isset($ranges['wineries']['error'][$key])) {
					
					$ranges_success[] = $winery;
					
				}
				
			}
			
			// Check if we found ranges with no error
			if(empty($ranges_success)) {
				
				return false;
				
			}
			
			// Re assign
			$ranges['wineries']['winery'] = $ranges_success;
			
		}
		
		$ranges = $this->_prevent_api($ranges, 'wineries', 'winery');
		
		return $ranges['wineries']['winery'];
		
	}
	
	public function get_range_by_id($id) {
		
		
		$range = $this->request_api(array(
			
			'method' => 'range',
			'action' => 'GetRangeById',
			'id' => $id
		
		));
		
		if(isset($range['wineries']['error']) || empty($range)) {
			
			return false;
			
		}
		
		return $range['wineries']['winery'];
	}
	
	
	public function get_range_by_vincod($id) {
		
		$range = $this->request_api(array(
			
			'method' => 'range',
			'action' => 'GetRangeByVincod',
			'id' => $id
		
		));
		
		if(isset($range['wineries']['error']) || empty($range)) {
			
			return false;
			
		}
		
		return $range['wineries']['winery'];
		
	}
	
	
	/* ---------------------------------------------------------------- */
	// Wine
	/* ---------------------------------------------------------------- */
	
	public function get_wines_by_range_id($id) {
		
		
		$wines = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetWinesByRangeId',
			'id' => $id
		
		));
		
		if(isset($wines['wines']['error']) || empty($wines)) {
			
			return false;
			
		}
		
		$wines = $this->_prevent_api($wines, 'wines', 'wine');
		
		return $wines['wines']['wine'];
		
		
	}
	
	public function get_wine_by_vincod($id) {
		
		$wine = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetWineByVincod',
			'id' => $id,
		
		));
		
		if(isset($wine['wines']['error']) || empty($wine)) {
			
			return false;
			
		}
		
		return $wine['wines']['wine'];
		
	}
	
	public function get_other_vintages_by_vincod($id) {
		
		$vintages = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetOtherVintagesByVincod',
			'id' => $id
		
		));
		
		if(isset($vintages['wines']['error']) || empty($vintages)) {
			
			return false;
			
		}
		
		$vintages = $this->_prevent_api($vintages, 'wines', 'wine');
		
		return $vintages['wines']['wine'];
		
	}
	
	public function get_wines_by_winery_id($id) {
		
		$wines = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetWinesByWineryId',
			'id' => $id
		
		));
		
		
		if(isset($wines['wines']['error']) || empty($wines)) {
			
			return false;
			
		}
		
		$wines = $this->_prevent_api($wines, 'wines', 'wine');
		
		return $wines['wines']['wine'];
	}
	
	
	public function get_wines_by_owner_id() {
		
		$wines = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetWinesByOwnerId'
		
		));
		
		if(isset($wines['wines']['error']) || empty($wines)) {
			
			return false;
			
		}
		
		$wines = $this->_prevent_api($wines, 'wines', 'wine');
		
		return $wines['wines']['wine'];
		
	}
	
	public function get_wines_by_search($search) {
		
		$wines = $this->request_api(array(
			
			'method' => 'wine',
			'action' => 'GetWinesBySearch',
			'id' => $search
		
		));
		
		if(isset($wines['wines']['error'])) {
			
			return array('error' => $wines['wines']['error']);
			
		}
		elseif(empty($wines)) {
			
			return false;
			
		}
		
		$wines = $this->_prevent_api($wines, 'wines', 'wine');
		
		return $wines['wines']['wine'];
		
	}
	
	private function _prevent_api($datas, $key, $second_key) {
		
		// Problem with return api datas
		// Sometimes return just one wine without multidimensionnal
		
		if(!wp_vincod_is_multi($datas[$key][$second_key])) {
			
			// Re format array
			$tmp = $datas[$key][$second_key];
			
			unset($datas[$key][$second_key]);
			
			$datas[$key][$second_key][0] = $tmp;
			
		}
		
		return $datas;
		
		
	}
	
}
