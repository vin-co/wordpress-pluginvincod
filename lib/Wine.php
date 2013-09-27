<?php
/*!
 * \file Wine.php
 * \class Wine
 * \brief A Wine is an AbstractDataObject. It contains all information about a single wine. Mostly made up of properties and DataStructure objects (Media, Shop, Recipe) and others such as Review, Variety, VintageProperty and WEObject. It s also possible to get other AbstractDataObject (Owner, Winery, Range) concerning the Wine.
 * \author Thomas
 * \version 1.0
 * \date 08/12/2011
 */ 
require_once("DataObjectFactory.php"); 
require_once("AbstractDataObject.php");
require_once("Recipe.php");
require_once("Media.php");
require_once("Shop.php");
require_once("WEObject.php");
require_once("Review.php");
require_once("Property.php");
require_once("Variety.php");
require_once("VintageProperty.php");
require_once("Utils.php");

class Wine extends AbstractDataObject
{
	private $objects;

	private static $properties = array(
		"id",
		"dateupdate",
		"vincod",
		"vincodperso",
		"abstract",
		"name",
		"vintageyear",
		"picture",
		"tag",
		"more",
		"winetype",
		"appellation",
		"advice",
		"presentation",
		"specifications"
	);

    /*! 
     *  \brief Wine constructor
     *  \param String id is what will be used to identify the object and query other objects.
     *  \param TYPE type relates to the pseudo-enum TYPE from AbstractDataObject and refer to the object type.
     *  \param SimpleXMLElement xmlObject represents the object on which queries will be done.
     *  \param String language is the language in which the first query has been done.
     *  \return Wine
     */
	public function __construct($id,$type,$xmlObject,$language)
	{
		parent::__construct($id,$type,$xmlObject,$language);
		$this->objects = array();
	}

	//Abstract Methods
	protected function isCustomProperties($propertyName)
	{
		return !(in_array($propertyName,Wine::$properties));
	}

	//Private Methods
	private function getTheValue ($value)
	{
		if ($val = $this->getTheValues ($value))
			return $val[0];
		return false;
	}
	
	private function getTheValues ($value)		
	{
		if(!array_key_exists($value,$this->objects))
		{
			$this->objects[$value] = parent::getProperty($value);
		}

		if(count($this->objects[$value]) > 0)
		{
			return $this->objects[$value];
		}
		else
			return array();
	}

	//Class Methods
	/*!
	* \brief Return the last update date as Property object.
	* \return Property / false
	*/
	public final function getDateUpdate(){return $this->getTheValue("dateupdate");}
	
	/*!
	* \brief Return the vincod as Property object.
	* \return Property / false
	*/
	public final function getVincod(){return $this->getTheValue("vincod");}
	
	/*!
	* \brief Return the vincodperso as Property object.
	* \return Property / false
	*/
	public final function getVincodPerso(){return $this->getTheValue("vincodperso");}
	
	/*!
	* \brief Return either vincodperso if not empty or vincod
	* \return Property / false
	*/
	public final function getAbsoluteVincod()
	{
		$value =  $this->getVincodPerso();
		if($value) 
		{
			$val = $value->getValue();
			if(empty($val))
				return $this->getVincod();
			else
				return $value;
		}
		else
			return false;
	}

    /*!
	* \brief Return the abstract (short wine description) as Property object.
	* \return Property / false
	*/
	public final function getAbstract(){return $this->getTheValue("abstract");}

    /*!
	* \brief Return the name as Property object.
	* \return Property / false
	*/
	public final function getName(){return $this->getTheValue("name");}
	
    /*!
	* \brief Return the vintage year as Property object.
	* \return Property / false
	*/
	public final function getVintageYear(){return $this->getTheValue("vintageyear");}

    /*!
	* \brief Return the picture as Property object.
	* \return Property / false
	*/
	public final function getPicture(){return $this->getTheValue("picture");}
	
	/*!
	* \brief Return the tag as Property object.
	* \return Property / false
	*/
	public final function getTag(){return $this->getTheValue("tag");}

