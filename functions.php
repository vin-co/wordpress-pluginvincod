<?php defined( 'ABSPATH' ) OR exit;

/**
 * Plugin functions
 *
 * All the small functions within our Vincod plugin
 *
 * @author		Laurent SCHAFFNER / Jérémie GES
 * @copyright   2013
 * @category	Helper
 *
 */
	
	$wp_vincod_views_datas = array(); // Our views datas

	/*
	|-------------------------------------------------------------------
	| Wordpress Hooks
	|-------------------------------------------------------------------
	| 
	| All extends wordpress functions
	|
	*/

	/**
	 * Custom wp_die()
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_die($title, $message) {

		$content = '<h1>' . $title . '</h1>' . '<p>' . $message . '</p>';
		$content .= '<br/>';
		$content .= '<a class="button" href="#" onclick="history.back();return false;">Précédent</a>';

		wp_die($content, $title);

	}
	
	/*
	|-------------------------------------------------------------------
	| Security 
	|-------------------------------------------------------------------
	| 
	| All functions about application security
	|
	*/

	/**
	 * Xss clean any string
	 *
	 * @access public
	 * @param string $str
	 * @return void
	 */
	function wp_vincod_xss_clean($str) {

		if ($str === NULL) return TRUE;

		$data = $str;

		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);

		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
			$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
				$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
				$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

				do {
			// Remove really unwanted tags
					$old_data = $data;
					$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);

				} while ($old_data !== $data);

				return $data;

			}

	/*
	|-------------------------------------------------------------------
	| Url
	|-------------------------------------------------------------------
	| 
	| All functions about urls/uris
	|
	*/

	/**
	 * Smart file get contents
	 *
	 * @access public
	 * @return mixed
	 */
	function wp_vincod_file_get_contents($url, $json_decode=FALSE) {


		if (function_exists('curl_exec')) {

			// Open url with CURL way (it's faster than file_get_contents())
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

			$datas = curl_exec($ch);
			curl_close($ch);

		} else {

			// Curl doesn't exists, use the normal way
			$datas = file_get_contents($url);

		}

		// Do you auto-decode json results
		if ($json_decode === TRUE) { 

			// TRUE -> Return array
			$datas = json_decode($datas, TRUE);

		}

		return $datas;
	}

	/**
	 * Get the link to resize
	 *
	 * @access public
	 * @param string Url of picture
	 * @return string
	 */
	function wp_vincod_url_resizer($url_picture) {

		// Get values
		$width = get_option('vincod_setting_picture_width');
		$height = get_option('vincod_setting_picture_height');

		// Conditions
		if (empty($width) OR !is_numeric($width)) {

			$width = WP_VINCOD_TEMPLATE_PICTURE_WIDTH;

		}

		if (empty($height) OR !is_numeric($height)) {

			$height = WP_VINCOD_TEMPLATE_PICTURE_HEIGHT;

		}

		$remote = WP_VINCOD_PLUGIN_URL . 'scripts/resizer.php?url=' . $url_picture . '&width=' . $width . '&height=' . $height;

		return $remote;

	}



	/*
	|-------------------------------------------------------------------
	| Custom functions
	|-------------------------------------------------------------------
	| 
	| Custom functions for this plugin
	|
	*/

	function wp_vincod_breadcrumb($breadcrumb) {

		if ($breadcrumb === FALSE) {

			return;

		}

		// Load lang
		wp_vincod_load_lang_view('shared');

		// Get content
		$back = wp_vincod_get_lang_content('vincod_back_lang');
		
		$output = '<a href="' . $breadcrumb . '">' . $back . '</a>';

		return $output;


	}

	function wp_include_picture($datas) {


		if ( ! empty($datas['picture'])) {

			$picture = $datas['picture'];

		} elseif ( ! empty($datas['medias']['media']['preview'])) {

			$picture = $datas['medias']['media']['preview'];

		} elseif ( ! empty($datas['medias']['media'][0]['preview'])) {

			$picture = $datas['medias']['media'][0]['preview'];

		} elseif ( ! empty($datas['medias']['media']['url'])) {

			$picture = $datas['medias']['media']['url'];

		} elseif ( ! empty($datas['medias']['media'][0]['url'])) {

			$picture = $datas['medias']['media'][0]['url'];

		} 



		if (  isset($picture)) {

			$picture = wp_vincod_url_resizer( wp_vincod_picture_format($picture) );

		} else {

			// Default picture
			$picture = WP_VINCOD_PLUGIN_URL . 'assets/img/ico_wine.png';

		}

		return '<img src="' . $picture . '">';

	}

	function wp_vincod_picture_format($url, $type=640) {

		$allowed_type_vincod = array(70, 140, 480, 640);
		$allowed_type_wml = array(72, 140, 640, 1024);

		// Get domain of url
		$elements = parse_url($url);

		if (isset($elements['host'])) {

			// CASE VINCOD.COM
			if ($elements['host'] == 'vincod.com' OR $elements['host'] == 'www.vincod.com') {

				if (in_array($type, $allowed_type_vincod)) {

					$url = str_replace('marque/', 'marque/' . $type . '/', $url);

				}

			}

			// CASE WML
			if ($elements['host'] == 'www.winemedialibrary.com' OR $elements['host'] == 'winemedialibrary.com') {
				
		

				if (in_array($type, $allowed_type_wml)) {

					$url = str_replace('marque/', 'marque/' . $type . '/', $url);

				}
			}

		} 

		return $url;


	}
	/**
	 * Get the custom permalink defined by the user in the dashboard
	 *
	 * @access public
	 * @param string The vincod ID for this wine
	 * @param string If no custom permalink return this string
	 * @return string
	 */
	function wp_vincod_wine_permalink($wine_vincod, $else_permalink) {

		$permalinks = get_option('vincod_setting_permalinks');

		if (isset($permalinks[$wine_vincod])) {

			return $permalinks[$wine_vincod];

		} else {

			return sanitize_title_with_dashes($else_permalink);

		}

	}
	/**
	 * Check if the wordpress user uses permalinks
	 *
	 * @access public
	 * @return bool
	 */
	function wp_vincod_permalinks_used() {

		global $wp_rewrite;
		$permalinks_used = $wp_rewrite->using_permalinks();

		return $permalinks_used;
	}

	/**
	 * Get all wines for this wordpress user
	 *
	 * @access public
	 * @return mixed (bool/array)
	 */
	function wp_vincod_get_wines() {

		// Options
		$id = get_option('vincod_setting_customer_id');
		$api = get_option('vincod_setting_customer_api');

		// The request to get all wines
		$request =  'http://api.vincod.com/json/wine/GetWinesByOwnerId/fr/' . $id . '?apiKey=' . $api;

		$results = wp_vincod_file_get_contents($request, TRUE);

		if ($results === null OR isset($results['wines']['error'])) {

			return FALSE;

		} else {

			return $results['wines']['wine'];

		}


	}

	function wp_vincod_empty($data, $text) {

		if ($data === FALSE OR empty($data)) {

			return $text;

		} 

		return $data;

	}

	function wp_vincod_group_wines($wines) {

		$wineid_found = array();
		$wines_filtered = array();

		foreach ($wines as $wine) {

			$wineid = $wine['wineid'];
			
			if ( ! in_array($wineid, $wineid_found)) {

				$wineid_found[] = $wineid;
				$wines_filtered[] = $wine;

			}


		}

	

		return $wines_filtered;

	}
	/**
	 * Construct the right link with smart detect permalinks used
	 *
	 * @access public
	 * @param string The type (winery/range/vincod)
	 * @param int 	 The id for this type
	 * @param text   The text to add if permalinks used
	 * @return void
	 */
	function wp_vincod_link($type, $id, $text) {

		// Import wp_rewrite to use some functions
		$link_page =  get_permalink();

		// Check if permalinks used
		if (wp_vincod_permalinks_used() === TRUE) {

			// Permalink from string
			$text = sanitize_title_with_dashes($text);

			switch($type) {

				case 'winery':
				$link_page .= 'exploitant-' . $id  . '-' . $text;
				break;

				case 'range':
				$link_page .= 'gamme-' . $id . '-' . $text;
				break;

				case 'vincod':
				$text = wp_vincod_wine_permalink($id, $text);
				$link_page .= 'vin-' . $id . '-' . $text;
				break;


			}

		} else {

			$link_page .= '&' . $type . '=' . $id;

		}

		return $link_page;

	}

	/**
	 * Create sitemap by datas API
	 *
	 * @access public
	 * @param string The customer id API
	 * @param string The key customer APi
	 * @return void
	 */
	function wp_vincod_create_sitemap($customer_id, $customer_api) {

		// Name of file when it will create
		$file_name = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

		// Start of xml (with sitemap0.9 structure)
		$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		// All requests
		$request_wineries = 'http://api.vincod.com/json/winery/GetWineriesByOwnerId/fr/' . $customer_id . '?apiKey=' . $customer_api;
		$request_ranges = 'http://api.vincod.com/json/range/GetRangesByOwnerId/fr/' . $customer_id . '?apiKey=' . $customer_api;
		$request_wines = 'http://api.vincod.com/json/wine/GetWinesByOwnerId/fr/' . $customer_id . '?apiKey=' . $customer_api;

		// Datas about requests
		$datas_wineries = wp_vincod_file_get_contents($request_wineries, TRUE);
		$datas_wines = wp_vincod_file_get_contents($request_wines, TRUE);
		// Create base link
		$link = get_bloginfo('wpurl') . '/?page_id=' . get_option('vincod_id_page_nos_vins');
	

		// Init loop
		$loop = array();

		// Wineries
		if (!isset($datas_wineries['wineries']['error'])) {

			// Loop wineries
			foreach ($datas_wineries['wineries']['winery'] as $winery) {

				$loop[] = $link . '&amp;winery=' . $winery['id'];

			}

		}

		// Wines
		if (!isset($datas_wines['wines']['error'])) {

			// Loop wines
			foreach ($datas_wines['wines']['wine'] as $wine) {

				$loop[] = $link . '&amp;vincod=' . $wine['vincod'];

			}

		}

		// Prep the xml
		foreach($loop as $link) {

			$xml .= '<url><loc>' . $link . '</loc><changefreq>always</changefreq></url>' . PHP_EOL;


		}

		$xml .= '</urlset>';

		if (file_exists($file_name)) {
			unlink($file_name);
		}
		// Save site map
		file_put_contents($file_name, $xml, LOCK_EX);

	}

	/**
	 * Check if sitemap exists
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_check_exists_sitemap() {

		$path = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

		if (file_exists($path)) {

			$exists = TRUE;

		} else {

			$exists = FALSE;

		}

		wp_vincod_view_var('sitemap_exists', $exists);
		wp_vincod_ping_bot();
	}

	/**
	 * Delete sitemap
	 *
	 * @access public
	 * @return bool
	 */
	function wp_vincod_delete_sitemap() {

		$path = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

		if (file_exists($path)) {

			$deleted = unlink($path);

			if ($deleted) {

				return TRUE;

			} else {

				return FALSE;

			}

		} else {

			return FALSE;

		}

	}

	/**
	 * Ping bots
	 *
	 * @access public
	 * @param string Url of picture
	 * @return string
	 */
	function wp_vincod_ping_bot() {

		$url = WP_VINCOD_PLUGIN_PATH . 'cache/sitemap/plugin-vincod-sitemap.xml';

		// Google
		@file_get_contents('http://www.google.com/webmasters/sitemaps/ping?sitemap=' . $url);

		// Bing
		@file_get_contents('http://www.bing.com/webmaster/ping.aspx?siteMap=' . $url);

	}


	/**
	 * Called when the user submit reset app form
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_reset_app() {


		// Delete sessions of log system
		wp_vincod_clear_log();

		wp_vincod_devlog('vincod_we_reset_plugin_lang');

		// Delete language
		delete_option('vincod_language');

		// Delete page
		wp_vincod_delete_page(get_option('vincod_id_page_nos_vins'));

		wp_vincod_devlog('vincod_we_delete_nos_vins_lang');

		// Delete sitemap
		wp_vincod_devlog('vincod_we_delete_the_sitemap_lang');
		wp_vincod_delete_sitemap();

		// Delete cache API
		wp_vincod_devlog('vincod_we_delete_the_cache_setting_lang');
		delete_option('vincod_setting_cache_api');

		// Delete styles options
		wp_vincod_devlog('vincod_we_delete_the_style_setting_lang');
		
		foreach (array('size_text', 'size_h2', 'picture_height', 'picture_width', 'color_general_text', 'color_titles_text') as $value) {

			delete_option('vincod_setting_' . $value);

		}

		// Re-create page
		$created = wp_vincod_create_page();

		wp_vincod_devlog('vincod_we_add_again_nos_vins_lang');

		// Stock id of this page
		update_option('vincod_id_page_nos_vins', $created);

		wp_vincod_devlog('vincod_we_stock_lang', $created);

		// Delete options API
		delete_option('vincod_setting_customer_id');
		delete_option('vincod_setting_customer_api');

		wp_vincod_devlog('vincod_we_delete_api_credentials_lang');

		// Delete permalinks
		delete_option('vincod_setting_permalinks');

		wp_vincod_devlog('vincod_reset_complete_lang');

	}

	/**
	 * Clear the log
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_clear_log() {

		if (isset($_SESSION['devlog'])) {

			unset($_SESSION['devlog']);

		}

	}

	/**
	 * DEVLOG SYSTEM
	 * It will add the entire process of a page generation within a session
	 * Then we can put it in our database or do whatever we want ; flexibility man.
	 *
	 * A simple devlog() without arg will output the entire devlog process
	 *
	 * @access public
	 * @param string $new_entry
	 * @return bool / array
	 */
	function wp_vincod_devlog($new_entry=NULL, $details='') {

		// We check if it's a lang variable, we the details otherwise we concatenate as it was one string
		if (strpos($new_entry, '_lang')) $new_entry = wp_vincod_get_lang_content($new_entry) . $details;
		else $new_entry .= $details;

		if ($new_entry === NULL) {

			return $_SESSION['devlog'];

		} else {

			$_SESSION['devlog'][] = array(

				'time' => time(),
				'msg' => $new_entry,
				'ip' => $_SERVER['REMOTE_ADDR'] // We won't use it but still.

				);

			return TRUE;

		}

	}

	/**
	 * Check if page exists
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_exists_page($name) {

		$exists = get_posts(array(

			'post_type' => 'page',
			'name' => $name

			));

		if (! $exists) {

			return FALSE;

		} else {

			return TRUE;

		}

	}

	/**
	 * Create new page Nos Vins
	 *
	 * @access public
	 * @return mixed (bool/int)
	 */
	function wp_vincod_create_page() {

		// Create new page with pending statut		
		$created = wp_insert_post(array(

			'comment_status' => 'closed',
			'ping_status' => 'closed',
			'post_name' => 'plugin-vincod-nos-vins',			  
			'post_status' => 'pending', 
			'post_title' => 'Plugin Vincod Nos Vins',
			'post_type' => 'page',
			'post_content' => 'Cette page est utilisé par le plugin vincod, merci de ne pas y toucher'

			), TRUE); 	

		if (isset($created->errors)) {

			return FALSE;

		} else {

			// Return id
			return $created;

		}

	}

	/**
	 * Switch status of page
	 *
	 * @access public
	 * @param  int 	The id of page to switch
	 * @param  type New type for this page
	 * @return bool
	 */
	function wp_vincod_switch_page($id, $type) {

		// Update the post
		$updated = wp_update_post(array(

			'ID' => $id,
			'post_status' => $type

			));

		if ($updated == (int) $id) {

			return TRUE;

		} else {

			return FALSE;
		}

	}

	/**
	 * Delete specific page by id
	 *
	 * @access public
	 * @param  int 	The page id to delete
	 * @return void
	 */
	function wp_vincod_delete_page($id) {

		$deleted = wp_delete_post($id);

		if ($deleted === FALSE) {

			return FALSE;

		} else {

			return TRUE;

		}

	}	

	/**
	 * Check if you can deal with the API
	 *
	 * @access public
	 * @param  int  The customer id API
	 * @param  string The customer key API
	 * @return bool
	 */
	function wp_vincod_test_api($id, $api) {

		$url = 'http://api.vincod.com/2/json/owner/checkOwnerApi/fr/' . $id . '?apiKey=' . $api;
		$output = wp_vincod_file_get_contents($url, TRUE);

		if ($output === null) {

			return FALSE;

		}

		if (isset($output['owners']['error'])) {

			return FALSE;

		} else {

			return TRUE;

		}
	}

	/**
	 * Get the slug of page by ID
	 *
	 * @access public
	 * @param  int  The page ID
	 * @return mixed (string/false)
	 */
	function wp_vincod_get_page_slug($id) {

		$post = get_post($id);

		if (!empty($post)) {

			return $post->post_name;

		} 

		return false;

	}

	/**
	 * DEVLOG VIEW INJECTION
	 * We pass through the function to put our devlog session variables within a view variable
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_devlog_inject_within_view() {

		wp_vincod_view_var('devlog_content', $_SESSION['devlog']);

	}

	/*
	|-------------------------------------------------------------------
	| Micro-Framework plugin development
	|-------------------------------------------------------------------
	| 
	| Commons functions to use when you create a plugin
	|
	*/
	function wp_vincod_detect_lang() {

		if(function_exists('qtrans_getLanguage')) {

			$lang = qtrans_getLanguage();

		} else {

			$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

		}

		return $lang;

	}


	function wp_vincod_is_multi($array) {

   		return (count($array) != count($array, 1));

	}

	/**
	 * Redirect the user in the dashboard after install
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_redirect_after_install() {

		if (get_option('vincod_redirect_after_install', false)) {
			delete_option('vincod_redirect_after_install');
			wp_redirect('options-general.php?page=vincod');
		}

	}

	/**
	 * We get the targeted lang variable
	 *
	 * @access public
	 * @param string $content the lang variable to get
	 * @return void
	 */
	function wp_vincod_get_lang_content($content) {

		return $GLOBALS['wp_vincod_views_datas'][$content];

	}

	/**
	 * Launch our views with their respective headers
	 *
	 * @access public
	 * @param string $view the view that will be launched (e.g. 'admin/dashboard')
	 * @return void
	 */
	function wp_vincod_launch($view) {

		/*
		 * 
		 * LANGUAGE SYSTEM
		 * We load our language variables
		 *
		 */
		wp_vincod_load_lang_view($view);

		$wp_vincod_views_datas = &$GLOBALS['wp_vincod_views_datas'];

		/*
		 *
		 * HEADER
		 * You can put whatever your want before it'll launch our view
		 * The _repeat/header is in the /views/
		 *
		 */
		wp_vincod_load_view('_repeat/header', $wp_vincod_views_datas);


		/*
		 *
		 * THE VIEW 
		 * 
		 *
		 */
		wp_vincod_load_view($view, $wp_vincod_views_datas);

		/*
		 *
		 * FOOTER
		 *
		 */
		wp_vincod_load_view('_repeat/footer', $wp_vincod_views_datas);


	}

	/**
	 * Load our CSS styles within our views
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_load_styles() {

		global $wp_styles;

		// Add styles
		$wp_styles->add('bootstrap', plugin_dir_url(__FILE__).'/assets/libs/bootstrap/bootstrap.css');
		$wp_styles->add('font-awesome', plugin_dir_url(__FILE__).'/assets/libs/font-awesome/css/font-awesome.css');
		$wp_styles->add('layout', plugin_dir_url(__FILE__).'/assets/css/layout.css');	
		
		// And put enqueue
		$wp_styles->enqueue(array('bootstrap', 'font-awesome', 'layout'));

	}

	/**
	 * Load our JS scripts within our views
	 *
	 * @access public
	 * @return void
	 */
	function wp_vincod_load_scripts() {

		global $wp_scripts;

		// Add scripts
		$wp_scripts->add('jquery', plugin_dir_url(__FILE__).'/assets/libs/jquery/jquery-1.10.2.min.js');
		$wp_scripts->add('api_connection_check', plugin_dir_url(__FILE__).'/assets/js/api_connection_check.js');
		$wp_scripts->add('dashboard_stuff', plugin_dir_url(__FILE__).'/assets/js/dashboard_stuff.js');

		// And put enqueue
		$wp_scripts->enqueue(array('jquery', 'api_connection_check', 'dashboard_stuff'));

	}

	/**
	 * Load a view, the cool way
	 *
	 * @access public
	 * @param string $view the view name (e.g. 'admin/dashboard')
	 * @return void
	 */
	function wp_vincod_load_view($view, $datas=array(), $return = FALSE, $start_path = 'views') {
		
		// We want return, so we must stock the buffer
		if ($return) ob_start();
		
		/*
		 * We get our global variable and extract it to simulate a view variable system
		 * For example, $wp_vincod_views_datas['var'] will become $var within this function and our view
		 *
		 */
		$datas = (array) $datas;

		if (! empty($datas)) {

			extract($datas);

		}

		require(rtrim($start_path, '/') . '/' . ltrim($view, '/') . '.php');

		// Get content of the buffer
		if ($return) {

			$buffer = ob_get_contents();

			// Bye bye buffer
			ob_get_clean();

			return $buffer;

		}

	}


	/**
	 * Load a language view, the cool way
	 *
	 * @access public
	 * @param string $view the view name (e.g. '_repeat/footer')
	 * @return void
	 */
	function wp_vincod_load_lang_view($view, $force_lang='') {


		if (!empty($force_lang)) {

			$actual_language = $force_lang;

		} else {

			$actual_language = wp_vincod_detect_lang();

		}

		$path = 'language/' . $actual_language . '/' . ltrim($view, '/') . '_lang.php';

		if ( ! file_exists(WP_VINCOD_PLUGIN_PATH . $path)) {

			require('language/fr/' . ltrim($view, '/') . '_lang.php');

		} else {

			require('language/' . $actual_language . '/' . ltrim($view, '/') . '_lang.php');

		}
	}

	/**
	 * Get/Set a view variable
	 *
	 * @access public
	 * @param string $label the name of our variable
	 * @param mixed $value the new value if we want to set a view variable
	 * @return bool / mixed
	 */
	function wp_vincod_view_var($label, $value=NULL) {

		$wp_vincod_views_datas = &$GLOBALS['wp_vincod_views_datas'];

		if ($value === NULL) return $wp_vincod_views_datas[$label];
		else {

			$wp_vincod_views_datas[$label] = $value;

			return TRUE;

		}

	}

	/**
	 * Here we treat all the $_POST updates
	 *
	 * @access public
	 * @param array $post should be $_POST or similar (such as $_GET)
	 * @return void
	 */
	function wp_wincod_post_updates(array $post) {

		// Our container
		$cleaned_post = array();

		foreach ($post as $entry_label => $entry_value) {

			$cleaned_post[$entry_label] = wp_vincod_xss_clean($entry_value);

		}

		// We want to change the language
		if (isset($cleaned_post['vincod_language'])) {

			if ($cleaned_post['vincod_language'] === 'Français') $vincod_language = 'fr';
			else $vincod_language = 'en';

			update_option('vincod_language', $vincod_language);
			wp_vincod_view_var('language', $vincod_language);

		}

		// We want to change ou settings (Numéro client / Clé API)
		if (isset($cleaned_post['vincod_setting_customer_id']) && (isset($cleaned_post['vincod_setting_customer_api']))) {


			if (isset($cleaned_post['vincod_setting_remove'])) {

					// Delete informations about api
				delete_option('vincod_setting_customer_id');
				delete_option('vincod_setting_customer_api');

				wp_vincod_devlog('vincod_we_delete_api_credentials_lang');

				// Switch page "Nos Vins" (publish -> pending)
				wp_vincod_switch_page(get_option('vincod_id_page_nos_vins'), 'pending');

				wp_vincod_devlog('vincod_status_nos_vins_was_changed_lang');

			} else {

				if (!empty($cleaned_post['vincod_setting_customer_id']) && !empty($cleaned_post['vincod_setting_customer_api'])) {

					$customer_id = $cleaned_post['vincod_setting_customer_id'];
					$customer_api = $cleaned_post['vincod_setting_customer_api'];


					$tested = wp_vincod_test_api($customer_id, $customer_api);

					if ($tested) {

						// Update options
						update_option('vincod_setting_customer_id', $customer_id);
						update_option('vincod_setting_customer_api', $customer_api);

						// Switch page "Nos vins" (pending -> publish)
						wp_vincod_switch_page(get_option('vincod_id_page_nos_vins'), 'publish');

						wp_vincod_devlog('vincod_api_settings_updated_lang');
						wp_vincod_devlog('vincod_nos_vins_published_lang');

					} else {

						wp_vincod_die('Mise à jour identifiants', 'Impossible de se connecter à l\'API avec ses identifiants');

					}

				} else {

					wp_vincod_die('Mise à jour des identifiants', 'Vous devez saisir vos informations de connexion');

				}
			}



		} 


		// Clear the devlog system
		if (isset($cleaned_post['vincod_clear_log'])) {

			wp_vincod_clear_log();

		}


		// Reset APP
		if (isset($cleaned_post['vincod_reset_app'])) {

			// True -> Log system
			wp_vincod_reset_app();

			wp_vincod_view_var('app_cleaned', TRUE);

		}

		// Styles
		foreach (array('size_text', 'size_h2', 'picture_height', 'picture_width', 'color_general_text', 'color_titles_text') as $value) {

			if (isset($cleaned_post['vincod_setting_' . $value])) {

				$setting = $cleaned_post['vincod_setting_' . $value];

				update_option('vincod_setting_' . $value, $setting);

				wp_vincod_devlog('vincod_value_updated_lang', ' ' . $value);


			}

		}

		// API cache
		if (isset($cleaned_post['vincod_setting_cache_api'])) {

			// Update
			update_option('vincod_setting_cache_api', $cleaned_post['vincod_setting_cache_api']);
			
			wp_vincod_devlog('vincod_cache_duration_updated_lang');

		}

		// Seo (create action)
		if (isset($cleaned_post['vincod_seo_create'])) {

			$id = get_option('vincod_setting_customer_id');
			$api = get_option('vincod_setting_customer_api');

			if ( ($id === FALSE && empty($id) ) OR ($api === FALSE && empty($api)) )  {
				
				wp_vincod_die('Erreur sitemap', 'Vos identifiants de connexion doivent être remplis');

			}

			$success = wp_vincod_test_api($id, $api);

			if ($success === FALSE) {

				wp_vincod_die('Erreur sitemap', 'Impossible de se connecter avec ces identifiants');


			}

			wp_vincod_create_sitemap($id, $api);
			wp_vincod_devlog('vincod_sitemap_creation_lang');

		}

		// Seo (delete action)
		if (isset($cleaned_post['vincod_seo_delete'])) {

			wp_vincod_devlog('vincod_the_sitamp_was_removed_lang');
			wp_vincod_delete_sitemap();

		}

		// Permalink
		if (isset($cleaned_post['vincod_permalink'])) {

			$vincod = $cleaned_post['vincod_permalink'];

			$the_permalink = $cleaned_post['vincod_' . $vincod];

			// Convert the permalink
			$new_permalink = sanitize_title_with_dashes($the_permalink);

			$permalinks = get_option('vincod_setting_permalinks');

			if (!$permalinks OR empty($permalinks)) {

				$permalinks = array($vincod => $new_permalink);

			} else {

				$permalinks[$vincod] = $new_permalink;

			}

			update_option('vincod_setting_permalinks', $permalinks);


		}


	}





	?>