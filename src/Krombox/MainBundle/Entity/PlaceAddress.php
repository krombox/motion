<?php

namespace Krombox\MainBundle\Entity;

use Krombox\CommonBundle\Model\Traits\Address;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * PlaceAddress
 */
class PlaceAddress
{
    use ORMBehaviors\Translatable\Translatable;
    //use Address;
    
    private $id;
    /**
     * @var string
     */
    protected $lat;

    /**
     * @var string
     */
    protected $lng;
    
    public function __call($method, $args)
    {
        if (!method_exists(self::getTranslationEntityClass(), $method)) {
            $method = 'get' . ucfirst($method);
        }

        return $this->proxyCurrentLocaleTranslation($method, $args);
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
     * Set lat
     *
     * @param string $lat
     * @return PlaceAddress
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return PlaceAddress
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string 
     */
    public function getLng()
    {
        return $this->lng;
    }    

    /**
     * @var string
     */
    private $formatted;

    /**
     * Set formatted
     *
     * @param string $formatted
     * @return PlaceAddress
     */
    public function setFormatted($formatted)
    {
        $this->formatted = $formatted;

        return $this;
    }

    /**
     * Get formatted
     *
     * @return string 
     */
    public function getFormatted()
    {
        return $this->formatted;
    }
}
