<?php

namespace Krombox\MainBundle\Repository\Search;

use FOS\ElasticaBundle\Repository;
//use Krombox\MainBundle\Model\PlaceSearch;
//use Krombox\MainBundle\DBAL\Types\StatusType;

class MyTagRepository extends Repository
{
    public function autocomplete($term, $limit = 10)
    {        
        if($term != null){                    
            $prefixQuery = new \Elastica\Query\Prefix();
            $prefixQuery->setPrefix('tag.name', $term);
        }
        else{
            $prefixQuery = new \Elastica\Query\MatchAll();
        }
        
        $baseQuery = $prefixQuery;                
        
        $filtered = new \Elastica\Query\Filtered($baseQuery);

        $query = \Elastica\Query::create($filtered);        
        
        return $this->find($query, $limit);
    }

}