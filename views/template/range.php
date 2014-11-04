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
        

	<div class="blocparent">

        <!-- Range presentation -->
        
        <? if ($range): ?>
            
            <? $range = $range['wineries']['winery'][0] ?>
    
            <? if ($range['picture']): ?>
            	<? if ($range['logo']): ?>
                <!-- Logo range & photo d'ambiance-->
                <div class="blocparentimgcontdouble" style="background-image:url('<?= $range['picture'] ?>') ; background-repeat: no-repeat; background-position: center center; background-size:100% auto;" >
                    <div class="blocparentimgdouble" style="background-image:url(<?= wp_include_picture_url($range) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>    
                </div>
                <? else: ?>
                <!-- photo d'ambiance-->
                <div class="blocparentimgcontdouble" style="background-image:url('<?= $range['picture'] ?>') ; background-repeat: no-repeat; background-position: center center; background-size:100% auto; height:400px;" ></div>
                <? endif; ?>
            <? else: ?>
				<!-- Logo range -->
                <div class="blocparentimgcont" >
                        <div class="blocparentimg" style="background-image:url(<?= wp_include_picture_url($range) ?>) ; background-repeat: no-repeat; background-position: center center; background-size:contain; "></div>    
                </div>
                
			<? endif; ?>
            <!-- Range Name & presentation -->
            <div class="blocparenttxt">
                
                <br /><h1><?= $range['name'] ?></h1>
                <? if (isset($range['signature']['value'])): ?>
						<h2><?= nl2br($range['signature']['value']) ?></h2>
				<? endif; ?>
                <?= nl2br($range['presentation']['value']) ?>
             </div>
             
             
             <div class="clear"></div>
         
            
		
   	</div>

    <div class="blocfils">
    
    <!-- List of wines in the range -->
	
		<? if ($wines): ?>
        
			
            
			<? foreach ($wines as $wine): ?>

				<!-- Old style 2 column
                <div>

				<div class="fleft w50 ">

					<!-- Picture 
					<?= wp_include_picture($wine) ?>


				</div>

				<div class="fright w50">
					<h1><?= $wine['name'] ?></h1>
					<?= $wine['abstract'] ?>

					<!-- Know more 
					<a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>"><?=$vincod_more_lang?></a>

				</div>

				<div class="clear"></div>
				<div class="spacer"></div>
                <hr>
                </div>-->
                
                <!-- New style like blocs -->
                
                <div class="blocfilsitems">  
                
                	<div class="blocfilsimgwine" style="background-image:url(<?= wp_include_picture_wine_url($wine) ?>) ; background-repeat: no-repeat; background-position: center center;">
                  
                    
    
                        <!-- Picture -->
                        <a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>" style="display:block; width:100%;height:100%;">&nbsp;</a>
    
    				</div>
                    
                    <div class="blocfilstxt">
                            <h2><a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>"><?= $wine['name'] ?></a></h2>
                            <? if (isset($wine['abstract'])): ?>
                            	<a href="<?= wp_vincod_link('vincod', $wine['vincod'], $wine['name']) ?>"><?= nl2br($wine['abstract']) ?><br /><? /*=$vincod_more_lang */ ?></a>
        					<? endif; ?>
                        
                        </div>

				
                </div>

			<? endforeach; ?>
            
            
		<? endif; ?>
        
        <!--<? $range = $range['wineries']['winery'][0] ?>

		<h2><?= $range['name'] ?></h2>

		<p><?= nl2br($range['presentation']['value']) ?></p>

		<div class="spacer"></div>
        <div class="clear"></div>
		<hr>-->
		
	<? endif; ?>
    </div>
    <div class="clear"></div>
    <div class="spacer"></div>
    
    <?= wp_vincod_breadcrumb($breadcrumb) ?>
    <div class="spacer"></div>

</div>

