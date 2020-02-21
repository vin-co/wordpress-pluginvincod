<?php
/**
 * Product.php
 *
 * The view served by the template when you have got ?product= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/product.php ; If you make this you can use
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

<section id="plugin-vincod" class="vincod-product" itemscope itemtype="http://schema.org/Product">

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

			<div class="product-wrapper">

				<!-- The Product : Picture -->
				<div class="product-media">

					<?php if($bottle = wp_vincod_get_bottle_url($product, '1024')): ?>

						<div class="product-image">
							<img src="<?= $bottle; ?>" alt="<?= $product['name']; ?>"/>
						</div>

					<?php endif; ?>

					<!-- The Product : Name and Vintages -->
					<div class="product-description">

						<div class="description">

							<h1 itemprop="name">
								<?= $product['name']; ?>
							</h1>

							<?php if($vintages && count($vintages) > 1): ?>

								<div class="dropdown">

									<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php _e('See all vintages', 'vincod'); ?>">
										<?= $product['vintageyear']; ?>
									</button>

									<div class="dropdown-menu fade">

										<?php foreach($vintages as $vintage): ?>

											<a href="<?= wp_vincod_link('product', $vintage['vincod'], $vintage['name']); ?>" title="<?= $product['name'] . ' - ' . $vintage['vintageyear']; ?>" class="dropdown-item<?= ($vintage['vintageyear'] == $product['vintageyear']) ? ' current' : ''; ?>">
												<?= $vintage['vintageyear']; ?>
											</a>

										<?php endforeach; ?>

									</div>

								</div>

							<?php endif; ?>

							<?php if(!empty($product['abstract'])): ?>

								<!-- The Product : Abstract -->
								<div class="product-abstract" itemprop="description">
									<?= $product['abstract']; ?>
								</div>

							<?php endif; ?>

							<?php if(!empty($product['shops']) || !empty($product['products'])): ?>

								<!-- Shop about product -->
								<div class="dropdown">

									<button type="button" class="btn btn-outline-secondary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php _e('Order now', 'vincod'); ?>">
										<?php _e('Order now', 'vincod'); ?>
									</button>

									<div class="dropdown-menu fade">

										<?php if(!empty($product['shops'])): ?>

											<?php foreach($product['shops'] as $shop): ?>

												<a target="_blank" href="<?= $shop['url'] ?>" title="<?= (!empty($shop['description'])) ? $shop['description'] : __('Where to buy', 'vincod'); ?>" class="dropdown-item">
													<?= (!empty($shop['description'])) ? $shop['description'] : __('Where to buy', 'vincod'); ?>
												</a>

											<?php endforeach; ?>

										<?php endif; ?>

										<?php if(!empty($product['products'])): ?>

											<?php foreach($product['products'] as $shop): ?>

												<a target="_blank" href="<?= $shop['url'] ?>" title="<?= (!empty($shop['url_title'])) ? $shop['url_title'] : __('Order now', 'vincod'); ?>" class="dropdown-item">
													<?= (!empty($shop['url_title'])) ? $shop['url_title'] : __('Order now', 'vincod'); ?>
												</a>

											<?php endforeach; ?>

										<?php endif; ?>

									</div>

								</div>

							<?php endif; ?>

						</div>

					</div>

				</div>

				<div class="product-content">

					<div class="product-fields">

						<?php if(!empty($product['presentation'])) : ?>

							<!-- Presentation fields -->
							<?php foreach($product['presentation'] as $presentation): ?>

								<?php if(!empty($presentation['value'])): ?>

									<div class="field" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">

										<?php if(strtolower($presentation['label']) == 'vidéo' || strtolower($presentation['label']) == 'video'): ?>

											<!-- Video -->
											<h2 itemprop="propertyID"><?= $presentation['label']; ?></h2>
											<p itemprop="value"><?= wp_vincod_include_video($presentation['value']); ?></p>

										<?php else: ?>

											<h2 itemprop="propertyID"><?= $presentation['label']; ?></h2>
											<p itemprop="value"><?= $presentation['value']; ?></p>

										<?php endif; ?>

									</div>

								<?php endif; ?>

							<?php endforeach; ?>

						<?php endif; ?>

						<?php if(!empty($product['advice'])): ?>

							<!-- Advices about product -->
							<?php foreach($product['advice'] as $advice): ?>

								<?php if(!empty($advice['value'])): ?>

									<div class="field" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">

										<?php if(strtolower($advice['label']) == 'vidéo' || strtolower($advice['label']) == 'video'): ?>

											<!-- Video -->
											<h2 itemprop="propertyID"><?= $advice['label']; ?></h2>
											<p itemprop="value"><?= wp_vincod_include_video($advice['value']); ?></p>

										<?php else: ?>

											<h2 itemprop="propertyID"><?= $advice['label']; ?></h2>
											<p itemprop="value"><?= $advice['value']; ?></>

										<?php endif; ?>

									</div>

								<?php endif; ?>

							<?php endforeach; ?>

						<?php endif; ?>

						<?php if(!empty($product['recipes'])): ?>

							<div class="field" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">

								<h2 itemprop="propertyID"><?php _e("Recipes", 'vincod'); ?></h2>
								<p itemprop="value">
									<?php foreach($product['recipes'] as $recipe): ?>

										<a href="<?= $recipe['url']; ?>" target="_blank">
											<?= $recipe['name']; ?>
										</a>
										<br>

									<?php endforeach; ?>
								</p>

							</div>

						<?php endif; ?>

						<?php if(!empty($product['specifications'])): ?>

							<!-- Specifications Fields -->
							<?php foreach($product['specifications'] as $specification): ?>

								<?php if(!empty($specification['value'])): ?>

									<div class="field" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">

										<h2 itemprop="propertyID"><?= $specification['label']; ?></h2>
										<p itemprop="value"><?= $specification['value']; ?></p>

									</div>

								<?php endif; ?>

							<?php endforeach; ?>

						<?php endif; ?>

						<?php if(!empty($product['grapesvarieties'])): ?>

							<div class="field" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">

								<!-- Varieties -->
								<h2 itemprop="propertyID"><?php _e('Grapes', 'vincod'); ?></h2>

								<?php $varieties = wp_vincod_varieties_desc($product['grapesvarieties']); ?>

								<p itemprop="value">
									<?php foreach($varieties as $variety): ?>

										<?= $variety['name']; ?>

										<?php if(!empty($variety['amount'])): ?>

											: <?= $variety['amount']; ?> %

										<?php endif; ?>
										<br>

									<?php endforeach; ?>
								</p>

							</div>

						<?php endif; ?>

						<?php if(!empty($product['reviews'])): ?>

							<div class="field">

								<h2><?php _e("Reviews & awards", 'vincod'); ?></h2>

								<!-- Reviews -->
								<?php foreach($product['reviews'] as $review): ?>

									<div class="product-review">

										<?php if(!empty($review['logo'])): ?>
											<img src="<?= $review['logo']; ?>" alt="<?= $review['source']; ?>"/>
										<?php endif; ?>

										<p>
											<?php if(!empty($review['content'])): ?>

												<?php if(!empty($review['url']) && $review['url'] != 'http://'): ?>
													<a href="<?= $review['url']; ?>"><?= $review['content']; ?></a>
												<?php else: ?>
													<?= $review['content']; ?>
												<?php endif; ?>
												<br>

											<?php endif; ?>

											<span>
												<?php if(!empty($review['author'])): ?>
													<strong><?= $review['author']; ?></strong>
													<br>
												<?php endif; ?>

												<em>
													<?= $review['source']; ?>

													<?php if(!empty($review['mark'])): ?>
														, <?= $review['mark']; ?>
													<?php endif; ?>

													<?php if($review['date'] != '0000-00-00'): ?>
														, <?= $review['date']; ?>
													<?php endif; ?>
												</em>

											</span>

										</p>

									</div>

								<?php endforeach; ?>

							</div>

						<?php endif; ?>

						<div class="field">

							<h2><?php _e('Downloads', 'vincod'); ?></h2>

							<ul class="list-unstyled">

								<?php if(!empty($product['medias'])): ?>

									<!-- Pictures -->
									<?php foreach($product['medias'] as $media): ?>

										<?php if(!empty($media['url'])): ?>
											<li>
												<a target="_blank" href="<?= $media['url']; ?>"><?= $media['name']; ?></a>
											</li>
										<?php endif; ?>

									<?php endforeach; ?>

								<?php endif; ?>

								<li>
									<a href="http://vincod.com/<?= $product['vincod']; ?>/get/print" target="_blank" title="<?php _e('Product datasheet', 'vincod'); ?> (.pdf)"><?php _e('Product datasheet', 'vincod'); ?></a>
								</li>

								<li>
									<a href="http://vincod.com/<?= $product['vincod']; ?>/get/tablecard" target="_blank" title="<?php _e('Table stand', 'vincod'); ?> (PLV)"><?php _e('Table stand', 'vincod'); ?></a>
								</li>

							</ul>

						</div>

					</div>

				</div>

			</div>

			<?php if(!empty($product['certifications'])): ?>

				<div class="labels-container">

					<?php foreach($product['certifications'] as $certification): ?>
						<div class="vincod-label">
							<img src="<?= $certification['value']; ?>" alt="<?= $certification['label']; ?>">
						</div>
					<?php endforeach; ?>

				</div>

			<?php endif; ?>

		</div>

	</div>

</section>
