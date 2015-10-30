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

    /**
     * @DI\InjectParams({     
     *     "container" = @DI\Inject("service_container"),
     *     "businessHoursHelper" = @DI\Inject("krombox.business_hours_helper")
     * })
     */
    public function __construct($container, $businessHoursHelper)
    {        
        $this->container = $container;
        $this->busineesHoursHelper = $businessHoursHelper;
    }

    public function getFunctions()
    {
        return array(            
            'businessHoursSheet' => new \Twig_Function_Method($this, 'businessHoursSheet'),
            'isWorkingNow' => new \Twig_Function_Method($this, 'isWorkingNow'),
            'placeCount' => new \Twig_Function_Method($this, 'placeCount')
        );
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

    public function getName()
    {
        return get_class($this);
    }

}