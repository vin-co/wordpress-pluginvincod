<?php
/**
 * Brand.php
 *
 * The view served by the template when you have got ?brand= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/brand.php ; If you make this you can use
 * all functions and all constants of the plugin.
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2016 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL; ?>assets/css/front.css"/>
<!-- Default plugin js -->
<script type="text/javascript">
	// <![CDATA[
	(function($) {
		$(document).ready(function() {
			if(typeof ($.fn.popover) === 'undefined') {
				var s = document.createElement("script");
				s.type = "text/javascript";
				s.src = "<?= WP_VINCOD_PLUGIN_URL; ?>assets/js/vendor.js";
				document.body.appendChild(s);
			}
		});
	})(jQuery);
	// ]]>
</script>

<section id="plugin-vincod" class="vincod-brand" itemscope itemtype="http://schema.org/Brand">
	
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
			
			<?php if($settings['has_breadcrumb']): ?>
				
				<ol class="breadcrumb">
					<?= $breadcrumb; ?>
				</ol>
			
			<?php endif; ?>
			
			<?php if($settings['has_content']): ?>
				
				<?php if($brand): ?>
					
					<div class="content-panel">
						
						<div class="panel-heading">
							
							<div class="content-cover"<?= ($background = wp_vincod_get_picture_url($brand, 'retina')) ? ' style="background-image: url(' . $background . ')"' : ''; ?>></div>
						
						</div>
						
						<div class="panel-body">
							
							<?php if($background = wp_vincod_get_logo_url($brand, '640')): ?>
								
								<div class="content-logo">
									<span style="background-image: url('<?= $background; ?>')"></span>
								</div>
							
							<?php endif; ?>
							
							<h1 itemprop="name"><?= $brand['name']; ?></h1>
							
							<?php if(!empty($brand['presentation'])): ?>
								
								<div class="content-presentation" itemprop="description">
									<?= nl2br($brand['presentation']); ?>
								</div>
							
							<?php endif; ?>
						
						</div>
					
					</div>
				
				<?php endif; ?>
			
			<?php endif; ?>
			
			<?php if($settings['has_links']): ?>
				
				<!-- Links -->
				<div class="content-links">
					
					<?php if($ranges): ?>
						
						<?php foreach($ranges as $range): ?>
							
							<?php
							
							$range_image = '';
							
							if(wp_vincod_get_picture_url($range, 'retina')) {
								$range_image = ' style="background-image: url(' . wp_vincod_get_picture_url($range, 'retina') . ')"';
							}
							elseif(wp_vincod_get_logo_url($range, '640')) {
								$range_image = ' style="background-image: url(' . wp_vincod_get_logo_url($range, '640') . ')"';
							}
							
							?>
							
							<a href="<?= wp_vincod_link('range', $range['vincod'], $range['name']); ?>" title="<?= $range['name']; ?>" class="range-link"<?= $range_image; ?> itemprop="brand" itemscope itemtype="http://schema.org/Brand">
								
								<h2 itemprop="name"><?= $range['name']; ?></h2>
							
							</a>
						
						<?php endforeach; ?>
					
					<?php elseif(!$ranges && $products): ?>
						
						<?php foreach($products as $product): ?>
							
							<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']); ?>" title="<?= $product['name']; ?>" class="product-link" itemprop="product" itemscope itemtype="http://schema.org/Product">
								
								<img src="<?= wp_vincod_get_bottle_url($product, '640') ?>" alt="<?= $product['name']; ?>"/>
								<h2 itemprop="name"><?= $product['name']; ?></h2>
							
							</a>
						
						<?php endforeach; ?>
					
					<?php else: ?>
						
						<?php _e("Nothing to Show.", 'vincod'); ?>
						<br/>
						<?php _e("Please check your Vincod Account id or enter a Brand Vincod id if you want to show only one brand.", 'vincod'); ?>
					
					<?php endif; ?>
				
				</div>
			
			<?php endif; ?>
		
		</div>
	
	</div>

</section>
