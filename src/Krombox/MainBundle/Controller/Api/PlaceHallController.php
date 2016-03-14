<?php

namespace Krombox\MainBundle\Controller\Api;

use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Util\Codes;
//use Nelmio\ApiDocBundle\Annotation as Doc;
use FOS\RestBundle\Controller\FOSRestController as RestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Krombox\MainBundle\Form\Type\PlaceHallImageType;
use Krombox\MainBundle\Entity\Hall;
use Krombox\MainBundle\Entity\PlaceHallImage;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
class PlaceHallController extends RestController
{
    /**
     * @View()
     */
    public function postPlaceHallImageAction()
    {
        $em = $this->getDoctrine()->getManager();        
        $placeImage = new PlaceHallImage();                
        $form = $this->get('form.factory')->createNamed(null, new PlaceHallImageType(), $placeImage);        
        
        if($form->handleRequest($this->getRequest()) && $form->isValid()){                                            
            $em->persist($placeImage);
            $em->flush();
            return ['status' => 200, 'id' => $placeImage->getId()];
        }else{
            return $form;
        }                                        
    }        
}
