<?php
/*!
 * \file Property.php
 * \class Property
 * \brief A Property is a data structure that is made up of: 
<br /><ul>
<li><b>name:</b> name of the xml node</li>
<li><b>label:</b> text content of label node if exists</li>
<li><b>value:</b> text content of value node if exists otherwise it contains the direct text value of the node</li>
<li><b>language:</b> text content of language node if exists</li>
</ul>
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
require_once("Utils.php");
class Property
{
	private $name;
	private $label;
	private $value;
	private $language;

	private static $xmlNodesName = array(
		"label",
		"value",
		"language"
	);
    
    /*! 
     *  \brief Property constructor
     *  \param String name of the node containing the property
     *  \param String label text content of the node label (if exists)
     *  \param String value text content of value node if exists otherwise it contains the direct text value of the node
     *  \param String language text content of language node if exists
     *  \return Property
     */
	public function __construct($name,$label, $value,$language="")
	{
		$this->name = $name;
		$this->label = $label;
		$this->value = $value;
		$this->language = $language;
	}

	/*! 
     *  \brief Return the name of the property or false if empty.
     *  \return String / false
     */
	public final function getName()
	{
		return (!empty($this->name)) ? $this->name : false;
	}
	
	/*! 
     *  \brief Return the label of the property or false if empty.
     *  \return String / false
     */
	public final function getLabel()
	{
		return (!empty($this->label)) ? $this->label : false;
	}

	/*! 
     *  \brief Return the value of the property or false if empty.
     *  \return String / false
     */
	public final function getValue()
	{
		return (!empty($this->value)) ? $this->value : false;
	}

	/*! 
     *  \brief Return the language of the property or false if empty.
     *  \return String / false
     */
	public final function getLanguage()
	{
		return (!empty($this->language)) ? $this->language : false;
	}

    /*! 
     *  \brief Return the property values as a String
     *  \return String
     */
	public function __toString()
	{
		return "Label: ".$this->label."<br />Value: ".$this->value."<br />Lang: ".$this->language;
	}

	/*! 
     *  \brief Build a property from a SimpleXMLElement. It returns false if the node doesn t contain a property.
     * \param SimpleXmlElement node
     *  \return Property / false
     */	
     public final static function getPropertyFromSXMLElement($node)
	{
		$label = "";
		$value =  "";
		$language = "";

		if(!Property::isSXMLNodeProperty($node))
		{
			error("Property creation failed for element: ".$node->getName());
			return false;
		}			
		
		if(!hasChildren($node))
		{
      		$name = $node->getName();
			$label = "";
			$value = (string) $node;
			$language = "";
		}
		else
		{			
			$label = $node->xpath("label");
			$value = $node->xpath("value");
			$language = $node->xpath("language");

			
			//If label node not in property then the node name will be the label
			if(count($label)==0)
				$label = "";
			else
				$label = (string)$label[0];	

			if(count($value)==0)
				$value="";
			else
				$value = (string)$value[0];

			if(count($language)>0)
				$language = (string)$language[0];
			else
				$language = "";

		}		

		$property = new Property($node->getName(),$label,$value,$language);
		return $property;
			
	}

    /*! 
     * \brief Check whether or not the SimpleXMLElement node is a property.
     * \param SimpleXmlElement node
     *  \return Boolean
     */	
	public final static function isSXMLNodeProperty($node)
	{
		if(!hasChildren($node))
			return true;
		else
		{
			foreach($node->children() as $child)
			{
				$name = $child->getName();

				if(! in_array($name, Property::$xmlNodesName))
					return false;				
			}

			return true;
		}
	}
}
?>
