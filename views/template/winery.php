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


	<?= wp_vincod_breadcrumb($breadcrumb) ?>


    <? if ($winery): ?>

         <?= wp_vincod_get_menu($winery['vincod']); ?>

         <!-- Winery presentation -->
         <div class="blocparent">

            <? if ($winery['picture']): ?>
            	<? if ($winery['logo']): ?>
                <!-- Logo winery & photo d'ambiance-->
                	<div class="blocparentimgcontdouble" style="background-image:url('<?= $winery['picture'] ?>') ; " >
                    <div class="blocparentimgdouble" style="background-image:url(<?= wp_include_picture_url($winery) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>
                	</div>
            	<? else: ?>
                <!-- photo d'ambiance -->
                	<div class="blocparentimgcontdouble" style="background-image:url('<?= $winery['picture'] ?>') ; " ></div>
                <? endif; ?>
			<? else: ?>
                <!-- Logo winery -->
                <div class="blocparentimgcont" >
                    <div class="blocparentimg" style="background-image:url(<?= wp_include_picture_url($winery) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>
                </div>
            <? endif; ?>



            <!-- Winery Name & presentation -->
            <div class="blocparenttxt">
                <br /><h1><?= $winery['name'] ?></h1>
                <? if (isset($winery['signature']['value'])): ?>
						<h2><?= nl2br($winery['signature']['value']) ?></h2>
				<? endif; ?>
                <?= isset($winery['fields']['presentation']) ? nl2br($winery['fields']['presentation']['value']) : '' ?>
            </div>


            <div class="clear"></div>


    	</div>

    <? endif; ?>

    <div class="blocfils">



        <!-- List of ranges in the winery -->

        <?
         if ($ranges): ?>



                <? foreach ($ranges['wineries']['winery'] as $range): ?>

                    <!-- Old style 2 column
                    <div><div class="fleft w50">
                        <?= wp_include_picture($range) ?><br><br>
                    </div>

                    <div class="fright w50">
                        <h1><?= $range['name'] ?></h1>
                        <?= nl2br($range['signature']['value']) ?>



                        <a href="<?= wp_vincod_link('range', $range['id'], $range['name']) ?>"><?=$vincod_more_lang?></a>

                    </div>

                    <div class="clear"></div>
                    <div class="spacer"></div>
                    <hr>
                    </div>
                    -->

                    <!-- New style like blocs -->


                    <div class="blocfilsitems">

                        <div class="blocfilsimg" style="background-image:url(<?= wp_include_picture_url($range) ?>); background-repeat: no-repeat; background-position: center center; background-size:100% auto;">

                            <!--<a href="<?= wp_vincod_link('range', $range['id'], $range['name']) ?>"><?= wp_include_picture($range) ?></a><br><br>-->
                            <a href="<?= wp_vincod_link('range', $range['id'], $range['name']) ?>" style="display:block; width:100%;height:100%;">&nbsp;</a>

                        </div>

                        <div class="blocfilstxt">

                            <h2><?= $range['name'] ?></h2>

                           <? if (isset($range['signature']['value'])): ?>
								<?= nl2br($range['signature']['value']) ?>
							<? endif; ?>

						   <a href="<?= wp_vincod_link('range', $range['id'], $range['name']) ?>"><?= $vincod_more_lang ?></a>
        				</div>

                   </div>

                <? endforeach; ?>

        <? endif; ?>



        <!-- if no range list wines in the winery -->

        <? if ( ! $ranges): ?>

            <? if ($wines): ?>

            	<? foreach ($wines as $wine): ?>



                    <!-- Old style 2 column
                    <div>

                    <div class="fleft w50">
                        <?= wp_include_picture($wine) ?><br><br>
                    </div>

                    <div class="fright w50">
                        <h1><?= $wine['name'] ?></h1>
                        <?= nl2br($wine['abstract']) ?>



                        <a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>"><?=$vincod_more_lang?></a>

                    </div>

                    <div class="clear"></div>
                    <div class="spacer"></div>
                    <hr>
                    </div>

                    -->
                    <!-- New style like blocs -->
                    <div class="blocfilsitems">

                        <div class="blocfilsimgwine" style="background-image:url(<?= wp_include_picture_wine_url($wine) ?>); background-repeat: no-repeat; background-position: center center;background-size:contain;">
                            <a href="<?= wp_vincod_link('product', $wine['vincod'], $wine['name']) ?>" style="display:block; width:100%;height:100%;">&nbsp;</a>
                        </div>

                        <div class="blocfilstxt">
                            <h2><?= $wine['name'] ?></h2>
                            <? if (isset($wine['abstract'])): ?>
                            	<h3><?= nl2br($wine['abstract']) ?><br /><? /* =$vincod_more_lang */ ?></h3>
        					<? endif; ?>

                            <a href="<?= wp_vincod_link('product', $wine['vincod'], $wine['name']) ?>"><?= $vincod_more_lang ?></a>

                        </div>


                    </div>




                <? endforeach; ?>



            <? else: ?>

                <?= $vincod_no_wines_lang ?>

            <? endif; ?>

        <? endif; ?>


    </div>

    <div class="clear"></div>
    <div class="spacer"></div>





    <?= wp_vincod_breadcrumb($breadcrumb) ?>
    <div class="spacer"></div>
</div>
