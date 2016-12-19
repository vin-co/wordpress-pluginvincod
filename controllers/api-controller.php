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
* @author Jeremie Ges
* @copyright 2013
* @category	Controller
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
	* @access	public
	* @return	void
	*
	*/
	public function config(array $config = array()) {

		foreach ($config as $key => $value) {

			if (isset($this->$key)) {

				$this->$key = $value;

			}

		}

	}


	/**
	* Request API
	*
	* Simple interface for API requests
	*
	* @access	public
	* @return	void
	*
	*/
	public function request_api(array $params = array()) {

		if (isset($params['method']) && !empty($params['method']) && isset($params['action']) && !empty($params['action'])) {

			$additional_params = (isset($params['params'])) ? $params['params'].'&' : '';

			// Create url to request
			$url = 'http://api.vincod.com/2/json/%method/%action/%lang/%id?'. $additional_params .'apiKey=' . $this->_customer_api;


			$url = $this->parse_url($url, $params);
			// Check in the cache
			$already_cached = $this->already_cached($url);

			if(WP_DEBUG == true) {
				var_dump($url);
			}

			if (! $already_cached) {

				// File get (located in plugin-vincod/functions.php)
				$datas = wp_vincod_file_get_contents($url, FALSE);

				// Create cache if user option different 0 or empty
				$option = (int) get_option('vincod_setting_cache_api');

				if ($option != 0) {

					$this->cache($url, $datas);

				}

				// Decode datas
				$datas = json_decode($datas, TRUE);

			} else {

				$datas = json_decode($already_cached, TRUE);

			}

			// Return the result
			return $datas;

		} else {

			return FALSE;

		}

	}


	/**
	* Parse Url
	* 
	* Replace some things in the url
	*
	* @access	private
	* @return	string
	* 
	*/
	private function parse_url($url, $params) {

		// Method
		$url = str_replace('%method', $params['method'], $url);

		// Action
		$url = str_replace('%action', $params['action'], $url);

		// Id
		if (isset($params['id'])) {

			$url = str_replace('%id', $params['id'], $url);

		} elseif (isset($params['vincod'])) {

			$url = str_replace('%id', $params['vincod'], $url);

		} else {

			$url = str_replace('%id', $this->_customer_id, $url);

		}

		// Lang
		if (isset($params['lang'])) {

			$url = str_replace('%lang', $params['lang'], $url);

		} else {

			$lang = wp_vincod_detect_lang();

			if (empty($lang)) {

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
	* @access	private
	* @param    string  Url request to check
	* @return	mixed (json / false)
	* 
	*/
	private function already_cached($url) {

		// Encode url in md5 
		$file = md5($url);

		$path = WP_VINCOD_PLUGIN_PATH . 'cache/api/' . $file . '.json';

		// Check if file exists in the cache folder
		if (file_exists($path)) {

			// Check if you can use this file (Time cache)
			// So get the timestamp of file
			$file_timestamp = filemtime($path);

			// Get duration cache
			// and convert days into seconds
			$duration_cache = (int) get_option('vincod_setting_cache_api');
			$duration_cache = 86400 * $duration_cache;

			if ($file_timestamp + $duration_cache > time()) {

				// Cache not expired
				$loaded = file_get_contents($path);
				return $loaded;

			} else {

				// Delete file
				unlink($path);

				return FALSE;

			}

		}

		return FALSE;

	}

	/**
	* Cache
	*
	* Write new file for cache
	*
	* @access	private
	* @param 	string 	Url to cache
	* @param  	json 	Json to stock
	* @return	string
	*
	*/
	private function cache($url, $datas) {

		$file = md5($url);

		file_put_contents(WP_VINCOD_PLUGIN_PATH . 'cache/api/' . $file . '.json', $datas);

		return TRUE;

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
		if (isset($owner['owners']['error'])) {

			return FALSE;

		}

		return $owner;
	}

	public function get_catalogue_by_vincod($vincod = NULL) {

		$params = array(

			'method' => 'owner',
			'action' => 'GetCatalogueByVincod'

		);

		if($vincod !== NULL) {
			$params['params'] = 'vincod='. $vincod;
		}

		$menu = $this->request_api($params);


		// Check error
		if (isset($menu['owners']['error'])) {

			return FALSE;

		}

		return $menu;
	}


	/* ---------------------------------------------------------------- */
	// Family
	/* ---------------------------------------------------------------- */

	public function get_families_by_owner_id() {

		$families = $this->request_api(array(

			'method' => 'family',
			'action' => 'GetFamiliesByOwnerId'

			));

		if (isset($families['families']['error']) || !isset($families['families']['family'])) {

			return FALSE;

		}

		$families = $this->_prevent_api($families, 'families', 'family');

		return $families;

	}

	public function get_family_by_id($id) {


		$family = $this->request_api(array(

			'method' => 'family',
			'action' => 'GetFamilyById',
			'id'	 => $id

			));

		if (isset($family['owners']['error']) || empty($family['owners']['family'])) {

			return FALSE;

		}

		return $family;
	}

	public function get_family_by_winery_id($id) {


		$family = $this->request_api(array(

			'method' => 'family',
			'action' => 'GetFamilyByWineryId',
			'id'	 => $id

			));

		if (isset($family['owners']['error']) || empty($family['owners']['family'])) {

			return FALSE;

		}

		return $family;
	}


	/* ---------------------------------------------------------------- */
	// Winery
	/* ---------------------------------------------------------------- */

	public function get_wineries_by_owner_id() {

		$wineries = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineriesByOwnerId'

			));

		if (isset($wineries['wineries']['error'])) {

			return FALSE;

		}

		$wineries = $this->_prevent_api($wineries, 'wineries', 'winery');

		return $wineries;

	}

	public function get_wineries_by_family_id($id) {

		$wineries = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineriesByFamilyId',
			'id' 	 => $id

			));

		if (isset($wineries['wineries']['error'])) {

			return FALSE;

		}

		$wineries = $this->_prevent_api($wineries, 'wineries', 'winery');

		return $wineries;

	}

	public function get_winery_by_id($id) {

		$winery = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryById',
			'id'	 => $id

			));

		// Check error
		if (isset($winery['wineries']['error'])) {

			return FALSE;

		}

		return $winery;
	}

	public function get_winery_by_vincod($id) {

		$winery = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryByVincod',
			'id'	 => $id

			));

		// Check error
		if (isset($winery['wineries']['error'])) {

			return FALSE;

		}

		return $winery;


	}

	public function get_winery_by_range_id($id) {

		$winery = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryByRangeId',
			'id'	 => $id

			));

		// Check error
		if (isset($winery['wineries']['error'])) {

			return FALSE;

		}

		return $winery;

	}


	/* ---------------------------------------------------------------- */
	// Range
	/* ---------------------------------------------------------------- */

	public function get_ranges_by_owner_id() {

		$ranges = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangesByOwnerId',

			));

		if (isset($ranges['wineries']['error'])) {

			return FALSE;

		}

		$ranges = $this->_prevent_api($ranges, 'wineries', 'winery');

		return $ranges;
	}

	public function get_ranges_by_winery_id($id) {


		$ranges = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangesByWineryId',
			'id'	 => $id

			));

		if (isset($ranges['wineries']['error'])) {

			// String returned, so just only for a single result
			if ( ! is_array($ranges['wineries']['error'])) {

				return FALSE;

			}

			// We get many results, we must check one per one if no error

			$ranges_success = array();

			foreach ($ranges['wineries']['winery'] as $key => $winery) {

				if ( ! isset($ranges['wineries']['error'][$key])) {

					$ranges_success[] = $winery;

				}

			}

			// Check if we found ranges with no error
			if (empty($ranges_success)) {

				return FALSE;

			} 

			// Re assign
			$ranges['wineries']['winery'] = $ranges_success;
			

		}

		$ranges = $this->_prevent_api($ranges, 'wineries', 'winery');

		return $ranges;

	}

	public function get_range_by_id($id) {


		$range = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangeById',
			'id'	 => $id

			));

		if (isset($range['wineries']['error'])) {

			return FALSE;

		}

		return $range;
	}


	public function get_range_by_vincod($id) {

		$range = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangeByVincod',
			'id'	 => $id

			));

		if (isset($range['wineries']['error'])) {

			return FALSE;

		}

		return $range;

	}


	/* ---------------------------------------------------------------- */
	// Wine
	/* ---------------------------------------------------------------- */

	public function get_wines_by_range_id($id) {


		$wines = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWinesByRangeId',
			'id'	=> $id

			));

		if (isset($wines['wines']['error'])) {

			return FALSE;

		}

		$wines = $this->_prevent_api($wines, 'wines', 'wine');

		return $wines;


	}

	public function get_wine_by_vincod($id) {

		$wine = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWineByVincod',
			'id'	=> $id,

			));

		if (isset($wine['wines']['error'])) {

			return FALSE;

		}

		return $wine;

	}

	public function get_other_vintages_by_vincod($id) {

		$vintages = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetOtherVintagesByVincod',
			'id' => $id

			));

		if (isset($vintages['wines']['error'])) {

			return FALSE;

		}

		$vintages = $this->_prevent_api($vintages, 'wines', 'wine');

		return $vintages;

	}

	public function get_wines_by_winery_id($id) {

		$wines = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWinesByWineryId',
			'id' => $id

			));


		if (isset($wines['wines']['error'])) {

			return FALSE;

		}

		$wines = $this->_prevent_api($wines, 'wines', 'wine');

		return $wines;
	}



	public function get_wines_by_owner_id() {

		$wines = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWinesByOwnerId'

			));

		if (isset($wines['wines']['error'])) {

			return FALSE;

		}

		$wines = $this->_prevent_api($wines, 'wines', 'wine');

		return $wines;

	}

	private function _prevent_api($datas, $key, $second_key) {

		// Problem with return api datas
		// Sometimes return just one wine without multidimensionnal

		if( ! wp_vincod_is_multi($datas[$key][$second_key])) {

			// Re format array
			$tmp = $datas[$key][$second_key];

			unset($datas[$key][$second_key]);

			$datas[$key][$second_key][0] = $tmp;

		}

		return $datas;


	}

}

?>
