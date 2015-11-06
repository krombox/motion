<?php

namespace Krombox\MainBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Krombox\CommonBundle\Model\RateableInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Krombox\Constants;

/**
 * @DI\Service("krombox.filter_manager")
 */
final class FilterManager
{
    private $em;
    
    private $class;

    /**
     * @DI\InjectParams({
           "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;
    }
    
    public function setDataClass($class)
    {
        $this->class = $class;
    }

    public function getFilterValuesAssocList()
    {
        $results = $this->em->getRepository($this->class)->get();
        $filterValuesAssocList = [];
        
        foreach ($results as $pfv)
        {
            $filterValuesAssocList[$pfv->getSlug()] = $pfv->getPlaceFilterKind()->getSlug();
        }
        
        return $filterValuesAssocList;        
    }
    
    public function getFiltersChoiceList($criteria)
    {
        $results = $this->em->getRepository($this->class)->get($criteria);        
        $choices = [];
        
        foreach ($results as $pfv)
        {
            $choices[$pfv->getSlug()] = $pfv->getName();
        }
        return $choices;        
    }
    
    public function getFilterOptions(Request $request)
    {
        $criteria = ['categories' => $request->request->get('categories')];
        $categoriesValues = $this->em->getRepository(\Krombox\MainBundle\Entity\PlaceFilterValue::class)->get($criteria);
        
        $filters = [];
        if($request->request->has('place')){
            $criteria['place'] = $request->request->get('place');
            $placeValues = $this->em->getRepository(\Krombox\MainBundle\Entity\PlaceFilterValue::class)->get($criteria);            
            //unset($criteria['place']);
            //$categoriesValues = $this->em->getRepository(\Krombox\MainBundle\Entity\PlaceFilterValue::class)->get($criteria);
            
            foreach($categoriesValues as $k => $cv){
                $filters[$cv->getPlaceFilterKind()->getSlug()][$k]['object'] = $cv;//TODO getSlug()                
                $filters[$cv->getPlaceFilterKind()->getSlug()][$k]['isChecked'] = in_array($cv, $placeValues) ? true : false;
            }            
        } else {
            //$categoriesValues = $this->em->getRepository(\Krombox\MainBundle\Entity\PlaceFilterValue::class)->get($criteria);
            foreach ($categoriesValues as $k => $cv){
                $filters[$cv->getPlaceFilterKind()->getSlug()][$k]['object'] = $cv;//TODO getSlug()                
                $filters[$cv->getPlaceFilterKind()->getSlug()][$k]['isChecked'] = false;
            }
        }
        return $filters;
    }
    
    public function getFilters($place)
    {
        $placeValues = $this->em->getRepository(\Krombox\MainBundle\Entity\PlaceFilterValue::class)->get(['place' => $place->getId()]);
        $filters = [];
        foreach ($placeValues as $k => $pv){
            $filters[$pv->getPlaceFilterKind()->getSlug()][$k] = $pv;            
        }
        return $filters;
    }
} 
