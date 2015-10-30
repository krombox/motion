<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Krombox\MainBundle\Entity\Traits\SchedulableEntity;
use Krombox\MainBundle\Entity\Traits\Entity;

/**
 * BusinessHoursException
 */
class BusinessHoursException
{
    use Entity,
        SchedulableEntity;    

    public function __construct()
    {
        $this->day = new \DateTime();
    }
    /**
     * @var \DateTime
     */
    private $day;        
    
    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;   

    /**
     * Set day
     *
     * @param \DateTime $day
     * @return BusinessHoursException
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return \DateTime 
     */
    public function getDay()
    {
        return $this->day;
    }   

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return BusinessHoursException
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
}
