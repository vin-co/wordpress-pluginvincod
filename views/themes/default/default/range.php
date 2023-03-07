<?php
/**
 * Range.php
 *
 * The view served by the template when you have got ?range= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/range.php ; If you make this you can use
 * all functions and all constants of the plugin.
 *
 * @author      Vinternet
 * @category    View
 * @copyright   2023 VINTERNET
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL; ?>assets/css/themes/default/default.css"/>
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

<section id="plugin-vincod" class="vincod-range" itemscope itemtype="http://schema.org/Brand">

	<div class="vincod-container">

		<?php if($settings['has_menu'] || $settings['has_search']): ?>

			<div class="menu-container">

				<?php if($settings['has_menu']): ?>

					<a class="btn btn-link" role="button" data-bs-toggle="collapse" href="#menu-collapse" aria-expanded="false" aria-controls="menu-collapse">
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

			<?php if($settings['has_content'] && $range): ?>

				<div class="content-panel">

					<div class="panel-heading">

						<div class="content-cover"<?= ($background = wp_vincod_get_picture_url($range, 'retina')) ? ' style="background-image: url(' . $background . ')"' : ''; ?>></div>

					</div>

					<div class="panel-body">

						<?php if($logo = wp_vincod_get_logo_url($range, '640')): ?>

							<div class="content-logo">
								<img src="<?= $logo; ?>" alt="<?= $range['name']; ?>"/>
							</div>

						<?php endif; ?>

						<h1 itemprop="name"><?= $range['name']; ?></h1>

						<?php if(!empty($range['presentation'])): ?>

							<div class="content-presentation" itemprop="description">
								<?= nl2br($range['presentation']); ?>
							</div>

						<?php endif; ?>

					</div>

				</div>

			<?php endif; ?>

			<?php if($settings['has_links']): ?>

				<!-- Links -->
				<div class="content-links">

					<?php if($products): ?>

						<?php if($settings['has_appellation'] && !empty($appellations)): ?>

							<div class="appellations-container">

								<!-- Nav tabs -->
								<div class="nav nav-pills" role="tablist">

									<?php foreach($appellations as $index => $appellation): ?>

										<a href="#appellation-<?= $index; ?>" class="nav-item nav-link<?= ($index == 0) ? ' active' : ''; ?>" aria-controls="presentation" role="tab" data-bs-toggle="tab">
											<?= $appellation['name']; ?>
										</a>

									<?php endforeach; ?>

								</div>

								<!-- Tab panes -->
								<div class="tab-content">

									<?php foreach($appellations as $index => $appellation): ?>

										<div role="tabpanel" class="tab-pane fade<?= ($index == 0) ? ' show active' : ''; ?>" id="appellation-<?= $index; ?>">

											<div class="products">

												<?php foreach($appellation['products'] as $product): ?>

													<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']); ?>" title="<?= $product['name']; ?>" class="product-link" itemprop="product" itemscope itemtype="http://schema.org/Product">

														<?php if($bottle = wp_vincod_get_bottle_url($product, '640')): ?>
															<img src="<?= $bottle; ?>" alt="<?= $product['name']; ?>" loading="lazy"/>
														<?php else: ?>
															<?= wp_vincod_get_icon('bottle'); ?>
														<?php endif; ?>

														<h2 itemprop="name"><?= $product['name']; ?></h2>

													</a>

												<?php endforeach; ?>

											</div>

										</div>

									<?php endforeach; ?>

								</div>

							</div>

						<?php else: ?>

							<?php foreach($products as $product): ?>

								<a href="<?= wp_vincod_link('product', $product['vincod'], $product['name']); ?>" title="<?= $product['name']; ?>" class="product-link" itemprop="product" itemscope itemtype="http://schema.org/Product">

									<img src="<?= wp_vincod_get_bottle_url($product, '640') ?>" alt="<?= $product['name']; ?>"/>
									<h2 itemprop="name"><?= $product['name']; ?></h2>

								</a>

							<?php endforeach; ?>

						<?php endif; ?>

					<?php else: ?>

						<?php _e("Nothing to Show.", 'vincod'); ?>
						<br/>
						<?php _e("Please check your Vincod Account id or enter a Brand Vincod id if you want to show only one brand.", 'vincod'); ?>

					<?php endif; ?>

				</div>

			<?php endif; ?>

			<?php if(!empty($range['certifications'])): ?>

				<div class="labels-container">

					<?php foreach($range['certifications'] as $certification): ?>
						<div class="vincod-label">
							<img src="<?= $certification['value']; ?>" alt="<?= $certification['label']; ?>">
						</div>
					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>

	</div>

</section>
