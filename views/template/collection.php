<?php
/**
 * Collection.php
 *
 * The view served by the template when you have got ?collection= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/views/template/collection.php ; If you make this you can use
 * all functions and all constants of the plugin.
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2016 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL ?>assets/css/front.css"/>
<!-- Default plugin js -->
<script type="text/javascript">
	// <![CDATA[
	(function($){
		$(document).ready(function() {
			if(typeof($.fn.popover) === 'undefined') {
				var s = document.createElement("script");
				s.type = "text/javascript";
				s.src = "<?= WP_VINCOD_PLUGIN_URL ?>assets/js/vendor.js";
				document.body.appendChild(script);
			}
		});
	})(jQuery);
	// ]]>
</script>

<section id="plugin-vincod" class="vincod-collection">
	
	<div class="container-fluid vincod-container">
		
		<?php if($settings['has_menu']): ?>
			
			<div class="menu-container col-xs-12 col-md-3">
				
				<a class="btn btn-link hidden-lg hidden-md" role="button" data-toggle="collapse" href="#menu-collapse" aria-expanded="false" aria-controls="menu-collapse">
					<i class="ion-navicon"></i>
					<span>Menu</span>
				</a>
				
				<div class="menu-collapse collapse" id="menu-collapse">
					<div class="well menu-well no-padding">
						<?= $menu ?>
					</div>
				</div>
			
			</div>
			
		<?php endif; ?>
		
		<div class="content-container <?= ($settings['has_menu']) ? 'col-xs-12 col-md-9' : 'clearfix' ?>">
			
			<?php if($settings['has_content']): ?>
				
				<?php if($collection): ?>
					
					<div class="panel panel-default content-panel">
						
						<div class="panel-heading">
							
							<div class="content-cover"<?= ($background = wp_vincod_get_picture_url($collection, 'retina')) ? ' style="background-image: url(' . $background . ')"' : '' ?>></div>
						
						</div>
						
						<div class="panel-body">
							
							<?php if($background = wp_vincod_get_logo_url($collection, '640')): ?>
								
								<div class="content-logo" style="background-image: url('<?= $background ?>')"></div>
							
							<?php endif; ?>
							
							<h1><?= $collection['name'] ?></h1>
							
							<div class="content-presentation">
								<?= !empty($collection['presentation']) ? nl2br($collection['presentation']) : '' ?>
							</div>
						
						</div>
					
					</div>
				
				<?php endif; ?>
			
			<?php endif; ?>
			
			<!-- Links -->
			<?php if($settings['has_links']): ?>
				
				<div class="content-links">
					
					<?php if($brands): ?>
						
						<?php foreach($brands as $brand): ?>
							
							<a href="<?= wp_vincod_link('brand', $brand['vincod'], $brand['name']) ?>" title="<?= $brand['name'] ?>">
								
							<?php if(wp_vincod_get_picture_url($brand, 'retina')): ?>
								
								<div class="well brand-link" style="background-image: url('<?= wp_vincod_get_picture_url($brand, 'retina') ?>')">
									
							<?php elseif(wp_vincod_get_logo_url($brand, 'retina')): ?>
									
								<div class="well brand-link" style="background-image: url('<?= wp_vincod_get_logo_url($brand, 'retina') ?>')">
								
							<?php else: ?>
								
								<div class="well brand-link">
									
							<?php endif; ?>
									
									<div class="block">
										
										<div class="centered-item">
											
											<h2><?= $brand['name'] ?></h2>
										
										</div>
									
									</div>
								
								</div>
							
							</a>
						
						<?php endforeach; ?>
					
					<?php else: ?>
						
						<?php _e("Nothing to Show.", 'vincod') ?>
						<br/>
						<?php _e("Please check your Vincod Account id or enter a Brand Vincod id if you want to show only one brand.", 'vincod') ?>
					
					<?php endif; ?>
					
				</div>
			
			<?php endif; ?>
		
		</div>
	
	</div>

</section>
