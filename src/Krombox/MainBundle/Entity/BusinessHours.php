<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Krombox\MainBundle\Entity\Traits\DayFlaggableEntity;
use Krombox\MainBundle\Entity\Traits\SchedulableEntity;
use Krombox\MainBundle\Entity\Traits\Entity;
use Krombox\MainBundle\Entity\Place;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;

/**
 * @ORM\Entity
 * @ORM\Table(name="business_hours")
 */

class BusinessHours
{
    use Entity,
        DayFlaggableEntity,
        SchedulableEntity;
    
    public function __construct() {        
        DayFlaggableHelper::fillFields($this, true);        
        $this->setStartsAt(new \DateTime());
    }

        /**
     * @ORM\ManyToOne(targetEntity="Place", inversedBy="businessHours")
     */
    private $place;           

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return BusinessHours
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
