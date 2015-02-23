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
 * @category    View
 * @author      Jérémie GES Philippe HUGON 
 * @copyright   2014 VINTERNET
 * @category    View
 * @link		http://vin.co/
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

	
	<?= wp_vincod_breadcrumb($breadcrumb) ?><br /><br />
    
        
     <? /*var_dump($wine)*/ ?>   
	
    <!-- Fiche vin -->
    
    <div class="blocvin_gen">
    	
    	
     
            <!-- The Wine : Picture, name and appellation -->
            
        <div class="blocvin_share" >
            
            <?= wp_include_picture_wine($wine) ?>
            
          
        
        </div>
            
        <div class="blocvin_fiche">
        
            <div class="blocvin_title" >
    
                <h1><?= $wine['name'] ?></h1>
                
                <h2><?= $wine['appellation'] ?></h2>
                
            </div>    
            
            <!-- The Wine : vintages -->
                
            <!--<div class="blocvinmillesime">   
            <? if(count($oldwines) > 1): ?>
            <h4>Millésime&nbsp;&nbsp;
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
            </h4>
            </div>-->
      <? /*count($oldwines).'-'.$oldwines.'-'.$select_wines.'-'.$select_wines['vintageyear'] */ ?>
      
      
            <? if(count($oldwines) > 1): ?>
            
                <div class="blocvin_vintage"> 
                    <h2>
                    <? foreach($oldwines as $select_wines): ?>
                
                            <? if ($select_wines['vintageyear'] == $wine['vintageyear']): ?>
                                
                                <strong><?=$select_wines['vintageyear']?></strong>
            
                            <? else: ?>
                
                            	<a href="<?= wp_vincod_link('vincod', $select_wines['vincod'], $select_wines['name']) ?>"><?=$select_wines['vintageyear']?></a>
                            
							<? endif; ?>
                                
                            &nbsp;&nbsp;
                
                     <? endforeach; ?>
                    </h2>
                </div>
                
                
            <? endif; ?>
                
            <!-- The Wine : claim -->
                
            <div class="blocvin_txt" >     
                <? if ( ! empty($wine['abstract'])): ?>
                <!--<strong><?= $vincod_brief_lang ?></strong><br/>-->
                <hr /><h3><?= $wine['abstract'] ?></h3>
            <? endif; ?>
                
            </div>
      
            
                
            <!-- <hr />
        
        
        
            Navigation 
            <div class="blocvin_txt" > 
            	<div class="nav-vincod">
                <h3>-->
                    <!-- About Button -->
                    <!-- <? if (!empty($wine['fields']['presentation'])) : ?>
                        <a id="trigger-about-wine" href="#"><?= $vincod_details_lang ?></a>&nbsp;&nbsp;
                    <? endif; ?>-->
            
                    <!-- Advice button -->
                    <!-- <? if (! empty($wine['fields']['advice'])): ?>
                        <a id="trigger-advice-wine" href="#"><?= $vincod_tips_lang ?></a>&nbsp;&nbsp;
                    <? endif; ?>-->
            
                    <!-- Review button-->
                    <!-- <? if ( ! empty($wine['reviews']['review'])): ?>
                        <a id="trigger-reviews-wine" href="#"><?= $vincod_reviews_lang ?></a>&nbsp;&nbsp;
                    <? endif; ?>-->
            
                    <!-- Shop button-->
                    <!-- <? if ( ! empty($wine['shops'])): ?>
                        <a id="trigger-shop-wine" href="#"><?= $vincod_shop_lang ?></a>&nbsp;&nbsp;
                    <? endif; ?>-->
            
                    <!-- Medias button-->
                    <!-- <? if ( ! empty($wine['medias']['media'])): ?>
                        <a id="trigger-media-wine" href="#"><?= $vincod_medias_lang ?></a>&nbsp;&nbsp;
                    <? endif; ?>-->
                <!-- </h3>
                </div>
            </div>-->
            
            <hr />
        
    
            <!-- Infos about wine -->
            <? if (!empty($wine['fields']['presentation'])) : ?>
                    
    
                <div data-blocks="about-wine">
                
                    <div class="blocvin_txt">
                    
                    <h3><?= $vincod_details_lang ?></h3>
            
                        <!-- Presentation fields -->
                        <? if ( ! wp_vincod_is_multi($wine['fields']['presentation'])): ?>
            
                            <!-- Manage problem API -->
                            <? $wine['fields']['presentation'] = array($wine['fields']['presentation']); ?>
            
                        <? endif; ?>
            
                        <? foreach ($wine['fields']['presentation'] as $field): ?>
            
                            <? if (!empty($field['value'])): ?>
            
                                <!-- Video case  -->
                                <? if ($field['label'] == 'Vidéo'): ?>
                
                                    <?= $field['label'] ?><br/>
                                    <?= wp_vincod_include_video($field['value'], '<br>') ?>
                
                                <? else: ?>
            
                                <?= $field['label'] ?></br>
                                <p><?= $field['value'] ?></p>
            
                                
                                
                                <? endif; ?> 
            
                            <? endif; ?>
                            
                            
            
                        <? endforeach; ?>
            
                        <!-- Specifications Fields -->
                        <? if ( ! wp_vincod_is_multi($wine['fields']['specifications'])): ?>
            
                            <!-- Manage problem API -->
                            <? $wine['fields']['specifications'] = array($wine['fields']['specifications']); ?>
            
                        <? endif; ?>
            
                        <? foreach ($wine['fields']['specifications'] as $field): ?>
            
                            <? if (!empty($field['value'])): ?>
            
            
                                <?= $field['label'] ?></br>
                                <p><?= $field['value'] ?></p>
            
                               
            
                            <? endif; ?>
            
                        <? endforeach; ?>
            
            
                        <!-- Varieties -->
                        <? if (!empty($wine['grapesvarieties']['variety'])): ?>
            
                            Cépages</br>
            
            
                            <? if ( ! wp_vincod_is_multi($wine['grapesvarieties']['variety'])): ?>
            
                                <? $wine['grapesvarieties']['variety'] = array($wine['grapesvarieties']['variety']) ?>
            
                            <? endif; ?>
            
            
                            <!-- Order by desc varieties -->
                            <? $varieties = wp_vincod_varieties_desc($wine['grapesvarieties']['variety']); ?>
            
                            <p><? foreach ($varieties as $variety): ?>
            
            
                                    <?= $variety['name'] ?> 
            
                                    <? if ( ! empty($variety['amount'])): ?>
            
                                        : <?= $variety['amount'] ?> %
            
                                    <? endif; ?>
                                    </br>
                                    
            
            
                            <? endforeach; ?></p>
            
            
            
                        <? endif; ?>
            
                    
                    </div>
                </div>
    
        <? endif; ?>
    
        
    
            <!-- Advices about wine -->
        
            <? if (! empty($wine['fields']['advice'])): ?>
    
                <!--<div data-blocks="advice-wine">-->
                
                   <div class="blocvin_txt">
                   
                    <h3><?= $vincod_tips_lang ?></h3>
            
                        <? if ( ! wp_vincod_is_multi($wine['fields']['advice'])): ?>
            
                            <!-- Manage problem API -->
                            <? $wine['fields']['advice'] = array($wine['fields']['advice']); ?>
            
                        <? endif; ?>
            
                        <? foreach ($wine['fields']['advice'] as $field): ?>
            
                            <? if ( ! empty($field['value'])): ?>
                            
                            <!-- Video case  -->
                                <? if ($field['label'] == 'Vidéo'): ?>
                
                                    <?= $field['label'] ?><br/>
                                    <?= wp_vincod_include_video($field['value'], '<br>') ?>
                
                                <? else: ?>
            
                                    <?= $field['label'] ?><br/>
                                    <p><?= $field['value'] ?></p>
                                    
            
                                <? endif; ?>
                                
            
                            <? endif; ?>
            
                            
            
                        <? endforeach; ?>
            
                    </div>
                <!--</div>-->
    
            <? endif; ?>
    
    
            <!-- Reviews -->
            <? if ( ! empty($wine['reviews']['review'])): ?>
    
                <!--<div data-blocks="reviews-wine">-->
                
                    <div class="blocvin_txt">
                    <h3><?= $vincod_reviews_lang ?></h3>
            
            
                        
                        <? if ( ! wp_vincod_is_multi($wine['reviews']['review'])): ?>
            
                            <!-- Manage problem API -->
                            <? $wine['reviews']['review'] = array($wine['reviews']['review']); ?>
            
                        <? endif; ?>
            
            
                        <? foreach ($wine['reviews']['review'] as $review): ?>
                        <p>
                            <? if ( ! empty($review['logo'])): ?>
                                <img src="<?= $review['logo'] ?>" /><br />
                            <? endif; ?>
            
                            <!-- Display with url if exists -->
                            <? if ( ! empty($review['url']) && $review['url'] != 'http://'): ?>
                                <a href="<?= $review['url'] ?>"><?= $review['content'] ?></a>
                            <? else: ?>
                                <?= $review['content'] ?>
                            <? endif; ?>
            
                            <br/>
                            
                            
            
                            <? if ( ! empty($review['author'])): ?>
                                <strong><?= $review['author'] ?></strong><br/>
                            <? endif; ?>
            
                            <em><?= $review['source'] ?>
            
                            <? if (! empty($review['mark'])): ?>
                                , <?= $review['mark'] ?>
                            <? endif; ?>
            
                            <? if ($review['date'] != '0000-00-00'): ?>
                                , <?= $review['date'] ?></em>
                            <? endif; ?>
            
                            </p>
            
                        <? endforeach; ?>
            
                    </div>
                <!--</div>-->
    
            <? endif; ?>
    
            <!-- Shop about wine -->
            <? if ( ! empty($wine['shops'])): ?>
            
                <!--<div data-blocks="shop-wine">-->
                
                    <div class="blocvin_txt">
                    <h3><?= $vincod_shop_lang ?></h3>
            
                        <? if ( ! wp_vincod_is_multi($wine['shops']['shop'])): ?>
            
                            <!-- Manage problem API -->
                            <? $wine['shops']['shop'] = array($wine['shops']['shop']); ?>
            
                        <? endif; ?>
            
            
                        <? foreach ($wine['shops']['shop'] as $shop): ?>
            
                            <?= $shop['shop'] ?><br />
                            <a target="_blank" href="<?= $shop['url'] ?>">
            
                                <? if (empty($shop['description'])): ?>
                                    ›&nbsp;<?= $vincod_shop_btn_lang ?>
                                <? else: ?>
                                    <p><?= $shop['description'] ?></p>
            
                                <? endif; ?>
                            </a>	
            
                            <br/><br/>
            
                        <? endforeach; ?>
            
                    </div>
                <!--</div>-->
    
            <? endif; ?>
    
            <!-- Pictures -->
        
        
            <? if ( ! empty($wine['medias']['media'])): ?>
    
                <!--<div data-blocks="media-wine">-->
                
                    <div class="blocvin_txt">
                        <h3><?= $vincod_medias_lang ?></h3>
                        <? if ( ! wp_vincod_is_multi($wine['medias']['media'])): ?>
                            <!-- Manage problem API -->
                            <? $wine['medias']['media'] = array($wine['medias']['media']); ?>
            
                        <? endif; ?>
            
                        <? foreach ($wine['medias']['media'] as $media): ?>
            
                            <? if ( ! empty($media['url'])): ?>
                                <div class="media">
                                    <div class="mediaimg" style="background-image:url(<?= $media['url'] ?>); background-repeat: no-repeat; background-position: center center; background-size:contain; ">
                                        <a target="_blank" href="<?= $media['url'] ?>" style="display:block; width:100%;height:100%;">&nbsp;</a>
                                    </div>
                                
                                    <div class="mediatxt">
                                        <p><a target="_blank" href="<?= $media['url'] ?>"><?= $media['name'] ?></a></p>
                                    </div>
                                </div>
                            <? endif; ?>
                            
                        <? endforeach ?>
                    </div>
                    
                <!--</div>-->
    
            <? endif; ?>
    
    
    <!-- Widgets -->
        
            <div class="blocvin_share">
            
                <!--<h2><?= $vincod_dwnl_lang ?></h2>-->
                <hr /> 
                <div class="widgets">
                    <ul>
                        <li><a href="http://vincod.com/<?=$wine['vincod']?>/get/pdf" target="_blank" title="Fiche techniqe <?=$wine['name']?>"><img src="http://vincod.com/images/picto/24px/pdf-nb.png" /></a></li>
                        <li><a href="http://vincod.com/<?=$wine['vincod']?>/get/set" target="_blank" title="Set de dégustation <?=$wine['name']?> - Vincod"><img src="http://vincod.com/images/picto/24px/set-nb.png" /></a></li>
                        <li><a href="http://vincod.com/<?=$wine['vincod']?>/get/qrcode" target="_blank" title="QR Code <?=$wine['name']?> - Vincod"><img src="http://vincod.com/images/picto/24px/qrcode.png" /></a></li>
                        <li><a href="http://vincod.com/<?=$wine['vincod']?>" target="_blank" title="Permalien (Webapp Mobile) <?=$wine['name']?> - Vincod"><img src="http://vincod.com/images/picto/24px/mobile.png" /></a></li>
                        <li><a href="http://vincod.com/<?=$wine['vincod']?>/get/embed" target="_blank" title="Shorcode <?=$wine['name']?> - Vincod"><img src="http://vincod.com/images/picto/24px/widget.png" /></a></li>
                        <!--
                       <li><a href="http://vincod.com/<?=$wine['vincod']?>" target="_blank" title="<?=$wine['name']?> - Vincod">Partager <img src="<?= WP_VINCOD_PLUGIN_URL ?>assets/img/b_permalink.png"></a></li>-->
                    </ul>
                </div>
                
                <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="width:200px; text-align:center; display:inline-block;">
                    <a class="addthis_button_email"></a>
                    <a class="addthis_button_facebook"></a>
                    <a class="addthis_button_twitter"></a>
                    <a class="addthis_button_pinterest_share"></a>
                    <a class="addthis_button_compact"></a>
                </div>
                <!--<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>-->
                <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f9000e64529dca2"></script>
                <!-- AddThis Button END -->
                
            </div> 
    
    
        </div>
    
    
        
	</div>        
	<div class="clear"></div>
	<div class="spacer"></div>
	<?= wp_vincod_breadcrumb($breadcrumb) ?>
    

</div>