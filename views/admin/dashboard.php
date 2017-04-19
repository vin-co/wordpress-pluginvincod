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
		
		"api":                   '<?= WP_VINCOD_PLUGIN_URL . "functions/api-test.php" ?>',
		"api_connexion_try":     "<?php _e("Trying to connect to Vincod API ...", 'vincod') ?>",
		"api_connexion_error":   "<?php _e("Unable to connect to Vincod API.", 'vincod') ?>",
		"api_connexion_success": "<?php _e("Vincod API connection success !", 'vincod') ?>",
		"api_connexion_save":    "<?php _e("Don't forget to validate your setting before leaving the page !", 'vincod') ?>",
		"api_connexion_missing": "<?php _e("Missing API key and/or Account id.", 'vincod') ?>"
		
	}
</script>


<div class="container-fluid">
	<div class="col-xs-12 col-lg-11 no-padding">
		<!-- The subheader with title and description -->
		<div class="page-header">
			<h1><?php _e("Vincod - Dashboard", 'vincod') ?></h1>
		</div>
		<p class="lead"><?php _e("Do you need to manage your plugin ? Make some datas analysis ? Customize what will appear ? Here you go...", 'vincod') ?></p>
		
		
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
			<div id="first-time-visit" class="alert alert-info alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span class="ion-android-close" aria-hidden="true"></span>
				</button>
				<h4>
					<i class="ion-help-circled"></i> <?php _e('First visit', 'vincod') ?> ?
				</h4>
				<p><?php _e("Is it your first visit ? For Vincod plugin to work properly, you first need to fill in set up informations. You need a valide API Key and an authorized account id. If needed email", 'vincod') ?>
					<a href='mailto:support@vincod.com'>support@vincod.com</a>.
				</p>
			</div>
		<?php endif ?>
		
		
		<!-- Settings -->
		<div class="page-header">
			<h2><?php _e("Settings", 'vincod') ?></h2>
		</div>
		
		<form id="settings" name="settings" method="POST" target="_self" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="input-group">
				<span class="input-group-addon"><?php _e("Your API key", 'vincod') ?></span>
				<input type="text" class="form-control" name="vincod_setting_customer_api" value="<?= get_option('vincod_setting_customer_api') ?>" placeholder="<?php _e("The API key from your Vincod customer account", 'vincod') ?>">
			</div>
			
			<div class="input-group">
				<span class="input-group-addon"><?php _e("Account id", 'vincod') ?></span>
				<input type="text" class="form-control" name="vincod_setting_customer_id" value="<?= get_option('vincod_setting_customer_id') ?>" placeholder="<?php _e("Your Vincod account id", 'vincod') ?>">
			</div>
			
			<p><?php _e("If you want only one brand from the account, specify the brand id. If needed email", 'vincod') ?>
				<a href='mailto:support@vincod.com'>support@vincod.com</a>.
			</p>
			<div class="input-group">
				<span class="input-group-addon"><?php _e("Brand id", 'vincod') ?></span>
				<input type="text" class="form-control" name="vincod_setting_customer_winery_id" value="<?= get_option('vincod_setting_customer_winery_id') ?>" placeholder="<?php _e("Your brand Vincod id", 'vincod') ?>">
			</div>
			
			<div class="buttons-group text-right">
				<button type="submit" id="api_connection_check" class="btn btn-default">
					<?php _e("Check API", 'vincod') ?>
				</button>
				<button type="submit" id="remove_settings" name="vincod_setting_remove" class="btn btn-danger">
					<?php _e("Remove my credentials", 'vincod') ?>
				</button>
				<button type="submit" id="validate_settings" class="btn btn-primary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
			</div>
			
			<div id="api_connection_check_console" class="devlog auto">
				<div class="clearfix"></div>
			</div>
		
		</form>
		
		
		<!-- Cache Api -->
		<div class="page-header">
			<h2><?php _e("API Cache", 'vincod') ?></h2>
		</div>
		
		<form method="POST" action="" target="_self" id="cache_api">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="input-group">
				<span class="input-group-addon"><?php _e("Cache duration", 'vincod') ?></span>
				<input type="text" class="form-control" name="vincod_setting_cache_api" value="<?= get_option('vincod_setting_cache_api') ?>" placeholder="<?php _e("Example : 1 (0 to do not cache at all)", 'vincod') ?>">
				<span class="input-group-addon"><?php _e("day(s)", 'vincod') ?></span>
			</div>
			
			<div class="buttons-group text-right">
				<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-cache" aria-expanded="false" aria-controls="collapse-cache">
					<i class="ion-help-circled"></i> <?php _e("What is this", 'vincod') ?> ?
				</button>
				<button type="submit" id="clear_cache" name="vincod_clear_cache" class="btn btn-danger">
					<?php _e("Clear Cache", 'vincod') ?>
				</button>
				<button type="submit" name="" class="btn btn-primary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
				<div class="collapse" id="collapse-cache">
					<div class="well no-margin text-left">
						<?php _e("The plugin communicate with the vincod API to get different details. It will cache these datas to improve the global treatment speed. You can setup the cache duration with the previous form.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
		
		
		<!-- Styles -->
		<div class="page-header">
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
		<form method="POST" action="" target="_self" id="style">
			
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
			
			<div class="buttons-group text-right">
				<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-style" aria-expanded="false" aria-controls="collapse-style">
					<i class="ion-help-circled"></i> <?php _e("What is this", 'vincod') ?> ?
				</button>
				<button type="submit" id="validate_settings" class="btn btn-primary">
					<?php _e("Validate my settings", 'vincod') ?>
				</button>
				<div class="collapse" id="collapse-style">
					<div class="well no-margin text-left">
						<?php _e("This part allow you to customize your page styles such as the elements showed in the page layout.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
		
		
		<!-- About SEO -->
		<div class="page-header">
			<h2><?php _e("SEO", 'vincod') ?></h2>
		</div>
		
		<form id="seo" name="seo" method="POST" target="_self" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<?php if($sitemap_exists): ?>
				
				<h5><?php _e("Your SEO is correctly optimized.", 'vincod') ?>
					(<a href="<?= WP_VINCOD_PLUGIN_URL ?>cache/sitemap/plugin-vincod-sitemap.xml"><?php _e("Check your site-map", 'vincod') ?></a>)
				</h5>
			
			<?php else: ?>
				
				<h5><?php _e("Your SEO isn't optimized (site-map was not found)", 'vincod') ?></h5>
			
			<?php endif; ?>
			
			<?php if($sitemap_exists): ?>
				
				<input type="hidden" name="vincod_seo_delete" value="">
				<button type="submit" name="" class="btn btn-danger">
					<i class="icon-globe"></i> <?php _e("Remove your sitemap", 'vincod') ?>
				</button>
			
			<?php else: ?>
				
				<div class="buttons-group">
					<input type="hidden" name="vincod_seo_create" value="">
					<button type="submit" name="" class="btn btn-success">
						<?php _e("Optimize my SEO", 'vincod') ?>
					</button>
					
					<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-sitemap" aria-expanded="false" aria-controls="collapse-sitemap">
						<i class="ion-information-circled"></i> <?php _e("What will happen", 'vincod') ?> ?
					</button>
					<div class="collapse" id="collapse-sitemap">
						<div class="well no-margin">
							<?php _e("The plugin will generate a sitemap which will be visited by some search engine robots ; each wine will be properly indexed.", 'vincod') ?>
						</div>
					</div>
				</div>
			
			<?php endif; ?>
		
		</form>
		
		
		<!-- About debug -->
		<div class="page-header">
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
		
		<div class="buttons-group">
			<!-- Clear devlog system -->
			<?php if(!empty($devlog_content)): ?>
				
				<form method="POST" target="_self" action="">
					
					<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
					
					<input type="hidden" name="vincod_clear_log" value="">
					<button class="btn btn-danger">
						<?php _e("Remove your devlog", 'vincod') ?>
					</button>
					<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-devlog" aria-expanded="false" aria-controls="collapse-devlog">
						<i class="ion-help-circled"></i> <?php _e("What is this", 'vincod') ?> ?
					</button>
					<div class="collapse" id="collapse-devlog">
						<div class="well no-margin">
							<?php _e("The plugin save each process that have been done ; what you can see here is the plugin latest API call. If there's any bug, it cans be solved by looking through this console.", 'vincod') ?>
						</div>
					</div>
				</form>
			
			<?php else: ?>
				
				<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-devlog" aria-expanded="false" aria-controls="collapse-devlog">
					<i class="ion-help-circled"></i> <?php _e("What is this", 'vincod') ?> ?
				</button>
				<div class="collapse" id="collapse-devlog">
					<div class="well no-margin">
						<?php _e("The plugin save each process that have been done ; what you can see here is the plugin latest API call. If there's any bug, it cans be solved by looking through this console.", 'vincod') ?>
					</div>
				</div>
			
			<?php endif; ?>
		</div>
		
		
		<!-- Reset plugin -->
		<div class="page-header">
			<h2><?php _e("Plugin reset", 'vincod') ?></h2>
		</div>
		
		<form id="debug" name="debug" method="POST" target="_self" action="">
			
			<?php wp_nonce_field('wp_vincod_admin_form', 'wp_vincod_admin_nonce'); ?>
			
			<div class="buttons-group">
				<input type="hidden" name="vincod_reset_app" value=""/>
				<button type="submit" name="" class="btn btn-danger">
					<?php _e("Reset my plugin", 'vincod') ?>
				</button>
				
				<button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse-reset" aria-expanded="false" aria-controls="collapse-reset">
					<i class="ion-help-circled"></i> <?php _e("Is there a problem with your plugin", 'vincod') ?> ?
				</button>
				<div class="collapse" id="collapse-reset">
					<div class="well no-margin">
						<?php _e("If your plugin got bugs, don't hesitate to reset it ; this will refresh your datas and make the structure clean.", 'vincod') ?>
					</div>
				</div>
			</div>
		
		</form>
	</div>

</div>

