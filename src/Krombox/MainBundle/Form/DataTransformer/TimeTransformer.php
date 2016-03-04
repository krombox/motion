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
        
        if($time){                       
            if($time == '23:59'){ 
                $time = $this->addSeconds ($time, '59');            
            } else {
                $time = $this->addSeconds ($time);
            }
            
            $date = \DateTime::createFromFormat('H:i:s', $time);                                
        }
        
        return $date;
    }
    
    protected function addSeconds($time, $seconds = '00')
    {
        return $time . ':' . $seconds;
    }
}
