<?php

namespace Krombox\MainBundle\Twig;

use Krombox\MainBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * adds some nice features to more easy twig templating
 *
 * @DI\Service("krombox.place_extension")
 * @DI\Tag("twig.extension")
 */
class PlaceExtension extends \Twig_Extension
{            
    private $container;
    
    private $busineesHoursHelper;
    
    private $filtermanager;

    /**
     * @DI\InjectParams({     
     *     "container" = @DI\Inject("service_container"),
     *     "businessHoursHelper" = @DI\Inject("krombox.business_hours_helper"),
     *     "filterManager" = @DI\Inject("krombox.filter_manager")
     * })
     */
    public function __construct($container, $businessHoursHelper, $filterManager)
    {        
        $this->container = $container;
        $this->busineesHoursHelper = $businessHoursHelper;
        $this->filtermanager = $filterManager;
    }

    public function getFunctions()
    {
        return array(            
            'businessHoursSheet' => new \Twig_Function_Method($this, 'businessHoursSheet'),
            'isWorkingNow' => new \Twig_Function_Method($this, 'isWorkingNow'),
            'closeIn' => new \Twig_Function_Method($this, 'closeIn'),
            'openIn' => new \Twig_Function_Method($this, 'openIn'),
            'placeCount' => new \Twig_Function_Method($this, 'placeCount'),
            'filters' => new \Twig_Function_Method($this, 'filters')
        );
    }    
    
    public function closeIn($place){        
        return $this->busineesHoursHelper->closeIn($place);
    }
    
    public function openIn($place){        
        return $this->busineesHoursHelper->openIn($place);
    }
    
    public function businessHoursSheet($place, $withException = true){        
        return $this->busineesHoursHelper->getBusinessHoursSheet($place, $withException);
    }
    
    public function isWorkingNow($place){        
        return $this->busineesHoursHelper->isWorkingNow($place);
    }
    
    public function placeCount($form)
    {
        $filters = [];
        foreach ($form->children as $k => $child)
        {
            foreach ($child as $v){
                $vars = $v->vars;
                if($vars['data']){
                    $filters[$k][] = $vars['value'];
                } 
                    
            }
        }
//        var_dump($filter);die();
//        return;
        $em = $this->container->get('doctrine.orm.entity_manager');
//        if(!in_array($current, $selected)){
//                array_push($selected, $current);
//        }
        return $em->getRepository(Place::class)->filterCount($filters);
    }
    
    public function filters($place)
    {
        return $this->filtermanager->getFilters($place);
    }

    public function getName()
    {
        return get_class($this);
    }

}