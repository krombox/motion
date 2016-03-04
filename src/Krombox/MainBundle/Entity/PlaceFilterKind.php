<?php

namespace Krombox\MainBundle\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterKind
 */
class PlaceFilterKind
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeFilterValues;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->placeFilterValues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
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
