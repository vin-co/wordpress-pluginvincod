<?php
/**
 * Search.php
 *
 * The view served by the template when you have got ?search_wine= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/search.php ; If you make this you can use
 * all functions and all constants of the plugin.
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2016 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL; ?>assets/css/themes/modern.css"/>
<!-- Default plugin js -->
<script type="text/javascript">
	// <![CDATA[
	(function($) {
		$(document).ready(function() {
			if(typeof ($.fn.popover) === 'undefined') {
				var s = document.createElement("script");
				s.type = "text/javascript";
				s.src = "<?= WP_VINCOD_PLUGIN_URL ?>assets/js/vendor.js";
				document.body.appendChild(s);
			}
		});
	})(jQuery);
	// ]]>
</script>

<section id="plugin-vincod" class="vincod-search">
	
	<div class="vincod-container">
		
		<?php if($settings['has_menu'] || $settings['has_search']): ?>
			
			<div class="menu-container">
				
				<?php if($settings['has_menu']): ?>
					
					<a class="btn btn-link" role="button" data-toggle="collapse" href="#menu-collapse" aria-expanded="false" aria-controls="menu-collapse">
						<?= wp_vincod_get_icon('menu'); ?>
						<span>Menu</span>
					</a>
					
					<div class="menu-collapse collapse" id="menu-collapse">
						
						<div class="menu-card">
							<?= $menu; ?>
						</div>
					
					</div>
				
				<?php endif; ?>
				
				<?php if($settings['has_search']): ?>
					
					<?= $search_form; ?>
				
				<?php endif; ?>
			
			</div>
		
		<?php endif; ?>
		
		<div class="content-container">
			
			<div class="content-panel">
				
				<div class="panel-body">
					
					<h1><?php _e('Search results for :', 'vincod'); ?></h1>
					
					<h2 class="content-presentation">
						<?= $search; ?>
					</h2>
				
				</div>
			
			</div>
			
			<!-- Links -->
			<div class="content-links">
				
				<?php if($products): ?>
					
					<?php foreach($products as $product): ?>
						
						<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']); ?>" title="<?= $product['name']; ?>" class="product-link">
							
							<img src="<?= wp_vincod_get_bottle_url($product, '640') ?>" alt="<?= $product['name']; ?>"/>
							<h2><?= $product['name']; ?></h2>
						
						</a>
					
					<?php endforeach; ?>
				
				<?php elseif($error): ?>
					
					<h3><?= $error; ?>.</h3>
				
				<?php else: ?>
					
					<h3><?php _e("No product found.", 'vincod'); ?></h3>
				
				<?php endif; ?>
			
			</div>
		
		</div>
	
	</div>

</section>
