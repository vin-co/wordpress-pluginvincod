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

	public function __construct() {

		parent::__construct();


		$this->config(array(

			'_customer_api' => get_option('vincod_setting_customer_api'),
			'_customer_id' => get_option('vincod_setting_customer_id')

			));

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

		// Urls
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

			// Winery.php
			$results = $this->request_api(array(

				'method' => 'winery',
				'action' => 'GetWineryById',
				'id'	 => $params['winery']

				));

			$ranges = $this->request_api(array(

				'method' => 'range',
				'action' => 'GetRangesByWineryId',
				'id'	 => $params['winery']

				));

			$success_results = TRUE;
			$success_ranges = TRUE;

			if (isset($result['wineries']['error'])) {

				$success_results = FALSE;

			}

			if (isset($ranges['wineries']['error'])) {

				$success_ranges = FALSE;

			}

			$view_datas = array(

				'success_results' => $success_results,
				'success_ranges'  => $success_ranges,
				'results' => $results,
				'ranges'  => $ranges,
				'link' => $link_page

				);


			$view_to_load = 'template/winery';


		} elseif ($this->route('range', $params)) {

			// Range.php

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

			$view_datas = array(

				'success' => $success,
				'results' => $results,
				'link'	  => $link_page,
				'wines'   => $wines

				);

			$view_to_load = 'template/range';


		} elseif($this->route('vincod', $params)) {

			// Wine.php

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

			$view_datas = array(

				'success' => $success,
				'wine'	=> $results['wines']['wine'],
				'oldwines' => $vintageyears,
				'link' => $link_page

				);

			$view_to_load = 'template/wine';

		} else {

			// Index.php

			$results = $this->request_api(array(

				'method' => 'winery',
				'action' => 'GetWineriesByOwnerId'

				));

			$success = TRUE;

			if (isset($result['wineries']['error'])) {

				$success = FALSE;

			}

			$view_datas = array(

				'success' => $success,
				'results' => $results,
				'link' => $link_page

				);


			$view_to_load = 'template/index';


		}

		$this->_view_loaded = $this->load_view($view_to_load, $view_datas, TRUE);

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


}

?>