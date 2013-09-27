<?php
/*!
 * \file Winery.php
 * \class Winery
 * \brief A Winery is an AbstractDataObject. It contains all information about a single winery. Mostly made up of properties, it s also possible to get other AbstractDataObject (Wine, Owner, Range) concerning the Winery.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
require_once("DataObjectFactory.php");
require_once("AbstractDataObject.php");
require_once("Property.php");
require_once("Utils.php");

class Winery extends AbstractDataObject
{
	private $objects;
	
	private static $properties = array(
		"id",
		"name",
		"website",
		"shop",
		"facebook",
		"twitter",
		"blog",
		"address",
		"postcode",
		"city",
		"country",
		"phone",
		"fax",
		"logo",
		"email",
		"content",
		"vincod"
	);

	/*! 
     *  \brief Winery constructor
     *  \param String id is what will be used to identify the object and query other objects.
     *  \param TYPE type relates to the pseudo-enum TYPE from AbstractDataObject and refer to the object type.
     *  \param SimpleXMLElement xmlObject represents the object on which queries will be done.
     *  \param String language is the language in which the first query has been done.
     *  \return Winery
     */	
	public function __construct($id,$type,$xmlObject,$language)
	{
		parent::__construct($id,$type,$xmlObject,$language);
		$this->objects = array();
	}

	//Abstract Methods
	protected function isCustomProperties($propertyName)
	{
		return !(in_array($propertyName,Winery::$properties));
	}
	
	//Private Methods
	private function getTheValue ($value)		
	{
		if ($val = $this->getTheValues ($value))
			return $val[0];
		return false;
	}
	
	private function getTheValues ($value)		
	{
		if(!array_key_exists($value,$this->objects))
		{
			$this->objects[$value] = parent::getProperty($value);
		}

		if(count($this->objects[$value]) > 0)
		{
			return $this->objects[$value];
		}
		else
			return array();
	}

	//Class Methods
	/*!
	* \brief Return the name as Property object.
	* \return Property / false
	*/
	public final function getName(){return $this->getTheValue ("name");}
	
	/*!
	* \brief Return the website as Property object.
	* \return Property / false
	*/
	public final function getWebSite(){return $this->getTheValue ("website");}
	
	/*!
	* \brief Return the shop as Property object.
	* \return Property / false
	*/
	public final function getShop(){return $this->getTheValue ("shop");}
	
	/*!
	* \brief Return the facebook as Property object.
	* \return Property / false
	*/
	public final function getFacebook(){return $this->getTheValue ("facebook");}
	
	/*!
	* \brief Return the twitter as Property object.
	* \return Property / false
	*/
	public final function getTwitter(){return $this->getTheValue ("twitter");}
	
	/*!
	* \brief Return the blog as Property object.
	* \return Property / false
	*/
	public final function getBlog(){return $this->getTheValue ("blog");}
	
	/*!
	* \brief Return the address as Property object.
	* \return Property / false
	*/
	public final function getAddress(){return $this->getTheValue ("address");}
	
	/*!
	* \brief Return the postcode as Property object.
	* \return Property / false
	*/
	public final function getPostcode(){return $this->getTheValue ("postcode");}
	
	/*!
	* \brief Return the city as Property object.
	* \return Property / false
	*/
	public final function getCity(){return $this->getTheValue ("city");}

	/*!
	* \brief Return the country as Property object.
	* \return Property / false
	*/
	public final function getCountry(){return $this->getTheValue ("country");}
	
	/*!
	* \brief Return the phone as Property object.
	* \return Property / false
	*/
	public final function getPhone(){return $this->getTheValue ("phone");}

	/*!
	* \brief Return the fax as Property object.
	* \return Property / false
	*/
	public final function getFax(){return $this->getTheValue ("fax");}
	
	/*!
	* \brief Return the logo as Property object.
	* \return Property / false
	*/
	public final function getLogo(){return $this->getTheValue ("logo");}
	
	/*!
	* \brief Return the email as Property object.
	* \return Property / false
	*/
	public final function getEmail(){return $this->getTheValue ("email");}
	
	/*!
	* \brief Return the content as Property object.
	* \return Property / false
	*/
	public final function getContent(){return $this->getTheValue ("content");}
	
	/*!
	* \brief Return the vincods as Property Array.
	* \return Property[]
	*/
	public final function getVincods(){return $this->getTheValues ("vincod");}
	
	/*!
	* \brief Return the winery's wines as Wine array or false if Wine [] cannot be instanciated.
	* \return Wine[]
	*/
	public final function getWines()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getWinesByWineryId($id,$this->getLanguage());

        return array();
	}
	
	/*!
	* \brief Return the winery's ranges as Range array or false if Range [] cannot be instanciated.
	* \return Range[]
	*/	
	public final function getRanges()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getRangesByWineryId($id,$this->getLanguage());

        return array();  
	}

    /*!
	* \brief Return the winery's owner as Owner object or false if Owner cannot be instanciated.
	* \return Owner / false
	*/	
	public final function getOwner()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getOwnerByWineryId($id,$this->getLanguage());

        return false;  
	}
	
	/*!
	* \brief Return the vincods as Property Array.
	* \return Property[]
	*/
	public final function getPresentation(){return $this->getTheValue ("presentation");}

}