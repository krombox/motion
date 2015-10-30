<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 */
class Category
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
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $description;

    /**
     * @var CategoryType
     */
    private $type;

    /**
     * @var string
     */
    private $file_name;

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
     * @return Category
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
     * Set slug
     *
     * @param string $slug
     * @return Category
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
     * Set description
     *
     * @param string $description
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param CategoryType $type
     * @return Category
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return CategoryType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set file_name
     *
     * @param string $fileName
     * @return Category
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Add places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     * @return Category
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
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeFilterKinds;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $events;


    /**
     * Add placeFilterKinds
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKinds
     * @return Category
     */
    public function addPlaceFilterKind(\Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKinds)
    {
        $this->placeFilterKinds[] = $placeFilterKinds;

        return $this;
    }

    /**
     * Remove placeFilterKinds
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKinds
     */
    public function removePlaceFilterKind(\Krombox\MainBundle\Entity\PlaceFilterKind $placeFilterKinds)
    {
        $this->placeFilterKinds->removeElement($placeFilterKinds);
    }

    /**
     * Get placeFilterKinds
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceFilterKinds()
    {
        return $this->placeFilterKinds;
    }

    /**
     * Add events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     * @return Category
     */
    public function addEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
    
    public function __toString() {
        return $this->getName();
    }
}
