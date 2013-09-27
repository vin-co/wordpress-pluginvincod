<?php
/*!
 * \file Recipe.php
 * \class Recipe
 * \brief The Recipe class is a kind of DataStructure with a video attribute. Those objects are usually embedded inside Wine object. 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
require_once("DataStructure.php");

class Recipe extends DataStructure
{
	private $video;

	private static $xmlNodesName = array(
		"name",
		"url",
		"video",
		"type"
	);
	
	/*! 
     *  \brief Recipe constructor
     *  \param String name
     *  \param String type
     *  \param String url
     *  \param String video
     *  \return Recipe
     */
	public function __construct($name,$type,$url,$video="")
	{
		parent::__construct($name,$type,$url);
		$this->video = $video;
	}

	/*! 
     *  \brief Returns whether or not the Recipe has a video 
     *  \return Boolean
     */
	public final function hasVideo()
	{
		return (!empty($this->video));
	}

	/*! 
     *  \brief Returns the recipe's video as String (HTML content) or false if empty
     *  \return String / false
     */
	public final function getVideo()
	{
		return (!empty($this->video)) ? $this->video : false;
	}

	/*! 
     *  \brief Build a Recipe object from a SimpleXMLElement node. It may return false if the node doesn t contain a recipe.
     * \param SimpleXmlElement node
     *  \return Recipe / false
     */
	public final static function getRecipeFromSXMLElement($node)
	{
		if(!Recipe::isSXMLNodeProperty($node))
		{
			error("Recipe Property creation failed for element: ".$node->getName());
			return false;
		}			
		
		$name = $node->xpath("name");
		
		if(count($name) > 0)
			$name = (string) $name[0];
		else
			$name = "";

		$url = $node->xpath("url");
		if(count($url) > 0)
			$url = (string) $url[0];
		else
			$url = "";

		$video = $node->xpath("video");
		if(count($video) > 0)
			$video = (string) $video[0];
		else
			$video = "";

		$type = $node->xpath("type");
		if(count($type) > 0)
			$type = (string) $type[0];
		else
			$type = "";

		$property = new Recipe($name,$type,$url,$video);
		return $property;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a Recipe from.
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

				if( ! in_array($name,Recipe::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>
