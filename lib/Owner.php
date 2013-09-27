<?php
/*!
 * \file Owner.php
 * \class Owner
 * \brief An Owner is an AbstractDataObject. It contains all information about a single owner. Mostly made up of properties, it s also possible to get other AbstractDataObject (Wine, Winery, Range) concerning the Owner.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
require_once("DataObjectFactory.php");
require_once("AbstractDataObject.php");
require_once("Property.php");
require_once("Utils.php");

class Owner extends AbstractDataObject
{
	private $objects;
	
	private static $properties = array(
		"id",
		"email",
		"company",
		"address",
		"postcode",
		"city",
		"phone",
		"fax",
		"website",
		"facebook",
		"twitter",
		"logo",
		"presentation"
	);
	
	/*! 
     *  \brief Owner constructor
     *  \param String id is what will be used to identify the object and query other objects.
     *  \param TYPE type relates to the pseudo-enum TYPE from AbstractDataObject and refer to the object type.
     *  \param SimpleXMLElement xmlObject represents the object on which queries will be done.
     *  \param String language is the language in which the first query has been done.
     *  \return Owner
     */
	public function __construct($id,$type,$xmlObject,$language)
	{
		parent::__construct($id,$type,$xmlObject,$language);
		$this->objects = array();
	}

	protected function isCustomProperties($label)
	{
		return !(in_array($propertyName,Owner::$properties));
	}
	
	//Private Method
	private function getTheValue ($value)		
	{
		if(!array_key_exists($value,$this->objects))
		{
			$this->objects[$value] = parent::getProperty($value);
		}

		if(count($this->objects[$value]) > 0)
		{
			$value = $this->objects[$value];
			return $value[0];
		}
		else
			return false;
	}

	//Class Methods
	/*!
	* \brief Return the email as Property object.
	* \return Property
	*/
	public final function getEmail(){return $this-> getTheValue ("email");}
	
	/*!
	* \brief Return the company as Property object.
	* \return Property
	*/
	public final function getCompany(){return $this-> getTheValue ("company");}
	
	/*!
	* \brief Return the address as Property object.
	* \return Property
	*/
	public final function getAdress(){return $this-> getTheValue ("adress");}
	
	/*!
	* \brief Return the postcode as Property object.
	* \return Property
	*/
	public final function getPostcode(){return $this-> getTheValue ("postcode");}
	
	/*!
	* \brief Return the city as Property object.
	* \return Property
	*/
	public final function getCity(){return $this-> getTheValue ("city");}
	
	/*!
	* \brief Return the phone as Property object.
	* \return Property
	*/
	public final function getPhone(){return $this-> getTheValue ("phone");}
	
	/*!
	* \brief Return the fax as Property object.
	* \return Property
	*/
	public final function getFax(){return $this-> getTheValue ("fax");}
	
	/*!
	* \brief Return the website as Property object.
	* \return Property
	*/
	public final function getWebsite(){return $this-> getTheValue ("website");}
	
	/*!
	* \brief Return the facebook as Property object.
	* \return Property
	*/
	public final function getFacebook(){return $this-> getTheValue ("facebook");}
	
	/*!
	* \brief Return the twitter as Property object.
	* \return Property
	*/
	public final function getTwitter(){return $this-> getTheValue ("twitter");}
	
	/*!
	* \brief Return the logo as Property object.
	* \return Property
	*/
	public final function getLogo(){return $this-> getTheValue ("logo");}
	
	/*!
	* \brief Return the presentation as Property object.
	* \return Property
	*/
	public final function getPresentation(){return $this-> getTheValue ("presentation");}
	
	/*!
	* \brief Return the owner's ranges as Range array or false if Range [] cannot be instanciated.
	* \return Range[]
	*/	
	public final function getRanges()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getRangesByOwnerId($id,$this->getLanguage);

        return array();  
	}
	
	/*!
	* \brief Return the owner's wineries as Winery array or false if Winery [] cannot be instanciated.
	* \return Winery[]
	*/	
	public final function getWineries()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getWineriesByOwnerId($id,$this->getLanguage);

        return array(); 
	}
	
		/*!
	* \brief Return the owner's wines as Wine array or false if Wine [] cannot be instanciated.
	* \return Wine[]
	*/	
	public final function getWines()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getWinesByOwnerId($id,$this->getLanguage);

        return array();  
	}
}
