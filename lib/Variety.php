<?php
/*!
 * \file Variety.php
 * \class Variety
 * \brief The Variety class is a data structure that contains information about variety of grape (or "cepage" in french). Those objects are usually embedded inside Wine object.  
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 

class Variety
{
	private $name;
	private $amount;	

	private static $xmlNodesName = array(
		"name",
		"amount"
	);
    
    /*! 
     *  \brief Variety constructor
     *  \param String name
     *  \param String amount    
     *  \return Variety
     */
	public function __construct($name,$amount)
	{
		$this->name = $name;
		$this->amount = $amount;		
	}

    /*! 
     *  \brief Returns the variety's name or false if empty
     *  \return String / false
     */
	public function getName()
	{
		return (!empty($this->name)) ? $this->name : false;
	}

    /*! 
     *  \brief Returns the variety's amount or false if empty
     *  \return String / false
     */
	public function getAmount()
	{
		return (!empty($this->amount)) ? $this->amount : false;
	}
    
    /*! 
     *  \brief Build a Variety object from a SimpleXMLElement node. It may return false if the node doesn t contain a variety.
     * \param SimpleXmlElement node
     *  \return Variety / false
     */
	public final static function getVarietyFromSXMLElement($node)
	{
		if(!Variety::isSXMLNodeProperty($node))
		{
			error("Shop creation failed for element: ".$node->getName());
			return false;
		}	
		
		$name = $node->xpath("name");		
		if(count($name) > 0)
			$name = (string) $name[0];
		else
			$name = "";

		$amount = $node->xpath("amount");		
		if(count($amount) > 0)
			$amount = (string) $amount[0];
		else
			$amount = "";

			
		$variety = new Variety($name,$amount);
		return $variety;			
	}

    /*! 
     *  \brief Check whether a node is acceptable to get a Variety from.
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

				if( ! in_array($name,Variety::$xmlNodesName) )
					return false;				
			}

			return true;
		}
	}
}

?>
