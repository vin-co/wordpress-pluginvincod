<?php defined('ABSPATH') OR exit;
/**
 * Plugin Helpers functions
 *
 * All the helpers functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2016 VINTERNET
 *
 */


/**
 * Smart file get contents
 *
 * @access public
 *
 * @return mixed
 */
function wp_vincod_file_get_contents($url, $json_decode = false) {
	
	
	if(function_exists('curl_exec')) {
		
		// Open url with CURL way (it's faster than file_get_contents())
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		$datas = curl_exec($ch);
		curl_close($ch);
		
	}
	else {
		
		// Curl doesn't exists, use the normal way
		$datas = file_get_contents($url);
		
	}
	
	// Do you auto-decode json results
	if($json_decode === true) {
		
		// TRUE -> Return array
		$datas = json_decode($datas, true);
		
	}
	
	return $datas;
}


/**
 * Test if a variable is empty
 *
 * @param $data
 * @param $text
 *
 * @return mixed
 */
function wp_vincod_empty($data, $text) {
	
	if($data === false OR empty($data)) {
		
		return $text;
		
	}
	
	return $data;
	
}


/**
 * Test if a variable is not empty
 *
 * @param $var
 *
 * @return bool
 */
function wp_vincod_is_not_empty($var) {
	return isset($var) && !empty($var);
}


/**
 * Test if an array has string as keys
 *
 * @param $array
 *
 * @return bool
 */
function wp_vincod_is_multi($array) {
	return !count(array_filter(array_keys($array), 'is_string')) > 0;
}


/**
 * Test if a page exists
 *
 * @param $name
 *
 * @return bool
 */
function wp_vincod_exists_page($name) {
	
	$exists = get_posts(array(
		
		'post_type' => 'page',
		'name'      => $name
	
	));
	
	if(!$exists) {
		
		return false;
		
	}
	else {
		
		return true;
		
	}
	
}


/**
 * Switch status of page
 *
 * @param int    The id of page to switch
 * @param type New type for this page
 *
 * @return bool
 */
function wp_vincod_switch_page($id, $type) {
	
	// Update the post
	$updated = wp_update_post(array(
		
		'ID'          => $id,
		'post_status' => $type
	
	));
	
	if($updated == (int)$id) {
		
		return true;
		
	}
	else {
		
		return false;
	}
	
}


/**
 * Delete specific page by id
 *
 * @param int    The page id to delete
 *
 * @return bool
 */
function wp_vincod_delete_page($id) {
	
	$deleted = wp_delete_post($id);
	
	if($deleted === false) {
		
		return false;
		
	}
	else {
		
		return true;
		
	}
	
}


/**
 * Get the slug of page by ID
 *
 * @param int  The page ID
 *
 * @return string|bool
 */
function wp_vincod_get_page_slug($id) {
	
	//If polylang exists, get translated page ID "NOS VINS"
	
	if(function_exists('pll_current_language')) {
		$id = pll_get_post($id, pll_current_language());
	}
	
	$post = get_post($id);
	
	
	if(!empty($post)) {
		
		return $post->post_name;
		
	}
	
	return false;
	
}


/**
 * Get the permalink of page by ID
 *
 * @param int  The page ID
 *
 * @return string|bool
 */
function wp_vincod_get_permalink($id) {
	
	//If polylang exists, get translated page ID "NOS VINS"
	
	if(function_exists('pll_current_language')) {
		$id = pll_get_post($id, pll_current_language());
	}
	
	$post = get_post($id);
	
	
	if(!empty($post)) {
		
		return get_permalink($post);
		
	}
	
	return false;
	
}


/**
 * @return array|bool|string
 */
function wp_vincod_detect_lang() {
	
	$lang = get_locale();
	$lang = explode('_', $lang);
	$lang = $lang[0];
	
	return $lang;
	
}


/**
 * Get/Set a view variable
 *
 * @param string $label the name of our variable
 * @param mixed $value the new value if we want to set a view variable
 *
 * @return bool|mixed
 */
function wp_vincod_view_var($label, $value = null) {
	
	$wp_vincod_views_datas = &$GLOBALS['wp_vincod_views_datas'];
	
	if($value === null)
		return (isset($wp_vincod_views_datas[$label])) ? $wp_vincod_views_datas[$label] : false;
	else {
		
		$wp_vincod_views_datas[$label] = $value;
		
		return true;
		
	}
	
}

function wp_vincod_untranslated_strings() {
	__('owner', 'vincod');
	__('collection', 'vincod');
	__('brand', 'vincod');
	__('range', 'vincod');
	__('product', 'vincod');
}

