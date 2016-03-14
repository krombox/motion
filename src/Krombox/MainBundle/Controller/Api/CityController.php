<?php

namespace Krombox\MainBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Krombox\MainBundle\Entity\City;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController as RestController;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
class CityController extends RestController
{
    /**     
     * @View()          
     */
    public function getCitiesAutocompleteAction(Request $request)
    {
        $query = $request->query->get('q');        
        //$em = $this->getDoctrine()->getManager();
        $elasticaManager = $this->container->get('fos_elastica.manager');
        
        $data = $elasticaManager->getRepository(City::class)->autocomplete($query);
        //var_dump($data);die();
        $factory = $this->get('krombox.city.wrapper_factory');
        $results = [];
        
        foreach ($data as $val){
            $results[] = $factory->wrap($val);
        }
        
        $view = $this->view($results, 200);
        
        //return $this->handleView($view);
        return $results;
        
        //var_dump(json_encode($results));die();
    }       
}
