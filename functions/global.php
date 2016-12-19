<?php defined('ABSPATH') OR exit;
/**
 * Plugin Global functions
 *
 * All the global functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2016 VINTERNET
 *
 */


/**
 * Custom wp_die()
 *
 * @return void
 */
function wp_vincod_die($title, $message) {
	
	$content = '<h1>' . $title . '</h1>' . '<p>' . $message . '</p>';
	$content .= '<br/>';
	$content .= '<a class="button" href="#" onclick="history.back();return false;">' . __("Back", 'vincod') . '</a>';
	
	wp_die($content, $title);
	
}


/**
 * Xss clean any string
 *
 * @param string $str
 * @return bool
 */
function wp_vincod_xss_clean($str) {
	
	if($str === null)
		return true;
	
	$data = $str;
	
	// Fix &entity\n;
	$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
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
		
	} while($old_data !== $data);
	
	return $data;
	
}


/**
 * Load a view, the cool way
 *
 * @param string $view the view name (e.g. 'admin/dashboard')
 * @return string
 */
function wp_vincod_load_view($view, $datas = array(), $return = false, $start_path = WP_VINCOD_PLUGIN_PATH.'views') {
	
	// We want return, so we must stock the buffer
	if($return)
		ob_start();
	
	/*
	 * We get our global variable and extract it to simulate a view variable system
	 * For example, $wp_vincod_views_datas['var'] will become $var within this function and our view
	 *
	 */
	$datas = (array)$datas;
	
	if(!empty($datas)) {
		
		extract($datas);
		
	}
	
	require(rtrim($start_path, '/') . '/' . ltrim($view, '/') . '.php');
	
	// Get content of the buffer
	if($return) {
		
		$buffer = ob_get_contents();
		
		// Bye bye buffer
		ob_get_clean();
		
		return $buffer;
		
	}
	
}



/**
 * Launch our views with their respective headers
 *
 * @param string $view the view that will be launched (e.g. 'admin/dashboard')
 * @return void
 */
function wp_vincod_launch($view) {
	
	
	$wp_vincod_views_datas = &$GLOBALS['wp_vincod_views_datas'];
	
	/*
	 *
	 * HEADER
	 * You can put whatever your want before it'll launch our view
	 * The _repeat/header is in the /views/
	 *
	 */
	wp_vincod_load_view('admin/header', $wp_vincod_views_datas);
	
	
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
	wp_vincod_load_view('admin/footer', $wp_vincod_views_datas);
	
	
}

