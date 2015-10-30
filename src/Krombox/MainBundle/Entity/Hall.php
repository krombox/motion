<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hall
 */
class Hall
{
    /**
     * @var integer
     */
    private $id;

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
        $this->placeHallImages = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Hall
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
     * @param \Krombox\MainBundle\Entity\PlaceHall $places
     * @return Hall
     */
    public function addPlace(\Krombox\MainBundle\Entity\PlaceHall $places)
    {
        $this->places[] = $places;

        return $this;
    }

    /**
     * Remove places
     *
     * @param \Krombox\MainBundle\Entity\PlaceHall $places
     */
    public function removePlace(\Krombox\MainBundle\Entity\PlaceHall $places)
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
    /**
     * @var integer
     */
    private $numberOfSeats;

    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;


    /**
     * Set numberOfSeats
     *
     * @param integer $numberOfSeats
     * @return Hall
     */
    public function setNumberOfSeats($numberOfSeats)
    {
        $this->numberOfSeats = $numberOfSeats;

        return $this;
    }

    /**
     * Get numberOfSeats
     *
     * @return integer 
     */
    public function getNumberOfSeats()
    {
        return $this->numberOfSeats;
    }

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return Hall
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeHallImages;


    /**
     * Add placeHallImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceHallImage $placeHallImages
     * @return Hall
     */
    public function addPlaceHallImage(\Krombox\MainBundle\Entity\PlaceHallImage $placeHallImages)
    {
        $this->placeHallImages[] = $placeHallImages;
        $placeHallImages->setHall($this);
        
        return $this;
    }

    /**
     * Remove placeHallImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceHallImage $placeHallImages
     */
    public function removePlaceHallImage(\Krombox\MainBundle\Entity\PlaceHallImage $placeHallImages)
    {
        $this->placeHallImages->removeElement($placeHallImages);
    }

    /**
     * Get placeHallImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceHallImages()
    {
        return $this->placeHallImages;
    }
}
