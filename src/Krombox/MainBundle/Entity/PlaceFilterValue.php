<?php

namespace Krombox\MainBundle\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterValue
 */
class PlaceFilterValue
{
    use ORMBehaviors\Translatable\Translatable;
    
    public function __call($method, $args)
    {
        if (!method_exists(self::getTranslationEntityClass(), $method)) {
            $method = 'get' . ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $args);
    }
    
    /**
     * @var integer
     */
    private $id;   

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \Krombox\MainBundle\Entity\PlaceFilterKind
     */
    private $placeFilterKind;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }   

    /**
     * Set slug
     *
     * @param string $slug
     * @return PlaceFilterValue
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set placeFilterKind
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKind
     * @return PlaceFilterValue
     */
    public function setPlaceFilterKind(\Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKind = null)
    {
        $this->placeFilterKind = $placeFilterKind;

        return $this;
    }

    /**
     * Get placeFilterKind
     *
     * @return \Krombox\MainBundle\Entity\PlaceFilterKind 
     */
    public function getPlaceFilterKind()
    {
        return $this->placeFilterKind;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $places;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->places = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     * @return PlaceFilterValue
     */
    public function addPlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places[] = $places;

        return $this;
    }

    /**
     * Remove places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     */
    public function removePlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places->removeElement($places);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaces()
    {
        return $this->places;
    }
}
