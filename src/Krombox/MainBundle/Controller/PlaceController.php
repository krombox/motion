<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Krombox\MainBundle\Entity\Place;
//use Krombox\MainBundle\Entity\PlaceImage;
use Krombox\MainBundle\Entity\PlaceHallImage;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\Form\Model\PlaceFilter;
use Krombox\MainBundle\Form\Type\PlaceType;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\PlaceProfileType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Session\Session;
use Krombox\CommonBundle\Model\Helper\RatingHelper;
use Krombox\MainBundle\Entity\Rating;
use Krombox\PaymentBundle\Form\Type\OrderMembershipType;
use Krombox\PaymentBundle\Entity\OrderMembership;
use Krombox\MainBundle\DBAL\Types\MembershipType;
use Krombox\MainBundle\Entity\MembershipSubscription;
use Krombox\MainBundle\Entity\Membership;
use Krombox\MainBundle\Form\Type\Filter\PlaceFilterType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\Constants;

/**
 * Place controller.
 *
 */
class PlaceController extends Controller
{
    /**
     * @FW\Route("/place/new", name="place_new")
     * @FW\Security("is_granted('ROLE_USER')")
     * @FW\Template          
     */
    public function newAction() {
        $place = new Place(); // Your form data class. Has to be an object, won't work properly with an array.

        $flow = $this->get('main_bundle.form.flow.new_place'); // must match the flow's service id
        $flow->bind($place);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
//                $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');
//
//        // get files
//        $files = $manager->getFiles();
//        var_dump($files);die();
                // flow finished
                $em = $this->getDoctrine()->getManager();
                $freeMembership = $em->getRepository(Membership::class)->findOneBy(['isFree' => true]);
                $membershipSubscription = new MembershipSubscription();
                $membershipSubscription->setMembership($freeMembership);
                $membershipSubscription->setPlace($place);
                $place->setUser($this->getUser());
                $place->addMembershipSubscription($membershipSubscription);
                
                
                $em->persist($place);
                $em->flush();

                $flow->reset(); // remove step data from the session

                return $this->redirect($this->generateUrl('places_list')); // redirect when done
            }
        }
        
        return ['form' => $form->createView(), 'flow' => $flow];
    }
    
    /**
     * @FW\Route("/place/{slug}/remove", name="place_remove")     
     * @FW\Template     
     */
    public function removeAction(Place $place)
    {
        $em = $this->getDoctrine()->getManager();
        
        $em->remove($place);
        $em->flush();
    }

    /**
     * @FW\Route("/place/{slug}", name="place_details")
     * @ParamConverter("place", options={"repository_method": "getPlace", "map_method_signature" = true})
     * @FW\Template     
     */
    public function detailsAction(Place $place)
    {
        return compact('place');        
    }
    
    /**
     * @FW\Route("/place/{slug}/membership", name="place_membership")
     * @FW\Security("is_granted('edit', place)")     
     * @FW\Template     
     */
    public function membershipAction(Place $place)
    {
//    {   $membership = new Membership();
//   var_dump($membership->setType(MembershipType::GOLD));die();
        $em = $this->getDoctrine()->getManager();
        //$membershipList = $em->getRepository(Membership::class)->findBy(['isFree' => 0]);
        $membershipList = $em->getRepository(Membership::class)->findAll();
        return compact('place','membershipList');
    }
    
    /**     
     * @FW\Route("/place/{slug}/membership/{name}/extend", name="place_membership_extend")
     * @FW\ParamConverter("place", class="KromboxMainBundle:Place", options={"mapping": {"slug": "slug"}})          
     * @FW\ParamConverter("membership", class="KromboxMainBundle:Membership", options={"mapping": {"name": "name"}})
     * @FW\Security("is_granted('edit', place)")     
     * @FW\Template     
     */
    public function membershipExtendAction(Place $place, Membership $membership, Request $request)
    {
        return $this->membershipProcess($place, $membership, $request);
    }
    
    /**
     * @FW\Route("/place/{slug}/membership/{name}/change", name="place_membership_change")     
     * @FW\ParamConverter("place", class="KromboxMainBundle:Place", options={"mapping": {"slug": "slug"}})          
     * @FW\ParamConverter("membership", class="KromboxMainBundle:Membership", options={"mapping": {"name": "name"}})
     * @FW\Security("is_granted('edit', place)")     
     * @FW\Template     
     */
    public function membershipChangeAction(Place $place, Membership $membership, Request $request)
    {   
        return $this->membershipProcess($place, $membership, $request);
    }
    
    protected function membershipProcess($place, $membership, $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = new OrderMembership();
        $order->setPlace($place);
        $order->setUser($this->getUser());        
        $order->setMembership($membership);
        
        $form = $this->createForm(new OrderMembershipType(), $order);                
        $form->handleRequest($request);

        if ($form->isValid()) {            
            //$amount = $this->processMembership($order);//TODO
            $amount = $order->getDaysCount() * $membership->getPrice();
            $order->setAmount($amount);
            $em->persist($order);
            $em->flush();            

            return $this->redirectToRoute('order_membership_pay', ['uniqueId' => $order->getUniqueId()]);
        }
                
        return ['form' => $form->createView(), 'place' => $place];
    }


    /**
     * @FW\Route("/membershipsubscription/{id}/extend", name="place_membershipsubscription_extend")          
     * @FW\Template     
     */
