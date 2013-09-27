<?php
if(isset($_GET['chemin']))
{
	$chemin = urldecode($_GET['chemin']); // le chemin en absolu
	//echo $chemin;
	// dimension de l image
	$size = getimagesize($chemin); 
	$largeur=$size[0];
	$hauteur=$size[1];
	//echo $chemin;
	// calcul proportionnalite
	if(isset($_GET['lmax']))
	{
		$largeur_max = intval($_GET['lmax']);
		if($largeur_max > $largeur)
		{
			$hauteur_max = $hauteur;
			$largeur_max = $largeur;
		}
		else
		{
			$rapport = $largeur / $largeur_max;
			$hauteur_max = $hauteur / $rapport;
		}
	}
	elseif(isset($_GET['hmax'])){
		$hauteur_max = intval($_GET['hmax']);
		if($hauteur_max > $hauteur)
		{
			$hauteur_max = $hauteur;
			$largeur_max = $largeur;
		}
		else
		{
			$rapport = $hauteur / $hauteur_max;
			$largeur_max = $largeur / $rapport;
		}
	}
	
	
	

	// recupere type
	$aNomImg = explode(".", $_GET['chemin']);
	$last_i = count($aNomImg)-1;
	if (strtolower($aNomImg[$last_i]) == 'jpeg' || strtolower($aNomImg[$last_i]) == 'jpg')
	{
		// JPEG
		header("Content-type: image/jpeg");
		$img_new = imagecreatefromjpeg($chemin);
		
		$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
		imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
		imagejpeg($img_mini);
	}
	else if (strtolower($aNomImg[$last_i]) == 'gif')
	{
		// GIF
		header("Content-type: image/gif");
		$img_new = imagecreatefromgif($chemin);
		
		$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
		imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
		imagegif($img_mini);
	}
	else if (strtolower($aNomImg[$last_i]) == 'png')
	{
		// PNG		
		header("Content-type: image/x-png" );
		$img_new = imagecreatefrompng($chemin);
		
		$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
		//
		imagealphablending($img_mini,false);
		imagesavealpha($img_mini,true);
		//
		imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
		imagepng($img_mini);
	}
	else
	{
		
		if($size['mime'] == 'image/jpeg' || $size['mime'] == 'image/jpg')
		{
			header("Content-type: ".$size['mime']);
			$img_new = imagecreatefromjpeg($chemin);
			
			$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
			imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
			imagejpeg($img_mini);
		}
		elseif($size['mime'] == 'image/x-png' || $size['mime'] == 'image/png')
		{
			header("Content-type: image/x-png" );
			$img_new = imagecreatefrompng($chemin);
			
			$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
			//
			imagealphablending($img_mini,false);
			imagesavealpha($img_mini,true);
			//
			imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
			imagepng($img_mini);
		}
		elseif($size['mime'] == 'image/gif')
		{
			header("Content-type: image/gif");
			$img_new = imagecreatefromgif($chemin);
			
			$img_mini = imagecreatetruecolor ($largeur_max, $hauteur_max);
			imagecopyresampled ($img_mini,$img_new,0,0,0,0,$largeur_max,$hauteur_max,$size[0],$size[1]);
			imagegif($img_mini);
		}
	}
}

?>