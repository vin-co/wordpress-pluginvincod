<?php
/*!
 * \file WEObject.php
 * \class WEObject
 * \brief The WEObject class is a data structure that contains WineEverybody's information. Those objects are usually embedded inside Wine object.  
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
class WEObject
{
	private $title;
	private $url;
	private $text;
	private $category;
	private $date;
	
	private static $xmlNodesName = array(
		"title",
		"url",
		"text",
		"category",
		"date"
	);

    /*! 
     *  \brief WEObject constructor
     *  \param String title
     *  \param String url
     *  \param String text
     *  \param String category
     *  \param String date
     *  \return WEObject
     */
	public function __construct($title,$url,$text,$category,$date)
	{
		$this->title = $title;
		$this->url = $url;
		$this->text = $text;
		$this->category = $category;
		$this->date = $date;
	}

	/*! 
     *  \brief Returns the WEObject's title or false if empty
     *  \return String / false
     */
     public final function getTitle()
	{
		return (!empty($this->title)) ? $this->title : false;
	}

	/*! 
     *  \brief Returns the WEObject's url or false if empty
     *  \return String / false
     */
	public final function getURL()
	{
		return (!empty($this->url)) ? $this->url : false;
	}

	/*! 
     *  \brief Returns the WEObject's text or false if empty
     *  \return String / false
     */
	public final function getText()
	{
		return (!empty($this->text)) ? $this->text : false;
	}

	/*! 
     *  \brief Returns the WEObject's category or false if empty
     *  \return String / false
     */
	public final function getCategory()
	{
		return (!empty($this->category)) ? $this->category : false;
	}

	/*! 
     *  \brief Returns the WEObject's date or false if empty
     *  \return String / false
     */
	public final function getDate()
	{
		return (!empty($this->date)) ? $this->date : false;
	}
	
	 /*! 
     *  \brief Build a WEObject object from a SimpleXMLElement node. It may return false if the node doesn t contain a WEObject.
     * \param SimpleXmlElement node
     *  \return WEObject / false
     */
	public final static function getWEObjectFromSXMLElement($node)
	{
		if(!WEObject::isSXMLNodeProperty($node))
		{
			error("WEObject Property creation failed for element: ".$node->getName());
			return false;
		}			

		$title = $node->xpath("title");
		if(count($title) > 0)
			$title = (string) $title[0];
		else
			$title = "";

		$url = $node->xpath("url");
		if(count($url) > 0)
			$url = (string) $url[0];
		else
			$url = "";
			
		$text = $node->xpath("text");
		if(count($text) > 0)
			$text = (string) $text[0];
		else
			$text = "";

		$category = $node->xpath("category");
		if(count($category) > 0)
			$category = (string) $category[0];
		else
			$category = "";
			
		$date = $node->xpath("date");
		if(count($date) > 0)
			$date = (string) $date[0];
		else
			$date = "";
		
		$property = new WEObject($title,$url,$text,$category,$date);
		return $property;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a VintageProperty from.
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

				if( ! in_array($name,WEObject::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>
