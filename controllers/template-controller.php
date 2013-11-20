<?php
/**
* Wp controller vincod template
*
* Called by template() method in wp_plugin_vincod main Class
*
*
* @author 		Jeremie Ges
* @copyright 	2013
* @category		Controller
*
*/
class wp_vincod_controller_template extends wp_vincod_controller_api {

	/**
	* The content of view loaded
	*
	* @var string
	*/
	private $_view_loaded = '';

	public $permalink = '';

	public function __construct() {

		parent::__construct();


		$this->config(array(

			'_customer_api' => get_option('vincod_setting_customer_api'),
			'_customer_id' => get_option('vincod_setting_customer_id')

			));

		// Get the right permalink
		global $wp_rewrite;
		$this->permalink =  get_permalink();

	}


	/**
	* Run
	* 
	* Get params by the $_GET var 
	* and launch the router
	*
	* @access	public
	* @return	void
	* 
	*/
	public function run() {

		global $wp_query;
		global $wp_rewrite;

		$permalinks_used = $wp_rewrite->using_permalinks();

		if ($permalinks_used === TRUE) {

			$request_wines = $wp_query->query_vars['request_wines'];
			$params = $this->understand_request($request_wines);

			// If no params, check with GET
			if (empty($params)) {
				$params = $_GET;
			}

		} else {

			$params = $_GET;

		}

		$this->router($params);

	}

	public function understand_request($request) {

		// Init final params
		$out = array();

		$request = ltrim($request, '/');
		$split = explode('-', $request);

		// Right request ?
		if (isset($split[0]) && !empty($split[0]) && isset($split[1]) && !empty($split[1])) {

			if ($split[0] == 'exploitant') {

				// Winery case
				$out = array('winery' => $split[1]);

			} elseif ($split[0] == 'gamme') {

				// Range case
				$out = array('range' => $split[1]);

			} elseif ($split[0] == 'vin') {

				// Wine case
				$out = array('vincod' => $split[1]);

			}

		} 

		return $out;

		// Urls (note)
		// Winery ->  /{slug}/exploitant-51-chateau-sainte-roseline
		// Range ->   /{slug}/gamme-455-chapelle-de-sainte-roseline



	}


	/**
	 * Router
	 * 
	 * Serve the right template page
	 *
	 * Routes : 
	 *  - none   : template/index.php
	 * 	- winery : template/winery.php
	 *  - range  : template/range.php
	 *  - wine   : template/wine.php
	 *
	 * @access	public
	 * @return	void
	 * 
	 */
	public function router($params) {

		// The base link
		global $wp_rewrite;
		$link_page =  get_permalink();

		// Simple routing system
		if ($this->route('winery', $params)) {

			$this->_exec_winery($params);

		} elseif ($this->route('range', $params)) {

			$this->_exec_range($params);			

		} elseif($this->route('vincod', $params)) {

			$this->_exec_wine($params);			

		} else {

			$this->_exec_index();
		}

	}

	/**
	 * Route
	 * 
	 * Check if it's the right route
	 *
	 * @access	public
	 * @return	bool
	 * 
	 */
	public function route($route, $params) {

		if (isset($params[$route]) && !empty($params[$route])) {

			return TRUE;

		} else {

			return FALSE;

		}

	}

	/**
	 * Load view
	 * 
	 * A simple hook of wp_vincod_load_view to accept custom view in 
	 * the current theme folder
	 *
	 * @access	public
	 * @return	string 
	 * 
	 */
	public function load_view($file, array $view_datas=array(), $return = TRUE) {

		$custom_view_path = WP_VINCOD_CURRENT_THEME_PATH . 'plugin-vincod/' . $file . '.php';

		// Load vars lang
		wp_vincod_load_lang_view($file, wp_vincod_detect_lang());

		$view_datas = array_merge($view_datas, $GLOBALS['wp_vincod_views_datas']);

		if (file_exists($custom_view_path)) {

			$view = wp_vincod_load_view($file, $view_datas, $return, WP_VINCOD_CURRENT_THEME_PATH . 'plugin-vincod');

		} else {

			$view = wp_vincod_load_view($file, $view_datas, $return);

		}

		return $view;

	}

	/**
	 * Render
	 * 
	 * Return the view loaded by the system
	 *
	 * @access	public
	 * @return	void
	 * 
	 */
	public function render() {

		return $this->_view_loaded;

	}

	/* -------------------------------------------------------------- */

	// Owner + Wineries
	private function _exec_index() {

		$owner = $this->get_owner_by_id();
		$wineries = $this->get_wineries_by_owner_id();
		$ranges = $this->get_ranges_by_owner_id();
		$wines = $this->get_wines_by_owner_id();
		

		$view_datas = array(

			'owner' => $owner,
			'wineries' => $wineries,
			'ranges' => $ranges,
			'wines' => $wines,
			'link' => $this->permalink

			);

		// Loader
		$this->_view_loaded = $this->load_view('template/index', $view_datas, TRUE);

	}

