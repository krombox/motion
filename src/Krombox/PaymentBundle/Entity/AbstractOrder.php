<?php

namespace Krombox\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Krombox\UserBundle\Entity\User;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Krombox\MainBundle\Entity\Place;
use Krombox\CommonBundle\Model\Traits\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Krombox\MainBundle\DBAL\Types\PaymentStatusType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity("uniqueId")
 */
abstract class AbstractOrder
{
    use Entity,
    ORMBehaviors\Timestampable\Timestampable;  
    
    public function __construct() {
        $this->uniqueId = md5(sha1(time() . self::class));        
    }

    /**
     * @ORM\ManyToOne(targetEntity="Krombox\UserBundle\Entity\User", inversedBy="payments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Krombox\MainBundle\Entity\Place", inversedBy="payments")
     * @ORM\JoinColumn(name="place_id", referencedColumnName="id", nullable=false)
     */
    protected $place;
    
    /**
     * Note, that type of field should be same as you set in doctrine config in this case it is BasketballPositionType
     *
     * @DoctrineAssert\Enum(entity="Krombox\MainBundle\DBAL\Types\PaymentStatusType")
     * @ORM\Column(name="status", type="PaymentStatusType", nullable=false)
     */
    protected $status = PaymentStatusType::PENDING;
    
    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $amount;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $uniqueId;
    

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }   

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return Order
     */
    public function setPlace(\Krombox\MainBundle\Entity\Place $place)
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
     * Set amount
     *
     * @param string $amount
     * @return OrderMembership
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set status
     *
     * @param PaymentStatusType $status
     * @return OrderMembership
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return PaymentStatusType 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set uniqueId
     *
     * @param integer $uniqueId
     * @return OrderMembership
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return integer 
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }
}
