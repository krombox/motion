<?php

namespace Krombox\MainBundle\Form\Model;

//use Kf\KitBundle\Doctrine\ORM\Query\FilterInterface;
//use Sittr\Common\Model\Traits\GeoEntity;
//use Sittr\Common\Model\Traits\WithLimitAndOffset;
//use Sittr\UserBundle\Entity\User;

class EventFilter
{
//    use GeoEntity,
//        WithLimitAndOffset;
//    private $s;
//    /** @var  \DateTime */
//    private $availableAt;
//    private $skills = [];
    //private $services;
    //private $relaxations = [];
    //private $kitchens = [];
   //private $menu = [];
    private $aggregations = ['filters'];
    private $filters;
    //private $businessHours;
    //private $categories = [];
    //private $category;
    private $city;

//    public function __construct(array $categories) {
//    //public function __construct() {
//        //$this->city = $city;
//        $this->categories = $categories;
//    }

//    public function getProperties()
//    {
//        return get_object_vars($this);        
//    }


//    private $ageGroups = [];
//    private $maxKids;
//    private $favorite;
//    /** @var  User */
//    private $user;
//    private $location;

//    public function __construct(User $user = null, $mode = 'user')
//    {
//        $this->user = $user;
//        if ($mode == 'user') {
//            if ($user && $addr = $user->getAddress()) {
//                $this->setLat($addr->getLat());
//                $this->setLng($addr->getLng());
//                $this->setLocation($addr->getFormatted());
//            }
//        }
//    }

//    public function getBusinessHours()
//    {
//        return $this->businessHours;
//    }
//
//    /**
//     * @param array $businessHours
//     */
//    public function setBusinessHours($businessHours)
//    {
//        $this->businessHours = $businessHours;
//    }
    
    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return array
     */
//    public function getCategories()
//    {
//        return $this->categories;
//    }
//
//    /**
//     * @param array $category
//     */
//    public function setCategories($categories)
//    {
//        $this->categories = $categories;
//    }
//    
//    /**
//     * @return array
//     */
//    public function getCategory()
//    {
//        return $this->category;
//    }
//
//    /**
//     * @param array $category
//     */
//    public function setCategory($category)
//    {
//        $this->category = $category;
//    }
    
    /**
     * @return array
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param array $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    
    /**
     * @return array
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * @param array $array
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }
}
