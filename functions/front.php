<?php defined('ABSPATH') or exit;
/**
 * Plugin Front functions
 *
 * All the front functions within our Vincod plugin
 *
 * @author      Vinternet
 * @category    Helper
 * @copyright   2023 VINTERNET
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
function wp_vincod_link($type, $id, $text, $filters = null) {

	// Import wp_rewrite to use some functions
	$link_page = wp_vincod_get_permalink(get_option('vincod_id_page_nos_vins'));

	// Check if permalinks used
	if(wp_vincod_permalinks_used()) {
		$type = __($type, 'vincod');

		// Permalink from string
		$text = sanitize_title($text);

		if(!empty($type)) {
			$link_page .= $type . '-' . $id . '-' . $text;

			if($filters) {
				$link_page .= '?' . implode('&', $filters);
			}
		}

	}
	else {

		$link_page .= '&' . $type . '=' . $id;

		if($filters) {
			$link_page .= '&' . implode('&', $filters);
		}
	}

	return $link_page;

}


/**
 * @return array
 */
function wp_vincod_link_filters($active_filters) {

	global $WP_VINCOD_ENTITIES;

	$link_filters = array();

	if(isset($active_filters['vintage'])) {
		$link_filters[] = $WP_VINCOD_ENTITIES['vintage'] . '=' . $active_filters['vintage'];
	}

	if(!empty($active_filters['brand'])) {
		$link_filters[] = $WP_VINCOD_ENTITIES['brand'] . '=' . $active_filters['brand']['vincod'];
	}

	if(!empty($active_filters['appellation'])) {
		$link_filters[] = $WP_VINCOD_ENTITIES['appellation'] . '=' . $active_filters['appellation']['numappel'];
	}

	if(!empty($active_filters['type'])) {
		$link_filters[] = $WP_VINCOD_ENTITIES['type'] . '=' . $active_filters['type']['id'];
	}

	return $link_filters;

}


/**
 * @return string
 */
function wp_vincod_link_add_filter($type, $value, $active_filters) {

	$link_page = wp_vincod_get_permalink(get_option('vincod_id_page_nos_vins'));

	$active_filters[$type] = $value;

	$link_filters = wp_vincod_link_filters($active_filters);

	if(!empty($active_filters)) {
		$filters = (wp_vincod_permalinks_used()) ? '?' . implode('&', $link_filters) : '&' . implode('&', $link_filters);
	}

	return $link_page . ((!empty($filters)) ? $filters : '');

}


/**
 * @return string
 */
