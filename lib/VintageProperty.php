<?php
/*!
 * \file VintageProperty.php
 * \class VintageProperty
 * \brief The VintageProperty class is a data structure that contains Vintage information. Those objects are usually embedded inside Wine object.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
 
class VintageProperty
{
	private $vincod;
	private $vintageyear;
	private $qrcode; 
	private $picture;
	private $vinogusto;

	private static $xmlNodesName = array(
		"vintageyear",
		"qrcode",
		"dateupdate",
		"picture",
		"vinogusto"
	);
    /*! 
     *  \brief VintageProperty constructor
     *  \param String vincod
     *  \param String vintageyear
     *  \param String qrcode
     *  \param String picture
     *  \param String vinogusto
     *  \return VintageProperty
     */
	public function __construct($vincod,$vintageyear,$qrcode,$picture,$vinogusto)
	{
		$this->vincod = $vincod;
		$this->vintageyear = $vintageyear;
		$this->qrcode = $qrcode;
		$this->picture = $picture;
		$this->vinogusto = $vinogusto; 
	}

	/*! 
     *  \brief Returns the vintage property's vincod as String or false if empty
     *  \return String / false
     */
	public final function getVincod()
	{
		return (!empty($this->vincod)) ? $this->vincod : false;
	}

	/*! 
     *  \brief Returns the vintage property's vintageyear as String or false if empty
     *  \return String / false
     */
	public final function getVintageYear()
	{
     	return (!empty($this->vintageyear)) ? $this->vintageyear : false;
	}

	/*! 
     *  \brief Returns the vintage property's qrcode as String or false if empty
     *  \return String / false
     */
	public final function getQRCode()
	{
     	return (!empty($this->qrcode)) ? $this->qrcode : false;
	}

	/*! 
     *  \brief Returns the vintage property's picture as String or false if empty
     *  \return String / false
     */
	public final function getPicture()
	{
     	return (!empty($this->picture)) ? $this->picture : false;
	}

	/*! 
     *  \brief Returns the vintage property's vinogusto as String or false if empty
     *  \return String / false
     */
	public final function getVinogusto()
	{
     	return (!empty($this->vinogusto)) ? $this->vinogusto : false;
	}

    /*! 
     *  \brief Build a VintageProperty object from a SimpleXMLElement node. It may return false if the node doesn t contain a vintage property.
     * \param SimpleXmlElement node
     *  \return VintageProperty / false
     */
	public final static function getVintagePropertyFromSXMLElement($vincod,$node)
	{
		if(!VintageProperty::isSXMLNodeProperty($node))
		{
			error("Vintage Property creation failed for element: ".$node->getName());
			return false;
		}			

		$vintageyear = $node->xpath("vintageyear");
		if(count($vintageyear) > 0)
			$vintageyear = (string) $vintageyear[0];
		else
			$vintageyear = "";

		$qrcode = $node->xpath("qrcode");
		if(count($qrcode) > 0)
			$qrcode = (string) $qrcode[0];
		else
			$qrcode = "";

		$picture = $node->xpath("picture");
		if(count($picture) > 0)
			$picture = (string) $picture[0];
		else
			$picture = "";

		$vinogusto = $node->xpath("vinogusto");
		if(count($vinogusto) > 0)
			$vinogusto = (string) $vinogusto[0];
		else
			$vinogusto = "";

		$property = new VintageProperty($vincod,$vintageyear,$qrcode,$picture,$vinogusto);
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

				if( ! in_array($name,VintageProperty::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}
?>
