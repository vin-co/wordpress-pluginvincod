<?php defined('ABSPATH') OR exit;
/**
 * Plugin Admin functions
 *
 * All the admin functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2016 VINTERNET
 *
 */


/**
 * Here we treat all the $_POST updates
 *
 * @param array $post should be $_POST or similar (such as $_GET)
 *
 * @return void
 */
function wp_wincod_post_updates(array $post) {

	if(!isset($post['wp_vincod_admin_nonce']) && !wp_verify_nonce($post['wp_vincod_admin_nonce'], 'wp_vincod_admin_form')) {
		return;
	}

	// Our container
	$cleaned_post = array();

	foreach($post as $entry_label => $entry_value) {
		if(is_array($post[$entry_label])) {
			foreach($post[$entry_label] as $sub_entry_label => $sub_entry_value) {
				$cleaned_post[$entry_label][$sub_entry_label] = wp_vincod_xss_clean($sub_entry_value);
			}
		}
		else {
			$cleaned_post[$entry_label] = wp_vincod_xss_clean($entry_value);
		}

	}

	// We want to change the language
	if(isset($cleaned_post['vincod_language'])) {

		if($cleaned_post['vincod_language'] === 'Français')
			$vincod_language = 'fr';
		else $vincod_language = 'en';

		update_option('vincod_language', $vincod_language);
		wp_vincod_view_var('language', $vincod_language);

	}


	// We want to change our settings (Numéro client / Clé API)
	if(isset($cleaned_post['vincod_setting_customer_id']) && (isset($cleaned_post['vincod_setting_customer_api']))) {


		if(isset($cleaned_post['vincod_setting_remove'])) {

			// Delete informations about api
			delete_option('vincod_setting_customer_id');
			delete_option('vincod_setting_customer_api');
			delete_option('vincod_setting_customer_winery_id');

			wp_vincod_devlog(__("We deleted the API credentials.", 'vincod'));

			// Switch page "Nos Vins" (publish -> pending)
			wp_vincod_switch_page(get_option('vincod_id_page_nos_vins'), 'pending');

			wp_vincod_devlog(__("The page 'Our Wines' status was changed : Waiting to be published.", 'vincod'));

		}
		else {

			if(!empty($cleaned_post['vincod_setting_customer_id']) && !empty($cleaned_post['vincod_setting_customer_api'])) {

				$customer_id = $cleaned_post['vincod_setting_customer_id'];
				$customer_api = $cleaned_post['vincod_setting_customer_api'];
				$customer_winery_id = $cleaned_post['vincod_setting_customer_winery_id'];


				$tested = wp_vincod_test_api($customer_id, $customer_api);

				if($tested) {

					// Update options
					update_option('vincod_setting_customer_id', $customer_id);
					update_option('vincod_setting_customer_api', $customer_api);
					update_option('vincod_setting_customer_winery_id', $customer_winery_id);

					// Switch page "Nos vins" (pending -> publish)
					wp_vincod_switch_page(get_option('vincod_id_page_nos_vins'), 'publish');

					wp_vincod_devlog(__("We updated your API credentials.", 'vincod'));
					wp_vincod_devlog(__("The page 'Our Wines' is now published.", 'vincod'));

				}
				else {

					wp_vincod_die(__('Credentials Update', 'vincod'), __("Unable to connect to the API with these credentials", 'vincod'));

				}

			}
			else {

				wp_vincod_die(__('Credentials Update', 'vincod'), __("You must enter your Vincod credentials", 'vincod'));

			}
		}
	}


	// API cache
	if(isset($cleaned_post['vincod_setting_cache_api'])) {

		if(isset($cleaned_post['vincod_clear_cache'])) {

			array_map('unlink', (glob(WP_VINCOD_PLUGIN_PATH . 'cache/api/*.json') ? glob(WP_VINCOD_PLUGIN_PATH . 'cache/api/*.json') : array()));

			wp_vincod_devlog(__("Cache was cleared.", 'vincod'));

		}
		else {

			// Update
			update_option('vincod_setting_cache_api', $cleaned_post['vincod_setting_cache_api']);

			wp_vincod_devlog(__("Cache duration was updated.", 'vincod'));

		}

	}


	// Styles
	if(isset($cleaned_post['vincod_setting_theme'])) {

		update_option('vincod_setting_theme', $cleaned_post['vincod_setting_theme']);

		wp_vincod_devlog(__("Theme was updated :", 'vincod'), $cleaned_post['vincod_setting_theme']);

	}

	foreach(array('owner', 'collection', 'brand', 'range', 'product', 'search') as $value) {

		if(isset($cleaned_post['vincod_' . $value . '_settings'])) {

			$setting = $cleaned_post['vincod_' . $value . '_settings'];

			update_option('vincod_' . $value . '_settings', $setting);

			wp_vincod_devlog(__("Value was updated :", 'vincod'), ' ' . ucwords($value) . ' settings');

		}

	}


	// Seo (create action)
	if(isset($cleaned_post['vincod_seo_create'])) {

		$id = get_option('vincod_setting_customer_id');
		$api = get_option('vincod_setting_customer_api');

		if(($id === false && empty($id)) OR ($api === false && empty($api))) {

			wp_vincod_die(__('Sitemap Error', 'vincod'), __("You must enter your Vincod credentials", 'vincod'));

		}

		$success = wp_vincod_test_api($id, $api);

		if($success === false) {

			wp_vincod_die(__('Sitemap Error', 'vincod'), __("Unable to connect to the API with these credentials", 'vincod'));


		}

		wp_vincod_create_sitemap($id, $api);
		wp_vincod_devlog(__("Sitemap generation ...", 'vincod'));

	}

	// Seo (delete action)
	if(isset($cleaned_post['vincod_seo_delete'])) {

		wp_vincod_devlog(__("Sitemap was removed.", 'vincod'));
		wp_vincod_delete_sitemap();

	}


	// Clear the devlog system
	if(isset($cleaned_post['vincod_clear_log'])) {

		wp_vincod_clear_log();

	}


	// Reset APP
	if(isset($cleaned_post['vincod_reset_app'])) {

		// True -> Log system
		wp_vincod_reset_app();

		wp_vincod_view_var('app_cleaned', true);

	}

}


