<?php

namespace Krombox\MainBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Event\PlaceEvent;
use Krombox\MainBundle\Event\PlaceEvents;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\BusinessHours;
use Krombox\MainBundle\Entity\BusinessHoursException;
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
class BusinessHoursListener
{        
    private $em;
    
    private $security;
    
    private $entities = []; 

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
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        
        if($entity instanceof BusinessHours){
            $this->divideBusinessHours($entity);//Move to BusinessHoursHelper
        }
        
        if($entity instanceof BusinessHoursException){
            $this->divideBusinessHoursException($entity);//Move to BusinessHoursHelper
        }
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {        
        $entity = $args->getObject();
       
        if($entity instanceof BusinessHours){
            $this->divideBusinessHours($entity);
        }
        
        if($entity instanceof BusinessHoursException){
            $this->divideBusinessHoursException($entity);//Move to BusinessHoursHelper
        }
    }
    
    public function postFlush(PostFlushEventArgs $event)
    {                            
        if(!empty($this->entities)) {                
            $em = $event->getEntityManager();

            foreach ($this->entities as $k => $entity) {                
                $em->persist($entity);
                unset($this->entities[$k]);
            }
            
            $em->flush();
        }
    }
    
    protected function divideBusinessHours($entity)
    {
        //var_dump($entity->getPlace());die();
        $today = new \DateTime();
        
        if($entity->getStartsAt()->format('H:i:s') > $entity->getEndsAt()->format('H:i:s')){
                        //var_dump($entity->getPlace(). 'dddd');die();
            $endsAt = $entity->getEndsAt();
            $entity->setEndsAt($today::createFromFormat('H:i:s', '23:59:59'));

            $bh = new BusinessHours();
            DayFlaggableHelper::fillFields($bh, false);
            $pa = PropertyAccess::createPropertyAccessor();
            foreach (DayFlaggableHelper::getWeekdays() as $key => $label) {
                if($pa->getValue($entity, 'day' . ucwords($key)) == true){
                    $nextDay = DayFlaggableHelper::nextDay($key);
                    $pa->setValue($bh, 'day' . ucwords($nextDay), true);
                }
            }
            //var_dump($entity->getPlace());
            
            $bh->setPlace($entity->getPlace());
            $bh->setStartsAt($today::createFromFormat('H:i:s', '00:00:00'));
            $bh->setEndsAt($endsAt);
            
            $this->entities[] = $bh;                
        }        
    }
    
    protected function divideBusinessHoursException($entity)
    {
        $today = new \DateTime();
        
        if($entity->getStartsAt()->format('H:i:s') > $entity->getEndsAt()->format('H:i:s')){                        
            $endsAt = $entity->getEndsAt();
            $entity->setEndsAt($today::createFromFormat('H:i:s', '23:59:59'));
            $nextDay = clone $entity->getDay();
            $nextDay->modify('+1 day');
            
            $bhE = new BusinessHoursException();
            $bhE->setDay($nextDay);            
            $bhE->setPlace($entity->getPlace());
            $bhE->setStartsAt($today::createFromFormat('H:i:s', '00:00:00'));
            $bhE->setEndsAt($endsAt);
            
            $this->entities[] = $bhE;                
        }        
    }
}
