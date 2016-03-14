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
    
    /**
     * @FW\Route("{city_slug}/events/{category_slug}", name="events_list")     
     * @FW\ParamConverter("city", class="KromboxMainBundle:City", options={"mapping": {"city_slug": "slug"}})          
     * @FW\ParamConverter("category", class="KromboxMainBundle:Category", options={"mapping": {"category_slug": "slug"}})
     * @FW\Template        
     */
    public function listAction(Request $request, $category)
    {        
        $placeFilter = new PlaceFilter();        
        //$placeFilter->setCategory($category);
        $placeFilter->setCity($city);
        
        $elasticaManager = $this->container->get('fos_elastica.manager');
        //$sort = 'membership';
        $page = '1';
        
        $filterForm = $this->get('form.factory')
            ->createNamed(
                '',
                'place_filter',
                $placeFilter,
                array(
                    'action' => $this->generateUrl('places_list', ['city_slug' => $city->getSlug(), 'category_slug' => $category->getSlug()]),
                    'method' => 'GET'
                )
            );
        $filterForm->handleRequest($request);
        
        
        if($request->query->has('sort')){
            $sortParam = $request->query->get('sort');
            if(in_array($sortParam, ['membership', 'rating','views'])){
                    $sort = $sortParam;                        
            }
        }
        
        if($request->query->has('page')){
            $page = $request->query->get('page');            
        }
        
        //var_dump($sort);die();
        $places = $elasticaManager->getRepository(Place::class)->facet($placeFilter, $sort);
        $places->setMaxPerPage(3);
        $places->setCurrentPage($request->query->get('page', 1));
        $filterFacet = $places->getAdapter()->getAggregations();
        //var_dump($filterFacet);die();
        //$filterFacet['businessHours']['buckets'][] = ['key' => 'workingNow', 'doc_count' => 5]; 
        //var_dump($filterFacet);die();
        $helper = $this->container->get('krombox.business_hours_helper');
        
//        if($placeFilter->getBusinessHours()){
//            echo 'here';
//            /*TODO make external helper*/
//            foreach($places as $key => $place){
//                if(!$helper->isWorkingNow($place)){                    
//                    var_dump($places);die();
//                    unset($places[$key]);                    
//                }
//            }
//        }
        if($request->isXmlHttpRequest()){
            return $this->render('KromboxMainBundle:Place/partial:placesList.html.twig', array(
                'places' => $places,
                'filterForm' => $filterForm->createView(),
                'filterFacet'     => $filterFacet,
                'sort' => $sort
            ));
        }        
        //var_dump($filterFacet);
        return $this->render('KromboxMainBundle:Place:list.html.twig',array(
            'places' => $places,
            'filterForm' => $filterForm->createView(),
            'filterFacet'     => $filterFacet,
            'sort' => $sort
        ));
//        $elasticaManager = $this->container->get('fos_elastica.manager');      
//        $events = $elasticaManager->getRepository(Event::class)->search($category, $placeSearch = null);/*TODO search form*/            
//        
//        return $this->render('KromboxMainBundle:Event:list.html.twig',array(
//            'events' => $events,            
//        ));
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
