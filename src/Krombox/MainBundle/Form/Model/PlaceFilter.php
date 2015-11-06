<?php

namespace Krombox\MainBundle\Form\Model;

//use Kf\KitBundle\Doctrine\ORM\Query\FilterInterface;
//use Sittr\Common\Model\Traits\GeoEntity;
//use Sittr\Common\Model\Traits\WithLimitAndOffset;
//use Sittr\UserBundle\Entity\User;

class PlaceFilter
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
    private $filters;
    private $businessHours;
    private $categories = [];

    public function __construct(array $categories) {
        $this->categories = $categories;
    }

    public function getProperties()
    {
        return get_object_vars($this);        
    }


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

    public function getBusinessHours()
    {
        return $this->businessHours;
    }

    /**
     * @param array $businessHours
     */
    public function setBusinessHours($businessHours)
    {
        $this->businessHours = $businessHours;
    }
    
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
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $category
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }        
}
