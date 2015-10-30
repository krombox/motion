<?php

namespace Krombox\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Krombox\MainBundle\Entity\MyTag;

class TimeTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;        

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Datetime) to a string (number).
     *
     * @param  Datetime|null
     * @return string
     */
    public function transform($datetime)
    {                
        if($datetime !== null){
            return $datetime->format('H:i');
        }                
    }

    /**
     * Transforms a string (time) to an object (datetime).
     *
     * @param  string $time
     *
     * @return Datetime|null
     *
     * @throws TransformationFailedException if object (time) is not found.
     */
    public function reverseTransform($time)
    {   
        $date = null;
        
        if($time)
            $date = \DateTime::createFromFormat('H:i', $time);                
        
        return $date;
    }     
}
