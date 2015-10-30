<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterValue
 */
class PlaceFilterValue
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
     * Set name
     *
     * @param string $name
     * @return PlaceFilterValue
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
}