//    public function membershipExtendAction(MembershipSubscription $membershipSubscription, Request $request)
//    {                         
//        $em = $this->getDoctrine()->getManager();
//        
//        $order = new OrderMembership();
//        $order->setPlace($membershipSubscription->getPlace());
//        $order->setUser($membershipSubscription->getPlace()->getUser());        
//        $order->setMembership($membershipSubscription->getType());
//        
//        $form = $this->createForm(new OrderMembershipType(), $order);                
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {                     
//            $amount = $this->processMembership($order);
//            $order->setAmount($amount);
//            $em->persist($order);
//            $em->flush();            
//
//            return $this->redirectToRoute('order_membership_pay', ['uniqueId' => $order->getUniqueId()]);
//        }
//                
//        return ['form' => $form->createView(), 'place' => $membershipSubscription->getPlace()];        
//    }
    
    /**
     * @FW\Route("/place/{slug}/invoice/membership", name="place_invoice_membership")
     * @FW\Security("is_granted('edit', place)")     
     * @FW\Template     
     */
    public function invoiceMembershipAction(Place $place, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $order = new OrderMembership();
        $order->setPlace($place);
        $order->setUser($this->getUser());
        
        $form = $this->createForm(new OrderMembershipType(), $order);                
        $form->handleRequest($request);

        if ($form->isValid()) {                                        
            $amount = $this->processMembership($order);
            $order->setAmount($amount);
            $em->persist($order);
            $em->flush();            

            return $this->redirectToRoute('order_membership_pay', ['id' => $order->getId()]);
        }
        
        //return ['form' => $form->createView(), 'place' => $place];
        return ['form' => $form->createView(), 'place' => $place];                
    }
      
    protected function processMembership($order){
        //$place = $order->getPlace();
        //$membership = $place->getMembershipSubscriptions()->getType();
        $orderMembership  = $order->getMembership();
        
//        if($membership == $orderMembership || $membership == MembershipType::STANDART)
//        {
//            $membershipName = MembershipType::getReadableValue($membership);
//
//            if($membership == MembershipType::STANDART){
//                //var_dump($order->getMembership() + 1);die();
//                $membershipName = MembershipType::getReadableValue($orderMembership);
//            }            
//        }
        
        //$membershipName = MembershipType::getReadableValue($orderMembership);
        //$membershipPrice = $this->getParameter($membershipName . 'MembershipPrice');

        return $order->getDaysCount() * $orderMembership->getPrice();
    }
//    protected function processMembership($order){
//        $place = $order->getPlace();
//        $membership = $place->getMembership()->getType();
//        $orderMembership  = $order->getMembership() + 1;
//        
//        if($membership == $orderMembership || $membership == MembershipType::STANDART)
//        {
//            $membershipName = MembershipType::getReadableValue($membership);
//
//            if($membership == MembershipType::STANDART){
//                //var_dump($order->getMembership() + 1);die();
//                $membershipName = MembershipType::getReadableValue($orderMembership);
//            }
//
//            $membershipPrice = $this->getParameter($membershipName . 'MembershipPrice');
//            //var_dump($membershipPrice);die();
//            return $order->getDaysCount() * $membershipPrice;        
//        }                
//    }

    /**
     * @FW\Route("/places/{category}", defaults={"category" = null}, name="places_list")
     * @FW\Template        
     */
    public function listAction(Request $request, $category)
    {           
//        $em = $this->getDoctrine()->getManager();
//                
//        $filterForm = $this->get('form.factory')->createNamed('', new PlaceFilterType());
//        $places = $em->getRepository(Place::class)->getPlaces();
//            
//            $filterForm->handleRequest($request);           
//            if ($filterForm->isValid()) {
//                $places = $em->getRepository(Place::class)->filter($filterForm->getData());
//            }                    
//        $filterForm = $filterForm->createView();    
//        if($request->isXmlHttpRequest()){
//            return $this->render('KromboxMainBundle:Place/partial:placesList.html.twig', array(
//                'places' => $places,
//                'form' => $filterForm,
//            ));
//        }    
//
//        return compact('places','filterForm');
        
               
        
        
        
        /////////test///////////////
//        $placeSearch = new PlaceSearch();
//        
//        $filterForm = $this->get('form.factory')
//            ->createNamed(
//                '',
//                'place_search_type',
//                $placeSearch,
//                array(
//                    'action' => $this->generateUrl('places_list', ['category' => $category]),
//                    'method' => 'GET'
//                )
//            );
//        
//        $places = $em->getRepository(Place::class)->getPlaces();
//        return $this->render('KromboxMainBundle:Place:list.html.twig',array(
//            'places' => $places,
//            'filterForm' => $filterForm->createView(),
//        ));
//        
//        
         // index
    $search = $this->get('fos_elastica.index.app.place');


    $query = new \Elastica\Query\MatchAll();

//    $elasticaQuery = new \Elastica\Query();
//    $elasticaQuery->setQuery($query);
//    $elasticaQuery->setSize(550);
//
//    $elasticaAggreg= new \Elastica\Aggregation\Terms('category');
//    $elasticaAggreg->setField('place.categories.slug');
//    $elasticaAggreg->setSize(550);
//    
//    $elasticaAggreg2= new \Elastica\Aggregation\Terms('service');
//    $elasticaAggreg2->setField('place.services.slug');
//    $elasticaAggreg2->setSize(550);
//
//    $elasticaQuery->addAggregation($elasticaAggreg);
//    $elasticaQuery->addAggregation($elasticaAggreg2);
    
//    $boolQuery = new \Elastica\Query\Bool();
//    
//    $queryStatus = new \Elastica\Query\Match();
//    $queryStatus->setFieldQuery('place.status', StatusType::VALIDATED);
//    $boolQuery->addMust($queryStatus);
       
//    $queryCategories = new \Elastica\Query\Terms();
//    $queryCategories->setTerms('place.categories.slug', ['pizza']);
//    $boolQuery->addShould($queryCategories);            

//    $elasticaQuery = new \Elastica\Query();
//    $elasticaQuery->setQuery($query);
//    $elasticaQuery->setSize(550);

//    $elasticaAggreg = new \Elastica\Aggregation\Terms('category');
//    $elasticaAggreg->setField('place.categories.slug');
//    $elasticaAggreg->setSize(550);
//    
//    $elasticaAggreg2 = new \Elastica\Aggregation\Terms('service');
//    $elasticaAggreg2->setField('place.services.slug');
//    $elasticaAggreg2->setSize(550);

    //$elasticaAggreg->setOrder('_count', 'desc');

    //$elasticaQuery->addAggregation($elasticaAggreg);
    //$elasticaQuery->addAggregation($elasticaAggreg2);
    
//    $sort = array( 
//            "_script" => array( 
//                'script' => "place.membershipSubscriptions.membership.score", 
//                'type' => 'number',
//                'order' => 'desc'
//            ) 
//        );
    //$boolQuery->setSort($sort);
//$query->setSort($sort); 
    //$elasticaQuery->setSort($sort);
//    $elasticaQuery->addSort(
//    ['place.membershipSubscriptions.membership.score' => 
//        ['order' => 'asc']
//    ]);
    //$elasticaQuery->addSort($elasticaAggreg2);
    //var_dump($elasticaQuery->getAggregations());die();
    // ResultSet
    //var_dump($this->find($elasticaQuery));die();
    
//    $boolFilter = new \Elastica\Filter\Bool();        
//    $active = new \Elastica\Filter\Term(['place.membershipSubscriptions.status' => MembershipStatusType::ACTIVE]);
//    $boolFilter->addMust($active);
//        
//    $filtered = new \Elastica\Query\Filtered($boolQuery, $boolFilter);
//    $query = \Elastica\Query::create($filtered);
//
//    //$places = $search->findPaginated($elasticaQuery);
//    var_dump(json_encode($query->getQuery(), JSON_PRETTY_PRINT));die();
    
    //var_dump($elasticaResultSet);die();
    //$elasticaAggregs = $elasticaResultSet->getAggregations();
    
    //var_dump($elasticaResultSet->getResponse(), get_class_methods($elasticaResultSet));die();
        ############################################################
        $placeFilter = new PlaceFilter();
        $elasticaManager = $this->container->get('fos_elastica.manager');

        $filterForm = $this->get('form.factory')
            ->createNamed(
                '',
                new PlaceFilterType(),
                $placeFilter,
                array(
                    'action' => $this->generateUrl('places_list', ['category' => $category]),
                    'method' => 'GET'
                )
            );
        $filterForm->handleRequest($request);
        //if ($filterForm->isValid()) {
            //$placeSearch = $filterForm->getData();                
            
            //$places = $elasticaManager->getRepository(Place::class)->search($category, $placeSearch);
//            $places = $elasticaManager->getRepository(Place::class)->facet($category, $placeSearch);            
            $places = $elasticaManager->getRepository(Place::class)->facet($category, $placeFilter);            
            $filterFacet = $places->getAdapter()->getAggregations();
            
            //var_dump($places->getAdapter()->getAggregations());
            //var_dump($places);die();
            //var_dump($places);die();
            $helper = $this->container->get('krombox.business_hours_helper');
            
//            if($placeSearch->getIsWorkingNow()){
//                /*TODO make external helper*/
//                foreach($places as $key => $place){
//                    if(!$helper->isWorkingNow($place)){                    
//                        unset($places[$key]);                    
//                    }
//                }
//            }
        if($request->isXmlHttpRequest()){
            return $this->render('KromboxMainBundle:Place/partial:placesList.html.twig', array(
                'places' => $places,
                'filterForm' => $filterForm->createView(),
                'filterFacet'     => $filterFacet
            ));
        }        
        //var_dump($filterFacet);
        return $this->render('KromboxMainBundle:Place:list.html.twig',array(
            'places' => $places,
            'filterForm' => $filterForm->createView(),
            'filterFacet'     => $filterFacet
        ));
    }
    
    /**
     * @FW\Route("/place/{slug}/edit", name="place_edit")     
     * @FW\Security("is_granted('edit', place)")
     * @FW\Template
     */
    public function editAction(Place $place, Request $request){                
        $em = $this->getDoctrine()->getManager();
            
        $form = $this->createForm(new PlaceType(), $place);
        $logoForm = $this->createForm(new PlaceImageType(), $place->getLogo());
        $form->handleRequest($request);

        if ($form->isValid()) {                                        
            $em->persist($place);            
            $em->flush();

            return $this->redirectToRoute('user_places');
        }
        
        //return ['form' => $form->createView(), 'place' => $place];
        return ['form' => $form->createView(), 'place' => $place,'logo_form' => $logoForm->createView()];                   
    }
    
    /**
    * @FW\Route("/filter-values/{place}", name="filter_values_by_categories", options={"expose"=true})
    */
    public function getPlaceValuesByCategories(Place $place, Request $request){
        $categories = $request->request->get('categories');
        //var_dump($categories);die();
        $result = $this->getDoctrine()->getManager()
                ->getRepository(\Krombox\MainBundle\Entity\PlaceFilterKind::class)->getPlaceFilterKindByCategories($categories);
        $filters = [];
        foreach ($result as $k => $v){
            foreach ($v->getPlaceFilterValues() as $pfv){
                $filters[$v->getName()][] = $pfv->getName();
            }
             //r->getPlaceFilterValues()->toArray();
        }
        var_dump($filters);die();
        return new JsonResponse($result);
    }

        protected function saveImages(Place $place)
    {
        $manager = $this->get('oneup_uploader.orphanage_manager')->get('gallery');

        // get files
        $files = $manager->getFiles();
        
        foreach ($files as $file){
            var_dump($file);die();
        }
        
        var_dump($files);die();
        
    }

        public function placesByCategoryAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $places = $em->getRepository(Place::class)->getPlaces($slug); 
        
        return $this->render('KromboxMainBundle:User:places.html.twig', array('places' => $places));
    }
    
    /**
     * @FW\Route("/rate/place/{hash}", name="place_rate")
     * @FW\Method("post")
     * @FW\Security("is_granted('rate', place)")          
     */
    public function ratePlaceAction(Place $place, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        if(!$request->request->has('rating'))
            throw new \Exception('rating parameter must be provided');
        
        $rate = $request->request->get('rating');
        
        //IS INT???
        if($rate <= Constants::RATING_MAX){
            $rating = new Rating();
            $rating->setPlace($place);
            $rating->setUser($this->getUser());
            $rating->setRate($rate);
            
            $em->persist($rating);
            $em->flush();
        }
        
        return new JsonResponse(['rating' => $place->getRating()], 200);
    }
    
    /**
     * @FW\Route("/unrate/place/{hash}", name="place_unrate")
     * @FW\Method("post")
     * @FW\Security("is_granted('unrate', place)")          
     */
    public function unRatePlaceAction(Place $place, Request $request)
    {
        $em = $this->getDoctrine()->getManager();                                
        $rating = $em->getRepository(Rating::class)->findOneBy(['place' => $place, 'user' => $this->getUser()]);
        
        $em->remove($rating);
        $em->flush();
        
        return new JsonResponse(['rating' => $place->getRating()], 200);
    }
    

    



    /**     
     * @FW\Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    /*public function placeLikeUpAction($hash){
        $em = $this->getDoctrine()->getManager();            
        $place = $em->getRepository(Place::class)->findOneBy(['hash' => $hash]);
                
        if(!$place)
            return new JsonResponse (['status' => 404, 'message' => 'error']);
        
        $like = $em->getRepository(Like::class)->findOneBy(['user' => $this->getUser(), 'place' => $place]);
        
        if($like){
            $like->setRate(LikeType::UP);
            $em->persist($like);
        }
        else{
            $newLike = new Like();
            $newLike->setUser($this->getUser())
                    ->setPlace($place)
                    ->setRate(LikeType::UP)
            ;
            $em->persist($newLike);
        }                
        
        $em->flush();                       
        
        $this->recalculateLikePersent($place);
        
        return new JsonResponse (['status' => 200, 'message' => 'success']);
    }
    
    public function placeLikeDownAction($hash){
        $em = $this->getDoctrine()->getManager();
               
        $place = $em->getRepository(Place::class)->findOneBy(['hash' => $hash]);
                
        if(!$place)
            return new JsonResponse (['status' => 404, 'message' => 'error']);
        
        $like = $em->getRepository(Like::class)->findOneBy(['user' => $this->getUser(), 'place' => $place]);
        
        if($like){
            $like->setRate(LikeType::DOWN);
            $em->persist($like);
        }
        else{
            $newLike = new Like();
            $newLike->setUser($this->getUser())
                    ->setPlace($place)
                    ->setRate(LikeType::DOWN)
            ;
            $em->persist($newLike);
        }                
        
        $em->flush();                       
        
        $this->recalculateLikePersent($place);
        
        return new JsonResponse (['status' => 200, 'message' => 'success']);
    }
    public function placeLikeUnsetAction($hash){
        $em = $this->getDoctrine()->getManager();
          
        $place = $em->getRepository(Place::class)->findOneBy(['hash' => $hash]);
                
        if(!$place)
            return new JsonResponse (['status' => 404, 'message' => 'error']);
        
        $like = $em->getRepository(Like::class)->findOneBy(['user' => $this->getUser(), 'place' => $place]);
        
        if($like)            
            $em->remove($like);
        
        $em->flush();
        
        $this->recalculateLikePersent($place);
        
        return new JsonResponse (['status' => 200, 'message' => 'success']);
    }*/        
    
    /**
     * @FW\Route("image/place/save")
     * @FW\Method("post")
     * @FW\Security("is_granted('ROLE_USER')")         
     */
    public function placeImageSaveAction(Request $request){
        /*TODO ADD VALIDATION*/
        $em = $this->getDoctrine()->getManager();
        $file = $request->files->get('file');        
        //var_dump($file);die();
        $placeImage = new PlaceHallImage();        
        $placeImage->setImage($file);
        //var_dump($placeImage->getImage());die();
        $upload_handler = $this->container->get('vich_uploader.upload_handler');
        $upload_handler->upload($placeImage, 'image');
        //var_dump($placeImage);die();
        $em->persist($placeImage);
        $em->flush();               
                
        return new JsonResponse (['status' => 200, 'id' => $placeImage->getId()]);        
    }
    
