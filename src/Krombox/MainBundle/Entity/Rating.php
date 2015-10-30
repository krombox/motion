<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 */
class Rating
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $rate;

    /**
     * @var \Krombox\UserBundle\Entity\User
     */
    private $user;

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
     * Set rate
     *
     * @param integer $rate
     * @return Rating
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set user
     *
     * @param \Krombox\UserBundle\Entity\User $user
     * @return Rating
     */
    public function setUser(\Krombox\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Krombox\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return Rating
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
