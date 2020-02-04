<?php
/**
 * Dashboard
 *
 * It's the admin dashboard which you can view into wordpress settings (vincod)
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2016 VINTERNET
 *
 */
?>

<script>
	// Here php can talk with javascript like a charm
	var vincod_plugin_app = {
		"api": '<?= WP_VINCOD_PLUGIN_URL . "functions/api-test.php" ?>',
		"api_connexion_try": "<?php _e("Trying to connect to Vincod API ...", 'vincod') ?>",
		"api_connexion_error": "<?php _e("Unable to connect to Vincod API.", 'vincod') ?>",
		"api_connexion_success": "<?php _e("Vincod API connection success !", 'vincod') ?>",
		"api_connexion_save": "<?php _e("Don't forget to validate your setting before leaving the page !", 'vincod') ?>",
		"api_connexion_missing": "<?php _e("Missing API key and/or Account id.", 'vincod') ?>"
	}
</script>


<div class="row no-gutters">
	
	<div class="col-12 col-xl-11">
		
		<!-- The subheader with title and description -->
		<div class="page-header">
			<h1><?php _e("Vincod - Dashboard", 'vincod') ?></h1>
		</div>
		<p class="lead mb-4"><?php _e("Do you need to manage your plugin ? Make some datas analysis ? Customize what will appear ? Here you go...", 'vincod') ?></p>
		
		
		<!-- When you clean the app by the reset button, this message appear -->
		<?php if(isset($app_cleaned)): ?>
			<div class="alert alert-danger">
				<strong><?php _e("You reset your plugin", 'vincod') ?></strong>
				<br/>
				<?php _e("Your plugin has been reset, please fill your API/account credentials.", 'vincod') ?>
			</div>
		<?php endif; ?>
		
		
		<?php if(!isset($_COOKIE['wp_vincod_first_visit'])): ?>
			<!-- Simple help for the first visit -->
			<div id="first-time-visit" class="alert alert-primary alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<?= wp_vincod_get_icon('close'); ?>
				</button>
				<h4 class="d-flex align-items-center my-2">
					<?= wp_vincod_get_icon('info'); ?>
					<span class="ml-1"><?php _e('First visit', 'vincod') ?> ?</span>
				</h4>
				<p class="my-2"><?php _e("Is it your first visit ? For Vincod plugin to work properly, you first need to fill in set up informations. You need a valide API Key and an authorized account id. If needed email", 'vincod') ?>
					<a href='mailto:support@vincod.com'>support@vincod.com</a>.
				</p>
			</div>
		<?php endif; ?>
		
		
		<!-- Settings -->
		<div class="page-header my-4">
			<h2><?php _e("Settings", 'vincod') ?></h2>
		</div>
		
		<form id="settings" name="settings" method="POST" target="_self" class="my-4" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><?php _e("Your API key", 'vincod') ?></span>
				</div>
				<input type="text" class="form-control" name="vincod_setting_customer_api" value="<?= get_option('vincod_setting_customer_api') ?>" placeholder="<?php _e("The API key from your Vincod customer account", 'vincod') ?>">
			</div>
			
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><?php _e("Account id", 'vincod') ?></span>
				</div>
				<input type="text" class="form-control" name="vincod_setting_customer_id" value="<?= get_option('vincod_setting_customer_id') ?>" placeholder="<?php _e("Your Vincod account id", 'vincod') ?>">
			</div>
			
			<p><?php _e("If you want only one brand from the account, specify the brand id. If needed email", 'vincod') ?>
				<a href='mailto:support@vincod.com'>support@vincod.com</a>.
			</p>
			
			<div class="input-group">
				<div class="input-group-prepend">
					<span class="input-group-text"><?php _e("Brand id", 'vincod') ?></span>
				</div>
				<input type="text" class="form-control" name="vincod_setting_customer_winery_id" value="<?= get_option('vincod_setting_customer_winery_id') ?>" placeholder="<?php _e("Your brand Vincod id", 'vincod') ?>">
			</div>
			
			<div class="buttons-group">
				<button type="submit" id="api_connection_check" class="btn btn-outline-primary">
					<?php _e("Check API", 'vincod') ?>
				</button>
				<button type="submit" id="remove_settings" name="vincod_setting_remove" class="btn btn-danger">
					<?php _e("Remove my credentials", 'vincod') ?>
				</button>
				<button type="submit" id="validate_settings" class="btn btn-secondary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
			</div>
			
			<div id="api_connection_check_console" class="devlog auto">
				<div class="clearfix"></div>
			</div>
		
		</form>
		
		
		<!-- Cache Api -->
		<div class="page-header my-4">
			<h2><?php _e("API Cache", 'vincod') ?></h2>
		</div>
		
		<form id="cache_api" method="POST" target="_self" class="my-4" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text"><?php _e("Cache duration", 'vincod'); ?></span>
				</div>
				<input type="number" min="0" class="form-control" name="vincod_setting_cache_api" value="<?= get_option('vincod_setting_cache_api') ?>" placeholder="<?php _e("Example : 1 (0 to do not cache at all)", 'vincod') ?>">
				<div class="input-group-append">
					<span class="input-group-text"><?php _e("day(s)", 'vincod'); ?></span>
				</div>
			</div>
			
			<div class="buttons-group">
				<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-cache" aria-expanded="false" aria-controls="collapse-cache">
					<?= wp_vincod_get_icon('info'); ?>
					<span class="ml-1"><?php _e("What is this", 'vincod') ?> ?</span>
				</button>
				<button type="submit" id="clear_cache" name="vincod_clear_cache" class="btn btn-danger">
					<?php _e("Clear Cache", 'vincod') ?>
				</button>
				<button type="submit" name="" class="btn btn-secondary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
				<div class="collapse" id="collapse-cache">
					<div class="card card-body">
						<?php _e("The plugin communicate with the vincod API to get different details. It will cache these datas to improve the global treatment speed. You can setup the cache duration with the previous form.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
		
		
		<!-- Styles -->
		<div class="page-header my-4">
			<h2>Style</h2>
		</div>
		
		<?php
		
		$style_settings = array('has_menu', 'has_breadcrumb', 'has_search', 'has_content', 'has_links');
		$templates_names = array('owner', 'collection', 'brand', 'range', 'product', 'search');
		$saved_settings = array();
		
		foreach($templates_names as $value) {
			
			$saved_settings[$value] = get_option('vincod_' . $value . '_settings');
			
		}
		
		?>
		<form id="style" method="POST" target="_self" class="my-4" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<table class="table table-striped">
				<thead>
				<tr>
					<th><?php _e("Template name", 'vincod') ?></th>
					<th><?php _e("Menu", 'vincod') ?></th>
					<th><?php _e("Breadcrumb", 'vincod') ?></th>
					<th><?php _e("Search", 'vincod') ?></th>
					<th><?php _e("Content", 'vincod') ?></th>
					<th><?php _e("Links", 'vincod') ?></th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><?php _e('Owner', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<input type="hidden" name="vincod_owner_settings[<?= $setting ?>]" value="0"/>
							<input type="checkbox" name="vincod_owner_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['owner'][$setting]) && $saved_settings['owner'][$setting]) ? ' checked' : '' ?>/>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td><?php _e('Collection', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<input type="hidden" name="vincod_collection_settings[<?= $setting ?>]" value="0"/>
							<input type="checkbox" name="vincod_collection_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['collection'][$setting]) && $saved_settings['collection'][$setting]) ? ' checked' : '' ?>/>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td><?php _e('Brand', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<input type="hidden" name="vincod_brand_settings[<?= $setting ?>]" value="0"/>
							<input type="checkbox" name="vincod_brand_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['brand'][$setting]) && $saved_settings['brand'][$setting]) ? ' checked' : '' ?>/>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td><?php _e('Range', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<input type="hidden" name="vincod_range_settings[<?= $setting ?>]" value="0"/>
							<input type="checkbox" name="vincod_range_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['range'][$setting]) && $saved_settings['range'][$setting] == "1") ? ' checked' : '' ?>/>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td><?php _e('Product', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<?php if($setting != 'has_links'): ?>
								<input type="hidden" name="vincod_product_settings[<?= $setting ?>]" value="0"/>
								<input type="checkbox" name="vincod_product_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['product'][$setting]) && $saved_settings['product'][$setting]) ? ' checked' : '' ?>/>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				<tr>
					<td><?php _e('Search', 'vincod'); ?></td>
					<?php foreach($style_settings as $setting) : ?>
						<td>
							<?php if($setting == 'has_menu' || $setting == 'has_search'): ?>
								<input type="hidden" name="vincod_search_settings[<?= $setting ?>]" value="0"/>
								<input type="checkbox" name="vincod_search_settings[<?= $setting ?>]" value="1"<?= (isset($saved_settings['search'][$setting]) && $saved_settings['search'][$setting]) ? ' checked' : '' ?>/>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				</tbody>
			</table>
			
			<div class="buttons-group">
				<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-style" aria-expanded="false" aria-controls="collapse-style">
					<?= wp_vincod_get_icon('info'); ?>
					<span class="ml-1"><?php _e("What is this", 'vincod') ?> ?</span>
				</button>
				<button type="submit" id="validate_settings" class="btn btn-secondary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
				<div class="collapse" id="collapse-style">
					<div class="card card-body">
						<?php _e("This part allow you to customize your page styles such as the elements showed in the page layout.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
		
		
		<!-- About SEO -->
		<div class="page-header my-4">
			<h2><?php _e("SEO", 'vincod') ?></h2>
		</div>
		
		<form id="seo" name="seo" method="POST" target="_self" class="my-4" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<?php if($sitemap_exists): ?>
				
				<h5 class="mb-3"><?php _e("Your SEO is correctly optimized.", 'vincod') ?>
					(<a href="<?= WP_VINCOD_PLUGIN_URL ?>cache/sitemap/plugin-vincod-sitemap.xml"><?php _e("Check your site-map", 'vincod') ?></a>)
				</h5>
			
			<?php else: ?>
				
				<h5 class="mb-3"><?php _e("Your SEO isn't optimized (site-map was not found)", 'vincod') ?></h5>
			
			<?php endif; ?>
			
			<div class="buttons-group text-sm-left">
				
				<?php if($sitemap_exists): ?>
					
					<input type="hidden" name="vincod_seo_delete" value="">
					<button type="submit" class="btn btn-danger ml-sm-0">
						<?php _e("Remove your sitemap", 'vincod') ?>
					</button>
				
				<?php else: ?>
					
					<input type="hidden" name="vincod_seo_create" value="">
					<button type="submit" name="" class="btn btn-success ml-sm-0">
						<?php _e("Optimize my SEO", 'vincod') ?>
					</button>
					
					<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-sitemap" aria-expanded="false" aria-controls="collapse-sitemap">
						<?= wp_vincod_get_icon('info'); ?>
						<span class="ml-1"><?php _e("What will happen", 'vincod') ?> ?</span>
					</button>
					<div class="collapse" id="collapse-sitemap">
						<div class="card card-body">
							<?php _e("The plugin will generate a sitemap which will be visited by some search engine robots ; each wine will be properly indexed.", 'vincod') ?>
						</div>
					</div>
				
				<?php endif; ?>
			
			</div>
		
		</form>
		
		
		<!-- About debug -->
		<div class="page-header my-4">
			<h2><?php _e("Analysis & Debug", 'vincod') ?></h2>
		</div>
		
		<div class="devlog scroll">
			<div class="devlog_content">
				<div class="title">Console :</div>
				<?php if(!empty($devlog_content)): ?>
					
					<?php foreach($devlog_content as $devlog_current): ?>
						
						<div class="time">
							[<i><?= date('d-m-Y H:i:s', $devlog_current['time']) ?></i>]
						</div>
						<div class="msg"><?= $devlog_current['msg'] ?></div>
					
					<?php endforeach; ?>
				
				<?php else: ?>
					
					<p><?php _e("No log to show", 'vincod') ?></p>
				
				<?php endif; ?>
			</div>
		</div>
		
		<div class="buttons-group text-sm-left">
			<!-- Clear devlog system -->
			<?php if(!empty($devlog_content)): ?>
				
				<form method="POST" target="_self" action="">
					
					<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
					
					<input type="hidden" name="vincod_clear_log" value="">
					<button class="btn btn-danger">
						<?php _e("Remove your devlog", 'vincod') ?>
					</button>
					<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-devlog" aria-expanded="false" aria-controls="collapse-devlog">
						<?= wp_vincod_get_icon('info'); ?>
						<span class="ml-1"><?php _e("What is this", 'vincod') ?> ?</span>
					</button>
					<div class="collapse" id="collapse-devlog">
						<div class="card card-body">
							<?php _e("The plugin save each process that have been done ; what you can see here is the plugin latest API call. If there's any bug, it cans be solved by looking through this console.", 'vincod') ?>
						</div>
					</div>
				</form>
			
			<?php else: ?>
				
				<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-devlog" aria-expanded="false" aria-controls="collapse-devlog">
					<?= wp_vincod_get_icon('info'); ?>
					<span class="ml-1"><?php _e("What is this", 'vincod') ?> ?</span>
				</button>
				<div class="collapse" id="collapse-devlog">
					<div class="card card-body">
						<?php _e("The plugin save each process that have been done ; what you can see here is the plugin latest API call. If there's any bug, it cans be solved by looking through this console.", 'vincod') ?>
					</div>
				</div>
			
			<?php endif; ?>
		</div>
		
		
		<!-- Reset plugin -->
		<div class="page-header my-4">
			<h2><?php _e("Plugin reset", 'vincod') ?></h2>
		</div>
		
		<form id="debug" name="debug" method="POST" target="_self" class="my-4" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="buttons-group text-sm-left">
				
				<input type="hidden" name="vincod_reset_app" value=""/>
				<button type="submit" name="" class="btn btn-danger ml-sm-0">
					<?php _e("Reset my plugin", 'vincod') ?>
				</button>
				
				<button class="btn btn-outline-primary" type="button" data-toggle="collapse" data-target="#collapse-reset" aria-expanded="false" aria-controls="collapse-reset">
					<?= wp_vincod_get_icon('info'); ?>
					<span class="ml-1"><?php _e("Is there a problem with your plugin", 'vincod') ?> ?</span>
				</button>
				<div class="collapse" id="collapse-reset">
					<div class="card card-body">
						<?php _e("If your plugin got bugs, don't hesitate to reset it ; this will refresh your datas and make the structure clean.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
	</div>

</div>
