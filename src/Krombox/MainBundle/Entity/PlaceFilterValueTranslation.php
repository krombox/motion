<?php

namespace Krombox\MainBundle\Entity;

use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\ORM\Mapping as ORM;

/**
 * PlaceFilterValueTranslation
 */
class PlaceFilterValueTranslation
{
    use ORMBehaviors\Translatable\Translation;    

    /**
     * @var string
     */
    private $name;   

    /**
     * Set name
     *
     * @param string $name
     * @return PlaceFilterValueTranslation
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
    
    public function __toString() {
        return $this->getName();
    }
}
