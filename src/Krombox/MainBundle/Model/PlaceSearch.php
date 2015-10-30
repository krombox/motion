<?php

namespace Krombox\MainBundle\Model;

use Symfony\Component\HttpFoundation\Request;

class PlaceSearch
{    
    protected $name;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;
    
    /**
     * @var boolean
     */
    private $is24h;
    
    private $isWorkingNow;
    
    private $isWifi;       
    
    private $isDelivery;
    
    private $birthdayDiscount;


    

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Set is24h
     *
     * @param boolean $is24h
     * @return Place
     */
    public function setIs24h($is24h)
    {
        $this->is24h = $is24h;

        return $this;
    }

    /**
     * Get is24h
     *
     * @return boolean 
     */
    public function getIs24h()
    {
        return $this->is24h;
    }
    
    public function setIsWorkingNow($isWorkingNow)
    {
        $this->isWorkingNow = $isWorkingNow;

        return $this;
    }

    /**
     * Get is24h
     *
     * @return boolean 
     */
    public function getIsWorkingNow()
    {
        return $this->isWorkingNow;
    }
    
    /**
     * Set isWifi
     *
     * @param boolean $isWifi
     * @return Place
     */
    public function setIsWifi($isWifi)
    {
        $this->isWifi = $isWifi;

        return $this;
    }

    /**
     * Get isWifi
     *
     * @return boolean 
     */
    public function getIsWifi()
    {
        return $this->isWifi;
    }
    
    /**
     * Set isDelivery
     *
     * @param boolean $isDelivery
     * @return Place
     */
    public function setIsDelivery($isDelivery)
    {
        $this->isDelivery = $isDelivery;

        return $this;
    }

    /**
     * Get isDelivery
     *
     * @return boolean 
     */
    public function getIsDelivery()
    {
        return $this->isDelivery;
    }
    
    /**
     * Set birthdayDiscount
     *
     * @param integer $birthdayDiscount
     * @return Place
     */
    public function setBirthdayDiscount($birthdayDiscount)
    {
        $this->birthdayDiscount = $birthdayDiscount;

        return $this;
    }

    /**
     * Get birthdayDiscount
     *
     * @return integer 
     */
    public function getBirthdayDiscount()
    {
        return $this->birthdayDiscount;
    }
}
