<?php

namespace Krombox\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Krombox\UserBundle\Entity\User;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Krombox\MainBundle\Entity\Place;
//use Krombox\MainBundle\Entity\MembershipSubscription;
use Krombox\CommonBundle\Model\Traits\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Krombox\MainBundle\DBAL\Types\MembershipType;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_membership")
 */
class OrderMembership extends AbstractOrder
{        
     /**
     * @ORM\ManyToOne(targetEntity="Krombox\MainBundle\Entity\Membership")
     * @ORM\JoinColumn(name="membership_id", referencedColumnName="id", nullable=false)
     */
    protected $membership;
    
    /**
     * @ORM\Column(type="integer")     
     */
    protected $daysCount;   

    /**
     * Set daysCount
     *
     * @param integer $daysCount
     * @return OrderMembership
     */
    public function setDaysCount($daysCount)
    {
        $this->daysCount = $daysCount;

        return $this;
    }

    /**
     * Get daysCount
     *
     * @return integer 
     */
    public function getDaysCount()
    {
        return $this->daysCount;
    }   

    /**
     * Set membership
     *
     * @param \Krombox\MainBundle\Entity\Membership $membership
     * @return OrderMembership
     */
    public function setMembership(\Krombox\MainBundle\Entity\Membership $membership)
    {
        $this->membership = $membership;

        return $this;
    }

    /**
     * Get membership
     *
     * @return \Krombox\MainBundle\Entity\Membership 
     */
    public function getMembership()
    {
        return $this->membership;
    }
    
    public function setEndsAt($endsAt)
    {
        $this->endsAt = clone $endsAt;

        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return integer 
     */
    public function getEndsAt()
    {
        return clone $this->endsAt;
    }
}
