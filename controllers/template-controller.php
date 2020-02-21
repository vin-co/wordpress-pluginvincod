<?php

/**
 * Wp controller vincod template
 *
 * Called by template() method in wp_plugin_vincod main Class
 *
 *
 * @author       Vinternet
 * @category     Controller
 * @copyright    2016 VINTERNET
 *
 */
class WP_Vincod_Template_Controller extends WP_Vincod_API {

	/**
	 * The content of view loaded
	 *
	 * @var string
	 */
	private $_view_loaded = '';

	public $permalink = '';

	public function __construct() {

		parent::__construct();

		// Get the right permalink
		$this->permalink = get_permalink();

	}


	/**
	 * Run
	 *
	 * Get params by the $_GET var
	 * and launch the router
	 *
	 * @return    void
	 */
	public function run() {

		global $wp_query, $wp_rewrite;

		if(wp_vincod_is_not_empty($_POST)) {

			$params = $_POST;

		}

		else {

			$permalinks_used = $wp_rewrite->using_permalinks();

			if($permalinks_used === true) {

				$request_wines = $wp_query->query_vars['vincod_request'];
				$params = $this->understand_request($request_wines);

				// If no params, check with GET
				if(empty($params)) {
					$params = $_GET;
				}

			}
			else {

				$params = $_GET;

			}
		}

		$this->router($params);

	}


	/**
	 * Understand Request
	 *
	 * Where the request is parsed
	 *
	 * @param $request
	 *
	 * @return array
	 */
	public function understand_request($request) {

		// Init final params
		$out = array();
		$request = ltrim($request, '/');
		$split = explode('-', $request);


		// Right request ?
		if(isset($split[0]) && !empty($split[0]) && isset($split[1]) && !empty($split[1])) {

			if($split[0] == 'collection' || $split[0] == 'family') {

				// Collection case
				$out = array('collection' => $split[1]);

			}
			elseif($split[0] == 'marque' || $split[0] == 'brand' || $split[0] == 'winery') {

				// Brand case
				$out = array('brand' => $split[1]);

			}
			elseif($split[0] == 'gamme' || $split[0] == 'range') {

				// Range case
				$out = array('range' => $split[1]);

			}
			elseif($split[0] == 'produit' || $split[0] == 'product' || $split[0] == 'wine') {

				// Product case
				$out = array('product' => $split[1]);

			}

		}

		return $out;

		// Urls (note)
		// Brand ->  /{slug}/brand-51-chateau-sainte-roseline
		// Range ->   /{slug}/range-455-chapelle-de-sainte-roseline
	}


	/**
	 * Router
	 *
	 * Serve the right template page
	 *
	 * Routes :
	 *  - none   : template/index.php
	 *  - collection : template/collection.php
	 *  - brand : template/brand.php
	 *  - range  : template/range.php
	 *  - product   : template/product.php
	 *
	 * @return    void
	 */
	public function router($params) {

		// The base link

		// Simple routing system
		if($this->route('collection', $params) || $this->route('family', $params)) {
			$params['collection'] = (isset($params['family'])) ? $params['family'] : $params['collection'];

			$this->_exec_collection($params);

		}
		elseif($this->route('marque', $params) || $this->route('brand', $params) || $this->route('winery', $params)) {
			if(isset($params['marque'])) {
				$params['brand'] = $params['marque'];
			}
			elseif(isset($params['winery'])) {
				$params['brand'] = $params['winery'];
			}

			$this->_exec_brand($params);

		}
		elseif($this->route('gamme', $params) || $this->route('range', $params)) {
			$params['range'] = (isset($params['gamme'])) ? $params['gamme'] : $params['range'];

			$this->_exec_range($params);

		}
		elseif($this->route('produit', $params) || $this->route('product', $params) || $this->route('wine', $params)) {
			if(isset($params['produit'])) {
				$params['product'] = $params['produit'];
			}
			elseif(isset($params['wine'])) {
				$params['product'] = $params['wine'];
			}

			$this->_exec_product($params);

		}
		elseif($this->route('search_wine', $params)) {

			$this->_exec_search($params);

		}
		else {

			$this->_exec_index();

		}

	}


	/**
	 * Route
	 *
	 * Check if it's the right route
	 *
	 * @return    bool
	 */
	public function route($route, $params) {

		return (isset($params[$route]) && !empty($params[$route]));

	}


