<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\MyTag;
use Krombox\MainBundle\Entity\Event;
use Krombox\MainBundle\Form\Type\EventType;
//use Krombox\MainBundle\DBAL\Types\EventType;
//use Krombox\MainBundle\Form\Type\Filter\PlaceFilterType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;

/**
 * Place controller.
 *
 */
class MyTagController extends Controller
{

   public function getFeedAction(Request $request){
       $em = $this->get('doctrine.orm.entity_manager');
       
       $elasticaManager = $this->container->get('fos_elastica.manager');
       
       $term = $request->get('term');
       $tags = $elasticaManager->getRepository(MyTag::class)->autocomplete($term);
       
       $result = [];
       
       foreach ($tags as $tag){
           $t['value'] = $tag->getName();
           $t['label'] = $tag->getName();
           $result[] = $t;
       }
       
       return new JsonResponse($result, 200);
   }
        
}
