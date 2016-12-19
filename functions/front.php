<?php defined('ABSPATH') OR exit;
/**
 * Plugin Front functions
 *
 * All the front functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2016 VINTERNET
 *
 */


/**
 * Construct the right link with smart detect permalinks used
 *
 * @param string The type (collection/brand/range/product)
 * @param int     The id for this type
 * @param text   The text to add if permalinks used
 *
 * @return bool|string
 */
function wp_vincod_link($type, $id, $text) {
	
	// Import wp_rewrite to use some functions
	$link_page = get_permalink();
	
	// Check if permalinks used
	if(wp_vincod_permalinks_used() === true) {
		$type = __($type, 'vincod');
		
		// Permalink from string
		$text = sanitize_title($text);
		
		if(!empty($type)) {
			$link_page .= $type . '-' . $id . '-' . $text;
		}
		
	}
	else {
		
		$link_page .= '&' . $type . '=' . $id;
		
	}
	
	return $link_page;
	
}


/**
 * Sort Wine varieties by amount DESC
 *
 * @param $varieties
 *
 * @return mixed
 */
function wp_vincod_varieties_desc($varieties) {
	
	foreach($varieties as $key => $row) {
		
		$amount[$key] = $row['amount'];
		
	}
	
	array_multisort($amount, SORT_DESC, $varieties);
	
	return $varieties;
}


/**
 * Group wines
 *
 * @param $products
 *
 * @return array
 */
function wp_vincod_group_products($products) {
	
	$productid_found = array();
	$products_filtered = array();
	
	foreach($products as $product) {
		
		$productid = $product['wineid'];
		
		if(!in_array($productid, $productid_found)) {
			
			$productid_found[] = $productid;
			$products_filtered[] = $product;
			
		}
		
	}
	
	return $products_filtered;
}


/**
 * Validate Picture format
 *
 * @param $url
 * @param bool $type
 *
 * @return mixed
 */
function wp_vincod_picture_format($url, $type = false) {
	
	if($type == false)
		$type = 640;
	
	$allowed_type_vincod = array(70, 80, 320, 640, 1024, 2048);
	$allowed_type_wml = array('mini', 320, 640, 1024, 'retina');
	
	if(!in_array($type, $allowed_type_vincod) && !in_array($type, $allowed_type_wml))
		$type = 640;
	
	// Get domain of url
	$elements = parse_url($url);
	
	if(strpos($url, '/_clients_folder/')) {
		
		if(strpos($url, '/_clients_folder/vincod/')) {
			$url = str_replace('marque/', 'marque/' . $type . '/', $url);
			
		}
		else {
			
			$infos = pathinfo($url);
			$pattern = '.' . $infos['extension']; // Ex. > .jpg
			$replace_pattern = '_' . $type . '.' . $infos['extension']; // Ex > _640.jpg
			$url = str_replace($pattern, $replace_pattern, $url);
		}
	}
	else {
		
		$url = str_replace('640/', $type . '/', $url); // parceque l'API remonte par défaut l'image en 640
		
	}
	
	return $url;
}


/**
 * Get a Picture image Url
 *
 * @param $datas
 * @param bool $type
 *
 * @return mixed|string
 */
function wp_vincod_get_picture_url($datas, $type = false) {
	
	if(!empty($datas['picture'])) {
		
		$picture = $datas['picture'];
		
	}
	
	$picture = (isset($picture)) ? wp_vincod_picture_format($picture, $type) : false;
	
	return $picture;
	
}


/**
 * Get a Logo image Url
 *
 * @param $datas
 * @param bool $type
 *
 * @return mixed|string
 */
function wp_vincod_get_logo_url($datas, $type = false) {
	
	if(!empty($datas['logo'])) {
		
		$logo = $datas['logo'];
		
	}
	
	$logo = (isset($logo)) ? wp_vincod_picture_format($logo, $type) : false;
	
	return $logo;
	
}


/**
 * Get a Cover image Url
 *
 * @param $datas
 * @param bool $type
 *
 * @return mixed|string
 */
function wp_vincod_get_cover_url($datas, $type = false) {
	
	if(!empty($datas['cover'])) {
		
		$cover = $datas['cover'];
		
	}
	
	$cover = (isset($cover)) ? wp_vincod_picture_format($cover, $type) : false;
	
	return $cover;
	
}


/**
 * Get a Label image Url
 *
 * @param $datas
 * @param bool $type
 *
 * @return mixed|string
 */
function wp_vincod_get_label_url($datas, $type = false) {
	
	if(!empty($datas['tag'])) {
		
		$label = $datas['tag'];
		
	}
	
	$label = (isset($label)) ? wp_vincod_picture_format($label, $type) : false;
	
	return $label;
	
}