    /*!
	* \brief Return the "more" as Property object.
	* \return Property / false
	*/
	public final function getMore(){return $this->getTheValue("more");}

    /*!
	* \brief Return the wine type as Property object.
	* \return Property / false
	*/
	public final function getWineType(){return $this->getTheValue("winetype");}

    /*!
	* \brief Return the appellation as Property object.
	* \return Property / false
	*/
	public final function getAppellation(){return $this->getTheValue("appellation");}

    /*!
	* \brief Return the advice as Property Array.
	* \return Property[]
	*/
	public final function getAdvice(){return $this->getTheValues("advice");}

    /*!
	* \brief Return the presentation as Property Array.
	* \return Property[]
	*/
	public final function getPresentation(){return $this->getTheValues("presentation");}

    /*!
	* \brief Return the specifications as Property Array.
	* \return Property[]
	*/
	public final function getSpecifications(){return $this->getTheValues("specifications");}

    /*!
	* \brief Return whether or not the wine is a Vintage.
	* \return Boolean
	*/
	public final function isVintage(){return count($this->getVintageProperty) > 0;}

    /*!
	* \brief Return the Vintage property or false if not a Vintage
	* \return VintageProperty / false
	*/
	public final function getVintageProperty()
	{
		if(!array_key_exists("vintageProperty",$this->objects))
		{
			$vintageArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"vintage",true);
			$vintagePropertyArray = array();
			foreach($vintageArrayElements as $vintageElement)
			{
				$vintageProperty = VintageProperty::getVintagePropertyFromSXMLElement($this->getAbsoluteVincod()->getValue(),$vintageElement);
				if($vintageProperty)
				{
					array_push
					(
						$vintagePropertyArray,
						$vintageProperty
					);
				}
			}

			$this->objects["vintageProperty"] = $vintagePropertyArray;
		}
		