//    private function redirectToStep($step)
//    {                
//        if ($step > 6 && $step < 10) {
//            // If the appStep is in pre-approval range (7-9), redirect to Thank You page.
//            //return $this->redirectToRoute('sittr_new_sittr_6');
//        } else {//echo $step;
//            return $this->redirectToRoute('place_new_step_' . $step);
//        }
//    }
    
    //    protected function checkAppStep($pos, $forced = false)
//    {                   
//        $appStep = $this->getAppStep();
//        
//        if ($appStep >= $pos) {
//            return;
//        } elseif ($appStep < $pos) {                       
//            return $this->redirectToStep($appStep ? $appStep : 1);
//        }
//    }
//    
//    protected function getAppStep()
//    {
//        $session = new Session();        
//        return $session->get('appStep');
//    }
//
//    /**
//     * @FW\Route("/place/new", name="place_new")
//     * @FW\Security("is_granted('ROLE_USER')")          
//     */
//    public function createPlaceAction()
//    {        
//        $app_step = $this->getAppStep();               
//        return $this->redirectToStep($app_step ? $app_step : 1);
//    }
//    
//    /**
//     * @FW\Route("/place/new/1", name="place_new_step_1")
//     * @FW\Security("is_granted('ROLE_USER')")
//     * @FW\Template               
//     */
//    public function oneAction(Request $request)
//    {
//        $this->checkAppStep(1);
//        $session = new Session();
//        //$session->clear();
//        $em = $this->getDoctrine()->getManager();                
//        
//        $place = new Place();
//        
//        if($session->has('placeId')){
//            $place = $em->getRepository(Place::class)->findOneBy(['id' => $session->get('placeId')]);       
//        }
//                                                          
//        $form = $this->get('form.factory')->create('place_profile', $place);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $place->setUser($this->getUser());//move to listener on pre persist
//            $em->persist($place);            
//            $em->flush();
//                                
//            $session->set('appStep', 2);
//            $session->set('placeId', $place->getId());
//
//            return $this->redirectToStep(2);
//        }
//        
//        return ['form' => $form->createView()];
//    }
//    
//    /**
//     * @FW\Route("/place/new/2", name="place_new_step_2")
//     * @FW\Security("is_granted('ROLE_USER')")
//     * @FW\Template          
//     */
//    public function twoAction(Request $request)
//    {
//        if ($result = $this->checkAppStep(2, true)) {
//            return $result;
//        }
//        $session = new Session();        
//        $em = $this->getDoctrine()->getManager();                
//                
//        if($session->has('placeId')){
//            $place = $em->getRepository(Place::class)->findOneBy(['id' => $session->get('placeId')]);       
//        }                
//                      
//        $form = $this->get('form.factory')->create('place_business_hours', $place);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {            
//            $place->setUser($this->getUser());//move to listener on pre persist
//            $em->persist($place);            
//            $em->flush();
//
//            $session->set('appStep', 3);            
//            return $this->redirectToStep(3);
//        }
//        
//        return ['form' => $form->createView()];
//    }
//    
//    /**
//     * @FW\Route("/place/new/3", name="place_new_step_3")
//     * @FW\Security("is_granted('ROLE_USER')")               
//     */
//    public function threeAction(Request $request)
//    {
//        if ($result = $this->checkAppStep(3, true)) {
//            return $result;
//        }
//        $session = new Session();        
//        $em = $this->getDoctrine()->getManager();                
//        echo 'redirect?';        
//        if($session->has('placeId')){
//            $place = $em->getRepository(Place::class)->findOneBy(['id' => $session->get('placeId')]);       
//        }                
//                      
//        $form = $this->get('form.factory')->create('place_services', $place);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {            
//            $place->setUser($this->getUser());//move to listener on pre persist
//            $em->persist($place);            
//            $em->flush();
//
//            $session->set('appStep', 3);            
//            return $this->redirectToStep(2);
//        }
//        
//        return ['form' => $form->createView()];
//    }
}
