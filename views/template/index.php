<?php
/**
 * Index.php
 *
 * The view served by the template when you haven't got params
 * 
 * You can replace this view by your, just create in your current theme folder
 * the file plugin-vincod/template/index.php ; If you make this you can use 
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
		
		<?  foreach ($results['wineries']['winery'] as $winery): ?>

			<!-- Result -->
			<div>
	
				<!-- Name -->
				<h2><?= $winery['name'] ?></h2><br/>

				<!-- Block Picture -->
				<div class="w50 fleft">
					<img src="<?= wp_vincod_url_resizer( wp_vincod_picture_format($winery['logo']) ) ?>" />
				</div>

				<!-- Block Text -->
				<div class="w50 fright">
					<p><?= nl2br($winery['signature']['value']) ?></p>
					<a class="button-vincod" href="<?= wp_vincod_link('winery', $winery['id'], $winery['name']) ?>"><?=$vincod_more_lang?></a>
				</div>

			</div>


			<div class="spacer"></div>
			<div class="clear"></div>

			<hr>
			
			<br/><br/>

		<? endforeach; ?>

	<? endif; ?>

</div>