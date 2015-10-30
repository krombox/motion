<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\Event;
use Krombox\MainBundle\Form\Type\PlaceType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /**     
     * @FW\Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function placesAction(Request $request){
//        
//        $imagemanagerResponse = $this->container
//            ->get('liip_imagine.controller')
//                ->filterAction(
//                    $request,          // http request
//                    'http://s.ill.in.ua/i/news/630x373/267/267023.jpg',      // original image you want to apply a filter to
//                    'place_image_thumb'              // filter defined in config.yml
//        );
//        var_dump($imagemanagerResponse);
//        die('it is all?!');
        
        /*testing*/
        $em = $this->getDoctrine()->getManager();
        $places = $em->getRepository(Place::class)->findBy(array('user' => $this->getUser()));
        
        return $this->render('KromboxMainBundle:User:places.html.twig', array('places' => $places));
    }
    
    /**     
     * @FW\Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function eventsAction(Request $request){
        
        $em = $this->getDoctrine()->getManager();
        $places = $em->getRepository(Event::class)->findBy(array('user' => $this->getUser()));
        
        return $this->render('KromboxMainBundle:User:events.html.twig', array('events' => $places));
    }
}