function wp_vincod_link_remove_filter($type, $active_filters) {

	$link_page = wp_vincod_get_permalink(get_option('vincod_id_page_nos_vins'));

	unset($active_filters[$type]);

	$link_filters = wp_vincod_link_filters($active_filters);

	if(!empty($active_filters)) {
		$filters = (wp_vincod_permalinks_used()) ? '?' . implode('&', $link_filters) : '&' . implode('&', $link_filters);
	}

	return $link_page . ((!empty($filters)) ? $filters : '');

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

	$allowed_type_vincod = array(80, 320, 640, 1024, 2048);
	$allowed_type_wml = array('mini', 320, 640, 1024, 'retina');

	if(!in_array($type, $allowed_type_vincod) && !in_array($type, $allowed_type_wml))
		$type = 640;

	// Get domain of url
	$elements = parse_url($url);

	if($type == 2048) {
		$type = 'retina';
	}
	elseif($type == 80) {
		$type = 'mini';
	}

	$infos = pathinfo($url);
	$pattern = '_retina.' . $infos['extension']; // Ex. > .jpg // par défaut l'api remonte _retina
	$replace_pattern = '_' . $type . '.' . $infos['extension']; // Ex > _640.jpg
	$url = str_replace($pattern, $replace_pattern, $url);

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
function wp_vincod_include_video($value) {

	// Maybe add this in dashboard to customise width & height ?
	$width = 400;
	$height = 250;

	if(preg_match('/^http(s?):\/\/vimeo.com\/([0-9_-]+)/', $value, $matches)) {
		return '<iframe src="http://player.vimeo.com/video/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen loading="lazy"></iframe>';
	}
	else if(preg_match('/^http(s?):\/\/www.youtube.com\/watch\?v=([a-zA-Z0-9_-]+)&?/', $value, $matches)) {
		return '<iframe src="http://www.youtube.com/embed/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen loading="lazy"></iframe>';
	}
	else if(preg_match('/^http(s?):\/\/www.dailymotion.com\/video\/([a-zA-Z0-9_-]+)_/', $value, $matches)) {
		return '<iframe src="http://www.dailymotion.com/embed/video/' . $matches[2] . '" width="' . $width . '" height="' . $height . '" frameborder="0" allowFullScreen loading="lazy"></iframe>';
	}

	return false;

}


/**
 * Get the Menu
 *
 * @return string
 */
function wp_vincod_get_search_form($icon = 'search') {

	ob_start();

	?>

	<form method="POST" action="<?= wp_vincod_get_permalink(get_option('vincod_id_page_nos_vins')); ?>" id="vincod-search-form" class="vincod-search-form">

		<?php wp_nonce_field('wp_vincod_search_form', 'wp_vincod_search_nonce'); ?>

		<div class="input-group">
			<input type="text" class="form-control" name="search_wine" placeholder="<?php _e('Search', 'vincod'); ?>">
			<button class="btn btn-outline-primary" type="submit">
				<?= wp_vincod_get_icon($icon); ?>
			</button>
		</div>

	</form>

	<?php

	return ob_get_clean();
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
 * Get the Menu
 *
 * @return string
 */
function wp_vincod_get_menu($vincod = null) {

	$api = new WP_Vincod_API();

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

	$menu = '';

	if(isset($menu_array['menu']) && isset($menu_array['menu'][0])) {

		$menu .= '<ul class="vincod-menu">';

		foreach($menu_array['menu'] as $sub_menu) {
			$menu .= wp_vincod_render_menu_links($sub_menu);
		}

		$menu .= '</ul>';
	}
	elseif(isset($menu_array['menu'])) {

		$menu .= '<ul class="vincod-menu">';

		$menu .= wp_vincod_render_menu_links($menu_array['menu']);

		$menu .= '</ul>';
	}

	return $menu;
}

/**
 * Render the Menu Links
 *
 * @return string
 */
function wp_vincod_render_menu_links($sub_menu) {

	$menu = '';

	$permalink_type = wp_vincod_menu_permalink_type($sub_menu['@type']);

	$menu_link = ($permalink_type == 'owner') ? get_permalink() : wp_vincod_link($permalink_type, $sub_menu['@vincod'], $sub_menu['title']);

	$is_active = (isset($sub_menu['actif']) && $sub_menu['actif'] == 1) ? ' active' : '';
	$is_parent = (isset($sub_menu['@fil_ariane']) && $sub_menu['@fil_ariane'] == 1) ? ' parent' : '';

	$menu .= '<li class="vincod-menu-item ' . $permalink_type . $is_parent . $is_active . '">';
	$menu .= '<a href="' . $menu_link . '" title="' . $sub_menu['title'] . '">' . $sub_menu['title'] . '</a>';

	if(isset($sub_menu['menu'])) {
		$menu .= wp_vincod_render_menu($sub_menu);
	}

	$menu .= '</li>';

	return $menu;
}


/**
 * Get the Breadcrumb
 *
 * @return string
 */
function wp_vincod_get_breadcrumb($vincod = null) {

	$api = new WP_Vincod_API();

	$menu = $api->get_catalogue_by_vincod($vincod);

	$menu = wp_vincod_render_breadcrumb($menu);

	return $menu;
}


/**
 * Render the Breacrumb
 *
 * @return string
 */
function wp_vincod_render_breadcrumb($menu_array) {

	$menu = '';

	if(isset($menu_array['menu']) && isset($menu_array['menu'][0])) {

		foreach($menu_array['menu'] as $sub_menu) {

			$menu .= wp_vincod_render_breadcrumb_links($sub_menu);

		}

	}
	elseif(isset($menu_array['menu'])) {

		$menu .= wp_vincod_render_breadcrumb_links($menu_array['menu']);

	}

	return $menu;
}

/**
 * Render the Breacrumb
 *
 * @return string
 */
function wp_vincod_render_breadcrumb_links($sub_menu) {

	$menu = '';

	$is_active = (isset($sub_menu['actif']) && $sub_menu['actif'] == 1);
	$is_parent = (isset($sub_menu['@fil_ariane']) && $sub_menu['@fil_ariane'] == 1);

	if($is_active || $is_parent) {

		$permalink_type = wp_vincod_menu_permalink_type($sub_menu['@type']);

		$menu = '<li class="breadcrumb-item vincod-breadcrumb ' . $permalink_type . ($is_active ? ' active' : '') . '">';

		$menu_link = ($permalink_type == 'owner') ? get_permalink() : wp_vincod_link($permalink_type, $sub_menu['@vincod'], $sub_menu['title']);

		$menu .= '<a href="' . $menu_link . '" title="' . $sub_menu['title'] . '">' . $sub_menu['title'] . '</a>';

		$menu .= '</li>';

		if(isset($sub_menu['menu'])) {
			$menu .= wp_vincod_render_breadcrumb($sub_menu);
		}

	}

	return $menu;

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

/**
 * Return SVG icon
 *
 * @return string
 */

function wp_vincod_get_icon($name) {

	$icons = array(

		'arrow' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/></svg>',

		'dropdown' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>',

		'menu' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M96 241h320v32H96zM96 145h320v32H96zM96 337h320v32H96z"/></svg>',

		'close' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M405 136.798L375.202 107 256 226.202 136.798 107 107 136.798 226.202 256 107 375.202 136.798 405 256 285.798 375.202 405 405 375.202 285.798 256z"/></svg>',

		'info' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M480 253C478.3 129.3 376.7 30.4 253 32S30.4 135.3 32 259c1.7 123.7 103.3 222.6 227 221 123.7-1.7 222.7-103.3 221-227zM256 111.9c17.7 0 32 14.3 32 32s-14.3 32-32 32-32-14.3-32-32 14.3-32 32-32zM300 395h-88v-11h22V224h-22v-12h66v172h22v11z"/></svg>',

		'search' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M337.509 305.372h-17.501l-6.571-5.486c20.791-25.232 33.922-57.054 33.922-93.257C347.358 127.632 283.896 64 205.135 64 127.452 64 64 127.632 64 206.629s63.452 142.628 142.225 142.628c35.011 0 67.831-13.167 92.991-34.008l6.561 5.487v17.551L415.18 448 448 415.086 337.509 305.372zm-131.284 0c-54.702 0-98.463-43.887-98.463-98.743 0-54.858 43.761-98.742 98.463-98.742 54.7 0 98.462 43.884 98.462 98.742 0 54.856-43.762 98.743-98.462 98.743z"/></svg>',

		'cart' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M169.6 377.6c-22.882 0-41.6 18.718-41.6 41.601 0 22.882 18.718 41.6 41.6 41.6s41.601-18.718 41.601-41.6c-.001-22.884-18.72-41.601-41.601-41.601zM48 51.2v41.6h41.6l74.883 151.682-31.308 50.954c-3.118 5.2-5.2 12.482-5.2 19.765 0 27.85 19.025 41.6 44.825 41.6H416v-40H177.893c-3.118 0-5.2-2.082-5.2-5.2 0-1.036 2.207-5.2 2.207-5.2l20.782-32.8h154.954c15.601 0 29.128-8.317 36.4-21.836l74.882-128.8c1.237-2.461 2.082-6.246 2.082-10.399 0-11.446-9.364-19.765-20.8-19.765H135.364L115.6 51.2H48zm326.399 326.4c-22.882 0-41.6 18.718-41.6 41.601 0 22.882 18.718 41.6 41.6 41.6S416 442.082 416 419.2c0-22.883-18.719-41.6-41.601-41.6z"/></svg>',

		'bottle' => '<svg class="vincod-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 117.68 460.62"><path fill="currentColor" d="m58.84.21c5.82-.24 11.69-.52 17.45.7 1.78.38 3.48 1.13 5.21 1.71 1.31 2.61 3.15 5.04 3.3 8.06.18 3.48.08 6.98.09 10.47 0 .61.11 1.39-.2 1.82-1.8 2.45-1.28 5.24-1.23 7.93.03 1.71 1.15 73.39 2.02 79.47.36 2.48.84 4.82 1.82 7.1 2.34 5.44 6.64 9.3 10.97 12.99 2.77 2.36 5.32 4.83 7.46 7.72 3.54 4.78 6.32 9.98 8.45 15.56 2.59 6.78 3.51 13.91 3.49 21-.06 25.4.21 50.81-.37 76.22-.67 29.42-.16 58.87-.16 88.3h-.09c0 10.85.13 21.7-.03 32.55-.31 20.79.75 41.59-.65 62.37-.33 4.88-1.52 9.44-3.8 13.76-1.52 2.88-3.75 4.73-6.76 5.97-5.26 2.18-10.67 3.53-16.27 4.54-7.94 1.44-22.65 2.23-30.71 2.15-8.06.08-22.77-.71-30.71-2.15-5.6-1.02-11.02-2.37-16.27-4.54-3.01-1.24-5.24-3.1-6.76-5.97-2.28-4.32-3.47-8.88-3.8-13.76-1.4-20.78-.34-41.58-.65-62.37-.16-10.85-.03-21.7-.03-32.55h-.09c0-29.44.51-58.88-.16-88.3-.56-25.41-.29-50.81-.35-76.22-.02-7.09.9-14.22 3.49-21 2.13-5.58 4.91-10.78 8.45-15.56 2.14-2.89 4.69-5.36 7.46-7.72 4.33-3.69 8.63-7.54 10.97-12.99.98-2.28 1.46-4.62 1.82-7.1.87-6.07 1.99-77.76 2.02-79.47.05-2.69.57-5.48-1.23-7.93-.31-.43-.2-1.2-.2-1.82 0-3.49-.09-6.99.09-10.47.15-3.02 1.99-5.46 3.3-8.06 1.74-.58 3.43-1.34 5.21-1.71 5.77-1.22 11.63-.94 17.45-.7z"/></svg>',

	);

	return (isset($icons[$name])) ? $icons[$name] : null;
}
