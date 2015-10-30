<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Krombox\MainBundle\Entity\Event;
use Krombox\MainBundle\DBAL\Types\StatusType;
//use Krombox\MainBundle\Entity\CategoryTranslation;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LocalEventSubscriber implements EventSubscriber
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
        );
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->setStatus($args);
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->setStatus($args);
    }
    
    public function setStatus(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        
        if ($entity instanceof Event) {
           //$entity->setStatus(StatusType::PENDING); 
           $securityContext = $this->container->get('security.context');
           //$user = $securityContext = $securityContext->getToken()->getUser();
           
            if($securityContext->isGranted('ROLE_USER_VERIFIED') && !$entity->getStatus()){
                //var_dump($securityContext->isGranted('ROLE_USER_VERIFIED'));die();
                $entity->setStatus(StatusType::VALIDATED);
            }
        }
        
//        if ($entity instanceof Category) {
//           $entity->generateSlug(); 
//           $em->persist($entity);
//           $em->flush();
//        }
    }
}
