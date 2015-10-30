<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\PlaceEvent;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\Entity\Place;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
//use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0
 * )
 */
class PlaceListener
{        
    private $em;
    
    private $security;

    /**
     * @DI\InjectParams({           
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "security" = @DI\Inject("security.context") 
     * })
     */
    public function __construct($em, $security)
    {     
        $this->em = $em;
        $this->security = $security;
    }

    /**
     * @DI\Observe(PlaceEvents::PRE_SAVE, priority=0)     
     */
    public function onPlacePreSave(PlaceEvent $event)
    {
        return;//TODO NOT USED
        $place = $event->getPlace();                
        $this->security->isGranted('ROLE_USER_VERIFIED') ? $status = StatusType::VALIDATED : $status = StatusType::PENDING;        
        $place->setStatus($status);       
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();        
        if ($entity instanceof Place) 
        {
            $this->manageStatus($entity);
        }                
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();        
        if ($entity instanceof Place) 
        {
            $this->manageStatus($entity);
        }
    }
    
    protected function manageStatus($entity)
    {                        
        $this->security->isGranted('ROLE_USER_VERIFIED') ? $status = StatusType::VALIDATED : $status = StatusType::PENDING;        
        $entity->setStatus($status);        
    }
}
