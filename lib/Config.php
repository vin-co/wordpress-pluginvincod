<?php
/*!
 * \file Config.php
 * \brief includes all required Classes to use the api as well as api settings
 such as apikey and displayed logs 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 

//File to includes
require_once(dirname(__FILE__)."/DataObjectFactory.php");
require_once(dirname(__FILE__)."/Utils.php");
require_once(dirname(__FILE__)."/Property.php");
require_once(dirname(__FILE__)."/Wine.php");
require_once(dirname(__FILE__)."/Winery.php");
require_once(dirname(__FILE__)."/Owner.php");
require_once(dirname(__FILE__)."/Range.php");
require_once(dirname(__FILE__)."/Shop.php");

//Global variables to call in function using: global keyword
$errorEnable = false;
$debugEnable = false;
$infoEnable = false;
$testEnable = false;

$apikey = get_option("vincod_apiKey");

?>