/**
 * Check if the wordpress user uses permalinks
 *
 * @return bool
 */
function wp_vincod_permalinks_used() {

	global $wp_rewrite;
	$permalinks_used = $wp_rewrite->using_permalinks();

	return $permalinks_used;
}


/**
 * Load our CSS styles within our views
 *
 * @return void
 */
function wp_vincod_load_styles() {

	global $wp_styles;

	// Add styles
	$wp_styles->add('vincod-styles', WP_VINCOD_PLUGIN_URL . 'assets/css/admin.css');

	// And put enqueue
	$wp_styles->enqueue(array('vincod-styles'));

}


/**
 * Load our JS scripts within our views
 *
 * @return void
 */
function wp_vincod_load_scripts() {

	global $wp_scripts;

	// Add scripts
	$wp_scripts->add('vincod-vendor', WP_VINCOD_PLUGIN_URL . 'assets/js/vendor.js');
	$wp_scripts->add('vincod-admin', WP_VINCOD_PLUGIN_URL . 'assets/js/admin.js');

	// And put enqueue
	$wp_scripts->enqueue(array('vincod-vendor', 'vincod-admin'));

}


/**
 * Create new page Nos Vins
 *
 * @return bool|int
 */
function wp_vincod_create_page() {

	// Create new page with pending statut
	// Create new page with pending statut
	$created = wp_insert_post(array(

		'comment_status' => 'closed',
		'ping_status'    => 'closed',
		'post_name'      => __('our-wines', 'vincod'),
		'post_status'    => 'pending',
		'post_title'     => __('Our Wines (powered by Vincod)', 'vincod'),
		'post_type'      => 'page',
		'post_content'   => __("This page is used by the Vincod Plugin. Please don't modify it.", 'vincod')

	), true);

	if(isset($created->errors)) {

		return false;

	}
	else {

		// Return id
		return $created;

	}

}


