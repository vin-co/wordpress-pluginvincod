<?php
/*!
 * \file AbstractDataObject.php
 * \class AbstractDataObject
 * \brief Object that implements methods to read through SimpleXMLElement
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 *
 * The AbstractDataObject implements a range of methods that can be used
 * to retrieve information in SimpleXMLObject.
 *
 * 
 *
 */ 
 
require_once("Property.php");
abstract class AbstractDataObject
{
	private $id;
	private $type;
	private $language;

	protected $xmlObject;

	public static $TYPE=array(
		"WINERY"=>0,"WINE"=>1,"RANGE"=>2,"OWNER"=>3
	);	

	private final static function typeIndexToName($index)
	{
		foreach(AbstractDataObject::$TYPE as $key=>$value)
		{
			if($index == $value)
				return $key;
		}
	}

    /*! 
     *  \brief Can and must only be call from subclass.
     *  \param String id is what will be used to identify the object and query other objects.
     *  \param TYPE type relates to the pseudo-enum TYPE and refer to the object type.
     *  \param SimpleXMLElement xmlObject represents the object on which queries will be done.
     *  \param String language is the language in which the first query has been done.
     *  \return AbstractDataObject
     */
	protected function __construct($id,$type,$xmlObject,$language)
	{
		$this->id = $id;
		$this->type = $type;
		$this->xmlObject = $xmlObject;
		$this->language = $language;
	}

    /*! 
     *  \brief Returns as a String the type of the object based on $TYPE in AbstractDataObject.
     *  \return String / false
     */
	public final function getType()
	{
		return (!empty($this->type)) ? AbstractDataObject::typeIndexToName($this->type) : false;
	}

	/*! 
     *  \brief Returns as a String the id.
     *  \return String / false
     */
     public final function getId()
	{
		return (!empty($this->id)) ? $this->id : false;
	}

	/*! 
     *  \brief Returns as a String the language in which the query has been done.
     *  \return String / false
     */
	public final function getLanguage()
	{
		return (!empty($this->language)) ? $this->language : false;
	}

    /*! 
     *  \brief Returns whether or not a property exists
     *  \return Boolean.
     */
	public final function hasProperty($propertyName)
	{		
		$propertiesElementArray = getSXMLElementWithNameSpace($this->xmlObject,"property",true);
		
		foreach($propertiesElementArray as $propertyElement)
		{
			$property = Property::getPropertyFromSXMLElement($propertyElement);
			if($property->getName() == $propertyName)
				return true;
		}	
		
		return false;
	}

	/*! 
     *  \brief Returns all properties.
     *  \return Property[]
     */
	public final function getProperties()
	{	
		$propertiesArray = array();
		$propertiesElementArray = getSXMLElementWithNameSpace($this->xmlObject,"property",true);
		
		foreach($propertiesElementArray as $propertyElement)
		{
			$property = Property::getPropertyFromSXMLElement($propertyElement);
			if($property)
			{
				array_push($propertiesArray,$property );
			}
		}

		return $propertiesArray;
	}

    /*! 
     *  \brief Must be implemented in subclasses and is used to know whether a property is custom or defined.
     *  \return Boolean
     */
	abstract protected function isCustomProperties($label);

	/*! 
     *  \brief Returns all custom properties.
     *  \return Property[]
     */
	public final function getCustomProperties()
	{
		$properties = $this->getProperties();
		$finalArray = array();
		foreach($properties as $property)
		{
			if($this->isCustomProperties($property->getName()))
				array_push($finalArray, $property);
		}
		
		return $finalArray;
	}

    /*! 
     *  \brief Returns all non-custom properties.
     *  \return Property[]
     */
	public final function getNonCustomProperties()
	{
		$properties = $this->getProperties();
		$finalArray = array();
		foreach($properties as $property)
		{
			if(!$this->isCustomProperties($property->getName()))
				array_push($finalArray, $property);
		}
		
		return $finalArray;
	}
	
	/*! 
     *  \brief Returns an array of property having the specified name.
     *  \param String propertyName corresponds to the seeking property.
     *  \return Property[]
     */
	public final function getProperty($propertyName)
	{		
		$propertiesElementArray = getSXMLElementWithNameSpace($this->xmlObject,"property",true);
		$finalPropertyArray = array();
		foreach($propertiesElementArray as $propertyElement)
		{
			$property = Property::getPropertyFromSXMLElement($propertyElement);

			if($property->getName() == $propertyName)
			{
				array_push($finalPropertyArray,$property);
			}
		}	
		
		return $finalPropertyArray;
	}

    /*! 
     *  \brief Add property to the current object. It uses the name and the value only. To insert a full property use addProperty instead.
     *  \param Property property is the property that will be added to the root node.
     */
	public final function addSimpleProperty($property)
	{
		$this->xmlObject->addChild($property->getName(),$property->getValue());
	}

     /*! 
     *  \brief Add property to the current object. It uses the name and the value only. To insert a full property use addProperty instead.
     *  \param Property property is the property that will be added to the root node.
     */
	public final function addProperty($property)
	{
     	$name = $property->getName();
		$label = $property->getLabel();
		$value = $property->getValue();
		
		$child = $this->xmlObject->addChild($name);
		$label = $property->getLabel();
		$value = $property->getValue();
		$language = $property->getLanguage();
    	 
		if(!empty($label))
			$child->addChild("label",$label);
    
		$child->addChild("value",$value);
    
		if(!empty($language))
    			$child->addChild("language",$language);
	}
}
?>
