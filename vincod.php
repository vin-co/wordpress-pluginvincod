<?php defined('ABSPATH') or exit;

/*
Plugin Name: Vincod
Plugin URI: http://dev.vincod.com/
Description: The « Vincod for WordPress » plugin allows you to instantly create a « Our Wines » section in your WordPress website.
Version: 3.0.0
Author: Vinternet
Author URI: http://www.vinternet.net/
Text Domain: vincod
Domain Path: /languages/
*/

/*
|-------------------------------------------------------------------
| Unable sessions
|-------------------------------------------------------------------
|
| By default wordpress clean all sessions, so you must use this
|
*/
session_start();

/*
|-------------------------------------------------------------------
| Require Files
|-------------------------------------------------------------------
|
| Below the list of require files :
| - constants.php
| 		Constants vars used by the plugin
| - functions.php
|		Common functions used by the plugin
| - controllers/api-controller.php
| 		All about vincod API requests
| - controllers/template-controller.php
| 		The template controller for page "Nos Vins"
*/
require_once('constants.php');
require_once('functions.php');
require_once('controllers/api-controller.php');
require_once('controllers/template-controller.php');

/*
|-------------------------------------------------------------------
| Class exists ?
|-------------------------------------------------------------------
|
| To avoid conflicts with another plugins, we must check
| the current state
|
*/
if(!class_exists('wp_vincod_plugin')) {


	/**
	 * Wp vincod plugin
	 *
	 * The main of the plugin
	 *
	 * @author      Vinternet
	 * @category    Main
	 * @copyright   2023 VINTERNET
	 *
	 */
	class wp_vincod_plugin {

		/**
		 * The customer id API
		 *
		 * @var mixed (false/int)
		 */
		private $_customer_id = false;

		/**
		 * The customer key API
		 *
		 * @var mixed (false/int)
		 */
		private $_customer_api = false;

		/**
		 * The constructor
		 *
		 * @access    public
		 * @return    void
		 *
		 */
		public function __construct() {

			if(!get_option('vincod_language')) {

				add_option('vincod_language', wp_vincod_detect_lang());

			}

			add_action('plugins_loaded', array(__CLASS__, 'load_textdomain'));

			// Triggers
			register_activation_hook(__FILE__, array(&$this, 'install'));
			register_deactivation_hook(__FILE__, array(&$this, 'uninstall'));

			// View dashboard
			add_action('admin_menu', array(&$this, 'add_admin_dashboard'));

			// Redirect the user after plugin install
			add_action('admin_init', 'wp_vincod_redirect_after_install');

			add_action('init', array(&$this, 'rewrite'));

			add_action('wp', array(&$this, 'run'));

			add_action('wp_enqueue_scripts', array(__CLASS__, 'load_jquery'));

		}

		/**
		 * Loads the plugin textdomain
		 */
		public static function load_textdomain() {

			load_plugin_textdomain('vincod', false, dirname(plugin_basename(__FILE__)) . '/languages');

			$GLOBALS['WP_VINCOD_ENTITIES'] = array(
				'owner'       => __('owner', 'vincod'),
				'collection'  => __('collection', 'vincod'),
				'brand'       => __('brand', 'vincod'),
				'range'       => __('range', 'vincod'),
				'product'     => __('product', 'vincod'),
				'vintage'     => __('vintage', 'vincod'),
				'type'        => __('type', 'vincod'),
				'appellation' => __('appellation', 'vincod'),
			);

		}

		/**
		 * Load jQuery
		 *
		 * Make sure that jQuery is loaded
		 *
		 * @return    void
		 */
		public static function load_jquery() {
			if(!wp_script_is('jquery', 'enqueued')) {

				//Enqueue
				wp_enqueue_script('jquery');

			}
		}

		/**
		 * Install
		 *
		 * Method executed when the user install this fabulous plugin
		 *
		 * @access    public
		 * @return    void
		 *
		 */
		public function install() {

			// Clear log if exists
			wp_vincod_clear_log();

			// Check if page "Nos Vins" already exists
			$id = ($vincod_page = get_option('vincod_id_page_nos_vins')) ? $vincod_page : wp_vincod_create_page();

			// Create pending page "Nos Vins"
			if($id !== false) {

				wp_vincod_devlog(__('Install : "Our Wines" Page created', 'vincod'));

				// Stock id
				update_option('vincod_id_page_nos_vins', $id);

				wp_vincod_devlog(__('Install : Page ID successfully stored -> ', 'vincod') . $id);

				// All it's ok, redirect the user to settings
				update_option('wp_vincod_redirect_after_install', true);

			}
			else {

				wp_vincod_die(__('Install Error', 'vincod'), __('Unable to create the "Our Wines" Page', 'vincod'));

			}

			add_option('vincod_setting_cache_api', 1);

			//Add default style settings
			add_option('vincod_setting_display_mode', 'default');
			add_option('vincod_setting_theme', 'default');

			$style_settings = array('has_menu', 'has_breadcrumb', 'has_search', 'has_content', 'has_links', 'has_appellation');
			$templates_names = array('owner', 'collection', 'brand', 'range', 'product', 'search');

			foreach($templates_names as $template_name) {
				$template_settings = array();

				foreach($style_settings as $setting) {
					$template_settings[$setting] = 1;
				}

				add_option('vincod_' . $template_name . '_settings', $template_settings);

			}

			//Add default catalog settings
			$catalog_settings = array('has_vintages', 'has_properties', 'has_types', 'has_appellations', 'has_search');

			$template_settings = array();

			foreach($catalog_settings as $setting) {
				$template_settings[$setting] = 1;
			}

			add_option('vincod_catalog_settings', $template_settings);

		}

		/**
		 * Uninstall
		 *
		 * Method executed when the user uninstall this fabulous plugin
		 *
		 * @access    public
		 * @return    void
		 *
		 */
		public function uninstall() {

			$id = get_option('vincod_id_page_nos_vins');

			$deleted = wp_delete_post($id);

			// Delete language
			delete_option('vincod_language');

			// Delete id stocked
			delete_option('vincod_id_page_nos_vins');

			// Delete API informations
			delete_option('vincod_setting_customer_id');
			delete_option('vincod_setting_customer_api');

			// Delete sitemap
			wp_vincod_delete_sitemap();

			// Delete cache API
			delete_option('vincod_setting_cache_api');

			$templates_names = array('owner', 'collection', 'brand', 'range', 'product', 'search');

			// Delete style options
			foreach($templates_names as $value) {

				delete_option('vincod_' . $value . '_settings');

			}

			// Delete catalog options
			delete_option('vincod_catalog_settings');

			// Delete permalinks
			delete_option('vincod_setting_permalinks');

			// The end of cool story dude.

		}

		public function rewrite() {

			global $wp_rewrite;

			// Get infos about the page
			$page_id = get_option('vincod_id_page_nos_vins');
			$lang_slug = '';

			//If polylang exists, get translated page ID "NOS VINS"
			if(function_exists('pll_current_language')) {
				$page_id = pll_get_post($page_id);

				$lang = pll_current_language();
				$lang_slug = $lang . '/';
			}

			//If qtranslate exists
			if(function_exists('qtrans_getLanguage')) {
				$lang = qtrans_getLanguage();
				$lang_slug = $lang . '/';
			}


			$slug = wp_vincod_get_page_slug($page_id);

			// Here the magic happen
			// We add a rewrite_rule with "top" option
			// to overide the slug system of wordpress for this page
			add_rewrite_rule($lang_slug . $slug . '(.*)', 'index.php?page_id=' . $page_id . '&vincod_request=$matches[1]', 'top');
			add_rewrite_rule($slug . '(.*)', 'index.php?page_id=' . $page_id . '&vincod_request=$matches[1]', 'top');

			// Vincod_request get all chars after the slug
			add_rewrite_tag('%vincod_request%', '(.*)');

			// Save new rules
			$wp_rewrite->flush_rules();

		}

		public function run() {

			$id = get_option('vincod_id_page_nos_vins');

			//If polylang exists, get translated page ID "NOS VINS"
			if(function_exists('pll_current_language')) {
				$id = pll_get_post($id);
			}

			// Page "Nos Vins" ?
			if(is_page($id)) {

				// Apply filters
				add_filter('body_class', 'wp_vincod_body_classes');
				add_filter('the_content', array(&$this, 'template'));

			}

		}

		public function template($content) {

			// Init template controller
			$template_controller = new WP_Vincod_Template_Controller();

			// Run controller
			$template_controller->run();

			// Get the final result
			$content = $template_controller->render();

			return $content;

		}

		/**
		 * Add admin dashboard
		 *
		 * Add new menu in admin settings
		 *
		 * @access    public
		 * @return    void
		 *
		 */
		public function add_admin_dashboard() {

			add_options_page('Vincod settings', 'Vincod', 'manage_options', 'vincod', array(&$this, 'admin_dashboard'));

		}

		/**
		 * Admin dashboard
		 *
		 * Load the dashboard view
		 *
		 * @access    public
		 * @return    void
		 *
		 */
		public function admin_dashboard() {

			// Form system & validation
			if(!empty($_POST)) {
				wp_wincod_post_updates($_POST);
			}

			// We launch our dashboard
			wp_vincod_launch('admin/dashboard');

		}

	}

	/*
	|-------------------------------------------------------------------
	| Run the plugin
	|-------------------------------------------------------------------
	|
	| - Instantiate the plugin
	|
	*/
	$wp_vincod_plugin = new wp_vincod_plugin;


}
else {

	// Set errror
	wp_vincod_die(__('Install Error', 'vincod'), __('Unable to install Vincod Plugin, the <em>wp_vincod_plugin</em> Class already exists', 'vincod'));

}
