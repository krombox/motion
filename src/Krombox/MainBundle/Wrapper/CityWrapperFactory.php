<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\SecurityContext;
use Krombox\CommonBundle\Wrapper\AbstractWrapperFactory;
use Krombox\MainBundle\Entity\City;

/**
 * @DI\Service("krombox.city.wrapper_factory")
 * @DI\Tag("wrapper_factory", attributes = {"class" = City::class})
 */
class CityWrapperFactory extends AbstractWrapperFactory
{

    private $user;    

    /**
     * @DI\InjectParams({     
     *     "security"       = @DI\Inject("security.context")     
     * })
     */
    public function __construct(SecurityContext $security)
    {        
        $this->setUser($security->getToken()->getUser());        
    }
    
    public function setUser($user){
        $this->user = $user;
    }

    /**
     * @param City $city
     * @return CityWrapper
     */
    public function wrap($city)
    {        
        //$p = $this->getProvider();
        //$isCurrent = $this->user == $user;                
        return new CityWrapper([
            'id' => $city->getId(),
            'name' => $city->getName(),
            'slug' => $city->getSlug()
        ]);
    }  
}
