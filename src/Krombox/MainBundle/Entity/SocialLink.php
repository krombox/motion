<?php

namespace Krombox\MainBundle\Entity;

use Krombox\MainBundle\DBAL\Types\SocialLinkType;
use Doctrine\ORM\Mapping as ORM;

/**
 * SocialLink
 */
class SocialLink
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var SocialLinkType
     */
    private $type;

    /**
     * @var string
     */
    private $url;

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
     * Set type
     *
     * @param \SocialLinkType $type
     * @return SocialLink
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \SocialLinkType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return SocialLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set place
     *
     * @param \Krombox\MainBundle\Entity\Place $place
     * @return SocialLink
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
