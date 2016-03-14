<?php

namespace Krombox\MainBundle\Repository\Search;

use FOS\ElasticaBundle\Repository;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
class CityRepository extends Repository
{
    public function autocomplete($term, $limit = 10)
    {        
        $fuzzyQuery = new \Elastica\Query\FuzzyLikeThis();
        $fuzzyQuery->addFields(['nameTranslatableRU', 'nameTranslatableEN']);
        $fuzzyQuery->setLikeText($term);
        
        //$baseQuery = $prefixQuery;                
        
        $filtered = new \Elastica\Query\Filtered($fuzzyQuery);

        $query = \Elastica\Query::create($filtered);        
        
        return $this->find($query, $limit);
    }
}
