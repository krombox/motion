<?php

namespace Krombox\MainBundle\EventListener;

use Krombox\MainBundle\Entity\ImageUploadableInterface;
/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
use Doctrine\ORM\Event\LifecycleEventArgs;

class UploadListener extends BaseListener 
{
    public function getSubscribedEvents()
    {
        return array(            
            'prePersist',
            'preUpdate'
        );
    }
    
    /**
     * @param LifecycleEventArgs $event The event.
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        
        if($entity instanceof ImageUploadableInterface){            
            $this->upload($entity);
        }        
    }
    
    public function preUpdate(LifecycleEventArgs $event)
    {        
        $entity = $event->getEntity();
        
        if($entity instanceof ImageUploadableInterface){            
            $this->upload($entity);
        }        
    }
    
    protected function upload($obj)
    {
        $this->handler->upload($obj, 'image');        
    }
}
