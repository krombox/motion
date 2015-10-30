<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Security\Core\SecurityContext;
use Krombox\CommonBundle\Wrapper\AbstractWrapperFactory;
use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service("krombox.place.wrapper_factory")
 * @DI\Tag("wrapper_factory", attributes = {"class" = Place::class})
 */
class PlaceWrapperFactory extends AbstractWrapperFactory
{

    private $user;    
    private $imageResolver;    

    /**
     * @DI\InjectParams({     
     *     "security"       = @DI\Inject("security.context"),
     *     "imageResolver"  = @DI\Inject("krombox.copy_com_image_resolver")
     * })
     */
    public function __construct(SecurityContext $security, $imageResolver)
    {        
        $this->setUser($security->getToken()->getUser());
        $this->imageResolver = $imageResolver;
    }
    
    public function setUser($user){
        $this->user = $user;
    }

    /**
     * @param User $user
     * @return DateWrapper
     */
    public function wrap($user)
    {
        $p = $this->getProvider();
        $isCurrent = $this->user == $user;        

        return new UserWrapper([
            'id' => $user->getId(),
            //'email' => $isCurrent ? $user->getEmail() : null,
//            'apikey' => $isCurrent ? $user->getApiKey() : null,
//            'role' => $isCurrent ? $user->getRole() : null,
//            'firstname' => $user->getFirstname(),
//            'lastname' => $user->getLastname(),
//            'avatar' => $this->wrapAvatar($user),
//            'address' => $this->bindAddress($user),
//            'appStep' => $isCurrent ? $user->getAppStep() : null,
//            'educations' => $p->wrap($user->getEducation()),
//            'refreqs' => $p->wrap($user->getRefreqs()),
//            'dob' => $user->getDob(),
//            'bio' => $user->getBio(),
//            'gender' => $user->getGender(),
//            'phone' => $user->getPhone(),
//            'skills' => $p->wrap($user->getSkills()),
//            'services' => $p->wrap($user->getServices()),
//            'allowedDistance' => ($isCurrent && $user->getProvider()) ? $user->getProvider()->getAllowedDistance() : null,
//            'trust' => $isCurrent ? $p->wrap($user->getTrust()) : null,
//            'feedbacks' => $feedbacks,
//	    'payoutMethod' => ($isCurrent && $user->getPayoutMethod()) ? $user->getPayoutMethod()->getAddress() : null,
//            'timezone' => $user->getTimeZone()
        ]);
    }

    public function wrapLogo($place)
    {   
        if($place->getLogo()->getFileName() == null)
            return 'http://dogvacay.com/img/default_home.jpg';
        
        return $this->imageResolver->getImagePath($place->getLogo()->getFileName(), 'place_image');
    }

    private function bindAddress(User $user){
        $isCurrent = $this->user == $user;
        $a = $user->getAddress();
        if(!$a)
            return [];
        if($isCurrent){
            return [
                'formatted' => $a->getFormatted(),
                'streetNo' => $a->getStreetNo(),
                'route' => $a->getRoute(),
                'city' => $a->getCity(),
                'state' => $a->getState(),
                'country' => $a->getCountry(),
                'gId' => $a->getGId(),
                'lat' => $a->getLat(),
                'lng' => $a->getLng(),
                'supported' => ($a->getCountry() == 'AU')
            ];
        }else{
            return [
                'city' => $a->getCity(),
                'state' => $a->getState(),
                'country' => $a->getCountry(),
            ];
 
        }
    }

    private function bindSkills($obj){
        return $obj;
    }
    private function bindServices($obj){
        return $obj;
    }

    public function setShowFeedbacks($bool){
        $this->showFeedbacks = $bool;
    }
}
