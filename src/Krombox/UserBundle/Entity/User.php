<?php

namespace Krombox\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser
{
    /**
     * @var integer
     */
    protected $id;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $places;


    /**
     * Add places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     * @return User
     */
    public function addPlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places[] = $places;

        return $this;
    }

    /**
     * Remove places
     *
     * @param \Krombox\MainBundle\Entity\Place $places
     */
    public function removePlace(\Krombox\MainBundle\Entity\Place $places)
    {
        $this->places->removeElement($places);
    }

    /**
     * Get places
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaces()
    {
        return $this->places;
    }    
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $events;


    /**
     * Add events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     * @return User
     */
    public function addEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
    /**
     * @var string
     */
    private $vk_id;

    /**
     * @var string
     */
    private $vk_access_token;


    /**
     * Set vk_id
     *
     * @param string $vkId
     * @return User
     */
    public function setVkId($vkId)
    {
        $this->vk_id = $vkId;

        return $this;
    }

    /**
     * Get vk_id
     *
     * @return string 
     */
    public function getVkId()
    {
        return $this->vk_id;
    }

    /**
     * Set vk_access_token
     *
     * @param string $vkAccessToken
     * @return User
     */
    public function setVkAccessToken($vkAccessToken)
    {
        $this->vk_access_token = $vkAccessToken;

        return $this;
    }

    /**
     * Get vk_access_token
     *
     * @return string 
     */
    public function getVkAccessToken()
    {
        return $this->vk_access_token;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ratings;


    /**
     * Add ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     * @return User
     */
    public function addRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     */
    public function removeRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }
    /**
     * @var integer
     */
    private $appStep;


    /**
     * Set appStep
     *
     * @param integer $appStep
     * @return User
     */
    public function setAppStep($appStep)
    {
        $this->appStep = $appStep;

        return $this;
    }

    /**
     * Get appStep
     *
     * @return integer 
     */
    public function getAppStep()
    {
        return $this->appStep;
    }
}
