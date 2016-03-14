<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Krombox\MainBundle\DBAL\Types\CategoryType;
use Krombox\MainBundle\Entity\City;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\Category;
use Krombox\MainBundle\Form\Type\CityType;
use Krombox\MainBundle\Form\Model\PlaceFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;

class HomeController extends Controller 
{
    /**
     * @FW\Route("/", name="home")
     * @FW\Template()          
     */
    public function indexAction(Request $request)
    {   
        $city = new City();
        $cityForm = $this->createForm(new CityType(), $city);        
        $cityForm->handleRequest($request);

        if ($cityForm->isValid()) {            
            return $this->redirectToRoute('location_map', ['slug' => $city->getSlug()]);
        }
        
        return ['cityForm' => $cityForm->createView()];
    }
    
    /**
     * @FW\Route("/{slug}/map", name="location_map", options={"expose"=true})
     * @FW\Template()          
     */
    public function locationMapAction(City $city)
    {   
        $elasticaManager = $this->container->get('fos_elastica.manager');
        $em = $this->getDoctrine()->getManager();
        
        $placeFilter = new PlaceFilter();
        $placeFilter->setAggregations(['categories']);
        $placeFilter->setCity($city);
        
        $places = $elasticaManager->getRepository(Place::class)->facet($placeFilter);
        $filterFacet = $places->getAdapter()->getAggregations();
        
        $placeCategories = $em->getRepository(Category::class)->getCategoriesByType(CategoryType::PLACE);
        
        return ['placeCategories' => $placeCategories, 'filterFacet' => $filterFacet, 'city' => $city];        
    }
    
    /**
     * @FW\Route("/checkit", name="checkit", options={"expose"=true})
     * @FW\Template()          
     */
    public function checkitAction(Request $request)
    {
        $city = $request->query->get('city');
        
        return $this->redirectToRoute('location_map', ['slug' => $city]);
    }
}
