<?php
/*!
 * \file DataObjectFactory.php
 * \class DataObjectFactory
 * \brief The DataObjectFactory is probably the most important class of the API. It's the main place
 AbstractDataObject such as Wine, Winery, Range and Owner are created. All the developper must provide is either 
 an id or vincod and the language (ex: en,fr,de...).
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 *
 * 
 *
 */ 
 
require_once("Config.php");

class DataObjectFactory
{
	private static $baseurl = "http://api.vincod.com/2/xml/";

	private final static function checkErrorInXML($xmlObject)
	{
		//TODO
	}

    /*!
    * \brief return the apikey
    * \return String
    */
	public final static function getApikey()
	{
		global $apikey;
		return $apikey;
	}
	
	/**RANGE BUILDER***********************************************************/
	private final static function getRangesFromSXMLDocument($document,$language)
	{
		$rangeArray = $document->xpath("winery");
		$ranges = array();
		foreach($rangeArray as $rangeXML)
		{
			$id = $rangeXML->xpath("property:id");
			
			info("Found range with id: <b>".$id[0]."</b>");
			
			$rangeObject = new Range($id[0],AbstractDataObject::$TYPE["RANGE"],$rangeXML,$language);
			array_push($ranges,$rangeObject);
		}

		return $ranges;
	}
	
