<?php
/**
 * Image Resizer
 *
 * Simple script which use vendor function to resize
 * on the fly all pictures.
 *
 * @author		Jérémie GES
 * @copyright   2013
 * @category	Script
 *
 */
ini_set('memory_limit', '90M');

require('resize-function.php');



class Resizer {

	public $suffix_path = '../cache/resizer/';
	//public $size_memory = '64M';

	public $width_resize = 250;
	public $height_resize = 250;
	public $url = '';
	public $ext_guessed = '';
	public $name = '';

	public function __construct() {

		// Upgrade memory limit
		//ini_set('memory_limit', $this->size_memory);

	}

	public function run() {

		$params = $_GET;

		if ($this->_check_param('url', $params)) {

			// Check Width
			if ($this->_check_param('width', $params)) {

				$this->width_resize = $params['width'];

			} 

			// Check height
			if ($this->_check_param('height', $params)) {

				$this->height_resize = $params['height'];

			}	

			$url = $params['url'];

			$name = $this->_download_remote_file($url);

			$new_file = $this->_resize($name);

			$this->_render($new_file);


		}

	}

	private function _resize($name) {

		// Generate unique name for the new file
		$new_name = 'resizer_' . uniqid() . '.' . $this->ext_guessed;

		$source_file = $name;
		$new_file = $this->suffix_path . $new_name;

		// Call the function
		smart_resize_image($source_file , $this->width_resize , $this->height_resize , true ,  $new_file, true , false , 100 );

		return $new_file;

	}

	private function _download_remote_file($url) {

		// Open the url
		$image = file_get_contents($url);

		// Guess the type of picture
		$ext = explode('.', $url);
		$this->ext_guessed = $ext[count($ext) - 1];

		// Give name for the new file
		$name = 'downloaded_' . uniqid() . '.' . $this->ext_guessed;

		// Create new file
		file_put_contents($this->suffix_path . $name, $image);

		return $this->suffix_path . $name;

	}

	private function _check_param($param, $params) {

		if (isset($params[$param]) && !empty($params[$param])) {

			return $params[$param];

		} else {

			return FALSE;

		}

	}

	private function _render($name) {

		switch( $this->ext_guessed ) {
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default:
		}

		header('Content-type: ' . $ctype);
		readfile($name);

		unlink($name);
		
	}

}

$resizer = new Resizer;

$resizer->run();


?>