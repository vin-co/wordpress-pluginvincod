<?php
/**
 * Dashboard
 *
 * It's the admin dashboard which you can view into wordpress settings (vincod)
 *
 * @author      Laurent SCHAFFNER / Jérémie GES
 * @copyright   2013
 * @category    View
 *
 */
?>

<script>
// Here php can talk with javascript like a charm
var vincod_plugin_app = {

    "api": '<?= plugin_dir_url(__FILE__) . "../../scripts/api-test.php" ?>'

}
</script>

<div class="col-xs-10">

    <!-- The subheader with title and description -->
	<div class="bs-docs-section">
		<div class="page-header">
			<h1><?=$vincod_title_lang?></h1>
		</div>
		<p class="lead"><?=$vincod_subtitle_lang?></p>
	</div>

    <!-- When you clean the app by the reset button, this message appear -->
    <? if (isset($app_cleaned)): ?>
        <div class="alert alert-danger"><strong><?=$vincod_you_reset_your_plugin_lang?></strong> <br/>
            <?=$vincod_plugin_has_been_reset_fill_your_credentials_lang?>
        </div>
    <? endif; ?>

    <!-- Simple help for the first visit -->
    <div class="alert alert-warning">

      <h4><i class="icon-question-sign"></i> <?=$vincod_first_visit_lang?></h4>
      <p><?=$vincod_first_visit_subtext_lang?></p>
   
    </div>

    <!-- Settings -->
    <div class="page-header">

    	<h2><?=$vincod_settings_lang?></h2>

    </div>



    <!-- About API connect -->
    <form id="settings" name="settings" method="POST" target="_self" action="">

    <div class="pspacer"></div>

    		<div class="input-group">
    			<span class="input-group-addon"><?=$vincod_your_customer_id_lang?></span>
    			<input type="text" class="form-control" name="vincod_setting_customer_id" value="<?=get_option('vincod_setting_customer_id')?>" placeholder="<?=$vincod_your_customer_id_placeholder_lang?>">
    		</div>

    		<div class="pspacer"></div>

    		<div class="input-group">
    			<span class="input-group-addon"><?=$vincod_your_apikey_lang?></span>
    			<input type="text" class="form-control" name="vincod_setting_customer_api" value="<?=get_option('vincod_setting_customer_api')?>" placeholder="<?=$vincod_your_apikey_placeholder_lang?>">
    		</div>

    		<div class="pspacer"></div>

    		<div class="pull-right">

    			<button type="submit" id="api_connection_check" class="btn btn-default"><i class="icon-gear"></i> <?=$vincod_check_the_api_lang?></button>
  				<button type="submit" id="remove_settings" name="vincod_setting_remove" class="btn btn-danger"><i class="icon-trash"></i> <?=$vincod_delete_my_credentials_lang?></button>

    			<button type="submit" id="validate_settings" class="btn btn-primary"><i class="icon-rocket"></i> <?=$vincod_confirm_my_credentials_lang?></button>
    		
    		</div>

            <div class="pspacer-big"></div>

            <div id="api_connection_check_console" class="devlog auto"><div></div></div>

    	</form>

    <div class="pspacer"></div>

    <!-- About SEO -->
    <div class="page-header">

    	<h2><?=$vincod_seo_lang?></h2>

    </div>


    	<form id="seo" name="seo" method="POST" target="_self" action="">

                <? if ($sitemap_exists): ?>

                    <h4><?=$vincod_seo_optimized_lang?> (<a href="<?= WP_VINCOD_PLUGIN_URL ?>cache/sitemap/plugin-vincod-sitemap.xml"><?=$vincod_check_sitemap_lang?></a>)</h4>

                <? else: ?>

                    <h4><?=$vincod_seo_isnt_optimized_lang?></h4>

                <? endif; ?>

    			<div class="pspacer-little"></div>

                <? if ($sitemap_exists): ?>

                    <input type="hidden" name="vincod_seo_delete" value="">
                    <button type="submit" name="" class="btn btn-danger"><i class="icon-globe"></i> <?=$vincod_remove_sitemap_lang?></button>

                <? else: ?>

                    <input type="hidden" name="vincod_seo_create" value="">
                    <button type="submit" name="" class="btn btn-success"><i class="icon-globe"></i> <?=$vincod_optimize_my_seo_lang?></button>
                    
                    <div class="pspacer-little"></div>

                    <div class="alert alert-success">

                    <h4><i class="icon-question-sign"></i> <?=$vincod_what_will_happen_seo_lang?></h4>
                    <p><?=$vincod_what_will_happen_seo_subtext_lang?></p>

                    </div>
                <? endif; ?>




    	</form>

    <div class="pspacer"></div>

    <!-- About debug -->
    <div class="page-header">

    	<h2><?=$vincod_analysis_and_debug_lang?></h2>

    </div>

    <div class="devlog scroll">

    	<div class="devlog_content">

    		<div class="title">Console :</div>

    		<div class="pspacer-little"></div>

            <? if (!empty($devlog_content)): ?>

        		<?php foreach($devlog_content as $devlog_current): ?>

        		<div class="time">[<i><?=date('d-m-Y H:i:s', $devlog_current['time'])?></i>]</div>
        		<div class="msg"><?=$devlog_current['msg']?></div>

        		<div class="pspacer-little"></div>

        		<?php endforeach; ?>

            <? else: ?>

                <p><?=$vincod_no_log_to_show_lang?></p>

            <? endif; ?>
    		<div class="pspacer"></div>

    	</div>

    </div>

    <div class="pspacer-little"></div>

    <!-- Clear devlog system -->
    <? if (! empty($devlog_content)): ?>

        <form method="POST" target="_self" action="">
            <input type="hidden" name="vincod_clear_log" value="">
            <button class="btn btn-default"><i class="icon-refresh"></i> <?=$vincod_erase_devlog_lang?></button>
        </form>

    <? endif; ?>

    <div class="pspacer-little"></div>

    <div class="alert alert-default">

    	<h4><i class="icon-question-sign"></i> <?=$vincod_what_is_this_devlog_lang?></h4>
    	<p><?=$vincod_what_is_this_devlog_subtext_lang?></p>

    </div>

    <div class="pspacer"></div>

    <!-- Reset plugin -->
    <div class="page-header">

        <h2><?=$vincod_reset_your_plugin_lang?></h2>

    </div>

    <div class="pspacer"></div>

    	<form id="debug" name="debug" method="POST" target="_self" action="">

                <input type="hidden" name="vincod_reset_app" value=""/>
    			<button type="submit" name="" class="btn btn-danger"><i class="icon-trash"></i> <?=$vincod_reset_plugin_lang?></button>

    			<div class="pspacer-little"></div>

    			<div class="alert alert-danger">

    				<h4><i class="icon-question-sign"></i> <?=$vincod_your_plugin_got_bugs_lang?></h4>
    				<p><?=$vincod_your_plugin_got_bugs_subtext_lang?></p>

    			</div>

    	</form>

    <div class="pspacer"></div>

    <!-- Cache Api -->
    <div class="page-header">

        <h2>Cache API</h2>

    </div>

    <form method="POST" action="" target="_self" id="cache_api">

        <div class="input-group">

            <span class="input-group-addon"><?=$vincod_cache_duration_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_cache_api" value="<?=get_option('vincod_setting_cache_api')?>" placeholder="<?=$vincod_example_1_lang?>">
            <span class="input-group-addon"><?=$vincod_days_lang?></span>

        </div>

        <div class="clear"></div>
        <div class="pspacer"></div>

        <button type="submit" name="" class="btn btn-default"><i class="icon-tasks"></i> <?=$vincod_change_cache_duration_lang?></button>

    </form>

    <div class="pspacer-little"></div>

     <div class="alert alert-info">

      <h4><i class="icon-question-sign"></i> <?=$vincod_what_is_this_devlog_lang?></h4>
      <p><?=$vincod_text_cache_api_lang?>?></p>
   
    </div>

    <div class="pspacer"></div>
 
    <!-- Styles -->
    <div class="page-header">

        <h2>Style</h2>

    </div>

     <div class="alert alert-info">

      <h4><i class="icon-question-sign"></i> <?=$vincod_what_is_this_devlog_lang?></h4>
      <p><?=$vincod_style_subtext_lang?></p>
   
    </div>

    <form method="POST" action="" target="_self" id="style">

        <h3><?=$vincod_text_lang?></h3>
        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_global_text_size_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_size_text" value="<?=get_option('vincod_setting_size_text')?>" placeholder="<?=$vincod_default_lang?> : <?= WP_VINCOD_TEMPLATE_SIZE_GENERAL_TEXT ?>">
            <span class="input-group-addon">px</span>

        </div>

        <div class="pspacer"></div>

        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_title_size_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_size_h2" value="<?=get_option('vincod_setting_size_h2')?>" placeholder="<?=$vincod_default_lang?> : <?= WP_VINCOD_TEMPLATE_SIZE_TITLES_TEXT ?>">
            <span class="input-group-addon">px</span>
        </div>

        <div class="pspacer"></div>

        <h3>Images</h3>

        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_images_size_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_picture_width" value="<?=get_option('vincod_setting_picture_width')?>" placeholder="<?=$vincod_default_lang?> : <?= WP_VINCOD_TEMPLATE_PICTURE_WIDTH ?>">
            <span class="input-group-addon">px</span>
        </div>

        <div class="pspacer"></div>

        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_images_width_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_picture_height" value="<?=get_option('vincod_setting_picture_height')?>" placeholder="<?=$vincod_default_lang?> : <?= WP_VINCOD_TEMPLATE_PICTURE_HEIGHT ?>">
            <span class="input-group-addon">px</span>
        </div>

        <div class="pspacer"></div>

        <h3><?=$vincod_colors_lang?></h3>

        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_titles_color_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_color_titles_text" value="<?=get_option('vincod_setting_color_titles_text')?>" placeholder="<?=$vincod_default_theme_color_lang?>">
        </div>

        <div class="pspacer"></div>

        <div class="input-group">
            <span class="input-group-addon"><?=$vincod_texts_color_lang?></span>
            <input type="text" class="form-control" name="vincod_setting_color_general_text" value="<?=get_option('vincod_setting_color_general_text')?>" placeholder="<?=$vincod_default_theme_color_lang?>">
        </div>

        <div class="clear"></div>
        <div class="pspacer"></div>



        <button type="submit" name="" class="btn btn-default"><i class="icon-eye-open"></i> <?=$vincod_change_your_styles_lang?></button>

    </form> 

    <div class="pspacer"></div>

    <!-- Permalinks -->
    <div class="page-header">

        <h2><?=$vincod_permalink_lang?></h2>

        <!-- Check permalinks used -->
        <? if (wp_vincod_permalinks_used()): ?>

            <!-- Check connect api -->
            <? if (wp_vincod_test_api(get_option('vincod_setting_customer_id'), get_option('vincod_setting_customer_api'))): ?>

                <? $wines = wp_vincod_get_wines() ?>

                <!-- Check if wines -->
                <? if ($wines): ?>

                    <table class="table table-striped">

                        <thead>
                            <tr>
                                <th><?=$vincod_wine_name_lang?></th>
                                <th><?=$vincod_permalink_lang?></th>
                            </tr>
                        </thead>
                            
                        <tbody>

                            <? foreach ($wines as $wine): ?>

                                <tr>

                                    <td><?= $wine['name'] ?></td>
                                    <td>
                                        <form method="POST" action="" target="_self">
                                            <input type="hidden" name="vincod_permalink" value="<?= $wine['vincod'] ?>" />
                                            <input type="text" value="<?= wp_vincod_wine_permalink($wine['vincod'], $wine['name']) ?>" name="vincod_<?= $wine['vincod'] ?>" /> <button class="btn btn-primary btn-xs btn-spacer"><i class="icon-pencil"></i> <?=$vincod_edit_lang?></button>
                                        </form>    
                                    </td>
                                        
                                </tr>


                            <? endforeach; ?>

                        </tbody>


                    </table>

                <? else: ?>

                    <div class="alert alert-danger">
                        <?=$vincod_no_wine_to_show_lang?>
                    </div>

                <? endif; ?>

            <? else: ?>

                <div class="alert alert-danger"><?=$vincod_you_must_be_logged_lang?></div>

            <? endif; ?>

        <? else: ?>

            <div class="alert alert-danger">
                <?=$vincod_section_off_permalinks_lang?>
            </div>

        <? endif; ?>


        <div class="alert alert-info">

          <h4><i class="icon-question-sign"></i> <?=$vincod_what_is_this_devlog_lang?></h4>
          <p><?=$vincod_section_on_permalinks_lang?> </p>

      </div>

    </div>

</div>     <!-- end of col-xs-10 -->


