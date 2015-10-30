<?php

namespace Krombox\MainBundle\Controller\Api;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;
//use Nelmio\ApiDocBundle\Annotation as Doc;
use FOS\RestBundle\Controller\FOSRestController as RestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Krombox\MainBundle\Form\Type\LogoType;
use Krombox\MainBundle\Entity\Place;

class EventController extends RestController
{   
    /**
     * @View()
     */
    public function getPlaceEventsAction(Place $place)
    {
        $factory = $this->get('krombox.event.wrapper_factory');
        $events = [];
        
        foreach ($place->getEvents() as $event){
            $events[] = $factory->wrap($event);
        }
        
        return $events;                        
    }        
}