	/**
	 * Load view
	 *
	 * A simple hook of wp_vincod_load_view to accept custom view in
	 * the current theme folder
	 *
	 * @return    string
	 */
	public function load_view($file, array $view_datas = array(), $return = true) {

		$custom_view_path = WP_VINCOD_CURRENT_THEME_PATH . 'vincod/' . $file . '.php';

		$view_datas = array_merge($view_datas, $GLOBALS['wp_vincod_views_datas']);

		if(file_exists($custom_view_path)) {

			$view = wp_vincod_load_view($file, $view_datas, $return, WP_VINCOD_CURRENT_THEME_PATH . 'vincod');

		}
		else {

			$theme = get_option('vincod_setting_theme', 'default');

			$view = wp_vincod_load_view($file, $view_datas, $return, WP_VINCOD_PLUGIN_PATH . 'views/themes/' . $theme . '/');

		}

		return $view;

	}


	/**
	 * Render
	 *
	 * Return the view loaded by the system
	 *
	 * @return    string
	 */
	public function render() {

		return $this->_view_loaded;

	}

	/* -------------------------------------------------------------- */


	/**
	 * Exec Index
	 *
	 * Render the view for Owners or Brands
	 *
	 * @return void
	 */
	private function _exec_index() {

		$owner = $this->get_owner_by_id();
		$collections = $this->get_families_by_owner_id();
		$brands = $this->get_wineries_by_owner_id();

		// Brand redirection if single brand exists
		if($brand = get_option('vincod_setting_customer_winery_id')) {
			$this->_exec_brand(array('brand' => $brand));

			return;
		}

		$menu = ($owner) ? wp_vincod_get_menu($owner['vincod']) : '';

		$breadcrumb = ($owner) ? wp_vincod_get_breadcrumb($owner['vincod']) : '';

		if(!empty($owner['fields']['presentation'])) {
			$owner['presentation'] = $owner['fields']['presentation']['value'];
		}

		if(!empty($owner['certifications'])) {
			$owner['certifications'] = $owner['certifications']['certification'];
		}

		$view_datas = array(

			'owner'       => $owner,
			'collections' => ($collections) ? array_map(function($collection) {
				if(!empty($collection['fields']['presentation'])) {
					$collection['presentation'] = $collection['fields']['presentation']['value'];
				}

				return $collection;
			}, $collections) : false,
			'brands'      => ($brands) ? array_map(function($brand) {
				if(!empty($brand['presentation'])) {
					$brand['presentation'] = $brand['presentation']['value'];
				}

				return $brand;
			}, $brands) : false,
			'link'        => $this->permalink,
			'settings'    => get_option('vincod_owner_settings'),
			'menu'        => $menu,
			'breadcrumb'  => $breadcrumb,
			'search_form' => wp_vincod_get_search_form()

		);
		// Loader
		$this->_view_loaded = $this->load_view('index', $view_datas, true);

	}


	/**
	 * Exec Collection
	 *
	 * Render the view for Collections
	 *
	 * @param $params
	 *
	 * @return void
	 */
	private function _exec_collection($params) {
		$collection = $this->get_family_by_id($params['collection']);
		$brands = $this->get_wineries_by_family_id($params['collection']);

		$menu = ($collection) ? wp_vincod_get_menu($collection['vincod']) : '';

		$breadcrumb = ($collection) ? wp_vincod_get_breadcrumb($collection['vincod']) : '';

		if(!empty($collection['fields']['presentation'])) {
			$collection['presentation'] = $collection['fields']['presentation']['value'];
		}

		if(!empty($collection['certifications'])) {
			$collection['certifications'] = $collection['certifications']['certification'];
		}

		$view_datas = array(

			'collection'  => $collection,
			'brands'      => ($brands) ? array_map(function($brand) {
				if(!empty($brand['presentation'])) {
					$brand['presentation'] = $brand['presentation']['value'];
				}

				return $brand;
			}, $brands) : false,
			'link'        => $this->permalink,
			'settings'    => get_option('vincod_collection_settings'),
			'menu'        => $menu,
			'breadcrumb'  => $breadcrumb,
			'search_form' => wp_vincod_get_search_form()

		);

		$this->_view_loaded = $this->load_view('collection', $view_datas, true);

	}


