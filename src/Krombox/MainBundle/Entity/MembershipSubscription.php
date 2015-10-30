<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;

/**
 * MembershipSubscription
 */
class MembershipSubscription
{
    use ORMBehaviors\Timestampable\Timestampable;
    /**
     * @var integer
     */
    private $id;      

    /**
     * @var MembershipStatusType
     */
    private $status = MembershipStatusType::ACTIVE;

    /**
     * @var string
     */
    private $endsAt;

    /**
     * @var \Krombox\MainBundle\Entity\Place
     */
    private $place;


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
     * Set status
     *
     * @param MembershipStatusType $status
     * @return MembershipSubscription
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return MembershipStatusType 
     */
    public function getStatus()
    {
        return $this->status;
    }    

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return MembershipSubscription
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
    
    /**
     * @var \Krombox\MainBundle\Entity\Membership
     */
    private $membership;


    /**
     * Set membership
     *
     * @param \Krombox\MainBundle\Entity\Membership $membership
     * @return MembershipSubscription
     */
    public function setMembership(\Krombox\MainBundle\Entity\Membership $membership = null)
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

    /**
     * Set endsAt
     *
     * @param \DateTime $endsAt
     * @return MembershipSubscription
     */
    public function setEndsAt($endsAt)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get endsAt
     *
     * @return \DateTime 
     */
    public function getEndsAt()
    {
        return $this->endsAt;
    }
}
