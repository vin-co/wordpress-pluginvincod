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

			// Create url to request
			$url = 'http://api.vincod.com/2/json/%method/%action/%lang/%id?apiKey=' . $this->_customer_api;

			$url = $this->parse_url($url, $params);
			
			// Check in the cache
			$already_cached = $this->already_cached($url);

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

}

?>