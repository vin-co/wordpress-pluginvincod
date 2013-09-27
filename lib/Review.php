<?php
/*!
 * \file Review.php
 * \class Review
 * \brief The Review is a data structure that embed information about a review (author, source content...). Those objects are usually embedded inside Wine object. 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
class Review
{
	private $author;
	private $source;
	private $content;
	private $mark;
	private $date;
	private $url;

	private static $xmlNodesName = array(
		"author",
		"source",
		"content",
		"mark",
		"date",
		"url"
	);
	
	/*! 
     *  \brief Review constructor
     *  \param String author
     *  \param String source
     *  \param String content
     *  \param String mark
     *  \param String date
     *  \param String url
     *  \return Review
     */
	public function __construct($author,$source,$content,$mark,$date,$url)
	{
		$this->author = $author;
		$this->source = $source;
		$this->content = $content;
		$this->mark = $mark;
		$this->date = $date;
		$this->url = $url;
	}

	/*! 
     *  \brief returns the review's author.
     *  \return String / false
     */
	public final function getAuthor()
	{
		return (!empty($this->author)) ? $this->author : false;
	}

	/*! 
     *  \brief returns the review's source.
     *  \return String / false
     */
	public final function getSource()
	{
		return (!empty($this->source)) ? $this->source : false;
	}

	/*! 
     *  \brief returns the review's content.
     *  \return String / false
     */
	public final function getContent()
	{
		return (!empty($this->content)) ? $this->content : false;
	}

	/*! 
     *  \brief returns the review's mark.
     *  \return String / false
     */
	public final function getMark()
	{
		return (!empty($this->mark)) ? $this->mark : false;
	}

	/*! 
     *  \brief returns the review's publication date.
     *  \return String / false
     */
	public final function getDate()
	{
		return (!empty($this->date)) ? $this->date : false;
	}

	/*! 
     *  \brief returns the review's url.
     *  \return String / false
     */
	public final function getUrl()
	{
		return (!empty($this->url)) ? $this->url : false;
	}
    
    /*! 
     *  \brief Build a Review object from a SimpleXMLElement node. It may return false if the node doesn t contain a review.
     * \param SimpleXmlElement node
     *  \return Review / false
     */
	public final static function getReviewFromSXMLElement($node)
	{
		if(!Review::isSXMLNodeProperty($node))
		{
			error("Review creation failed for element: ".$node->getName());
			return false;
		}		

		$author = $node->xpath("author");		
		if(count($author) > 0)
			$author = (string) $author[0];
		else
			$author = "";

		$source = $node->xpath("source");		
		if(count($source) > 0)
			$source = (string) $source[0];
		else
			$source = "";

		$content = $node->xpath("content");		
		if(count($content) > 0)
			$content = (string) $content[0];
		else
			$content = "";

		$mark = $node->xpath("mark");		
		if(count($mark) > 0)
			$mark = (string) $mark[0];
		else
			$mark = "";

		$date = $node->xpath("date");		
		if(count($date) > 0)
			$date = (string) $date[0];
		else
			$date = "";

		$url = $node->xpath("url");		
		if(count($url) > 0)
			$url = (string) $url[0];
		else
			$url = "";
	
		$review = new Review($author,$source,$content,$mark,$date,$url);
		return $review;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a Review from.
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

				if( ! in_array($name,Review::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>
