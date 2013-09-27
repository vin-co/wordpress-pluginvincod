<?php
/*!
 * \file DataStructure.php
 * \class DataStructure
 * \brief Data structure that contains a name, a type and a url. Those objects are usually embedded inside Wine object. 
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
class DataStructure
{
	private $name;
	private $type;
	private $url;
	private $zone;
	private $preview;
    
    /*! 
     *  \brief Can and must only be call from subclass.
     *  \param String name
     *  \param String type
     *  \param String url
     *  \return DataStructure
     */
	protected function __construct($name,$type,$url,$zone,$preview)
	{
		$this->name = $name;
		$this->type = $type;
		$this->url = $url;
		$this->zone = $zone;
		$this->preview = $preview;
	}

	/*! 
     *  \brief Returns the name as a String or false if empty.
     *  \return String / false
     */
	public final function getName()
	{
		return (!empty($this->name)) ? $this->name : false;
	}

	/*! 
     *  \brief Returns the type as a String or false if empty.
     *  \return String / false
     */
	public final function getType()
	{
		return (!empty($this->type)) ? $this->type : false;
	}

	/*! 
     *  \brief Returns the url as a String or false if empty.
     *  \return String / false
     */
	public final function getURL()
	{
		return (!empty($this->url)) ? $this->url : false;
	}
	
		/*! 
     *  \brief Returns the preview as a String or false if empty.
     *  \return String / false
     */
	public final function getPreview()
	{
		return (!empty($this->preview)) ? $this->preview : false;
	}
}

?>
