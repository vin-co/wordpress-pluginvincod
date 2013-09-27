<?php
/*
Plugin Name: Vincod
Plugin URI: http://dev.vincod.com/
Description: Ajoute une rubrique vin basée sur l'API Vincod
Version: 0.1
Author: Vinternet
Author URI: http://vinternet.net/
*/


if(!class_exists("Vincod")) 
{
	class Vincod
	{
		public function __construct() 
		{			
			register_activation_hook(__FILE__, array(&$this, 'install_vincod'));
			register_deactivation_hook(__FILE__, array(&$this, 'uninstall_vincod'));
					
			add_action( 'init', array(&$this, 'init_plugin') );
			add_action('admin_menu', array(&$this, 'vincod_admin_menu'));				
		}
		
		public function init_plugin()
		{			
			$args = array(
				 'label' => __('Rubrique Vins'),
				 'singular_label' => __('Rubrique Vins'),
				 'public' => true,
				 'show_ui' => true,
				 'exclude_from_search' => true,
				 '_builtin' => false, // It's a custom post type, not built in
				 '_edit_link' => 'post.php?post=%d',
				 'capability_type' => 'page',
				 'hierarchical' => false,
				 'show_in_menu' => true,
				 'show_in_nav_menus' => true,
				 'rewrite' => array("slug" => "vins"), 
				 'supports' => array('title', 'custom-fields')
			);
			register_post_type( 'vincod_rub' , $args );		
			
			wp_register_style( 'vincodStylesheet', plugins_url( 'css/stylesheet.css', __FILE__ ) );
			wp_enqueue_style( 'vincodStylesheet' );	
		}
		
		public function install_vincod()
		{		
			$post = array(
			  'ID' => '',
			  'comment_status' => 'closed',
			  'ping_status' => 'closed',
			  'post_date' => date('Y-m-d H:i:s'),
			  'post_name' => 'nos-vins',			  
			  'post_status' => 'publish', 
			  'post_title' => 'Nos vins',
			  'post_type' => 'vincod_rub'
			); 
			
			$id = wp_insert_post($post, true); 
			add_post_meta($id, 'lang_vincod', 'fr');
			update_post_meta($id, "_wp_page_template", "vincod_rub.php");
			
			if ( ! copy( WP_PLUGIN_DIR .'/vincod/template/single-vincod_rub.php', TEMPLATEPATH .'/single-vincod_rub.php' ) ) {
				add_action( 'admin_notices', create_function( '', "echo 'Error copy template file';" ) );
			}
		}
		
		public function uninstall_vincod()
		{
			if ( ! unlink( TEMPLATEPATH .'/single-vincod_rub.php' ) ) {
				add_action( 'admin_notices', create_function( '', "echo 'Error delete template file';" ) );
			}
			$posts_array = get_posts( array('post_type' => 'vincod_rub') ); 
			
			foreach($posts_array as $post)
			{				
				wp_delete_post( $post->ID );
			}
		}
		
		public function vincod_admin_menu() 
		{
			add_options_page('Vincod settings', 'Vincod', 'manage_options', 'vincod', array(&$this, 'vincod_options_page'));				
		}
			
		public function vincod_options_page() 
		{
			if (isset($_POST["Submit"]))  
			{  
				update_option("vincod_apiKey", $_POST['vincod_apikey']); 
				update_option("vincod_userId", intval($_POST['vincod_userid']));    
			}  
			
			$options["vincod_apiKey"] = get_option("vincod_apiKey");
			$options["vincod_userId"] = get_option("vincod_userId"); 
		?>
			<style type="text/css">
				label{
				display:inline-block;
				width:100px;
				text-align:right;
				}
				
				.field{
					margin:10px 0;
				}
				
				h2{
					font-size:24px;
				}
			</style>
			<div>
			<h2>Vincod settings</h2>
			
			<form action="" method="post">
				<div class="field">
					<label for="vincod_apikey">API Key *</label> <input type="text" id="vincod_apikey" name="vincod_apikey" value="<?php echo $options["vincod_apiKey"];  ?>" class="regular-text all-options" />
				</div>
				<div class="field">
					<label for="vincod_userid">User ID *</label> <input type="text" id="vincod_userid" name="vincod_userid" value="<?php echo $options["vincod_userId"];  ?>" />
				</div>
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</form></div>
			
			<?php
			
		}		
		
	}
	$wp_vincod = new Vincod();	
}

?>