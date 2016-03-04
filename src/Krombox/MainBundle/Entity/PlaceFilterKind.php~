<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterKind
 */
class PlaceFilterKind
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeFilterValues;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->placeFilterValues = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PlaceFilterKind
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
     * @return PlaceFilterKind
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
     * Add placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     * @return PlaceFilterKind
     */
    public function addPlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues[] = $placeFilterValues;

        return $this;
    }

    /**
     * Remove placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     */
    public function removePlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues->removeElement($placeFilterValues);
    }

    /**
     * Get placeFilterValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceFilterValues()
    {
        return $this->placeFilterValues;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;


    /**
     * Add categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     * @return PlaceFilterKind
     */
    public function addCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     */
    public function removeCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
