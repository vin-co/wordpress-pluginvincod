<?php
if (!function_exists ('wp_vincod_get_setting')) {
function wp_vincod_get_setting($setting, $default='') {

	$opt = get_option($setting);

	if (! $opt OR empty($opt)) {
		return $default;
	} else {
		return $opt;
	}

}
}
?>
<style type="text/css">
.plugin-vincod p { 

	color : <?= wp_vincod_get_setting('vincod_setting_color_general_text') ?> !important;
	font-size : <?= wp_vincod_get_setting('vincod_setting_size_text', WP_VINCOD_TEMPLATE_SIZE_GENERAL_TEXT) ?>px; 

}
.plugin-vincod h2 { 

	color : <?= wp_vincod_get_setting('vincod_setting_color_titles_text') ?> !important;
	font-size : <?= wp_vincod_get_setting('vincod_setting_size_h2', WP_VINCOD_TEMPLATE_SIZE_TITLES_TEXT) ?>px; }
</style>