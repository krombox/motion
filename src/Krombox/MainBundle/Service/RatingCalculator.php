<?php

namespace Krombox\MainBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Rating;
use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service("krombox.rating_calculator")
 */
class RatingCalculator
{
    
    private $em;

    /**
     * @DI\InjectParams({
           "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;
    }
    
    public function addRating($rating){
        $this->calculate($rating);
    }
    
    public function removeRating($rating){
        $this->calculate($rating, true);
    }

    protected function calculate($rating, $isUnrate = false){       
        $place = $rating->getPlace();   
        $rate = $rating->getRate();
        $ratingCount = $place->getRatingCount();
        $ratingsSum = $this->em->getRepository(Place::class)->getPlaceRatingsSum($place);
        //var_dump($ratingsSum);        
        if($isUnrate){             
            $ratingsSum -= $rate;
            $ratingCount--;            
        }
        else{
            $ratingsSum += $rate;
            $ratingCount++;            
        }
        
        $placeRating = $ratingsSum / $ratingCount;            
        
        $place->setRating($placeRating);
        var_dump($place->getRating());//die();
        $this->em->persist($place);
        $this->em->flush();
    }

//    public function getHoursFromInterval(\DateInterval $interval)
//    {
//        $hours    = $interval->h + ($interval->d * 24);
//        if ($interval->m > 30) {
//            $hours++;
//        } elseif ($interval->m > 0) {
//            $hours = $hours + 0.5;
//        }
//        return $hours;
//    }
//
//    public function roundHours($hours){
//        $integer = floor($hours);
//        $decimal = $hours - $integer;
//        if($decimal > 0.5)
//            return $integer + 1;
//        if($decimal > 0)
//            return $integer + 0.5;
//        else
//            return $integer;
//    }
} 
