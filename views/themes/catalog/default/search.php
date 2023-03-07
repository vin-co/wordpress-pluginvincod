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
 * @copyright   2023 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL; ?>assets/css/themes/catalog/default.css"/>
<!-- Default plugin js -->
<script type="text/javascript">
	// <![CDATA[
	(function($) {
		$(document).ready(function() {
			var s = document.createElement("script");
			s.type = "text/javascript";
			s.src = "<?= WP_VINCOD_PLUGIN_URL; ?>assets/js/vendor.js";
			document.body.appendChild(s);
		});
	})(jQuery);
	// ]]>
</script>

<section id="plugin-vincod" class="vincod-search">

	<div class="vincod-container">


		<div class="filters-container">

			<div class="available-filters">

				<div class="filters-buttons">

					<div class="accordion accordion-buttons">

						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#filters-collapse" aria-expanded="false" aria-controls="filters-collapse">
							<?php _e("Filter by", 'vincod'); ?>
						</button>

						<?php if($settings['has_vintages'] && !empty($filters['vintage'])): ?>

							<button class="accordion-button collapsed"<?= (isset($active_filters['vintage'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#vintage-accordion" aria-expanded="false" aria-controls="vintage-accordion">
								<?php _e("Vintage", 'vincod'); ?>
							</button>

						<?php endif; ?>

						<?php if($settings['has_brands'] && !empty($filters['brand'])): ?>

							<button class="accordion-button collapsed"<?= (isset($active_filters['brand'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#brand-accordion" aria-expanded="false" aria-controls="brand-accordion">
								<?php _e("Brand", 'vincod'); ?>
							</button>

						<?php endif; ?>

						<?php if($settings['has_appellations'] && !empty($filters['appellation'])): ?>

							<button class="accordion-button collapsed"<?= (isset($active_filters['appellation'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#appellation-accordion" aria-expanded="false" aria-controls="appellation-accordion">
								<?php _e("Appellation", 'vincod'); ?>
							</button>

						<?php endif; ?>

						<?php if($settings['has_types'] && !empty($filters['type'])): ?>

							<button class="accordion-button collapsed"<?= (isset($active_filters['type'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#type-accordion" aria-expanded="false" aria-controls="type-accordion">
								<?php _e("Type", 'vincod'); ?>
							</button>

						<?php endif; ?>

					</div>

					<?php if($settings['has_search']): ?>

						<div class="search-button">

							<div class="collapse collapse-horizontal" id="search-form-collapse">
								<?= $search_form; ?>
							</div>

							<button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#search-form-collapse" aria-expanded="false" aria-controls="search-form-collapse">
								<?= wp_vincod_get_icon('search'); ?>
							</button>

						</div>

					<?php endif; ?>

				</div>

				<div id="filters-collapse" class="filters-collapse collapse">

					<div id="filters-accordion" class="filters-accordion accordion accordion-flush">

						<?php if($settings['has_vintages'] && !empty($filters['vintage'])): ?>

							<div class="accordion-item">

								<button class="accordion-button collapsed"<?= (isset($active_filters['vintage'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#vintage-accordion" aria-expanded="false" aria-controls="vintage-accordion">
									<?php _e("Vintage", 'vincod'); ?>
								</button>

								<div id="vintage-accordion" class="accordion-collapse collapse" data-bs-parent="#filters-accordion">

									<div class="accordion-body">

										<?php foreach($filters['vintage'] as $filter): ?>
											<a class="btn btn-primary" href="<?= wp_vincod_link_add_filter('vintage', $filter, $active_filters); ?>"><?= (!$filter) ? __("No vintage", 'vincod') : $filter; ?></a>
										<?php endforeach; ?>

									</div>

								</div>

							</div>

						<?php endif; ?>

						<?php if($settings['has_brands'] && !empty($filters['brand'])): ?>

							<div class="accordion-item">

								<button class="accordion-button collapsed"<?= (isset($active_filters['brand'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#brand-accordion" aria-expanded="false" aria-controls="brand-accordion">
									<?php _e("Brand", 'vincod'); ?>
								</button>

								<div id="brand-accordion" class="accordion-collapse collapse" data-bs-parent="#filters-accordion">

									<div class="accordion-body">

										<?php foreach($filters['brand'] as $filter): ?>
											<a class="btn btn-primary" href="<?= wp_vincod_link_add_filter('brand', $filter, $active_filters); ?>"><?= $filter['name']; ?></a>
										<?php endforeach; ?>

									</div>

								</div>

							</div>

						<?php endif; ?>

						<?php if($settings['has_appellations'] && !empty($filters['appellation'])): ?>

							<div class="accordion-item">

								<button class="accordion-button collapsed"<?= (isset($active_filters['appellation'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#appellation-accordion" aria-expanded="false" aria-controls="appellation-accordion">
									<?php _e("Appellation", 'vincod'); ?>
								</button>

								<div id="appellation-accordion" class="accordion-collapse collapse" data-bs-parent="#filters-accordion">

									<div class="accordion-body">

										<?php foreach($filters['appellation'] as $filter): ?>
											<a class="btn btn-primary" href="<?= wp_vincod_link_add_filter('appellation', $filter, $active_filters); ?>"><?= $filter['label']; ?></a>
										<?php endforeach; ?>

									</div>

								</div>

							</div>

						<?php endif; ?>

						<?php if($settings['has_types'] && !empty($filters['type'])): ?>

							<div class="accordion-item">

								<button class="accordion-button collapsed"<?= (isset($active_filters['type'])) ? ' disabled' : ''; ?> type="button" data-bs-toggle="collapse" data-bs-target="#type-accordion" aria-expanded="false" aria-controls="type-accordion">
									<?php _e("Type", 'vincod'); ?>
								</button>

								<div id="type-accordion" class="accordion-collapse collapse" data-bs-parent="#filters-accordion">

									<div class="accordion-body">

										<?php foreach($filters['type'] as $filter): ?>
											<a class="btn btn-primary" href="<?= wp_vincod_link_add_filter('type', $filter, $active_filters); ?>"><?= $filter['label']; ?></a>
										<?php endforeach; ?>

									</div>

								</div>

							</div>

						<?php endif; ?>

					</div>

				</div>

			</div>

			<div class="active-filters">

				<h1 class="h4 mb-3"><?php _e('Search results for :', 'vincod'); ?>&nbsp;<?= $search; ?></h1>

			</div>

		</div>

		<div class="content-container">

			<!-- Links -->
			<div class="content-links">

				<?php if($products): ?>

					<?php foreach($products as $product): ?>

						<div class="product-link" itemprop="product" itemscope itemtype="http://schema.org/Product">

							<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']); ?>" title="<?= $product['name']; ?>">

								<div class="bottle">
									<?php if($bottle = wp_vincod_get_bottle_url($product, '640')): ?>
										<img src="<?= $bottle; ?>" alt="<?= $product['name']; ?>" loading="lazy"/>
									<?php else: ?>
										<?= wp_vincod_get_icon('bottle'); ?>
									<?php endif; ?>
								</div>

								<div class="description">

									<div class="description-content">

										<h2 itemprop="name"><?= $product['name']; ?></h2>

									</div>

								</div>

							</a>

						</div>

					<?php endforeach; ?>

				<?php elseif($error): ?>

					<h3 class="not-found"><?= $error; ?>.</h3>

				<?php else: ?>

					<h3 class="not-found"><?php _e("No product found.", 'vincod'); ?></h3>

				<?php endif; ?>

			</div>

		</div>

	</div>

</section>
