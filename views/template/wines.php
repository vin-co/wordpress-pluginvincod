<?php
/**
 * Wines.php
 *
 * The view served when no wineries
 * 
 * You can replace this view by your, just create in your current theme folder
 * the file plugin-vincod/template/wines.php ; If you make this you can use 
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

	<? if ($success): ?>

		<div>

			<? foreach ($results['wines']['wine'] as $wine): ?>

				<div class="fleft w50">
					<!-- Picture -->
					<? if (! empty($wine['picture'])): ?>
						<img src="<?= wp_vincod_url_resizer($wine['picture']) ?>" />
					<? elseif (! empty($wine['medias']['media']['url'])): ?>
						<img src="<?= wp_vincod_url_resizer($wine['medias']['media']['url']) ?>" />
					<? elseif (! empty($wine['medias']['media'][0]['url'])): ?>
						<img src="<?= wp_vincod_url_resizer($wine['medias']['media'][0]['url']) ?>" />
					<? else: ?>
						<img src="<?= WP_VINCOD_PLUGIN_URL . 'assets/img/ico_wine.png' ?>">
					<? endif; ?>				
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
		<p>Aucun vins pour le moment</p>
	<? endif; ?>
</div>