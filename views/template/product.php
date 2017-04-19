<?php
/**
 * Product.php
 *
 * The view served by the template when you have got ?product= GET param
 *
 * You can replace this view by your, just create in your current theme folder
 * the file vincod/views/template/product.php ; If you make this you can use
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

<section id="plugin-vincod" class="vincod-product">
	
	<div class="container-fluid vincod-container">
		
		<?php if($settings['has_menu'] || $settings['has_search']): ?>
			
			<div class="menu-container col-xs-12 col-md-3">
				
				<?php if($settings['has_menu']): ?>
					
					<a class="btn btn-link hidden-lg hidden-md" role="button" data-toggle="collapse" href="#menu-collapse" aria-expanded="false" aria-controls="menu-collapse">
						<i class="ion-navicon"></i>
						<span>Menu</span>
					</a>
					
					<div class="menu-collapse collapse" id="menu-collapse">
						<div class="well menu-well no-padding">
							<?= $menu; ?>
						</div>
					</div>
				
				<?php endif; ?>
				
				<?php if($settings['has_search']): ?>
					
					<?= $search_form; ?>
				
				<?php endif; ?>
			
			</div>
			
		<?php endif; ?>
		
		<div class="content-container <?= ($settings['has_menu'] || $settings['has_search']) ? 'col-xs-12 col-md-9' : 'clearfix' ?>">
			
			<?php if($settings['has_breadcrumb']): ?>
				
				<div class="col-xs-12 no-padding">
					<ol class="breadcrumb">
						<?= $breadcrumb; ?>
					</ol>
				</div>
			
			<?php endif; ?>
			
			<!-- The product : Picture -->
			<div class="col-xs-12 col-md-5">
				
				<div class="row product-image">
					
					<img class="lazy img-responsive" src="<?= wp_vincod_get_bottle_url($product, '1024') ?>" alt="<?= $product['name']; ?>"/>
				
				</div>
				
				<div class="product-medias">
					
					<h2><?php _e('Downloads', 'vincod') ?></h2>
					
					<ul class="list-unstyled">
						
						<?php if(!empty($product['medias'])): ?>
							
							<!-- Pictures -->
							<?php foreach($product['medias'] as $media): ?>
								
								<?php if(!empty($media['url'])): ?>
									<li>
										<a target="_blank" href="<?= $media['url'] ?>"><?= $media['name'] ?></a>
									</li>
								<?php endif; ?>
							
							<?php endforeach ?>
						
						<?php endif; ?>
						
						<li>
							<a href="http://vincod.com/<?= $product['vincod'] ?>/get/print" target="_blank" title="<?php _e('Product datasheet', 'vincod') ?> (.pdf)"><?php _e('Product datasheet', 'vincod') ?></a>
						</li>
						
						<li>
							<a href="http://vincod.com/<?= $product['vincod'] ?>/get/tablecard" target="_blank" title="<?php _e('Table stand', 'vincod') ?> (PLV)"><?php _e('Table stand', 'vincod') ?></a>
						</li>
					
					</ul>
				
				</div>
			
			</div>
			
			<div class="col-xs-12 col-md-7 no-padding">
				
				<!-- The Product : Name and Vintages -->
				<div class="product-name">
					
					<div class="btn-group vintage-btn-group clearfix">
						
						<?php if($vintages && count($vintages) > 1): ?>
							
							<a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php _e('See all vintages', 'vincod') ?>">
								
								<h1>
									<span class="vintage-name"><?= $product['name'] ?></span>
									<span class="vintage-year">
										<?= $product['vintageyear'] ?>
										<i class="ion-android-arrow-dropdown"></i>
									</span>
								</h1>
							
							</a>
							
							<ul class="dropdown-menu">
								
								<?php foreach($vintages as $vintage): ?>
									
									<?php if($vintage['vintageyear'] == $product['vintageyear']): ?>
										
										<li>
											<strong><?= $vintage['vintageyear'] ?></strong>
										</li>
									
									<?php else: ?>
										
										<li>
											<a href="<?= wp_vincod_link('product', $vintage['vincod'], $vintage['name']) ?>" title="<?= $product['name'] . ' - ' . $vintage['vintageyear'] ?>"><?= $vintage['vintageyear'] ?></a>
										</li>
									
									<?php endif; ?>
								
								<?php endforeach; ?>
							
							</ul>
						
						<?php else: ?>
							
							<div class="btn btn-link no-vintage">
								<span class="vintage-name full-width"><?= $product['name'] ?></span>
							</div>
						
						<?php endif; ?>
					
					</div>
				
				</div>
				
				
				<!-- The Product : Abstract -->
				
				<?php if(!empty($product['abstract'])): ?>
					
					<div class="product-abstract">
						<?= $product['abstract'] ?>
					</div>
				
				<?php endif; ?>
				
				
				<div class="product-fields">
					
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						
						<?php if(!empty($product['presentation'])) : ?>
							
							<li role="presentation" class="active">
								<a href="#product-presentation" aria-controls="presentation" role="tab" data-toggle="tab">
									<h2><?php _e("Presentation", 'vincod') ?></h2>
								</a>
							</li>
						
						<?php endif; ?>
						
						<?php if(!empty($product['advice']) || !empty($product['recipes'])): ?>
							
							<li role="presentation">
								<a href="#product-advice" aria-controls="advice" role="tab" data-toggle="tab">
									<h2><?php _e("Tasting tips", 'vincod') ?></h2>
								</a>
							</li>
						
						<?php endif; ?>
						
						<?php if(!empty($product['specifications']) || !empty($product['grapesvarieties'])): ?>
							
							<li role="presentation">
								<a href="#product-specifications" aria-controls="specifications" role="tab" data-toggle="tab">
									<h2><?php _e("Specifications", 'vincod') ?></h2>
								</a>
							</li>
						
						<?php endif; ?>
						
						<?php if(!empty($product['reviews'])): ?>
							
							<li role="presentation">
								<a href="#product-reviews" aria-controls="reviews" role="tab" data-toggle="tab">
									<h2><?php _e("Reviews & awards", 'vincod') ?></h2>
								</a>
							</li>
						
						<?php endif; ?>
						
						<?php if(!empty($product['shops']) || !empty($product['products'])): ?>
							
							<li role="presentation">
								<a href="#product-shops" aria-controls="shops" role="tab" data-toggle="tab">
									<h2><?php _e("Where to buy", 'vincod') ?></h2>
								</a>
							</li>
						
						<?php endif; ?>
					
					</ul>
					
					
					<!-- Tab panes -->
					<div class="tab-content">
						
						<!-- Infos about product -->
						<?php if(!empty($product['presentation'])) : ?>
							
							<div role="tabpanel" class="tab-pane fade in active" id="product-presentation">
								
								<!-- Presentation fields -->
								
								<?php foreach($product['presentation'] as $presentation): ?>
									
									<?php if(!empty($presentation['value'])): ?>
										
										<?php if(strtolower($presentation['label']) == 'vidéo' || strtolower($presentation['label']) == 'video'): ?>
											
											<!-- Video -->
											<strong class="text-uppercase"><?= $presentation['label'] ?></strong>
											<br>
											<?= wp_vincod_include_video($presentation['value'], '<br>') ?>
										
										<?php else: ?>
											
											<strong class="text-uppercase"><?= $presentation['label'] ?></strong>
											<p><?= $presentation['value'] ?></p>
										
										<?php endif; ?>
									
									<?php endif; ?>
								
								<?php endforeach; ?>
							
							</div>
						
						<?php endif; ?>
						
						
						<!-- Advices about product -->
						<?php if(!empty($product['advice']) || !empty($product['recipes'])): ?>
							
							<div role="tabpanel" class="tab-pane fade" id="product-advice">
								<?php if(!empty($product['advice'])): ?>
									
									<?php foreach($product['advice'] as $advice): ?>
										
										<?php if(!empty($advice['value'])): ?>
											
											<?php if(strtolower($advice['label']) == 'vidéo' || strtolower($advice['label']) == 'video'): ?>
												
												<!-- Video -->
												<strong class="text-uppercase"><?= $advice['label'] ?></strong>
												<br>
												<?= wp_vincod_include_video($advice['value'], '<br>') ?>
											
											<?php else: ?>
												
												<strong class="text-uppercase"><?= $advice['label'] ?></strong>
												<p><?= $advice['value'] ?></p>
											
											<?php endif; ?>
										
										<?php endif; ?>
									
									<?php endforeach; ?>
								
								<?php endif; ?>
								
								<?php if(!empty($product['recipes'])): ?>
									
									<strong class="text-uppercase"><?php _e("Recipes", 'vincod') ?></strong>
									<p>
										<?php foreach($product['recipes'] as $recipe): ?>
											
											<a href="<?= $recipe['url'] ?>" target="_blank">
												<?= $recipe['name'] ?>
											</a>
											<br>
										
										<?php endforeach; ?>
									</p>
								
								<?php endif; ?>
							
							</div>
						
						<?php endif; ?>
						
						
						<!-- Infos about product -->
						<?php if(!empty($product['specifications']) || !empty($product['grapesvarieties'])) : ?>
							
							<div role="tabpanel" class="tab-pane fade" id="product-specifications">
								
								<!-- Specifications Fields -->
								<?php if(!empty($product['specifications'])): ?>
									
									<?php foreach($product['specifications'] as $specification): ?>
										
										<?php if(!empty($specification['value'])): ?>
											
											<strong class="text-uppercase"><?= $specification['label'] ?></strong>
											<p><?= $specification['value'] ?></p>
										
										<?php endif; ?>
									
									<?php endforeach; ?>
								
								<?php endif; ?>
								
								<!-- Varieties -->
								<?php if(!empty($product['grapesvarieties'])): ?>
									
									<strong class="text-uppercase"><?php _e('Grapes', 'vincod') ?></strong>
									
									<?php $varieties = wp_vincod_varieties_desc($product['grapesvarieties']); ?>
									
									<p>
										<?php foreach($varieties as $variety): ?>
											
											<?= $variety['name'] ?>
											
											<?php if(!empty($variety['amount'])): ?>
												
												: <?= $variety['amount'] ?> %
											
											<?php endif; ?>
											<br>
										
										<?php endforeach; ?>
									</p>
								
								<?php endif; ?>
							
							</div>
						
						<?php endif; ?>
						
						
						<!-- Reviews -->
						<?php if(!empty($product['reviews'])): ?>
							
							<div role="tabpanel" class="tab-pane fade" id="product-reviews">
								
								<!-- Reviews -->
								<?php foreach($product['reviews'] as $review): ?>
									
									<div class="product-review">
										
										<p class="no-margin">
											<?php if(!empty($review['url']) && $review['url'] != 'http://'): ?>
												<a href="<?= $review['url'] ?>"><?= $review['content'] ?></a>
											<?php else: ?>
												<?= $review['content'] ?>
											<?php endif; ?>
										</p>
										
										<p class="clearfix">
											<?php if(!empty($review['logo'])): ?>
												<img class="pull-left" src="<?= $review['logo'] ?>"/>
											<?php endif; ?>
											
											<span class="pull-left">
												<?php if(!empty($review['author'])): ?>
													<strong><?= $review['author'] ?></strong>
													<br>
												<?php endif; ?>
												
												<em>
													<?= $review['source'] ?>
													
													<?php if(!empty($review['mark'])): ?>
														, <?= $review['mark'] ?>
													<?php endif; ?>
													
													<?php if($review['date'] != '0000-00-00'): ?>
														, <?= $review['date'] ?>
													<?php endif; ?>
												</em>
											
											</span>
										
										</p>
									
									</div>
								
								<?php endforeach; ?>
							
							</div>
						
						<?php endif; ?>
						
						
						<!-- Shop about product -->
						<?php if(!empty($product['shops']) || !empty($product['products'])): ?>
							
							<div role="tabpanel" class="tab-pane fade" id="product-shops">
								
								<?php if(!empty($product['shops'])): ?>
									
									<?php foreach($product['shops'] as $shop): ?>
										
										<p>
											<a target="_blank" href="<?= $shop['url'] ?>" title="<?= (!empty($shop['description'])) ? $shop['description'] : __('Where to buy', 'vincod') ?>">
												<i class="ion-ios-cart"></i> ›&nbsp;<?= (!empty($shop['description'])) ? $shop['description'] : __('Where to buy', 'vincod') ?>
											</a>
										</p>
									
									<?php endforeach; ?>
								
								<?php endif; ?>
								
								<?php if(!empty($product['products'])): ?>
									
									<?php foreach($product['products'] as $shop): ?>
										
										<p>
											<a target="_blank" href="<?= $shop['url'] ?>" title="<?= (!empty($shop['url_title'])) ? $shop['url_title'] : __('Order now', 'vincod') ?>">
												<i class="ion-ios-cart"></i> ›&nbsp;<?= (!empty($shop['url_title'])) ? $shop['url_title'] : __('Order now', 'vincod') ?>
											</a>
										</p>
									
									<?php endforeach; ?>
								
								<?php endif; ?>
							
							</div>
						
						<?php endif; ?>
					
					</div>
				
				</div>
			
			</div>
		
		</div>
	
	</div>

</section>