	/**
	 * Exec Brand
	 *
	 * Render the view for Brands
	 *
	 * @param $params
	 *
	 * @return void
	 */
	private function _exec_brand($params) {
		$brand = $this->get_winery_by_id($params['brand']);
		$ranges = $this->get_ranges_by_winery_id($params['brand']);
		$products = $this->get_wines_by_winery_id($params['brand']);

		// Group products
		$products = ($products) ? wp_vincod_group_products($products) : $products;

		$menu = ($brand) ? wp_vincod_get_menu($brand['vincod']) : '';

		$breadcrumb = ($brand) ? wp_vincod_get_breadcrumb($brand['vincod']) : '';

		if(!empty($brand['presentation'])) {
			$brand['presentation'] = $brand['presentation']['value'];
		}

		if(!empty($brand['certifications'])) {
			$brand['certifications'] = $brand['certifications']['certification'];
		}

		$settings = get_option('vincod_brand_settings');
		$appellations = array();

		if($settings['has_appellation'] && !empty($products)) {

			$raw_appellations = $this->get_appellation_by_winery($params['brand']);

			if(!empty($raw_appellations)) {

				foreach($raw_appellations as $appellation) {

					$appellation_products = array();

					foreach($products as $product) {

						if(!empty($product['appellation']) && $product['appellation'] == $appellation['name']) {
							$appellation_products[] = $product;
						}
					}

					if(!empty($appellation_products)) {

						$appellations[] = array(
							'name'     => $appellation['name'],
							'products' => $appellation_products,
						);
					}
				}
			}
		}

		$view_datas = array(

			'brand'        => $brand,
			'ranges'       => ($ranges) ? array_map(function($range) {
				if(!empty($range['presentation'])) {
					$range['presentation'] = $range['presentation']['value'];
				}

				return $range;
			}, $ranges) : false,
			'products'     => $products,
			'appellations' => $appellations,
			'link'         => $this->permalink,
			'settings'     => $settings,
			'menu'         => $menu,
			'breadcrumb'   => $breadcrumb,
			'search_form'  => wp_vincod_get_search_form()

		);

		$this->_view_loaded = $this->load_view('brand', $view_datas, true);

	}


	/**
	 * Exec Range
	 *
	 * Render the view for Ranges
	 *
	 * @param $params
	 *
	 * @return void
	 */
	private function _exec_range($params) {
		$range = $this->get_range_by_id($params['range']);
		$products = $this->get_wines_by_range_id($params['range']);

		// Group products
		$products = ($products) ? wp_vincod_group_products($products) : $products;

		$menu = ($range) ? wp_vincod_get_menu($range['vincod']) : '';

		$breadcrumb = ($range) ? wp_vincod_get_breadcrumb($range['vincod']) : '';

		if(!empty($range['presentation'])) {
			$range['presentation'] = $range['presentation']['value'];
		}

		if(!empty($range['certifications'])) {
			$range['certifications'] = $range['certifications']['certification'];
		}

		$settings = get_option('vincod_range_settings');
		$appellations = array();

		if($settings['has_appellation'] && !empty($products)) {

			$raw_appellations = $this->get_appellation_by_range($params['range']);

			if(!empty($raw_appellations)) {

				foreach($raw_appellations as $appellation) {

					$appellation_products = array();

					foreach($products as $product) {

						if(!empty($product['appellation']) && $product['appellation'] == $appellation['name']) {
							$appellation_products[] = $product;
						}
					}

					if(!empty($appellation_products)) {

						$appellations[] = array(
							'name'     => $appellation['name'],
							'products' => $appellation_products,
						);
					}
				}
			}
		}

		$view_datas = array(

			'range'        => $range,
			'products'     => $products,
			'appellations' => $appellations,
			'link'         => $this->permalink,
			'settings'     => $settings,
			'menu'         => $menu,
			'breadcrumb'   => $breadcrumb,
			'search_form'  => wp_vincod_get_search_form()

		);

		// Loader
		$this->_view_loaded = $this->load_view('range', $view_datas, true);

	}


