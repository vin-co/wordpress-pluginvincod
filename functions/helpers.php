<?php defined('ABSPATH') or exit;
/**
 * Plugin Helpers functions
 *
 * All the helpers functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2023 VINTERNET
 *
 */


/**
 * Smart file get contents
 *
 * @access public
 *
 * @return mixed
 */
function wp_vincod_remote_request($url, $json_decode = false, $post_data = null) {

	$args = array('method' => 'GET');

	if(!empty($post_data)) {

		$args = array(
			'method' => 'POST',
			'body'   => $post_data
		);
	}

	$request = wp_remote_request($url, $args);
	$datas = wp_remote_retrieve_body($request);

	// Do you auto-decode json results
	if($json_decode) {

		// TRUE -> Return array
		$datas = json_decode($datas, true);

	}

	return $datas;
}


/**
 * Test if an array has string as keys
 *
 * @param $array
 *
 * @return bool
 */
function wp_vincod_is_multi($array) {
	return (is_array($array)) ? !count(array_filter(array_keys($array), 'is_string')) > 0 : false;
}


/**
 * @return mixed
 */
function wp_vincod_search_in_array($array, $value) {

	if(is_array($array) && !empty($array)) {

		foreach($array as $item) {

			if($item === $value) {
				return $item;
			}
		}
	}

	return null;
}

/**
 * @return mixed
 */
function wp_vincod_search_in_array_by_key($array, $key, $value) {

	if(is_array($array) && !empty($array)) {

		foreach($array as $item) {

			if($item[$key] === $value) {
				return $item;
			}
		}
	}

	return null;
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

	return ($updated == (int)$id);

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

	return ($deleted === false);

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
 * @return string
 */
function wp_vincod_sanitize_param($param) {

	$param = wp_unslash($param);
	$param = wp_vincod_xss_clean($param);
	$param = strip_tags($param);
	$param = sanitize_text_field($param);
	$param = esc_html($param);

	return $param;

}


/**
 * @return string
 */
function wp_vincod_url_encode($url) {

	$url = sanitize_user($url, true);
	$url = filter_var($url, FILTER_SANITIZE_ENCODED);

	return $url;

}

