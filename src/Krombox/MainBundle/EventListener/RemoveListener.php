<?php

namespace Krombox\MainBundle\EventListener;

use Krombox\MainBundle\Entity\ImageUploadableInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */



class RemoveListener extends BaseListener 
{
    public function getSubscribedEvents()
    {
        return array(            
            'preRemove'
        );
    }
    
    /**
     * @param EventArgs $event The event.
     */
    public function preRemove(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();                
        
        if($entity instanceof ImageUploadableInterface){            
            $this->handler->remove($entity, 'image');
        }                   
    }
}
