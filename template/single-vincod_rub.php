<?php
/**
 * Template Name: vincod
 *
 * This template allows you to display the latest posts on any page of the site.
 *
 */
require_once(WP_PLUGIN_DIR."/vincod/lib/Config.php");
$custom_fields = get_post_custom($page_id);
if(isset($custom_fields['lang_vincod'])){
	$vincod_language = $custom_fields['lang_vincod'][0];
}
else
{
	if(function_exists('qtrans_getLanguage'))
		$vincod_language = qtrans_getLanguage();
	else
		$vincod_language = 'fr';
}
	
$link_rub_vincod =  get_permalink().($wp_rewrite->using_permalinks() || $wp_rewrite->using_mod_rewrite_permalinks() ? '?' : '&amp;');
?>
<div id="container">
	<div role="main" id="content">
		<div class="vincod_content">
			<div class="vincod_arbo">
				<a href="#" id="popin-close">
					<?php if( qtrans_getLanguage() == 'en' ){ ?>
						All our wines
					<?php } else { ?>
						Tous nos vins
					<?php } ?>
				</a>
			</div>
<?php
if(isset($wp_vincod))
{
	if(isset($_GET['winery']))
	{
		$wineryid = $_GET['winery'];
		$winery = DataObjectFactory::getWineryById($wineryid, $vincod_language);
		var_dump($winery);
		$winery_name = $winery->getName()->getValue();
		echo '</div>';
		echo '<h1>'.$winery_name.'</h1>';
		if($property = $winery->getProperty('presentation'))
		{												
			echo '<p style="margin-bottom:20px;">'.nl2br($property[0]->getValue()).'</p>';
		}
		 	
		if($ranges = DataObjectFactory::getRangesByWineryId($wineryid, $vincod_language))
		{	
				
			foreach($ranges as $range)
			{	
				$img = $range->getLogo()->getValue();
				?>
				<table class="vincod-tab">
					<tr>
						<td class="range-logo">
							<a href="<?php echo $lien; ?>" class="range">															
								
								<?php if($img) { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;lmax=500" alt="" /></div>
									<?php } else { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/images/ico_range.png" alt="" /></div>
								<?php } ?>
								</a>
						</td>
						<td class="range-desc">
							<h2><?php echo $range->getName()->getValue(); ?></h2>
							<?php if($property = $range->getProperty('presentation')) echo '<p>'.nl2br($property[0]->getValue()).'</p>'; ?>	
							<a class="btlink" href="<?php echo $link_rub_vincod.'&amp;range='.$range->getId(); ?>"><?php echo __('En savoir plus', 'vincod'); ?></a>
						</td>
					</tr>
				</table>	
			<?php				
			}				
		}	
		else
		{			
			if($wines = DataObjectFactory::getWinesByWineryId($wineryid, $vincod_language))
			{	
				foreach($wines as $wine)
				{
					$img = $wine->getPicture()->getValue();	
												
					$medias = $wine->getMedias();
					foreach($medias as $obj)
					{
						$type = $obj->getType();
						$url_media = $obj->getPreview();
						$extbubblePh = substr($url_media,-4);
						if($extbubblePh == '.jpg' || $extbubblePh == '.gif' || $extbubblePh == '.png')
						{
							$img = $url_media;
							break;
						}
					}
					
					$lien = $link_rub_vincod.'vincod='.$wine->getAbsoluteVincod()->getValue();
					?>
					<table class="vincod-tab">
						<tr>
							<td class="range-logo">
								<a href="<?php echo $lien; ?>" class="range">
															
								<?php if($img) { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;lmax=40" alt="" /></div>
									<?php } else { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/images/ico_wine.png" alt="" /></div>
								<?php } ?>
								</a>
							</td>
							<td class="range-desc">
								<h2><?php echo $wine->getName()->getValue(); ?></h2>
								<p><?php echo $wine->getAbstract()->getValue(); ?>	</p>
								<a class="btlink" href="<?php echo $lien; ?>"><?php echo __('En savoir plus', 'vincod'); ?></a>
							</td>
						</tr>
					</table>				
					<?php					
				}
			}
		}		
	}
	elseif(isset($_GET['range']))
	{
	
		$winery = DataObjectFactory::getWineryByRangeId($_GET['range'], $vincod_language);
		echo ' &gt; <a href="'.$link_rub_vincod.'&amp;winery='.$winery->getId().'">'.$winery->getName()->getValue().'</a></div>';
		
		$range = DataObjectFactory::getRangeById($_GET['range'], $vincod_language);
		$range_name = $range->getName()->getValue();
		
		echo '<h1>'.$range_name.'</h1>';
		if($property = $winery->getProperty('presentation'))
		{												
			echo '<p style="margin-bottom:20px;">'.nl2br($property[0]->getValue()).'</p>';
		}
		
		if($wines = DataObjectFactory::getWinesByWineryId($_GET['range'], $vincod_language))
		{	
			foreach($wines as $wine)
			{
				$img = $wine->getPicture()->getValue();	
												
				$medias = $wine->getMedias();
				foreach($medias as $obj)
				{
					$type = $obj->getType();
					$url_media = $obj->getPreview();
					$extbubblePh = substr($url_media,-4);
					if($extbubblePh == '.jpg' || $extbubblePh == '.gif' || $extbubblePh == '.png')
					{
						$img = $url_media;
						break;
					}
				}
				$lien = $link_rub_vincod.'vincod='.$wine->getAbsoluteVincod()->getValue();
				?>
					<table class="vincod-tab">
						<tr>
							<td class="range-logo">
								<a href="<?php echo $lien; ?>" class="range">
								<?php if($img) { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;lmax=40" alt="" /></div>
									<?php } else { ?>
									<div><img src="<?php echo plugins_url(); ?>/vincod/images/ico_wine.png" alt="" /></div>
								<?php } ?>							
								
								</a>
							</td>
							<td class="range-desc">
								<h2><?php echo $wine->getName()->getValue(); ?></h2>
								<p><?php echo $wine->getAbstract()->getValue(); ?>	</p>
								<a class="btlink" href="<?php echo $lien; ?>"><?php echo __('En savoir plus', 'vincod'); ?></a>
							</td>
						</tr>
					</table>				
					<?php				
			}
		}
	}
	elseif(isset($_GET['vincod']))
	{	
		$arbo = '';
		$rangeid = 0;
		if( $range = DataObjectFactory::getRangeByVincod( $_GET['vincod'], $vincod_language ) )	
		{
			$rangeid = $range->getId();
			$arbo .= ' &gt; <a href="'.$link_rub_vincod.'range='.$range->getId().'">'. $range->getName()->getValue().'</a>';
		}
		
		if($rangeid > 0)
		{
			if( $winery = DataObjectFactory::getWineryByRangeId( $rangeid, $vincod_language ) )		
				$arbo = ' &gt; <a href="'.$link_rub_vincod.'winery='.$winery->getId().'">'.$winery->getName()->getValue().'</a>'.$arbo;
		}
		else
		{
			if( $winery = DataObjectFactory::getWineryByVincod( $_GET['vincod'], $vincod_language ) )		
				$arbo .= ' &gt; <a href="'.$link_rub_vincod.'winery='.$winery->getId().'">'.$winery->getName()->getValue().'</a>';
		}
		
		
		//echo $arbo . '</div>';
		
		if($wine = DataObjectFactory::getWineByVincod($_GET['vincod'], $vincod_language))
		{	
			$img = $wine->getPicture()->getValue();	
												
			$medias = $wine->getMedias();
			foreach($medias as $obj)
			{
				$type = $obj->getType();
				$url_media = $obj->getURL();
				$extbubblePh = substr($url_media,-4);
				if($extbubblePh == '.jpg' || $extbubblePh == '.gif' || $extbubblePh == '.png')
				{
					$img = $url_media;
					break;
				}
			}
																								
									
			$winename = $wine->getName()->getValue();
											
			$presentation = $wine->getPresentation();
			$specifications = $wine->getSpecifications();
			$advice = $wine->getAdvice();
			$reviews = $wine->getReviews();
			$wes = $wine->getWES();
			$cepages = $wine->getGrapesVarieties();	
			$nb_cepages = count($cepages);
			$shops = $wine->getShops();
?>
						<table class="vincod-tab">
							<tr>
								<td class="desc-vins">
								<div class="wine-desc">
									<h1><?php echo $winename; ?></h1>
									<h2><?php echo $wine->getAppellation()->getValue(); ?></h2>
									<ul id="tabs">
									<?php 
										if(count($presentation) || $nb_cepages)
										{
									?>
										<li><a id="tabinfos" href="#" class="lienmenu lienmenuon">
											<?php if( qtrans_getLanguage() == 'en' ){ ?>
												Presentation
											<?php } else { ?>
												Présentation
											<?php } ?>
										</a></li>
									<?php
										}
										if(count($advice))
										{
									?>
										<li><a id="tabadvice" href="#" class="lienmenu">
											<?php if( qtrans_getLanguage() == 'en' ){ ?>
												Tasting tips
											<?php } else { ?>
												Conseils
											<?php } ?>
										</a></li>
									<?php
										}
										if(count($reviews))
										{
									?>
										<li><a id="tabreviews" href="#" class="lienmenu">
											<?php if( qtrans_getLanguage() == 'en' ){ ?>
												Reviews
											<?php } else { ?>
												Avis
											<?php } ?>
										</a></li>
									<?php
										}
										if(count($shops))
										{
									?>
										<li><a id="tabbuying" href="#" class="lienmenu">
											<?php if( qtrans_getLanguage() == 'en' ){ ?>
												Where to find our wines ?
											<?php } else { ?>
												Où trouver nos vins ?
											<?php } ?>
										</a></li>
									<?php
										}
										if(count($medias))
										{
									?>
										<li><a id="tabgalery" href="#" class="lienmenu">
											<?php if( qtrans_getLanguage() == 'en' ){ ?>
												Photos gallery
											<?php } else { ?>
												Galerie photos
											<?php } ?>
										</a></li>
									<?php
										}
									?>
									</ul>
								</div>
								<div class="wine-vintage"> <!-- partie millésimes -->						
									<?php
					                if($vintages = $wine->getOtherVintages()) {
										$total = count($vintages);
										$vincod = $wine->getAbsoluteVincod()->getValue(); 
										$i = 1;
										$last = false;
					                    foreach($vintages as $vintage) {
					                        $vintagecod = $vintage->getAbsoluteVincod()->getValue();
											
											if($vintagecod == $vincod) {
					                        	echo $vintage->getVintageYear()->getValue();
											} else {
					                        	echo '<a href="'.$link_rub_vincod.'vincod='.$vintagecod.'" class="link-millesime">'.$vintage->getVintageYear()->getValue().'</a>';
											}
											
											if($i%2 === 0) {
												echo '<br>';
											} else {
												if($i < $total) {
													echo ' - ';
												}
											}

											$i++;
					                    }
					                }
									?>
								</div> <!-- Fin partie millésimes -->
								<div class="bot"> <!-- partie logos vincod -->
									<a href="http://vincod.com/<?php echo $_GET['vincod']; ?>" target="blank"><span class="bottom-bar phone"></span></a>
									<a href="http://vincod.com/print/<?php echo $_GET['vincod']; ?>" target="blank"><span class="bottom-bar print"></span></a>
									<a href="http://vincod.com/qrcode/<?php echo $_GET['vincod']; ?>" target="blank"><span class="bottom-bar qr-code"></span></a>
									<a href="http://vincod.com/get-widget/<?php echo $_GET['vincod']; ?>" target="blank"><span class="bottom-bar integration"></span></a>
									<!--<span class="bottom-bar vinlogo"></span>-->
								</div><!-- Fin partie logos vincod -->
								</td>
								<td class="logo">
									<div class="wine-logo">		
										<?php if($img) { ?>
										<div><img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;lmax=500" alt="" /></div>
										<?php } else { ?>
										<div><img src="<?php echo plugins_url(); ?>/vincod/images/ico_wine.png" alt="" /></div>
										<?php } ?>																											
									</div>
								</td>
								<td class="infos-vins">			
									<div id="blinfos" class="tabcontent active">
									<?php
										foreach($presentation as $obj)
										{
											if ($value = $obj->getValue()) //Don't display the info if it's empty
											{
												echo '<p>'.($obj->getLabel() ? '<strong>'.$obj->getLabel() . "</strong> :<br /> " . $value : $value).'</p>' ;
											}
										}
										foreach($specifications as $obj)
										{
											if ($value = $obj->getValue()) //Don't display the info if it's empty
											{
												echo '<p>'.($obj->getLabel() ? '<strong>'.utf8_decode($obj->getLabel()) . "</strong> : " . utf8_decode($value) : utf8_decode($value)).'</p>' ;
											}
										}
										if($nb_cepages)
										{
											echo '<p><strong>'. __('Cépages', 'vincod') .'</strong> :<br />';
											foreach($cepages as $obj)
											{
														
												echo $obj->getName().($obj->getAmount() ? ' : '.$obj->getAmount() . "% " : '').'<br />' ;
																	
											}
											echo '</p>';
										}
									?>													
									</div>
														
									<div id="bladvice" class="tabcontent" style="display:none">
									<?php
										foreach($advice as $obj)
										{
											if ($value = $obj->getValue()) //Don't display the info if it's empty
											{
												
												if ($obj->getLabel() == "Vidéo dégustation" || $obj->getLabel() == "Tasting video") {//affichage video degustation onglet conseil
													$value = utf8_decode($value);
													$width = 400;
													$height = 250;
													echo '<p><strong>'.$obj->getLabel() . '</strong> :<br />';
													
													if(preg_match('/^http(s?):\/\/vimeo.com\/([0-9_-]+)/', $value, $matches)) {        
														echo '<iframe src="http://player.vimeo.com/video/'.$matches[2].'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
													} else if(preg_match('/^http(s?):\/\/www.youtube.com\/watch\?v=([a-zA-Z0-9_-]+)&?/', $value, $matches)) {
														echo '<iframe src="http://www.youtube.com/embed/'.$matches[2].'" width="'.$width.'" height="'.$height.'" frameborder="0" allowFullScreen></iframe>';
													} else if(preg_match('/^http(s?):\/\/www.dailymotion.com\/video\/([a-zA-Z0-9_-]+)_/', $value, $matches)) {
														echo '<iframe src="http://www.dailymotion.com/embed/video/'.$matches[2].'" width="'.$width.'" height="'.$height.'" frameborder="0" allowFullScreen></iframe>';
													}
													
													echo '</p>' ;
												}
												else
													echo '<p>'.($obj->getLabel() ? '<strong>'.$obj->getLabel() . "</strong> :<br /> " . $value : $value).'</p>' ;
											}
										}
									?>													
									</div>	
														
									<div id="blreviews" class="tabcontent" style="display:none">
									<?php
										foreach($reviews as $obj)
										{
											echo '<p>'.$obj->getContent().'<br /><span class="metadata">';												
														
											$str_metadata = '';
											if($auteur = $obj->getAuthor())
												$str_metadata .= $auteur.', ';
														
											if($source = $obj->getSource()) {
												$url = $obj->getUrl();

												if($url != "http://") {
													$str_metadata .= '<a href="'.$url.'" target="_blank">'.$source.'</a>, ';
												} else {
													$str_metadata .= $source.', ';
												}
											}
														
											if($note = $obj->getMark())
												$str_metadata .= 'Note : '.$note.', ';
																
											if($date = $obj->getDate())
											{
												if($date != '0000-00-00')
													$str_metadata .= $date;
											}
											echo rtrim($str_metadata, ', ').'</p>';
										}
										foreach($wes as $obj)
										{
											echo '<p><a href='.$obj->getURL().'"" target="_blank">'.utf8_decode($obj->getTitle()).'</a><br /><span class="metadata">'.$date = $obj->getDate().'</span></p>';
										}
										?>													
									</div>

									<div id="blbuying" class="tabcontent" style="display:none">
										<?php
						                foreach($shops as $obj)
						                {
						                    if ($value = $obj->getName()) //Don't display the info if it's empty
						                    {
						                        echo '<div style="padding:10px; border-bottom:#CCC 1px dotted; margin-bottom:10px;">';
												echo '<strong>'.$obj->getName().'</strong><br>';
												echo '<a href="'.$obj->getURL().'" target="_blank">';
												if ($description = $obj->getDescription())
												{echo '<a href="'.$obj->getURL().'" target="_blank">'.$obj->getDescription().'</a>  ›';}
												else{echo '<a href="'.$obj->getURL().'" target="_blank">Voir le site ›</a>';}
												echo '</div>';
						                    }
						                }
						                ?>
									</div>

									<div id="blmedia" class="tabcontent" style="display:none">
									<p><strong><?php echo __('Galerie photos: ', 'vincod'); ?></strong></p>
									<ul class="nav">
									<?php
									foreach($medias as $obj)
									{										
										$type = $obj->getType();										
										$url_media = $obj->getUrl();
										$extbubblePh = substr($url_media,-4);
										if($extbubblePh == '.jpg' || $extbubblePh == '.gif' || $extbubblePh == '.png')
										{
											$img = $url_media;
											?>
												<!-- BEGIN media -->
												<li>
													<a class="medialink" href="<?php echo $img; ?>" target="_blank">
														<table>
															<tr>
																<td class="bloclogo">
																	<img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;hmax=78" alt="" />
																				
																</td>
																<td class="namelink">
																	<?php
																	echo $obj->getName();
																	?>
																</td>
															</tr>
														</table>
													</a>
												</li>
												<!-- END media -->
											<?php
										}
									}
									?>
									</ul> <!--fin ul-->	
									</div>												
								</td>
							</tr>
						</table>	
						
<?php
		}
	}
	else
	{
		$options["vincod_userId"] = get_option("vincod_userId");
		$apkey["vincod_apiKey"] = get_option("vincod_apiKey");
			var_dump(DataObjectFactory::getWineriesByOwnerId($options["vincod_userId"], $vincod_language));
			var_dump($options);
			var_dump($apkey);
		if($wineries = DataObjectFactory::getWineriesByOwnerId($options["vincod_userId"], $vincod_language))
		{
			foreach($wineries as $winery)
			{
				var_dump($winery);
				$lien = $link_rub_vincod.'winery='.$winery->getId();
				var_dump($lien);
				$img = $winery->getLogo()->getValue();
				?>
				<table class="vincod-tab">
					<tr>
						<td class="range-logo">
							<?php if($img) { ?>
							<div><img src="<?php echo plugins_url(); ?>/vincod/image_resize.php?chemin=<?php echo $img; ?>&amp;lmax=90" alt="" /></div>
							<?php } else { ?>
							<div><img src="<?php echo plugins_url(); ?>/vincod/images/ico_winery.png" alt="" /></div>
							<?php } ?>
						</td>
						<td class="range-desc">
							<h2><?php echo $winery->getName()->getValue(); ?></h2>
							<?php if($property = $winery->getProperty('presentation')) echo '<p>'.nl2br($property[0]->getValue()).'</p>'; ?>	
							<a class="btlink" href="<?php echo $lien; ?>"><?php echo __('En savoir plus', 'vincod'); ?></a>
						</td>
					</tr>
				</table>	
				<?php				
			}
		}
		else{
		}	
	}
}

?>
	</div>
</div>
