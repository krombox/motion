<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\RatingEvent;
use Krombox\MainBundle\Event\RatingEvents;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\Rating;
use Krombox\CommonBundle\Model\Helper\RatingHelper;
/**
 * @DI\Service
 */
class RatingListener
{        
    private $em;

    /**
     * @DI\InjectParams({           
     *     "em" = @DI\Inject("doctrine.orm.entity_manager") 
     * })
     */
    public function __construct($em)
    {     
        $this->em = $em;
    }

    /**
     * @DI\Observe(RatingEvents::POST_SAVE, priority=0)     
     */
    public function onRatingPostSave(RatingEvent $ratingEvent)
    {
        $rating = $ratingEvent->getRating();        
        $this->updateRating($rating);        
    }
    
    /**
     * @DI\Observe(RatingEvents::POST_REMOVE, priority=0)     
     */
    public function onRatingPostRemove(RatingEvent $ratingEvent)
    {
        $rating = $ratingEvent->getRating();        
        $this->updateRating($rating, true);        
    }

    private function updateRating($rating, $isSub = false){
        $place = $rating->getPlace();
        
        if($isSub)           
            RatingHelper::subRating($place, $rating->getRate());        
        else
            RatingHelper::addRating($place, $rating->getRate());        
        
        $this->em->persist($place);
        $this->em->flush();
    }        
}