/**
 * Get a Bottle image Url
 *
 * @param $datas
 * @param bool $type
 *
 * @return mixed|string
 */
function wp_vincod_get_bottle_url($datas, $type = false) {
	
	return wp_vincod_get_picture_url($datas, $type);
	
}


/**
 * Include a video
 *
 * @param $value
 * @param $extend
 *
 * @return bool|string
 */
function wp_vincod_include_video($value, $extend) {
	
	// Maybe add this in dashboard to customise width & height ?
	$width = 400;
	$height = 250;
	
	
	if(preg_match('/^http(s?):\/\/vimeo.com\/([0-9_-]+)/', $value, $matches)) {
		return '<iframe src="http://player.vimeo.com/video/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>' . $extend;
	}
	else if(preg_match('/^http(s?):\/\/www.youtube.com\/watch\?v=([a-zA-Z0-9_-]+)&?/', $value, $matches)) {
		return '<iframe src="http://www.youtube.com/embed/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen></iframe>' . $extend;
	}
	else if(preg_match('/^http(s?):\/\/www.dailymotion.com\/video\/([a-zA-Z0-9_-]+)_/', $value, $matches)) {
		return '<iframe src="http://www.dailymotion.com/embed/video/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen></iframe>' . $extend;
	}
	
	return false;
	
}


/**
 * Get the Menu
 *
 * @return string
 */
function wp_vincod_get_menu($vincod = null) {
	
	$api = new wp_vincod_controller_template();
	
	$menu = $api->get_catalogue_by_vincod($vincod);
	
	$menu = wp_vincod_render_menu($menu);
	
	return $menu;
}


/**
 * Render the Menu
 *
 * @return string
 */
function wp_vincod_render_menu($menu_array) {
	
	if(isset($menu_array['menu']) && isset($menu_array['menu'][0])) {
		
		$permalink_type = wp_vincod_menu_permalink_type($menu_array['menu'][0]['@attributes']['type']);
		$menu = '<ul class="vincod-menu ' . $permalink_type . '">';
		
		foreach($menu_array['menu'] as $sub_menu) {
			$menu_link = '';
			$permalink_type = wp_vincod_menu_permalink_type($sub_menu['@attributes']['type']);
			
			$menu_link = ($permalink_type == 'owner') ? get_permalink() : wp_vincod_link($permalink_type, $sub_menu['@attributes']['vincod'], $sub_menu['title']);
			
			$is_active = (isset($sub_menu['actif']) && $sub_menu['actif'] == 1) ? ' active' : '';
			
			$menu .= '<li class="vincod-menu-item ' . $permalink_type . $is_active . '">';
			$menu .= '<a href="' . $menu_link . '" title="' . $sub_menu['title'] . '">' . $sub_menu['title'] . '</a>';
			
			if(isset($sub_menu['menu'])) {
				$menu .= wp_vincod_render_menu($sub_menu);
			}
			
			$menu .= '</li>';
		}
		
		$menu .= '</ul>';
	}
	elseif(isset($menu_array['menu'])) {
		
		$permalink_type = wp_vincod_menu_permalink_type($menu_array['menu']['@attributes']['type']);
		
		$menu = '<ul class="vincod-menu ' . $permalink_type . '">';
		
		$menu_link = '';
		
		$menu_link = ($permalink_type == 'owner') ? get_permalink() : wp_vincod_link($permalink_type, $menu_array['menu']['@attributes']['vincod'], $menu_array['menu']['title']);
		
		$is_active = (isset($menu_array['menu']['actif']) && $menu_array['menu']['actif'] == 1) ? ' active' : '';
		
		$menu .= '<li class="vincod-menu-item ' . $permalink_type . $is_active . '">';
		$menu .= '<a href="' . $menu_link . '" title="' . $menu_array['menu']['title'] . '">' . $menu_array['menu']['title'] . '</a>';
		
		if(isset($menu_array['menu']['menu'])) {
			$menu .= wp_vincod_render_menu($menu_array['menu']);
		}
		
		$menu .= '</li>';
		$menu .= '</ul>';
	}
	
	return $menu;
}


/**
 * Get the correct permalink type
 *
 * @return string
 */
function wp_vincod_menu_permalink_type($type) {
	switch($type) {
		case 'owner':
			return 'owner';
		case 'family':
			return 'collection';
		case 'winery':
			return 'brand';
		case 'range':
			return 'range';
		default:
			return $type;
	}
}


/**
 * Set Body Classes
 *
 * @return array
 */
function wp_vincod_body_classes($classes) {
	$classes[] = 'plugin-vincod-page';
	
	return $classes;
}