		if(count($this->objects["vintageProperty"]) > 0)
		{
			$value = $this->objects["vintageProperty"];
			return $value[0];
		}
		else
			return false;
	}

    /*!
	* \brief Return the Reviews
	* \return Review[]
	*/
	public final function getReviews()
	{
		if(!array_key_exists("reviews",$this->objects))
		{
			$reviewArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"review",true);
			
			$reviewPropertyArray = array();
			foreach($reviewArrayElements as $reviewElement)
			{
				$review = Review::getReviewFromSXMLElement($reviewElement);
				if($review)
				{
					array_push
					(
						$reviewPropertyArray,
						$review
					);
				}				
			}

			$this->objects["reviews"] = $reviewPropertyArray;
		}

		
		if(count($this->objects["reviews"] ) > 0)
		{
			return $this->objects["reviews"];
		}
		else
			return array();
	}

    /*!
	* \brief Return the Varieties
	* \return Variety[]
	*/
	public final function getGrapesVarieties()
	{
		if(!array_key_exists("grapesvarieties",$this->objects))
		{
			$varietiesArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"variety",true);
			
			$varietiesPropertyArray = array();
			foreach($varietiesArrayElements as $varietyElement)
			{
				$variety = Variety::getVarietyFromSXMLElement($varietyElement);
				if($variety)
				{
					array_push
					(
						$varietiesPropertyArray,
						$variety
					);
				}				
			}

			$this->objects["grapesvarieties"] = $varietiesPropertyArray;
		}

		
		if(count($this->objects["grapesvarieties"] ) > 0)
		{
			return $this->objects["grapesvarieties"];
		}
		else
			return array();
	}

    /*!
	* \brief Return the Medias
	* \return Media[]
	*/
	public final function getMedias()
	{
		if(!array_key_exists("medias",$this->objects))
		{
			$mediaArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"media",true);
			
			$mediaPropertyArray = array();
			foreach($mediaArrayElements as $mediaElement)
			{
				$media = Media::getMediaFromSXMLElement($mediaElement);
				if($media)
				{
					array_push
					(
						$mediaPropertyArray,
						$media
					);
				}				
			}

			$this->objects["medias"] = $mediaPropertyArray;
		}

		
		if(count($this->objects["medias"] ) > 0)
		{
			return $this->objects["medias"];
		}
		else
			return array();
	}

    /*!
	* \brief Return the Recipes
	* \return Recipe[]
	*/
	public final function getRecipes()
	{
		if(!array_key_exists("recipes",$this->objects))
		{
			$recipeArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"recipe",true);
			
			$recipePropertyArray = array();
			foreach($recipeArrayElements as $recipeElement)
			{
				$recipe = Recipe::getRecipeFromSXMLElement($recipeElement);
				if($recipe)
				{
					array_push
					(
						$recipePropertyArray,
						$recipe
					);
				}				
			}

			$this->objects["recipes"] = $recipePropertyArray;
		}

		
		if(count($this->objects["recipes"] ) > 0)
		{
			return $this->objects["recipes"];
		}
		else
			return array();
	}

    /*!
	* \brief Return the Shops
	* \return Shop[]	
	*/
	public final function getShops()
	{
		if(!array_key_exists("shops",$this->objects))
		{
			$shopArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"shop",true);
			
			$shopPropertyArray = array();
			foreach($shopArrayElements as $shopElement)
			{
				$shop = Shop::getShopFromSXMLElement($shopElement);
				if($shop)
				{
					array_push
					(
						$shopPropertyArray,
						$shop
					);
				}				
			}

			$this->objects["shops"] = $shopPropertyArray;
		}

		
		if(count($this->objects["shops"] ) > 0)
		{
			return $this->objects["shops"];
		}
		else
			return array();
	}

    /*!
	* \brief Return the WEs
	* \return WE[]
	*/
	public final function getWES()
	{
		if(!array_key_exists("weobjects",$this->objects))
		{
			$weArrayElements = getSXMLElementWithNameSpace($this->xmlObject,"we",true);
			
			$wePropertyArray = array();
			foreach($weArrayElements as $weElement)
			{
				$we = WEObject::getWEObjectFromSXMLElement($weElement);
				if($we)
				{
					array_push
					(
						$wePropertyArray,
						$we
					);
				}				
			}

			$this->objects["weobjects"] = $wePropertyArray;
		}

		
		if(count($this->objects["weobjects"] ) > 0)
		{
			return $this->objects["weobjects"];
		}
		else
			return array();
	}
	
	/*!
	* \brief Return the wine's owner as Owner object or false if Owner cannot be instanciated.
	* \return Owner / false
	*/
	public final function getOwner()
	{
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getOwnerByVincod($vincod->getValue(),$this->getLanguage());
           
           return false;
	}
	
	/*!
	* \brief Return the wine's winery as Winery object or false if Winery cannot be instanciated.
	* \return Winery / false
	*/
	public final function getWinery()
	{
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getWineryByVincod($vincod->getValue(),$this->getLanguage());
           
           return false;
	}
	
    /*!
	* \brief Return the wine's range as Range object or false if Range cannot be instanciated.
	* \return Range / false
	*/
	public final function getRange()
	{
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getRangeByVincod($vincod->getValue(),$this->getLanguage());
           
           return false;
	}
	
	/*!
	* \brief Return other vintages as Wine array
	* \return Wine[]
	*/
	public final function getOtherVintages()
	{
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getOtherVintagesByVincod($vincod->getValue(),$this->getLanguage());
           	
          return array();
	}

	/*!
	* \brief Return other wine in other language or false if queried language not valid
	* \return Wine / false
	*/	
	public final function getOtherLanguage($lang)
	{
     	
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getOtherLanguagesByVincod($vincod->getValue(),$lang);
           	
          return false;
     }

	/*!
	* \brief Return all available languages for the wine
	* \return String[]
	*/		
	public final function getAvailableLanguages()
	{
     	$vincod = $this->getAbsoluteVincod();
     	if($vincod)
           	return DataObjectFactory::getAvailableLanguagesByVincod($vincod->getValue());
           	
          return array();	
     }
	
}
?>
