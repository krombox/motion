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
    private $services;
    //private $relaxations = [];
    //private $kitchens = [];
    private $menu = [];
    
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

    /**
     * @return array
     */
        

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @param array $services
     */
    public function setServices($services)
    {
        $this->services = $services;
    }
    
    /**
     * @return array
     */
    public function getRelaxations()
    {
        return $this->relaxations;
    }

    /**
     * @param array $relaxations
     */
    public function setRelaxations($relaxations)
    {
        $this->relaxations = $relaxations;
    }
    
    public function getKitchens()
    {
        return $this->kitchens;
    }
    
    /**
     * @param array $kitchens
     */
    public function setKitchens($kitchens)
    {
        $this->kitchens = $kitchens;
    }
    
    public function getMenu()
    {
        return $this->menu;
    }
    
    /**
     * @param array $menu
     */
    public function setMenu($menu)
    {
        $this->menu = $menu;
    }

}
