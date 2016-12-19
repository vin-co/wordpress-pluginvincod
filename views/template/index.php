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
 * @category    View
 * @author      Jérémie GES Philippe HUGON
 * @copyright   2014 VINTERNET
 * @category    View
 * @link		http://vin.co/
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



    <!-- About owner -->

    <!-- *************************************************************************
	*
	* tester avec le compte marcel petit, la page owner ne s'affichge pas alors qu'elle existe...
	*
	*************************************************************************-->
	<? // var_dump($owner);?>

	<? if ($owner): ?>

        <div class="blocparent">

        <!-- Create shorcut -->
            <?if (is_array($owner['medias']) && $owner['medias']['media'][1]['url']): ?><!-- ici on devrait identifier owner picture -->
                <!-- Logo owner & photo d'ambiance-->
                <div class="blocparentimgcontdouble" style="background-image:url('<?= $owner['medias']['media'][1]['url'] ?>') ; " >
                <div class="blocparentimgdouble" style="background-image:url(<?= wp_include_picture_url($owner) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>
                </div>

                <!-- ici il manque une condition pour afficher photo d'mabiance seule comme sur winery et range mais il faut pouvoir identifier owner logo -->

            <? else: ?>
            <!-- Logo owner -->
            <div class="blocparentimgcont" >
                        <div class="blocparentimg" style="background-image:url(<?= wp_include_picture_url($owner) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>
			</div>
            <? endif; ?>

            <!-- Winery Name & presentation -->
            <div class="blocparenttxt">
                <br /><h1><?= $owner['company'] ?></h1>
                <?= nl2br($owner['fields']['presentation']['value']) ?>
            </div>


            <div class="clear"></div>



        </div>

	<? endif; ?>

    <!-- List all wineries -->



	<? if ($wineries): ?>

    <div class="blocfils">


		<?  foreach ($wineries['wineries']['winery'] as $winery): ?>


			<!-- Winery introduction -->

            <!-- Old style 2 columns

            <div>

				<!-- Block Picture
				<div class="w50 fleft">
					<?= wp_include_picture($winery) ?>
				</div>

				<!-- Block Text
				<div class="w50 fright">

                    <!-- Name
                    <h1><?= $winery['name'] ?></h1>

					<? if (isset($winery['signature']['value'])): ?>
						<?= nl2br($winery['signature']['value']) ?>
					<? endif; ?>

					<a href="<?= wp_vincod_link('winery', $winery['id'], $winery['name']) ?>"><?=$vincod_more_lang?></a>
				</div>

			</div>

            <div class="spacer"></div>
			<div class="clear"></div>

			<hr>

			<br/><br/>
            -->

            <!-- New style like blocs -->

            <div class="blocfilsitems">

             	<div class="blocfilsimg" style="background-image:url(<?= wp_include_picture_url($winery) ?>); background-repeat: no-repeat; background-position: center center;">


					<!--<a href="<?= wp_vincod_link('winery', $winery['id'], $winery['name']) ?>" ><?= wp_include_picture($winery) ?></a><br /><br />-->
                    <a href="<?= wp_vincod_link('winery', $winery['id'], $winery['name']) ?>" style="display:block; width:100%;height:100%;">&nbsp;</a>

				</div>

                 <div class="blocfilstxt">

				<!-- Block Text -->

                	<h2><?= $winery['name'] ?></h2>

					<? if (isset($winery['signature']['value'])): ?>
						<?= nl2br($winery['signature']['value']) ?>
					<? endif; ?>

					<a href="<?= wp_vincod_link('winery', $winery['id'], $winery['name']) ?>" ><?= $vincod_more_lang  ?></a>

               </div>

            </div>

		<? endforeach; ?>
    </div>



	<? elseif ( ! $wineries && $ranges): ?>


	<? elseif ( ! $wineries && ! $ranges && $wines): ?>

	<? else: ?>

		<?= $vincod_no_wines_lang ?>

	<? endif; ?>

</div>
