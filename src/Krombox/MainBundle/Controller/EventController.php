<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\MyTag;
use Krombox\MainBundle\Entity\Event;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\Form\Type\EventType;
//use Krombox\MainBundle\DBAL\Types\EventType;
//use Krombox\MainBundle\Form\Type\Filter\PlaceFilterType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Place controller.
 *
 */
class EventController extends Controller
{

    /**
     * Lists all Place entities.
     *
     */
    public function detailsAction($slug)
    {
        $em = $this->getDoctrine()->getManager();                
        /*TODO chnage for elastica AND ONLY VALIDATED*/        
        $event = $em->getRepository(Event::class)->findOneBy(['slug' => $slug]);
                
        if(!$event)
            throw new NotFoundHttpException();                                       
        
        return $this->render('KromboxMainBundle:Event:details.html.twig', array('entity' => $event));
    }
    
    public function listAction(Request $request, $category){
        //$redis = $this->container->get('snc_redis.default');
//        $placeSearch = new PlaceSearch();
        $elasticaManager = $this->container->get('fos_elastica.manager');

//        $placeSearchForm = $this->get('form.factory')
//            ->createNamed(
//                '',
//                'place_search_type',
//                $placeSearch,
//                array(
//                    'action' => $this->generateUrl('places_list', ['category' => $category]),
//                    'method' => 'GET'
//                )
//            );
//        $placeSearchForm->handleRequest($request);
//        //if ($placeSearchForm->isValid()) {
//            $placeSearch = $placeSearchForm->getData();                
//            
            $events = $elasticaManager->getRepository(Event::class)->search($category, $placeSearch = null);/*TODO search form*/
            //$events = $this->getDoctrine()->getManager()->getRepository(Event::class)->findAll();
//
//            if($placeSearch->getIsWorkingNow()){
//                /*TODO make external helper*/
//                foreach($places as $key => $place){
//                    if(!$place->isWorkingNow()){                    
//                        unset($places[$key]);                    
//                    }
//                }
//            }
//        }
//        else
//            $places = $elasticaManager->getRepository(Place::class)->search(new PlaceSearch());
        
        return $this->render('KromboxMainBundle:Event:list.html.twig',array(
            'events' => $events,
            //'placeSearchForm' => $placeSearchForm->createView(),
        ));
    }
    
    public function getEventsFeedAction($place_hash){
        $em = $this->getDoctrine()->getManager();                
        /*TODO chnage for elastica AND ONLY VALIDATED*/  
//        if($place_hash == null)
//            $events = $em->getRepository(Event::class)->findAll();
//        else
            $events = $em->getRepository(Event::class)->getEvents($place_hash);
        
        $events = $this->prepareEventsFeed($events);
        
        return new JsonResponse($events, 200);
    }
    
    public function prepareEventsFeed($events){
        if(!$events)
            return;
        
        $eventsFeed = [];
        foreach ($events as $key => $event){
            $eventsFeed[$key] = $event->prepareForCalendar();
        }
        
        return $eventsFeed;
    }
    
    /**     
     * @FW\Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();                
        $event = new Event();
        $event->setUser($this->getUser());
        
        $form = $this->createForm(new EventType(), $event);        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
//            $data = $form->getData();
//            var_dump($data);die();
            //echo $data->getTags();die();
            
//            foreach ($event->getTags() as $t){
//                var_dump($t->getName());
//            }
//            $tagManager = $this->get('fpn_tag.tag_manager');
//            
//            // ask the tag manager to create a Tag object
//            $fooTag = $tagManager->loadOrCreateTag('foo');            
//
//            // assign the foo tag to the post
//            $tagManager->addTag($fooTag, $event);
            
            $em->persist($event);
            //$place->mergeNewTranslations();
            $em->flush();
            
//            foreach ($event->getTags() as $t){
//                var_dump($t->getName());
//            }            
            return $this->redirectToRoute('user_events');
        }
        
        return $this->render('KromboxMainBundle:Event:new.html.twig', array(
            'form' => $form->createView()
        ));          
    }
    
    /**     
     * @FW\Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function editAction(Request $request, $slug){
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->findOneBy(['slug' => $slug]);
        
        if(!$event)
            throw new NotFoundHttpException();        
        
        $this->denyAccessUnlessGranted('edit', $event);
        //var_dump($event->getTags());
        $form = $this->createForm(new EventType(), $event);
        
        $form->handleRequest($request);

        if ($form->isValid()) {            
            $em->persist($event);            
            $em->flush();            
            return $this->redirectToRoute('user_events');
        }
        
        return $this->render('KromboxMainBundle:Event:new.html.twig', array(
            'form' => $form->createView()
        ));            
    }        
        
}