/**
 * Called when the user submit reset app form
 *
 * @return void
 */
function wp_vincod_reset_app() {

	$style_settings = array('has_menu', 'has_search', 'has_content', 'has_links');
	$templates_names = array('owner', 'collection', 'brand', 'range', 'product');

	// Delete sessions of log system
	wp_vincod_clear_log();

	wp_vincod_devlog(__("We are resetting the plugin ...", 'vincod'));

	// Delete language
	delete_option('vincod_language');

	// Delete page
	wp_vincod_delete_page(get_option('vincod_id_page_nos_vins'));

	wp_vincod_devlog(__("The page 'Our Wines' was removed.", 'vincod'));

	// Delete sitemap
	wp_vincod_devlog(__("Sitemap was removed.", 'vincod'));
	wp_vincod_delete_sitemap();

	// Delete cache API
	wp_vincod_devlog(__("Cache setting was removed.", 'vincod'));
	delete_option('vincod_setting_cache_api');

	// Delete style options
	delete_option('vincod_setting_theme');

	foreach($templates_names as $value) {

		delete_option('vincod_' . $value . '_settings');

	}
	wp_vincod_devlog(__("Style options were removed.", 'vincod'));

	// Re-create page
	$created = wp_vincod_create_page();

	wp_vincod_devlog(__("The page 'Our Wines' was created again.", 'vincod'));

	// Stock id of this page
	update_option('vincod_id_page_nos_vins', $created);

	wp_vincod_devlog(__("We saved the new Page ID of 'Our Wines' : ", 'vincod'), $created);

	// Delete options API
	delete_option('vincod_setting_customer_id');
	delete_option('vincod_setting_customer_api');

	wp_vincod_devlog(__("We deleted the API credentials.", 'vincod'));

	add_option('vincod_setting_cache_api', 1);

	foreach($templates_names as $template_name) {
		$template_settings = array();

		foreach($style_settings as $setting) {
			$template_settings[$setting] = 1;
		}

		add_option('vincod_' . $template_name . '_settings', $template_settings);

	}

	wp_vincod_devlog(__("We reset style settings.", 'vincod'));

	wp_vincod_devlog(__("End of the plugin reset !", 'vincod'));

}


/**
 * Redirect the user in the dashboard after install
 *
 * @return void
 */
function wp_vincod_redirect_after_install() {

	if(get_option('vincod_redirect_after_install', false)) {
		delete_option('vincod_redirect_after_install');
		wp_redirect('options-general.php?page=vincod');
	}

}


/**
 * DEVLOG SYSTEM
 * It will add the entire process of a page generation within a session
 * Then we can put it in our database or do whatever we want ; flexibility man.
 *
 * A simple devlog() without arg will output the entire devlog process
 *
 * @param string $new_entry
 *
 * @return bool|array
 */
function wp_vincod_devlog($new_entry = null, $details = '') {

	$new_entry .= $details;

	if($new_entry === null) {

		return $_SESSION['devlog'];

	}
	else {

		$_SESSION['devlog'][] = array(

			'time' => time(),
			'msg'  => $new_entry,
			'ip'   => $_SERVER['REMOTE_ADDR'] // We won't use it but still.

		);

		return true;

	}

}


/**
 * DEVLOG VIEW INJECTION
 * We pass through the function to put our devlog session variables within a view variable
 *
 * @return void
 */
function wp_vincod_devlog_inject_within_view() {
	if(isset($_SESSION['devlog']))
		wp_vincod_view_var('devlog_content', $_SESSION['devlog']);
}


/**
 * Clear the log
 *
 * @return void
 */
function wp_vincod_clear_log() {

	if(isset($_SESSION['devlog'])) {

		unset($_SESSION['devlog']);

	}

}


/**
 * Check if you can deal with the API
 *
 * @param int  The customer id API
 * @param string The customer key API
 *
 * @return bool
 */
