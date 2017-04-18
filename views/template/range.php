<?php
/**
 * Range.php
 *
 * The view served by the template when you have got ?range= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/views/template/range.php ; If you make this you can use
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
	(function($) {
		$(document).ready(function() {
			if(typeof($.fn.popover) === 'undefined') {
				var s = document.createElement("script");
				s.type = "text/javascript";
				s.src = "<?= WP_VINCOD_PLUGIN_URL ?>assets/js/vendor.js";
				document.body.appendChild(s);
			}
		});
	})(jQuery);
	// ]]>
</script>

<section id="plugin-vincod" class="vincod-range">
	
	<div class="container-fluid vincod-container">
		
		<?php if($settings['has_menu']): ?>
			
			<div class="menu-container col-xs-12 col-md-3">
				
				<a class="btn btn-link hidden-lg hidden-md" role="button" data-toggle="collapse" href="#menu-collapse" aria-expanded="false" aria-controls="menu-collapse">
					<i class="ion-navicon"></i>
					<span>Menu</span>
				</a>
				
				<div class="menu-collapse collapse" id="menu-collapse">
					<div class="well menu-well no-padding">
						<?= $menu; ?>
					</div>
				</div>
			
			</div>
		
		<?php endif; ?>
		
		<div class="content-container <?= ($settings['has_menu']) ? 'col-xs-12 col-md-9' : 'clearfix' ?>">
			
			<?php if($settings['has_breadcrumb']): ?>
				
				<ol class="breadcrumb">
					<?= $breadcrumb; ?>
				</ol>
			
			<?php endif; ?>
			
			
			<?php if($settings['has_content']): ?>
				
				<?php if($range): ?>
					
					<div class="panel panel-default content-panel">
						
						<div class="panel-heading">
							
							<div class="content-cover"<?= ($background = wp_vincod_get_picture_url($range, 'retina')) ? ' style="background-image: url(' . $background . ')"' : '' ?>></div>
						
						</div>
						
						<div class="panel-body">
							
							<?php if($background = wp_vincod_get_logo_url($range, '640')): ?>
								
								<div class="content-logo" style="background-image: url('<?= $background ?>')"></div>
							
							<?php endif; ?>
							
							<h1><?= $range['name'] ?></h1>
							
							<div class="content-presentation">
								<?= !empty($range['presentation']) ? nl2br($range['presentation']) : '' ?>
							</div>
						
						</div>
					
					</div>
				
				<?php endif; ?>
			
			<?php endif; ?>
			
			<!-- Links -->
			<?php if($settings['has_links']): ?>
				
				<div class="content-links clearfix">
					
					<?php if($products): ?>
						
						<?php foreach($products as $product): ?>
							
							<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']) ?>" title="<?= $product['name'] ?>">
								
								<div class="col-xs-12 col-sm-6 col-md-4 col-centered product-link">
									
									<img class="img-responsive" src="<?= wp_vincod_get_bottle_url($product, '640') ?>" alt="<?= $product['name']; ?>"/>
									<h2><?= $product['name'] ?></h2>
								
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
