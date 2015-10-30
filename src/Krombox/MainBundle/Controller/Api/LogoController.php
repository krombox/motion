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
use Krombox\MainBundle\Entity\PlaceImage;

class LogoController extends RestController
{   
    public function getPlaceLogoAction(Place $place)
    {
        $path = $this->get('krombox.place.wrapper_factory')->wrapLogo($place);                
        return $path;
    }
    
    /**
     * @View()
     */
    public function postPlaceLogoAction(Place $place)
    {
        $em = $this->getDoctrine()->getManager();
        $placeImage = new PlaceImage();
        $placeImage->setPlace($place);
        if($place->getLogo())
            $placeImage = $place->getLogo();
                        
        $form = $this->createForm(LogoType::TYPE_NAME, $placeImage, ['method' => 'POST']);
        //var_dump($form->isValid(), $form->getErrorsAsString());die();
        //var_dump($this->getRequest()->request->all());
        if($form->handleRequest($this->getRequest()) && $form->isValid()){
            
            //$place->setLogo($placeImage);
            $em->persist($placeImage);
            $em->flush();
            return $this->getPlaceLogoAction($place);
        }else{
            return $form;
        }
    }
}
