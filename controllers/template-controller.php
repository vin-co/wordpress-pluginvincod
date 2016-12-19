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
			'_customer_id' => get_option('vincod_setting_customer_id'),
			'_customer_winery_id' => get_option('vincod_setting_customer_winery_id')

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

			if ($split[0] == 'collection') {

				// Winery case
				$out = array('family' => $split[1]);

			} elseif ($split[0] == 'brand') {

				// Winery case
				$out = array('winery' => $split[1]);

			} elseif ($split[0] == 'range') {

				// Range case
				$out = array('range' => $split[1]);

			} elseif ($split[0] == 'product') {

				// Wine case
				$out = array('wine' => $split[1]);

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
		if ($this->route('family', $params) || $this->route('collection', $params)) {

			$this->_exec_family($params);

		} elseif ($this->route('winery', $params) || $this->route('brand', $params)) {

			$this->_exec_winery($params);

		} elseif ($this->route('range', $params)) {

			$this->_exec_range($params);

		} elseif($this->route('wine', $params) || $this->route('product', $params)) {

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

		$custom_view_path = WP_VINCOD_CURRENT_THEME_PATH . 'vincod/' . $file . '.php';

		// Load vars lang
		wp_vincod_load_lang_view('template/'.$file, wp_vincod_detect_lang());

		$view_datas = array_merge($view_datas, $GLOBALS['wp_vincod_views_datas']);

		if (file_exists($custom_view_path)) {

			$view = wp_vincod_load_view($file, $view_datas, $return, WP_VINCOD_CURRENT_THEME_PATH . 'vincod');

		} else {

			$view = wp_vincod_load_view($file, $view_datas, $return,  WP_VINCOD_PLUGIN_PATH . 'views/template/');

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
		$families = $this->get_families_by_owner_id();
		$wineries = $this->get_wineries_by_owner_id();
		//Si une seule winery alors on cache le owner et on renvoit au template winery
		if (get_option('vincod_setting_customer_winery_id')) {
			$this->_exec_winery(array('winery'=>get_option('vincod_setting_customer_winery_id')));
			return;
		}

		$view_datas = array(

			'owner' => $owner['owners']['owner'],
			'families' => $families,
			'wineries' => $wineries,
			'link' => $this->permalink

			);
		// Loader
		$this->_view_loaded = $this->load_view('index', $view_datas, TRUE);

	}

	// Family
	private function _exec_family($params) {
		$family = $this->get_family_by_id($params['family']);
		$wineries = $this->get_wineries_by_family_id($params['family']);

		$breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));

		$view_datas = array(

			'family' => $family['families']['family'],
			'wineries' => $wineries,
			'breadcrumb' => $breadcrumb,
			'link' => $this->permalink

		);

		$this->_view_loaded = $this->load_view('family', $view_datas, TRUE);

	}

	// Winery + Ranges
	private function _exec_winery($params) {
		$winery = $this->get_winery_by_id($params['winery']);
		$ranges = $this->get_ranges_by_winery_id($params['winery']);
		$wines = $this->get_wines_by_winery_id($params['winery']);
		$family = $this->get_family_by_winery_id($params['winery']);
		$breadcrumb = ($family) ? wp_vincod_link('collection', $family['families']['family']['vincod'], $family['families']['family']['name']) : get_permalink(get_option('vincod_id_page_nos_vins'));

		// Group wines
		if ($wines) {

			// Shortcut for template
			$wines = $wines['wines']['wine'];

			$wines = wp_vincod_group_wines($wines);


		}

		$view_datas = array(

			'winery' => $winery['wineries']['winery'],
			'ranges' => $ranges,
			'wines' => $wines,
			'breadcrumb' => $breadcrumb,
			'link' => $this->permalink

			);

		$this->_view_loaded = $this->load_view('winery', $view_datas, TRUE);

	}

	private function _exec_range($params) {

		$range = $this->get_range_by_id($params['range']);
		$wines = $this->get_wines_by_range_id($params['range']);
		$winery = $this->get_winery_by_range_id($params['range']);

		$breadcrumb = wp_vincod_link('winery', $winery['wineries']['winery']['id'], $winery['wineries']['winery']['name']);


		if ($wines) {

			// Shortcut for template
			$wines = $wines['wines']['wine'];
			// Group wines
			$wines = wp_vincod_group_wines($wines);

		}

		$view_datas = array(

			'range' => $range['wineries']['winery'],
			'link'	  => $this->permalink,
			'wines'   => $wines,
			'breadcrumb' => $breadcrumb

			);

		// Loader
		$this->_view_loaded = $this->load_view('range', $view_datas, TRUE);


	}

	private function _exec_wine($params) {

		$wine = $this->get_wine_by_vincod($params['wine']);
		$vintages = $this->get_other_vintages_by_vincod($params['wine']);

		// Breadcrumb part

		$range = $this->get_range_by_vincod($params['wine']);
		$winery = $this->get_winery_by_vincod($params['wine']);

		if ($range) {

			$breadcrumb = wp_vincod_link('range', $range['wineries']['winery']['id'], $range['wineries']['winery']['name']);
			$parent = $range['wineries']['winery'];

		} elseif ($winery) {

			$breadcrumb = wp_vincod_link('brand', $winery['wineries']['winery']['id'], $winery['wineries']['winery']['name']);
			$parent = $winery['wineries']['winery'];

		} else {

			$breadcrumb = get_permalink(get_option('vincod_id_page_nos_vins'));
			$customer_id = get_option('vincod_setting_customer_id');
			$customer_winery_id = get_option('vincod_setting_customer_winery_id');
			$parent =  ($customer_winery_id) ? $customer_winery_id : $customer_id;

		}

		$view_datas = array(

			'wine'	=> $wine['wines']['wine'],
			'oldwines' => $vintages['wines']['wine'],
			'link' => $this->permalink,
			'breadcrumb' => $breadcrumb,
			'parent' => $parent

			);

		// Loader
		$this->_view_loaded = $this->load_view('wine', $view_datas, TRUE);


	}

}

?>
