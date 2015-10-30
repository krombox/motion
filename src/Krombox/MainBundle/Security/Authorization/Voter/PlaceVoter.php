<?php

namespace Krombox\MainBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation as DI;
//use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
//use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
//use Symfony\Component\Security\Core\User\UserInterface;
use Krombox\CommonBundle\Security\AbstractVoter;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service(public=false)
 * @DI\Tag("security.voter")
 */
class PlaceVoter extends AbstractVoter
{    

    const DATA_CLASS = Place::CLASS;

    static $attributes = array(        
        self::VIEW,
        self::EDIT,
        self::RATE,
        self::UNRATE
    );
    
    const RATE = 'rate';
    const UNRATE = 'unrate';
    
    
    /**
     * @param  string $attribute
     * @return int
     */
    public function dispatch($attribute)
    {
        $user   = $this->getUser();
        if(!is_object($user)) return self::ACCESS_DENIED;
            
        $entity = $this->getEntity();

        $isAdmin           = $user->hasRole('ROLE_ADMIN');        
        $isOwner           = $entity->getUser() == $user;        
        
        switch ($attribute) {             
//            case self::VIEW:
//                return $this->checkIf(
//                    $isAdmin || $isProvider || $isClient
//                );            
            case self::EDIT:
                return $this->checkIf(
                    $isAdmin || $isOwner
                );            
            case self::RATE:
                return $this->checkIf(
                    !$this->isRated($entity)
                );
            case self::UNRATE:
                return $this->checkIf(
                    $this->isRated($entity)
                );
        }

        return self::ACCESS_DENIED;
    }
    
    private function isRated(Place $entity)
    {   
        $user = $this->getUser();
        
        foreach ($user->getRatings() as $rating){
            if($rating->getPlace() == $entity) return true;                    
        }
        
        return false;        
    }
}

