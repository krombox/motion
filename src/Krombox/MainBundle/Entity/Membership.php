<?php

namespace Krombox\MainBundle\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\ORM\Mapping as ORM;
use Krombox\MainBundle\DBAL\Types\MembershipType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;

/**
 * Membership
 */
class Membership
{
    //use ORMBehaviors\Timestampable\Timestampable;
    /**
     * @var integer
     */
    
    public function __construct() {
        //$this->score = MembershipType::$score[$this->type];
    }
    
    private $id;

    /**
     * @var MembershipType
     */
    private $type = MembershipType::STANDART;

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
     * Set type
     *
     * @param MembershipType $type
     * @return Membership
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->setScore(MembershipType::$score[$this->type]);
        return $this;
    }

    /**
     * Get type
     *
     * @return MembershipType 
     */
    public function getType()
    {
        return $this->type;
    }
       
    /**
     * @var integer
     */
    private $score;


    /**
     * Set score
     *
     * @param integer $score
     * @return Membership
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $price;

    /**
     * @var float
     */
    private $priceSpecial;

    /**
     * @var \DateTime
     */
    private $priceSpecialEndsAt;


    /**
     * Set name
     *
     * @param string $name
     * @return Membership
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return Membership
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set priceSpecial
     *
     * @param float $priceSpecial
     * @return Membership
     */
    public function setPriceSpecial($priceSpecial)
    {
        $this->priceSpecial = $priceSpecial;

        return $this;
    }

    /**
     * Get priceSpecial
     *
     * @return float 
     */
    public function getPriceSpecial()
    {
        return $this->priceSpecial;
    }

    /**
     * Set priceSpecialEndsAt
     *
     * @param \DateTime $priceSpecialEndsAt
     * @return Membership
     */
    public function setPriceSpecialEndsAt($priceSpecialEndsAt)
    {
        $this->priceSpecialEndsAt = $priceSpecialEndsAt;

        return $this;
    }

    /**
     * Get priceSpecialEndsAt
     *
     * @return \DateTime 
     */
    public function getPriceSpecialEndsAt()
    {
        return $this->priceSpecialEndsAt;
    }
    /**
     * @var boolean
     */
    private $isFree = false;


    /**
     * Set isFree
     *
     * @param boolean $isFree
     * @return Membership
     */
    public function setIsFree($isFree)
    {
        $this->isFree = $isFree;

        return $this;
    }

    /**
     * Get isFree
     *
     * @return boolean 
     */
    public function getIsFree()
    {
        return $this->isFree;
    }
}
