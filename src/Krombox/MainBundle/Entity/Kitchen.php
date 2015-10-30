<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Kitchen
 */
class Kitchen
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var KitchenType
     */
    private $type;

    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;


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
     * Set type
     *
     * @param KitchenType $type
     * @return Kitchen
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return KitchenType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return Kitchen
     */
    public function setPlace(\Krombox\MainBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \Krombox\MainBundle\Entity\Place 
     */
    public function getPlace()
    {
        return $this->place;
    }
    /**
     * @var string
     */
    private $name;

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
     * Set name
     *
     * @param string $name
     * @return Kitchen
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     * @return Kitchen
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