	/**
	 * Exec Product
	 *
	 * Render the view for Products
	 *
	 * @param $params
	 *
	 * @return void
	 */
	private function _exec_product($params) {
		$product = $this->get_wine_by_vincod($params['product']);
		$vintages = $this->get_other_vintages_by_vincod($params['product']);

		$menu = ($product) ? wp_vincod_get_menu($product['parentid']) : '';

		$breadcrumb = ($product) ? wp_vincod_get_breadcrumb($product['parentid']) : '';

		if(!empty($product['fields']['presentation'])) {
			$product['presentation'] = (!wp_vincod_is_multi($product['fields']['presentation'])) ? array($product['fields']['presentation']) : $product['fields']['presentation'];
		}

		if(!empty($product['fields']['specifications'])) {
			$product['specifications'] = (!wp_vincod_is_multi($product['fields']['specifications'])) ? array($product['fields']['specifications']) : $product['fields']['specifications'];
		}

		if(!empty($product['fields']['advice'])) {
			$product['advice'] = (!wp_vincod_is_multi($product['fields']['advice'])) ? array($product['fields']['advice']) : $product['fields']['advice'];
		}

		if(!empty($product['reviews']['review'])) {
			$product['reviews'] = (!wp_vincod_is_multi($product['reviews']['review'])) ? array($product['reviews']['review']) : $product['reviews']['review'];
		}

		if(!empty($product['recipes']['recipe'])) {
			$product['recipes'] = (!wp_vincod_is_multi($product['recipes']['recipe'])) ? array($product['recipes']['recipe']) : $product['recipes']['recipe'];
		}

		if(!empty($product['products']['product'])) {
			$product['products'] = (!wp_vincod_is_multi($product['products']['product'])) ? array($product['products']['product']) : $product['products']['product'];
		}

		if(!empty($product['shops']['shop'])) {
			$product['shops'] = (!wp_vincod_is_multi($product['shops']['shop'])) ? array($product['shops']['shop']) : $product['shops']['shop'];
		}

		if(!empty($product['grapesvarieties']['variety'])) {
			$product['grapesvarieties'] = (!wp_vincod_is_multi($product['grapesvarieties']['variety'])) ? array($product['grapesvarieties']['variety']) : $product['grapesvarieties']['variety'];
		}

		if(!empty($product['medias']['media'])) {
			$product['medias'] = (!wp_vincod_is_multi($product['medias']['media'])) ? array($product['medias']['media']) : $product['medias']['media'];
		}

		if(!empty($product['certifications'])) {
			$product['certifications'] = $product['certifications']['certification'];
		}

		$view_datas = array(

			'product'     => $product,
			'vintages'    => $vintages,
			'link'        => $this->permalink,
			'settings'    => get_option('vincod_product_settings'),
			'menu'        => $menu,
			'breadcrumb'  => $breadcrumb,
			'search_form' => wp_vincod_get_search_form()

		);

		// Loader
		$this->_view_loaded = $this->load_view('product', $view_datas, true);

	}

	/**
	 * Exec Search
	 *
	 * Render the view for Search
	 *
	 * @param $params
	 *
	 * @return void
	 */
	private function _exec_search($params) {

		if(!isset($params['wp_vincod_search_nonce']) && !wp_verify_nonce($params['wp_vincod_search_nonce'], 'wp_vincod_search_form')) {

			$this->_exec_index();

			return;

		}

		$search = $params['search_wine'];

		$search = wp_unslash($search);
		$search = wp_vincod_xss_clean($search);
		$search = strip_tags($search);
		$search = sanitize_text_field($search);
		$search = esc_html($search);

		$search_encoded = sanitize_user($search, true);
		$search_encoded = filter_var($search_encoded, FILTER_SANITIZE_ENCODED);

		$error = false;
		$products = $this->get_wines_by_search($search_encoded);

		if(isset($products['error'])) {
			$error = $products['error'];
			$products = false;
		}

		// Group products
		$products = ($products) ? wp_vincod_group_products($products) : $products;

		$menu = wp_vincod_get_menu();

		$view_datas = array(

			'error'       => $error,
			'search'      => $search,
			'products'    => $products,
			'link'        => $this->permalink,
			'settings'    => get_option('vincod_search_settings'),
			'menu'        => $menu,
			'search_form' => wp_vincod_get_search_form()

		);

		// Loader
		$this->_view_loaded = $this->load_view('search', $view_datas, true);

	}


}
