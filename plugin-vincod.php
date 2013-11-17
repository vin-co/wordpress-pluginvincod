<?php defined( 'ABSPATH' ) OR exit;

/*
Plugin Name: Vincod
Plugin URI: http://dev.vincod.com/
Description: /
Version: Beta
Author: Ges Jeremie
Author URI: http://www.gesjeremie.fr/
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
require('constants.php');
require('functions.php');
require('controllers/api-controller.php');
require('controllers/template-controller.php');


if (! class_exists('wp_vincod_plugin')) {


	/**
	 * Wp vincod plugin
	 *
	 * The main of the plugin
	 *
	 * @author		Jeremie Ges
	 * @copyright   2013
	 * @category	Main
	 *
	 */
	class wp_vincod_plugin {

		/**
		 * The customer id API
		 *
		 * @var mixed (false/int)
		 */
		private $_customer_id = FALSE;

		/**
		 * The customer key API
		 *
		 * @var mixed (false/int)
		 */
		private $_customer_api = FALSE;

		/**
		 * The constructor
		 *
		 * @access	public
		 * @return	void
		 * 
		 */
		public function __construct() {

			if ( ! get_option('vincod_language')) {

				add_option('vincod_language', 'fr');

			}

			wp_vincod_load_lang_view('admin/devlog');

			// Triggers
			register_activation_hook(__FILE__, array(&$this, 'install'));
			register_deactivation_hook(__FILE__, array(&$this, 'uninstall'));

			// View dashboard
			add_action('admin_menu', array(&$this, 'add_admin_dashboard'));				

			// Redirect the user after plugin install
			add_action('admin_init', 'wp_vincod_redirect_after_install');

			add_action('init', array(&$this, 'run'));

		}



		/**
		 * Install
		 * 
		 * Method executed when the user install this fabulous plugin
		 *
		 * @access	public
		 * @return	void
		 * 
		 */
		public function install() {

			// Clear log if exists
			wp_vincod_clear_log();

			// Check if page "Nos Vins" already exists
			if (! wp_vincod_exists_page('Plugin Vincod Nos Vins')) {

				$id = wp_vincod_create_page();

				// Create pending page "Nos Vins"
				if ($id !== FALSE) {

					wp_vincod_devlog('Installation : Création de la page "Nos Vins" avec le statut "En attente"');

					// Stock id 
					add_option('vincod_id_page_nos_vins', $id);

					wp_vincod_devlog('Installation : Stockage de l\'identifiant de la page : ' . $id);

					// All it's ok, redirect the user to settings
					add_option('wp_vincod_redirect_after_install', TRUE);


				} else {

					wp_vincod_die('Erreur Installation', 'Impossible de créer la page "Nos Vins" avec le statut "En attente de publication"');

				}



			} else {

				wp_vincod_die('Erreur Installation', 'La page "Nos Vins" existe déjà');

			}

		}

		/**
		 * Uninstall
		 * 
		 * Method executed when the user uninstall this fabulous plugin
		 *
		 * @access	public
		 * @return	void
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

			// Delete style options
			foreach (array('size_text', 'size_h2', 'picture_height', 'picture_width', 'color_general_text', 'color_titles_text') as $value) {

				delete_option('vincod_setting_' . $value);

			}

			// Delete permalinks
			delete_option('vincod_setting_permalinks');
			
			// The end of cool story dude.

		}


		public function run() {
			
			
			global $wp_rewrite;

			// Get infos about the page
			$page_id = get_option('vincod_id_page_nos_vins');
			$slug = wp_vincod_get_page_slug( $page_id );

			// Here the magic happen
			// We add a rewrite_rule with "top" option 
			// to overide the slug system of wordpress for this page
			add_rewrite_rule($slug . '(.*)', 'index.php?page_id=' . $page_id . '&request_wines=$matches[1]', 'top');

			// Request_wines get all chars after the slug
			add_rewrite_tag('%request_wines%','(.*)');

			// Save new rules
			$wp_rewrite->flush_rules();
		

			add_filter('the_content', array(&$this, 'template'));
			

		}



		public function template($content) {

			$id = get_option('vincod_id_page_nos_vins');

			// Page "Nos Vins" ?
			if (is_page($id)) {

				// Init template controller
				$template_controller = new wp_vincod_controller_template();

				// Run controller
				$template_controller->run();

				// Get the final result
				$content = $template_controller->render();	


			}

			return $content;

		}

		/**
		 * Add admin dashboard
		 * 
		 * Add new menu in admin settings
		 *
		 * @access	public
		 * @return	void
		 * 
		 */
		public function add_admin_dashboard() {

			if (!get_option('vincod_language')) {

				$language = get_bloginfo('language');
				
				if ($language === 'fr-FR') $language = 'fr';
				else $language = 'en';

				update_option('vincod_language', $language);

			} else {

				$language = get_option('vincod_language');

			}

			wp_vincod_view_var('language', $language);

			add_options_page('Vincod settings', 'Vincod', 'manage_options', 'vincod', array(&$this, 'admin_dashboard'));				

		}

		/**
		 * Admin dashboard
		 * 
		 * Load the dashboard view
		 *
		 * @access	public
		 * @return	void
		 * 
		 */
		public function admin_dashboard() {

			// Form system & validation
			if (isset($_POST)) wp_wincod_post_updates($_POST);

			wp_vincod_check_exists_sitemap();

			// We load our vincod devlog system and put it within our view
			wp_vincod_devlog_inject_within_view();

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



} else {

	// Set errror
	wp_vincod_die('Erreur installation', 'Impossible d\'installer le plugin vincod, la class <em>wp_vincod_plugin</em> existe déjà');

}


?>