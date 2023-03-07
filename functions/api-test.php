<?php
/**
 * Api Test
 *
 * Because you can't perform AJAX GET cross domain AND jsonp isn't cross browser
 * i use this tip. With this, you get a "local api access"
 *
 * @author      Vinternet
 * @category    Script
 * @copyright   2023 VINTERNET
 *
 */

// Init output
$output = '{}';

if(isset($_GET['api']) && isset($_GET['id'])) {

	// Get values
	$api = $_GET['api'];
	$id = $_GET['id'];

	// Little check
	if(!empty($api) && !empty($id)) {

		// It's ok, go open
		$url = 'http://api.vincod.com/2/json/owner/checkOwnerApi/fr/' . $id . '?apiKey=' . $api;
		$output = file_get_contents($url);


	}

}

// Render
echo $output;


?>
