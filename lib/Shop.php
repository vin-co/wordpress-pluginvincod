<?php
/*!
 * \file Shop.php
 * \class Shop
 * \brief The Shop class is a kind of DataStructure with a description. A shop always has the type equals to shop. Those objects are usually embedded inside Wine object. 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
require_once("DataStructure.php");

class Shop extends DataStructure
{
	private static $xmlNodesName = array(
		"name", //PH corrige name>shop>name
		"url",
		"description"
	);

	private $description;

    /*! 
     *  \brief Shop constructor
     *  \param String name
     *  \param String description
     *  \param String url
     *  \return Shop
     */
	public function __construct($name,$description,$url)
	{
		parent::__construct($name,"shop",$url);
		$this->description = $description;
	}
    
    /*! 
     *  \brief Returns the shop's description as String or false if empty
     *  \return String / false
     */
	public final function getDescription()
	{
		return (!empty($this->description)) ? $this->description : false;
	}

    /*! 
     *  \brief Build a Shop object from a SimpleXMLElement node. It may return false if the node doesn t contain a shop.
     * \param SimpleXmlElement node
     *  \return Shop / false
     */
	public final static function getShopFromSXMLElement($node)
	{
		if(!Shop::isSXMLNodeProperty($node))
		{
			error("Shop creation failed for element: ".$node->getName());
			return false;
		}			
		
		$name = $node->xpath("name");		//PH corrige name>shop>name
		if(count($name) > 0)
			$name = (string) $name[0];
		else
			$name = "";

		$url = $node->xpath("url");
		if(count($url) > 0)
			$url = (string) $url[0];
		else
			$url = "";

		$description = $node->xpath("description");
		if(count($description) > 0)
			$description = (string) $description[0];
		else
			$description = "";

	
		$shop = new Shop($name,$description,$url);
		return $shop;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a Shop from.
     * \param SimpleXmlElement node
     *  \return Boolean
     */
	public final static function isSXMLNodeProperty($node)
	{
		if(!hasChildren($node))
			return false;
		else
		{
			foreach($node->children() as $child)
			{
				$name = $child->getName();

				if( ! in_array($name,Shop::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>
