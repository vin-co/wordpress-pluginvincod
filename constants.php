<?php defined('ABSPATH') OR exit;

/**
 * Plugin constants
 *
 * Constants for vincod plugin
 *
 * @author      Vinternet
 * @category    constants
 * @copyright   2016 VINTERNET
 *
 */

// Name of this plugin
define('WP_VINCOD_PLUGIN_NAME', 'Vincod Plugin');

// Version of this plugin
define('WP_VINCOD_VERSION', '1.0');

// Folder name of this plugin
define('WP_VINCOD_DIRNAME', basename(dirname(__FILE__)));

// Url to access this plugin
define('WP_VINCOD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Url to manage this plugin
define('WP_VINCOD_SETTINGS_URL', admin_url('options-general.php?page=vincod'));

// Path directory current theme
define('WP_VINCOD_CURRENT_THEME_PATH', get_template_directory() . '/');

// Path current plugin
define('WP_VINCOD_PLUGIN_PATH', plugin_dir_path(__FILE__));


?>
