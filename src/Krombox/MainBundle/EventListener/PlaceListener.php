<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\PlaceEvent;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\BusinessHours;

use Krombox\MainBundle\Entity\Membership;
use Krombox\MainBundle\Entity\MembershipSubscription;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
//use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service
 */
class PlaceListener
{        
    private $em;
    
    private $security;
    
    private $elastica;

    /**
     * @DI\InjectParams({           
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "security" = @DI\Inject("security.context"),
     *     "elastica" = @DI\Inject("fos_elastica.object_persister.app.place") 
     * })
     */
    public function __construct($em, $security, $elastica)
    {     
        $this->em = $em;
        $this->security = $security;
        $this->elastica = $elastica;
    }

    /**
     * @DI\Observe(PlaceEvents::PRE_CREATE, priority=0)     
     */
    public function onPlacePreCreate(PlaceEvent $event)
    {        
        $entity = $event->getPlace();        
        $this->manageStatus($entity);
        $this->createMembershipSubscription($entity);
    }
    
    /**
     * @DI\Observe(PlaceEvents::PRE_UPDATE, priority=0)     
     */
    public function onPlacePreUpdate(PlaceEvent $event)
    {          
        $entity = $event->getPlace();        
        $this->manageStatus($entity);
        
        $this->updateES($entity);
    }

    //TODO - is used?????
//    public function prePersist(LifecycleEventArgs $args)
//    {
//        $entity = $args->getObject();
//        if($entity instanceof Place) 
//        {
//            $this->manageStatus($entity);
//        }        
//    }
//    
//    public function preUpdate(LifecycleEventArgs $args)
//    {
//        $entity = $args->getObject();//var_dump(get_class($entity));
//        if ($entity instanceof Place)
//        {
//            $this->manageStatus($entity);
//        }        
//    }
    
    protected function manageStatus($entity)
    {    
        //return;//TODO REMOVE. JUST FOR Fixtures 
        $this->security->isGranted('ROLE_USER_VERIFIED') ? $status = StatusType::VALIDATED : $status = StatusType::PENDING;        
        $entity->setStatus($status);        
    }
    
    protected function createMembershipSubscription($entity)
    {
        $freeMembership = $this->em->getRepository(Membership::class)->findOneBy(['isFree' => true]);
        $membershipSubscription = new MembershipSubscription();
        $membershipSubscription->setMembership($freeMembership);
        $membershipSubscription->setPlace($entity);        
        $entity->addMembershipSubscription($membershipSubscription);
    }

    protected function updateES($entity)
    {
        //$persister = $this->get('fos_elastica.object_persister.app.place');
        $this->elastica->insertOne($entity);
    }
}
