<?php
/**
 * Range.php
 *
 * The view served by the template when you have got ?range= GET param
 * 
 * You can replace this view by your, just create in your current theme folder
 * the file plugin-vincod/template/range.php ; If you make this you can use 
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

	<? if ($success): ?>
		
		<? $range = $results['wineries']['winery'] ?>

		<h2><?= $range['name'] ?></h2>

		<p><?= nl2br($range['presentation']['value']) ?></p>

		<div class="spacer"></div>
		
		<? if ($wines): ?>

			<? foreach ($wines as $wine): ?>

				<h2><?= $wine['name'] ?></h2>

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

					<!-- What's that -->
					<p><?= $wine['abstract'] ?></p>

					<!-- Know more -->
					<a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>"><?=$vincod_more_lang?></a>

				</div>

				<div class="clear"></div>
				<div class="spacer"></div>

			<? endforeach; ?>

		<? endif; ?>
		
	<? endif; ?>


</div>