	/*!
    * \brief return Range array that share the same WineryId
    * \param id is the winery's id you want to get ranges of.
    * \param lang is the language you want to get information in.
    * \return Range[]
    */
	public final static function getRangesByWineryId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."range/GetRangesByWineryId/$language/$id?apiKey=".$apikey);
		
		if($ranges = DataObjectFactory::getRangesFromSXMLDocument($document,$language))
			return $ranges;
			
		return array();
	}

    /*!
    * \brief return Range of the specified vincod
    * \param id is the vincod you want to get range of.
    * \param lang is the language you want to get information in.
    * \return Range / false
    */
	public final static function getRangeByVincod($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."range/GetRangeByVincod/$language/$id?apiKey=".$apikey);
		
		if($range = DataObjectFactory::getRangesFromSXMLDocument($document,$language))
			return $range[0];
			
		return false;
	}

    /*!
    * \brief return Range array that share the same OwnerId
    * \param id is the owner's id you want to get ranges of.
    * \param lang is the language you want to get information in.
    * \return Range[]
    */
	public final static function getRangesByOwnerId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."range/GetRangesByOwnerId/$language/$id?apiKey=".$apikey);
		
		if($ranges = DataObjectFactory::getRangesFromSXMLDocument($document,$language))
			return $ranges;
			
		return array();
	}

    /*!
    * \brief return Range of the specified id
    * \param id is the range's id you want to get range of.
    * \param lang is the language you want to get information in.
    * \return Range / false
    */
	public final static function getRangeById($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."range/GetRangeById/$language/$id?apiKey=".$apikey);
		
		if($range = DataObjectFactory::getRangesFromSXMLDocument($document,$language))
			return $range[0];
			
		return false;
	}

	/**WINE BUILDER***********************************************************/
	private final static function getWinesFromSXMLDocument($document,$language)
	{
		$wineArray = $document->xpath("wine");
		$wines = array();
		foreach($wineArray as $wineXML)
		{
			$id = $wineXML->xpath("property:id");
			
			info("Found wine with id: <b>".$id[0]."</b>");
			
			$wineObject = new Wine($id[0],AbstractDataObject::$TYPE["WINE"],$wineXML,$language);
			array_push($wines,$wineObject);
		}

		return $wines;
	}
	
   /*!
    * \brief return Wine array that share the same RangeId
    * \param id is the range's id you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getWinesByRangeId($id, $language)
	{
		return DataObjectFactory::getWinesByWineryId($id,$language);
	}

    /*!
    * \brief return Wine array that share the same WineryId
    * \param id is the winery's id you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getWinesByWineryId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetWinesByWineryId/$language/$id?apiKey=".$apikey);
		
		if($wines = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wines;
			
		return false;
	}

    /*!
    * \brief return Wine array that share the same OwnerId
    * \param id is the owner's id you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getWinesByOwnerId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetWinesByOwnerId/$language/$id?apiKey=".$apikey);
		
		if($wines = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wines;
			
		return false;
	}

    /*!
    * \brief return Wine array that share the same VintageYear
    * \param vintageYear is the vintage year you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getWinesByVintageYear($vintageYear,$language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetWinesByVintageYear/$language/$vintageYear?apiKey=".$apikey);
		
		if($wines = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wines;
			
		return false;
	}

    /*!
    * \brief return Wine array that share the same VintageYear
    * \param vintageYear is the vintage year you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getVintagesByVintageYear($vintageYear, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetVintagesByVintageYear/$language/$vintageYear?apiKey=".$apikey);
		
		if($wines = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wines;
			
		return false;
	}

    /*!
    * \brief return Wine of the specified vincod
    * \param id is the vincod you want to get wine of.
    * \param lang is the language you want to get information in.
    * \return Wine / false
    */
	public final static function getWineByVincod($id, $language)
	{
		global $apikey;
				
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetWineByVincod/$language/$id?apiKey=".$apikey);
		
		if($wine = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wine[0];
			
		return false;
	}
	
	/*!
    * \brief return other Wine in array for a specific vincod
    * \param id is the vincod you want to get Vintages of.
    * \param lang is the language you want to get information in.
    * \return Wine[]
    */
	public final static function getOtherVintagesByVincod($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetOtherVintagesByVincod/$language/$id?apiKey=".$apikey);
		
		if($wine = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wine;
			
		return array();
	}
	
	/*!
    * \brief return Wine into another language using a Vincod
    * \param id is the vincod used to find other wines.
    * \param lang is the language you want to get information in.
    * \return Wine / false
    */
	public final static function getOtherLanguagesByVincod($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetOtherLanguagesByVincod/$language/$id?apiKey=".$apikey);
		
		if($wine = DataObjectFactory::getWinesFromSXMLDocument($document,$language))
			return $wine;
			
		return false;
	}
	
	/*!
    * \brief return String array with available languages for a vincod
    * \param id is the vincod used to find other wines.
    * \return String[]
    */
	public final static function getAvailableLanguagesByVincod($id)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."wine/GetAvailableLanguagesByVincod/en/$id?apiKey=".$apikey);
				
		$languageArray = $document->xpath("lang");
		$languages = array();
		foreach($languageArray as $languageXML)
		{
      		
			$lang = $languageXML->xpath("langabrv");
			$lang = (string) $lang[0];
			if(!empty($lang))
     			array_push($languages,$lang);
		}			
		return $languages;
	}

	/**WINERY BUILDER***********************************************************/	
	private final static function getWineriesFromSXMLDocument($document,$language)
	{
		$wineryArray = $document->xpath("winery");
		$wineries = array();
		foreach($wineryArray as $wineryXML)
		{
			$id = $wineryXML->xpath("property:id");
			
			info("Found winery with id: <b>".$id[0]."</b>");
			
			$wineryObject = new Winery($id[0],AbstractDataObject::$TYPE["WINERY"],$wineryXML,$language);

			array_push($wineries,$wineryObject);
		}
          
          return $wineries;
	}

    /*!
    * \brief return Winery of the specified RangeId
    * \param id is the range's id you want to get winery of.
    * \param lang is the language you want to get information in.
    * \return Winery / false
    */	
	public final static function getWineryByRangeId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."winery/GetWineryByRangeId/$language/$id?apiKey=".$apikey);

		if($winery = DataObjectFactory::getWineriesFromSXMLDocument($document,$language))
			return $winery[0];
			
		return false;
	}

  /*!
    * \brief return Winery of the specified Vincod
    * \param id is the vincod you want to get winery of.
    * \param lang is the language you want to get information in.
    * \return Winery / false
    */	
	public final static function getWineryByVincod($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."winery/GetWineryByVincod/$language/$id?apiKey=".$apikey);
		
		if($winery = DataObjectFactory::getWineriesFromSXMLDocument($document,$language))
			return $winery[0];
			
		return false;
	}

    /*!
    * \brief return Winery array that share the same OwnerId
    * \param id is the owner's id you want to get winery of.
    * \param lang is the language you want to get information in.
    * \return Winery[]
    */
	public final static function getWineriesByOwnerId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."winery/GetWineriesByOwnerId/$language/$id?apiKey=".$apikey);
		
		if($wineries = DataObjectFactory::getWineriesFromSXMLDocument($document,$language))
			return $wineries;
			
		return array();
	}

  /*!
    * \brief return Winery of the specified id
    * \param id is the winery's id you want to get winery of.
    * \param lang is the language you want to get information in.
    * \return Winery / false
    */	
	public final static function getWineryById($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."winery/GetWineryById/$language/$id?apiKey=".$apikey);
		
		if($winery = DataObjectFactory::getWineriesFromSXMLDocument($document,$language))
			return $winery[0];
			
		return false;
	}

	/**Owner BUILDER***********************************************************/
	private final static function getOwnersFromSXMLDocument($document,$language)
	{
		$ownerArray = $document->xpath("owner");
		$owners = array();
		foreach($ownerArray as $ownerXML)
		{
			$id = $ownerXML->xpath("property:id");
			
			info("Found owner with id: <b>".$id[0]."</b>");
			
			$ownerObject = new Owner($id[0],AbstractDataObject::$TYPE["OWNER"],$ownerXML,$language);

			array_push($owners,$ownerObject);
		}

		return $owners;
	}

  /*!
    * \brief return Owner of the specified RangeId
    * \param id is the range's id you want to get owner of.
    * \param lang is the language you want to get information in.
    * \return Owner / false
    */		
	public final static function getOwnerByRangeId($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."owner/GetOwnerByRangeId/$language/$id?apiKey=".$apikey);
		
		if($owner = DataObjectFactory::getOwnersFromSXMLDocument($document,$language))
			return $owner[0];
			
		return false;
	}

  /*!
    * \brief return Owner of the specified Vincod
    * \param id is the vincod you want to get owner of.
    * \param lang is the language you want to get information in.
    * \return Owner / false
    */	
	public final static function getOwnerByVincod($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."owner/GetOwnerByVincod/$language/$id?apiKey=".$apikey);
		
		if($owner = DataObjectFactory::getOwnersFromSXMLDocument($document,$language))
			return $owner[0];
			
		return false;
	}

  /*!
    * \brief return Owner of the specified WineryId
    * \param id is the winery's id you want to get owner of.
    * \param lang is the language you want to get information in.
    * \return Owner / false
    */	
	public final static function getOwnerByWineryId ($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."owner/GetOwnerByWineryId/$language/$id?apiKey=".$apikey);
		
		if($owner = DataObjectFactory::getOwnersFromSXMLDocument($document,$language))
			return $owner[0];
			
		return false;
	}

   /*!
    * \brief return Owner of the specified Id
    * \param id is the owner's id you want to get owner of.
    * \param lang is the language you want to get information in.
    * \return Owner / false
    */	
	public final static function getOwnerById($id, $language)
	{
		global $apikey;
		$document = simplexml_load_file(DataObjectFactory::$baseurl."owner/GetOwnerById/$language/$id?apiKey=".$apikey);
		
		if($owner = DataObjectFactory::getOwnersFromSXMLDocument($document,$language))
			return $owner[0];
			
		return false;
	}
}
?>
