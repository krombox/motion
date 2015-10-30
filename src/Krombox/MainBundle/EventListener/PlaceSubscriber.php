<?php

namespace Krombox\MainBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Krombox\MainBundle\Entity\Place;
//use Krombox\MainBundle\Entity\Category;
use Krombox\MainBundle\DBAL\Types\StatusType;

use Krombox\MainBundle\Event\RatingEvent;
//use Krombox\MainBundle\Entity\CategoryTranslation;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Doctrine\Common\EventSubscriber;

class PlaceSubscriber implements EventSubscriberInterface
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }        
    
    public static function getSubscribedEvents()
    {
        return array(            
            'rating.pre_remove'     => array('onRatingPreRemove', 0),
        );
    }
    
//    public function prePersist(LifecycleEventArgs $args)
//    {
//        $this->setPlaceStatus($args);
//    }
//    
//    public function preUpdate(LifecycleEventArgs $args)
//    {
//        $this->setPlaceStatus($args);
//    }
//    
//    public function setPlaceStatus(LifecycleEventArgs $args)
//    {
//        $entity = $args->getEntity();
//        $em = $args->getEntityManager();
//        
//        if ($entity instanceof Place) {           
//           $securityContext = $this->container->get('security.context');           
//                       
//            if(!$securityContext->isGranted('ROLE_USER_VERIFIED')){                
//                $entity->setStatus(StatusType::PENDING);
//            }
//        }        
//    }
    
    public function onRatingPreRemove(RatingEvent $event){
        echo 'CALLED???';die();
    }
}