function wp_vincod_test_api($id, $api) {

	$url = 'http://api.vincod.com/2/json/owner/checkOwnerApi/' . wp_vincod_detect_lang() . '/' . $id . '?apiKey=' . $api;
	$output = wp_vincod_file_get_contents($url, true);

	if($output === null) {

		return false;

	}

	if(isset($output['owners']['error'])) {

		return false;

	}
	else {

		return true;

	}
}


/**
 * Check if sitemap exists
 *
 * @return void
 */
function wp_vincod_check_exists_sitemap() {

	$path = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

	if(file_exists($path)) {

		$exists = true;

	}
	else {

		$exists = false;

	}

	wp_vincod_view_var('sitemap_exists', $exists);
	wp_vincod_ping_bot();
}


/**
 * Create sitemap by datas API
 *
 * @param string The customer id API
 * @param string The key customer APi
 *
 * @return void
 */
function wp_vincod_create_sitemap($customer_id, $customer_api) {
	$current_lang = wp_vincod_detect_lang();

	// Name of file when it will create
	$file_name = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

	// Start of xml (with sitemap0.9 structure)
	$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
	$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	// All requests
	$request_wineries = 'http://api.vincod.com/2/json/winery/GetWineriesByOwnerId/' . $current_lang . '/' . $customer_id . '?apiKey=' . $customer_api;
	$request_ranges = 'http://api.vincod.com/2/json/range/GetRangesByOwnerId/' . $current_lang . '/' . $customer_id . '?apiKey=' . $customer_api;
	$request_wines = 'http://api.vincod.com/2/json/wine/GetWinesByOwnerId/' . $current_lang . '/' . $customer_id . '?apiKey=' . $customer_api;

	// Datas about requests
	$datas_wineries = wp_vincod_file_get_contents($request_wineries, true);
	$datas_wines = wp_vincod_file_get_contents($request_wines, true);
	// Create base link
	$link = get_bloginfo('wpurl') . '/?page_id=' . get_option('vincod_id_page_nos_vins');


	// Init loop
	$loop = array();

	// Wineries
	if(!isset($datas_wineries['wineries']['error'])) {

		if(!wp_vincod_is_multi($datas_wineries['wineries']['winery'])) {

			$datas_wineries['wineries']['winery'] = array($datas_wineries['wineries']['winery']);

		}
		// Loop wineries
		foreach($datas_wineries['wineries']['winery'] as $winery) {

			$loop[] = $link . '&amp;winery=' . $winery['id'];

		}

	}

	// Wines
	if(!isset($datas_wines['wines']['error'])) {

		if(!wp_vincod_is_multi($datas_wines['wines']['wine'])) {

			$datas_wines['wines']['wine'] = array($datas_wines['wines']['wine']);

		}

		// Loop wines
		foreach($datas_wines['wines']['wine'] as $wine) {

			$loop[] = $link . '&amp;vincod=' . $wine['vincod'];

		}

	}

	// Prep the xml
	foreach($loop as $link) {

		$xml .= '<url><loc>' . $link . '</loc><changefreq>always</changefreq></url>' . PHP_EOL;


	}

	$xml .= '</urlset>';

	if(file_exists($file_name)) {
		unlink($file_name);
	}

	// Save site map
	file_put_contents($file_name, $xml, LOCK_EX);

}


/**
 * Delete sitemap
 *
 * @return bool
 */
function wp_vincod_delete_sitemap() {

	$path = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

	if(file_exists($path)) {

		$deleted = unlink($path);

		if($deleted) {

			return true;

		}
		else {

			return false;

		}

	}
	else {

		return false;

	}

}


/**
 * Ping bots
 *
 * @param string Url of picture
 *
 * @return string
 */
function wp_vincod_ping_bot() {

	$url = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

	// Google
	@file_get_contents('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url);

	// Bing
	@file_get_contents('http://www.bing.com/webmaster/ping.aspx?siteMap=' . $url);

}
