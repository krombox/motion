<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\SecurityContext;
use Krombox\CommonBundle\Wrapper\AbstractWrapperFactory;
use Krombox\MainBundle\Entity\Event;

/**
 * @DI\Service("krombox.event.wrapper_factory")
 * @DI\Tag("wrapper_factory", attributes = {"class" = Event::class})
 */
class EventWrapperFactory extends AbstractWrapperFactory
{

    private $user;    

    /**
     * @DI\InjectParams({     
     *     "security"       = @DI\Inject("security.context")     
     * })
     */
    public function __construct(SecurityContext $security)
    {        
        $this->setUser($security->getToken()->getUser());        
    }
    
    public function setUser($user){
        $this->user = $user;
    }

    /**
     * @param Event $event
     * @return EventWrapper
     */
    public function wrap($event)
    {
        //$p = $this->getProvider();
        //$isCurrent = $this->user == $user;                
        return new EventWrapper([
            'id' => $event->getId(),
            'title' => $event->getName(),
            'start' => $event->getStartDate()->format('Y-m-d') . ' ' . $event->getStartTime()->format('H:i:s'),
            'end' => $event->getEndDate() ?  $event->getEndDate()->format('Y-m-d') . ' ' . $event->getEndTime()->format('H:i:s') : null
        ]);
    }  
}
