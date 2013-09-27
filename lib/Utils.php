<?php 
/*!
 * \file Util.php
 * \brief Gather multiple useful function for strings, array and logs.  
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
require_once("Config.php");

/*!
 * \brief Check whether the string starts with the needle pattern 
 * \param String str
 * \param String needlePattern
 * \return Boolean
 */ 
function startsWith($str, $needle)
{
    $length = strlen($needle);
    return (substr($str, 0, $length) == $needle);
}

/*!
 * \brief Check whether the string ends with the needle pattern 
 * \param String str
 * \param String needlePattern
 * \return Boolean
 */ 
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    $start  = $length * -1; //negative
    return (substr($haystack, $start) == $needle);
}

/*!
 * \brief Copy an array and return the copy
 * \param Array source
 * \return Array
 */ 
function array_copy ($aSource) 
{
    // check if input is really an array
    if (!is_array($aSource)) {
        throw new Exception("Input is not an Array");
    }
    
    // initialize returned array
    $aRetAr = array();
    
    // get array keys
    $aKeys = array_keys($aSource);
    // get array values
    $aVals = array_values($aSource);
    
    // loop through array and assign keys+values to new return array
    for ($x=0;$x<count($aKeys);$x++) {
        // clone if object
        if (is_object($aVals[$x])) {
            $aRetAr[$aKeys[$x]]=clone $aVals[$x];
        // recursively add array
        } elseif (is_array($aVals[$x])) {
            $aRetAr[$aKeys[$x]]=array_copy ($aVals[$x]);
        // assign just a plain scalar value
        } else {
            $aRetAr[$aKeys[$x]]=$aVals[$x];
        }
    }
    
    return $aRetAr;
}

/*!
 * \brief Print debug message if debug is enable (cf. Config.php)
 */
function debug($message)
{
	global $debugEnable;
	
	if($debugEnable)
	{
		$trace = array_shift( debug_backtrace() );;

		$from = array_pop( explode('/', $trace["file"]));
		$line = $trace["line"];
		$function = $trace["function"];
		
		echo "<pre style='background-color:#F0C771;padding:10px;'>";
		echo "<b>DEBUG:</b> $from - line: $line<hr />";
		print_r($message);
		echo "</pre>";
	}		
}

/*!
 * \brief Print info message if info is enable (cf. Config.php)
 */
function info($message)
{
	global $infoEnable;
	
	if($infoEnable)
	{
		$trace = array_shift( debug_backtrace() );;

		$from = array_pop( explode('/', $trace["file"]));
		$line = $trace["line"];
		$function = $trace["function"];
		
		echo "<pre style='background-color:#BFE289;padding:10px;'>";
		echo "<b>INFO:</b> $from - line: $line<hr />";
		print_r($message);
		echo "</pre>";
	}		
}

/*!
 * \brief Print error message if error is enable (cf. Config.php)
 */
function error($message)
{
	global $errorEnable;
	
	if($errorEnable)
	{
		$trace = array_shift( debug_backtrace() );;

		$from = array_pop( explode('/', $trace["file"]));
		$line = $trace["line"];
		$function = $trace["function"];
		
		echo "<pre style='background-color:#FFA3A5;padding:10px;'>";
		echo "<b>ERROR:</b> $from - line: $line<hr />";
		print_r($message);
		echo "</pre>";
	}		
}

/*!
 * \brief Print test message if test is enable (cf. Config.php)
 */
function test($title,$message)
{
	global $testEnable;
	
	if($testEnable)
	{
		$trace = array_shift( debug_backtrace() );;

		$from = array_pop( explode('/', $trace["file"]));
		$line = $trace["line"];
		$function = $trace["function"];
		
		echo "<pre style='background-color:#9EC2E7;padding:10px;'>";
		echo "<b>TEST: $title</b><br />from: $from - line: $line<hr />";
		print_r($message);
		echo "</pre>";
	}		
}

/*!
 * \brief Open a test tag if test is enable (cf. Config.php) WARNING DO NOT FORGET TO CALL closetest
 */
function opentest($title)
{
	global $testEnable;
	
	if($testEnable)
	{
		$trace = array_shift( debug_backtrace() );;

		$from = array_pop( explode('/', $trace["file"]));
		$line = $trace["line"];
		$function = $trace["function"];
		
		echo "<pre style='background-color:#D5E6F8;padding:10px;'>";
		echo "<b>OPEN TEST: $title</b><br />from $from - line:$line<hr />";
	}		
}

/*!
 * \brief close a test tag if test is enable (cf. Config.php) WARNING Call it only if opentest has previously been called.
 */
function closetest($title,$message)
{
	global $testEnable;
	
	if($testEnable)
	{
		echo "<hr />";
		echo "<b>CLOSE TEST: $title - $message</b>";
		echo "</pre>";
	}		
}

/*!
 * \brief Check if SimpleXMLElement has children.
 * \return Boolean
 */
function hasChildren($SXMLElement)
{
	foreach($SXMLElement->children() as $child)
	{
		return true;
	}
	
	return false;
}

/*!
 * \brief Search and returns all SimpleXMLElement sharing a namespace from the root node.
 * \param SimpleXMLElement rootnode: is the root where the namespace will be searched from.
 * \param String namespace : is the searched namespace.
 * \param Boolean : whether to search recursively or not.
 * \return SimpleXMLElement[]
 */
function getSXMLElementWithNameSpace($rootnode,$namespace, $recursively)
{	
	$xmlElementsArray = array();
	
	
	$keyArray = (is_array($recursively)) ? $recursively : array();
	$propertiesElement = $rootnode->children($namespace);	
	
	foreach($propertiesElement as $propertyElement)
	{
		array_push($xmlElementsArray,$propertyElement);
	}		
		
	if($recursively)
	{
		foreach($rootnode->children() as $child)
		{	
			$xmlElementsArray = array_merge($xmlElementsArray,getSXMLElementWithNameSpace($child,$namespace,$recursively));
		}
	}	

	return $xmlElementsArray;		
}
?>
