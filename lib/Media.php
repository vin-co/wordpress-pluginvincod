<?php
/*!
 * \file Media.php
 * \class Media
 * \brief The Media class is a kind of DataStructure. Those objects are usually embedded inside Wine object. 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
require_once("DataStructure.php");

class Media extends DataStructure
{

    private static $xmlNodesName = array(
		"name",
		"url",
		"type",
		"zone",
		"preview"
	);
	
    /*! 
     *  \brief Media constructor
     *  \param String name
     *  \param String type
     *  \param String url
	 *	\param String zone
	 *  \param String preview
     *  \return Media
     */
	public function __construct($name,$type,$url,$zone,$preview)
	{
		parent::__construct($name,$type,$url,$zone,$preview);
	}
	
	/*! 
     *  \brief Build a Media object from a SimpleXMLElement node. It may return false if the node doesn t contain a media.
     * \param SimpleXmlElement node
     *  \return Media / false
     */
	public final static function getMediaFromSXMLElement($node)
	{
		if(!Media::isSXMLNodeProperty($node))
		{
			error("Media creation failed for element: ".$node->getName());
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

		$type = $node->xpath("type");
		if(count($type) > 0)
			$type = (string) $type[0];
		else
			$type = "";
		
		$zone = $node->xpath("zone");
		if(count($zone) > 0)
			$zone = (string) $zone[0];
		else
			$zone = "";
			
		$preview = $node->xpath("preview");
		if(count($preview) > 0)
			$preview = (string) $preview[0];
		else
			$preview = "";
	
		$media = new Media($name, $type, $url, $zone, $preview);
		return $media;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a Media from.
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

				if( ! in_array($name,Media::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>