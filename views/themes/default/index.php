<?php
/**
 * Index.php
 *
 * The view served by the template when you haven't got params
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/index.php ; If you make this you can use
 * all functions and all constants of the plugin.
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2016 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL; ?>assets/css/themes/default.css"/>
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

<section id="plugin-vincod" class="vincod-index" itemscope itemtype="http://schema.org/Organization">

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

				<?php if($owner): ?>

					<div class="content-panel">

						<div class="panel-heading">

							<div class="content-cover"<?= ($background = wp_vincod_get_picture_url($owner, 'retina')) ? ' style="background-image: url(' . $background . ')"' : ''; ?>></div>

						</div>

						<div class="panel-body">

							<?php if($logo = wp_vincod_get_logo_url($owner, '640')): ?>

								<div class="content-logo">
									<img src="<?= $logo; ?>" alt="<?= $owner['name']; ?>"/>
								</div>

							<?php endif; ?>

							<h1 itemprop="name"><?= $owner['company']; ?></h1>

							<?php if(!empty($owner['presentation'])): ?>

								<div class="content-presentation" itemprop="description">
									<?= nl2br($owner['presentation']); ?>
								</div>

							<?php endif; ?>

						</div>

					</div>

				<?php endif; ?>

			<?php endif; ?>

			<?php if($settings['has_links']): ?>

				<!-- Links -->
				<div class="content-links">

					<?php if($collections): ?>

						<?php foreach($collections as $collection): ?>

							<?php

							$collection_image = '';

							if(wp_vincod_get_picture_url($collection, 'retina')) {
								$collection_image = ' style="background-image: url(' . wp_vincod_get_picture_url($collection, 'retina') . ')"';
							}
							elseif(wp_vincod_get_logo_url($collection, '640')) {
								$collection_image = ' style="background-image: url(' . wp_vincod_get_logo_url($collection, '640') . ')"';
							}

							?>

							<a href="<?= wp_vincod_link('collection', $collection['vincod'], $collection['name']); ?>" title="<?= $collection['name']; ?>" class="collection-link"<?= $collection_image; ?> itemprop="brand" itemscope itemtype="http://schema.org/Brand">

								<h2 itemprop="name"><?= $collection['name']; ?></h2>

							</a>

						<?php endforeach; ?>

					<?php elseif(!$collections && $brands): ?>

						<?php foreach($brands as $brand): ?>

							<?php

							$brand_image = '';

							if(wp_vincod_get_picture_url($brand, 'retina')) {
								$brand_image = ' style="background-image: url(' . wp_vincod_get_picture_url($brand, 'retina') . ')"';
							}
							elseif(wp_vincod_get_logo_url($brand, '640')) {
								$brand_image = ' style="background-image: url(' . wp_vincod_get_logo_url($brand, '640') . ')"';
							}

							?>

							<a href="<?= wp_vincod_link('brand', $brand['vincod'], $brand['name']); ?>" title="<?= $brand['name']; ?>" class="brand-link"<?= $brand_image; ?> itemprop="brand" itemscope itemtype="http://schema.org/Brand">

								<h2 itemprop="name"><?= $brand['name']; ?></h2>

							</a>

						<?php endforeach; ?>

					<?php else: ?>

						<?php _e("Nothing to Show.", 'vincod'); ?>
						<br/>
						<?php _e("Please check your Vincod Account id or enter a Brand Vincod id if you want to show only one brand.", 'vincod'); ?>

					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if(!empty($owner['certifications'])): ?>

				<div class="labels-container">

					<?php foreach($owner['certifications'] as $certification): ?>
						<div class="vincod-label">
							<img src="<?= $certification['value']; ?>" alt="<?= $certification['label']; ?>">
						</div>
					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>

	</div>

</section>
