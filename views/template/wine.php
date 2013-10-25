<?php
/**
 * Wine.php
 *
 * The view served by the template when you have got ?vincod= GET param
 * 
 * You can replace this view by your, just create in your current theme folder
 * the file plugin-vincod/template/wine.php ; If you make this you can use 
 * all functions and all constants of the plugin.
 *
 * 
 * @author      Jérémie GES
 * @copyright   2013
 * @category    View
 *
 */
?>

<!-- Default plugin css -->
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL ?>assets/css/template-nos-vins.css" />

<!-- Load jQuery lib -->
<script src="<?= WP_VINCOD_PLUGIN_URL ?>assets/libs/jquery/jquery-1.10.2.min.js"></script>

<!-- Load script -->
<script src="<?= WP_VINCOD_PLUGIN_URL ?>assets/js/wine.js"></script>


<?php

/**
 * Hook.php
 *
 * It's just a file injected and replace many css styles
 * by users preferences setted in the dashboard
 *
 */
require(WP_VINCOD_PLUGIN_PATH . 'assets/css/hook.php') 

 ?>

<div class="plugin-vincod">

	<!-- The Wine -->
	<h2><?= $wine['name'] ?></h2>
	<strong><?= $wine['appellation'] ?></strong>
	<br />

	<? if(count($oldwines) > 1): ?>
		<select name="years">

			<? foreach($oldwines as $select_wines): ?>

				<? if ($select_wines['vintageyear'] == $wine['vintageyear']): ?>
					
					<option selected="selected" value="<?= wp_vincod_link('vincod', $select_wines['vincod'], $select_wines['name']) ?>"><?=$select_wines['vintageyear']?></option>

				<? else: ?>

					<option value="<?= wp_vincod_link('vincod', $select_wines['vincod'], $select_wines['name']) ?>"><?=$select_wines['vintageyear']?></option>

				<? endif; ?>

			<? endforeach; ?>

		</select>
	<? endif; ?>

	<div class="spacer"></div>	

	<!-- Picture -->
	<? if (! empty($wine['picture'])): ?>
		<img src="<?= wp_vincod_url_resizer($wine['picture']) ?>" />
	<? else: ?>
		<img src="<?= WP_VINCOD_PLUGIN_URL . 'assets/img/ico_wine.png' ?>">
	<? endif; ?>

	<div class="spacer"></div>

	<!-- Navigation -->
	<div class="nav-vincod">

		<!-- About Button -->
		<? if (!empty($wine['fields']['presentation'])) : ?>
			<a id="trigger-about-wine" href="#"><?= $vincod_details_lang ?></a>
		<? endif; ?>

		<!-- Advice button -->
		<? if (! empty($wine['fields']['advice'])): ?>
			<a id="trigger-advice-wine" href="#"><?= $vincod_tips_lang ?></a>
		<? endif; ?>

		<!-- Shop button -->
		<? if (!empty($wine['shops'])): ?>
			<a id="trigger-shop-wine" href="#">Commander</a>
		<? endif; ?>

	</div>

	<hr>
	<div class="pspacer"></div>

	<!-- Infos about wine -->
	<? if (!empty($wine['fields']['presentation'])) : ?>
		
		<div data-blocks="about-wine">

			<? foreach ($wine['fields']['presentation'] as $field): ?>

				<? if (!empty($field['value'])): ?>


					<strong><?= $field['label'] ?></strong> <br/>
					<?= $field['value'] ?>

					<div class="spacer"></div>

				<? endif; ?>

			<? endforeach; ?>

		</div>

	<? endif; ?>

	<div class="spacer"></div>

	<!-- Advices about wine -->

	<? if (! empty($wine['fields']['advice'])): ?>

		<div data-blocks="advice-wine">

			<? foreach ($wine['fields']['advice'] as $field): ?>

				<? if (!empty($field['value'])): ?>

						<strong><?= $field['label'] ?></strong> <br/>
						<?= $field['value'] ?>

					<div class="spacer"></div>

				<? endif; ?>

			<? endforeach; ?>

	 	</div>

	<? endif; ?>

	<!-- Shop about wine -->
	<? if (!empty($wine['shops'])): ?>
		
		<div data-blocks="shop-wine">

			<? foreach ($wine['shops'] as $shop): ?>

				<strong><?= $shop['shop'] ?></strong><br/>
				<a target="_blank" href="<?= $shop['url'] ?>"><?= $shop['description'] ?></a>

			<? endforeach; ?>

		</div>

	<? endif; ?>
	
	<!-- Widgets -->
	<div class="widgets">
		<ul>
			<li class="bordered"><a href="http://vincod.com/print/<?=$wine['vincod']?>" target="_blank" title="Fiche PDF <?=$wine['name']?>">Imprimer <img src="<?= WP_VINCOD_PLUGIN_URL ?>assets/img/b_print.png"></a></li>
			<li class="bordered"><a href="http://vincod.com/get-widget/<?=$wine['vincod']?>" target="_blank">Insérer</a> <img src="<?= WP_VINCOD_PLUGIN_URL ?>assets/img/b_widget.png"></li>
			<li><a href="http://vincod.com/<?=$wine['vincod']?>" target="_blank" title="<?=$wine['name']?> - Vincod">Lien permanent <img src="<?= WP_VINCOD_PLUGIN_URL ?>assets/img/b_permalink.png"></a></li>
		</ul>
	</div>

	<? 

	/* Specs - Maybe will happen in another version 

	<!-- Specifications about wine -->
	<h2><?=$vincod_specifications_lang?></h2><br/>

	<? $specifications_are_here = FALSE; ?>

	<? foreach ($wine['fields']['specifications'] as $field): ?>

		<? if (!empty($field['value'])): ?>

			<? $specifications_are_here = TRUE; ?>

			<strong><?= $field['label'] ?></strong> <br/>
			<?= $field['value'] ?>

			<div class="spacer"></div>

		<? endif; ?>

	<? endforeach; ?>

	<? if (!$specifications_are_here): ?><?=$vincod_nothing_lang?><? endif; ?>
	*/ 

	?>


</div>