	// Winery + Ranges
	private function _exec_winery($params) {

		$winery = $this->get_winery_by_id($params['winery']);
		$ranges = $this->get_ranges_by_winery_id($params['winery']);
		$wines = $this->get_wines_by_winery_id($params['winery']);

		$breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));

		$view_datas = array(

			'winery' => $winery,
			'ranges' => $ranges,
			'wines' => $wines,
			'breadcrumb' => $breadcrumb,
			'link' => $this->permalink

			);

		$this->_view_loaded = $this->load_view('template/winery', $view_datas, TRUE);

	}

	private function _exec_range($params) {

		$range = $this->get_range_by_id($params['range']);
		$wines = $this->get_wines_by_range_id($params['range']);
		$winery = $this->get_winery_by_range_id($params['range']);

		$breadcrumb = wp_vincod_link('winery', $winery['wineries']['winery'][0]['id'], $winery['wineries']['winery'][0]['name']);

		if ($wines) {

			// Shortcut for template
			$wines = $wines['wines']['wine'];

			// Group wines
			$wines = wp_vincod_group_wines($wines);

		}



		$view_datas = array(

			'range' => $range,
			'link'	  => $this->permalink,
			'wines'   => $wines,
			'breadcrumb' => $breadcrumb

			);

		// Loader
		$this->_view_loaded = $this->load_view('template/range', $view_datas, TRUE);


	}

	private function _exec_wine($params) {

		$wine = $this->get_wine_by_vincod($params['vincod']);
		$vintages = $this->get_other_vintages_by_vincod($params['vincod']);


		// We will get all the wines from the other years
		$vintageyears = array();
		$inc = 0;

		if ($vintages) {

			foreach($vintages as $results_years_specific) {

				$vintageyears[$inc] = $results_years_specific;
				$inc++;

			}

		}


		// Breadcrumb part

		$range = $this->get_range_by_vincod($params['vincod']);
		$winery = $this->get_winery_by_vincod($params['vincod']);

		if ($range) {

			$breadcrumb = wp_vincod_link('range', $range['wineries']['winery'][0]['id'], $range['wineries']['winery'][0]['name']);

		} elseif ($winery) {

			$breadcrumb = wp_vincod_link('winery', $winery['wineries']['winery'][0]['id'], $winery['wineries']['winery'][0]['name']);


		} else {

			$breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));

		}


		$view_datas = array(

			'wine'	=> $wine['wines']['wine'][0],
			'oldwines' => $vintageyears,
			'link' => $this->permalink,
			'breadcrumb' => $breadcrumb

			);

		// Loader
		$this->_view_loaded = $this->load_view('template/wine', $view_datas, TRUE);


	}

	/*
	private function _exec_wines_for_winery($params) {

		$wines = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWinesByWineryId',
			'id' => $params['winery']

			));

		$this->breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));

		if (isset($wines['wines']['error'])) {

			$wines = FALSE;

		}

		if ($wines) {

			// Problem with return api datas
			// Sometimes return just one wine without multidimensionnal
			if( ! wp_vincod_is_multi($wines['wines']['wine'][0])) {

				// Re format array
				$tmp = $wines['wines']['wine'];

				unset($wines['wines']['wine']);

				$wines['wines']['wine'][0] = $tmp;

			}

		}


		
		$view_datas = array(

			'wines' => $wines,
			'link' => $this->permalink,
			'breadcrumb' => $this->breadcrumb

			);



		$view_to_load = 'template/wines';

		// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);


	}

	private function _exec_winery($params) {

			// Winery.php

			// Requests
		$winery = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryById',
			'id'	 => $params['winery']

			));

		$ranges = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangesByWineryId',
			'id'	 => $params['winery']

			));

		if (isset($winery['wineries']['error'])) {

			$winery = FALSE;

		}

		if (isset($ranges['wineries']['error'])) {

			$ranges = FALSE;

				// No ranges, so we must display all wines of winery ID
				// We will "re-route"
			$this->_exec_wines_for_winery($params);

				// Stop exec 
			return;


		}

		$this->breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));


		$view_datas = array(

			'winery' => $winery,
			'ranges'  => $ranges,
			'link' => $this->permalink,
			'breadcrumb' => $this->breadcrumb

			);

		$view_to_load = 'template/winery';

			// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);

	}

	private function _exec_range($params) {

		$wines = FALSE;


		$results = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangeById',
			'id'	 => $params['range']

			));

		$success = TRUE;

		if (isset($results['wineries']['error'])) {

			$success = FALSE;

		} else {


			$wines_results = $this->request_api(array(

				'method' => 'wine',
				'action' => 'GetWinesByRangeId',
				'id'	=> $params['range']

				));


			// Check errors
			if (isset($wines_results['wines']['error'])) {

				$wines = FALSE;

			} else {

				// Because the APi sucks and returns a simple array as we got only one wine. 
				if ($wines_results['wines']['wine'][0] === null) {

					// Shortcut 
					$wines[] = $wines_results['wines']['wine'];

				} else {

					// Shortcut 
					$wines = $wines_results['wines']['wine'];

				}


			}

		}


		// Breadcrumb part
		$winery_by_range = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryByRangeId',
			'id'	=> $params['range']

			));


		$this->breadcrumb = wp_vincod_link('winery', $winery_by_range['wineries']['winery']['id'], $winery_by_range['wineries']['winery']['name']);




			// Group wines
		$wines = wp_vincod_group_wines($wines);

		
		$view_datas = array(

			'success' => $success,
			'results' => $results,
			'link'	  => $this->permalink,
			'wines'   => $wines,
			'breadcrumb' => $this->breadcrumb

			);

		$view_to_load = 'template/range';

			// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);


	}

	private function _exec_wine($params) {

		$results = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWineByVincod',
			'id'	=> $params['vincod'],

			));

		$results_years = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetOtherVintagesByVincod',
			'id' => $params['vincod']

			));

		// We will get all the wines from the other years
		$vintageyears = array();
		$inc = 0;

		if (isset($results_years['wines']['wine'])) {


			// Because the APi sucks and returns a simple array as we got only one wine. 
			if ($results_years['wines']['wine'][0] === null) {

				// Shortcut 
				$wines[] = $results_years['wines']['wine'];

			} else {

				// Shortcut 
				$wines = $results_years['wines']['wine'];

			}

			foreach($wines as $results_years_specific) {

				$vintageyears[$inc] = $results_years_specific;


				$inc++;

			}

		}

		$success = TRUE;

		if (isset($results['wines']['error'])) {

			$success = FALSE;

		}

		// Breadcrumb part
		$range = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangeByVincod',
			'id'	=> $params['vincod'],

			));

		$range_id = $range['wineries']['winery']['id'];

		$winery = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineryByRangeId',
			'id'	=> $range_id,

			));


		$this->breadcrumb = wp_vincod_link('range', $range['wineries']['winery']['id'], $range['wineries']['winery']['name']);



		$view_datas = array(

			'success' => $success,
			'wine'	=> $results['wines']['wine'],
			'oldwines' => $vintageyears,
			'link' => $this->permalink,
			'breadcrumb' => $this->breadcrumb

			);

		$view_to_load = 'template/wine';

		// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);

	}

	private function _exec_index() {


		$owner = $this->request_api(array(

			'method' => 'owner',
			'action' => 'GetOwnerById'

			));

		if (isset($owner['owners']['error'])) {

			$owner = FALSE;

		} 

		$results = $this->request_api(array(

			'method' => 'winery',
			'action' => 'GetWineriesByOwnerId'

			));


		if (isset($results['wineries']['error'])) {

			$results = FALSE;

			// No wineries for this owner
			// We will display ranges
			$this->_exec_ranges_for_owner();

			// Stop exec
			return;

		}

		$view_datas = array(

			'owner' => $owner,
			'results' => $results,
			'link' => $this->permalink

			);


		$view_to_load = 'template/index';


		// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);


	}

	private function _exec_ranges_for_owner() {

		$ranges = $this->request_api(array(

			'method' => 'range',
			'action' => 'GetRangesByOwnerId',

			));

		if (isset($ranges['wineries']['error'])) {

			// No ranges
			// Display just wines
			$this->_exec_wines_for_owner();

			return;

		}

		// To do : No error, so display all ranges
		// I didn't see this case right now.

	}

	private function _exec_wines_for_owner() {

		$wines = $this->request_api(array(

			'method' => 'wine',
			'action' => 'GetWinesByOwnerId',

			));


		if (isset($wines['wines']['error'])) {

			$wines = FALSE;

		}

		if ($wines) {

			// Problem with return api datas
			// Sometimes return just one wine without multidimensionnal
			if( ! wp_vincod_is_multi($wines['wines']['wine'][0])) {

				// Re format array
				$tmp = $wines['wines']['wine'];

				unset($wines['wines']['wine']);

				$wines['wines']['wine'][0] = $tmp;

			}

		}


		
		$view_datas = array(

			'wines' => $wines,
			'link' => $this->permalink,
			'breadcrumb' => FALSE

			);



		$view_to_load = 'template/wines';

		// Loader
		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);


	}
	*/



}

?>