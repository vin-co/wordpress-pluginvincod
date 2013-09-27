<?php
/*!
 * \file Range.php
 * \class Range
 * \brief A Range is an AbstractDataObject. It contains all information about a single range. Mostly made up of properties, it s also possible to get other AbstractDataObject (Wine, Winery, Owner) concerning the Range.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
require_once("DataObjectFactory.php");
require_once("Property.php");
require_once("Utils.php");

class Range extends Winery
{
    /*! 
     *  \brief Range constructor
     *  \param String id is what will be used to identify the object and query other objects.
     *  \param TYPE type relates to the pseudo-enum TYPE from AbstractDataObject and refer to the object type.
     *  \param SimpleXMLElement xmlObject represents the object on which queries will be done.
     *  \param String language is the language in which the first query has been done.
     *  \return Range
     */
	public function __construct($id,$type,$xmlObject,$language)
	{
		parent::__construct($id,$type,$xmlObject,$language);
	}
	
	/*!
	* \brief Return the ranges's winery as Winery or false if Winery cannot be instanciated.
	* \return Winery / false
	*/	
	public final function getWinery()
	{
        $id = $this->getId();
        if($id)
          return DataObjectFactory::getWineryByRangeId($id,$this->getLanguage);

        return false;  
	}
}
