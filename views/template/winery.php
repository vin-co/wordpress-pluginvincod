<?php
/**
 * Winery.php
 *
 * The view served by the template when you have got ?winery= GET param
 * 
 * You can replace this view by your, just create in your current theme folder
 * the file plugin-vincod/template/winery.php ; If you make this you can use 
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
<link rel="stylesheet" type="text/css" media="all" href="<?= WP_VINCOD_PLUGIN_URL ?>/assets/css/template-nos-vins.css" />

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

	<?= wp_vincod_breadcrumb($breadcrumb) ?>
	<br/><br/>

	<!-- About the winery -->
	<? if ($winery): ?>
			
		<!-- Create shorcut -->
		<? $winery = $winery['wineries']['winery'][0] ?>

		<h2><?= $winery['name'] ?></h2> 
		<p><?= nl2br($winery['presentation']['value']) ?></p>

	<? endif; ?>

	<!-- Ranges for this winery -->
	<? if ($ranges): ?>

		<div>

			<? foreach ($ranges['wineries']['winery'] as $winery): ?>

				<div class="fleft w50">
					<?= wp_include_picture($winery) ?>
				</div>

				<div class="fright w50">
					<h2><?= $winery['name'] ?></h2>
					<p><?= nl2br($winery['signature']['value']) ?></p>

					<br/><br/>

					<a href="<?= wp_vincod_link('range', $winery['id'], $winery['name']) ?>"><?=$vincod_more_lang?></a>

				</div>

				<div class="clear"></div>
				<div class="spacer"></div>

			<? endforeach; ?>

		</div>


	<? endif; ?>

	<? if ( ! $ranges): ?>

		<? if ($wines): ?>

			<div>

				<? foreach ($wines as $wine): ?>

				<div class="fleft w50">
					<?= wp_include_picture($wine) ?>			
				</div>

				<div class="fright w50">
					<h2><?= $wine['name'] ?></h2>
					<p><?= nl2br($wine['abstract']) ?></p>

					<br/><br/>

					<a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>">En savoir plus</a>

				</div>

				<div class="clear"></div>
				<div class="spacer"></div>

			<? endforeach; ?>
			</div>

		<? else: ?>

			<?= $vincod_no_wines_lang ?>

		<? endif; ?>

	<? endif; ?>


</div>