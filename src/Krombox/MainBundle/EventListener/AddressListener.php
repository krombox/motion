<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\PlaceEvent;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\PlaceAddress;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;
use Symfony\Component\PropertyAccess\PropertyAccess;

//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
//use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
//use Doctrine\ORM\Event\PostFlushEventArgs;
//use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service
 * @DI\DoctrineListener(
 *     events = {"prePersist", "preUpdate", "postFlush"},
 *     connection = "default",
 *     lazy = true,
 *     priority = 0
 * )
 */
class AddressListener
{        
    private $locales = [];
    
    private $em;
    
    private $security;
    
    private $container;
    
    private $entities = []; 

    /**
     * @DI\InjectParams({           
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "security" = @DI\Inject("security.context"),
     *     "container" = @DI\Inject("service_container")  
     * })
     */
    public function __construct($em, $security, $container)
    {     
        $this->em = $em;
        $this->security = $security;
        $this->container = $container;
        $this->locales = $this->container->getParameter('locales');
    }   
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
       
        if($entity instanceof PlaceAddress){
            $this->insertTranslation($entity);
        }
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {        
        $entity = $args->getObject();
       
        if($entity instanceof PlaceAddress){
            $this->insertTranslation($entity);
        }                
    }
    
    public function postFlush(PostFlushEventArgs $event)
    {                            
        
//        if(!empty($this->entities)) {                
//            $em = $event->getEntityManager();
//
//            foreach ($this->entities as $k => $entity) {                
//                $em->persist($entity);
//                unset($this->entities[$k]);
//            }
//            
//            //$entity->mergeNewTranslations();
//            $em->flush();
//        }        
    }
    
    protected function insertTranslation($entity)
    {
        $httpAdapter  = new \Ivory\HttpAdapter\CurlHttpAdapter();

        $geocoder = new \Geocoder\Provider\GoogleMaps(
            $httpAdapter,
            null,
            null,
            true, // true|false
            'AIzaSyDzmQiEmu390yCXaB9RQ4jFwXnm_hEJJBI'
        );           
               
        foreach ($this->locales as $lng){
            $geocoder->setLocale($lng);
            $result = $geocoder->reverse($entity->getLat(), $entity->getLng())->first();
            //var_dump($result);
            $entity->setFormatted($result->getStreetNumber() . ', ' . $result->getStreetName() . ', ' . $result->getLocality() . ', ' . $result->getAdminLevels()->first()->getName() . ', ' . $result->getCountry()->getName());
            
            $entity->translate($lng)
                    ->setCountry($result->getCountry()->getName())
                    ->setState($result->getAdminLevels()->first()->getName())
                    ->setCity($result->getLocality())
                    ->setStreet($result->getStreetName())
                    ->setStreetNumber($result->getStreetNumber())
            ; 
            
            $entity->mergeNewTranslations();
        }      
        //$entity->setFormatted($entity->getStreetNumber() . ', ' . $entity->getStreet() . ', ' . $entity->getCity() . ', ' . $entity->getState() . ', ' . $entity->getCountry());
        
        //$this->entities[] = $entity;        
        
        //$this->em->flush();
        
//        $geocoder = $this->container
//            ->get('bazinga_geocoder.geocoder')->using('google_maps')->getLocale()
//        ;
        
        //die();         
    }
}
