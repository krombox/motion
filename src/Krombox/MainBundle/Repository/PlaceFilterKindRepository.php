<?php

namespace Krombox\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;

/**
 * PlaceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlaceFilterKindRepository extends EntityRepository
{
    
    public function queryPlaceFilterKindByCategories($categories)
    {                
        $qb = $this->createQueryBuilder('pfk')
                ->innerJoin('pfk.categories', 'pfkc')
                ->where('pfkc.id IN(:categories)')                
                ->setParameter('categories', $categories)                
        ;                
                                
        return $qb;
    }        
    
    public function getPlaceFilterKindByCategories($categories)
    {                
        $qb = $this->queryPlaceFilterKindByCategories($categories);                                                
        $query = $qb->getQuery();                
        
        try {
            $result = $query->getResult();                        
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
                          
        return $result;
    }        
}
