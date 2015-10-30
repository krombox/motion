<?php

namespace Krombox\MainBundle\Repository\Search;

use FOS\ElasticaBundle\Repository;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\MainBundle\DBAL\Types\LikeType;

class PlaceRepository extends Repository
{
    public function search($category, PlaceSearch $placeSearch)
    {
        // we create a query to return all the articles
        // but if the criteria title is specified, we use it
        
        $boolQuery = new \Elastica\Query\Bool();
        /*Fetch only VALIDATED place*/        
        $queryStatus = new \Elastica\Query\Match();
        $queryStatus->setFieldQuery('place.status', StatusType::VALIDATED);
        $boolQuery->addMust($queryStatus);               
        
        //$qSort = new \Elastica\Query();
        //$qSort->setSort(['likes.rate' => ['nested_filter' => ['term' => ['likes.rate' => LikeType::UP]], 'order' => 'desc']]);        
        
//        $fnScoreQuery = new \Elastica\Query\FunctionScore();
//        $script = new \Elastica\Script('place.birthdayDiscount * 3');
//        $fnScoreQuery->addFunction('script_score', ['script' => "3 *4"]);                

        //$script->setScript('rate * 3');
        
        
        //$boolQuery->addMust($fnScoreQuery);
        
        if($category !== null){
            $queryCategory = new \Elastica\Query\Match();
            $queryCategory->setFieldQuery('place.categories.slug', $category);
            $boolQuery->addMust($queryCategory);
        }        
        
        if($placeSearch->getBirthdayDiscount() != null && $placeSearch->getBirthdayDiscount() > 0 ){
           $queryRange = new \Elastica\Query\Range();
           $queryRange->setParam('place.birthdayDiscount', ['gte' => 1]);
           $boolQuery->addMust($queryRange);
        }
        
        if(($placeSearch->getName() != null || $placeSearch->getCategories() != null ) && $placeSearch != null){
            
            if($placeSearch->getName() != null){
                $query = new \Elastica\Query\Match();
                $query->setFieldQuery('place.name', $placeSearch->getName());
                $query->setFieldFuzziness('place.name', 1);
                $query->setFieldMinimumShouldMatch('place.name', '10%');
                $boolQuery->addMust($query);
            }
            
            if($placeSearch->getCategories() != null){  
                foreach ($placeSearch->getCategories() as $cat){                
                    $categories[] = $cat->getName();                
                }
                $queryCategories = new \Elastica\Query\Terms();
                $queryCategories->setTerms('place.categories', $categories);
                $boolQuery->addShould($queryCategories);
            }
                                    
        } else {
            $query = new \Elastica\Query\MatchAll();
        }
         $baseQuery = $boolQuery;         

        // then we create filters depending on the chosen criterias
        $boolFilter = new \Elastica\Filter\Bool();        
        $active = new \Elastica\Filter\Term(['membershipSubscriptions.statusSubscription' => MembershipStatusType::ACTIVE]);
        $boolFilter->addMust($active);
        /*
            Dates filter
            We add this filter only the getIspublished filter is not at "false"
        */
//        if("false" != $articleSearch->getIsPublished()
//           && null !== $articleSearch->getDateFrom()
//           && null !== $articleSearch->getDateTo())
//        {
//            $boolFilter->addMust(new \Elastica\Filter\Range('publishedAt',
//                array(
//                    'gte' => \Elastica\Util::convertDate($articleSearch->getDateFrom()->getTimestamp()),
//                    'lte' => \Elastica\Util::convertDate($articleSearch->getDateTo()->getTimestamp())
//                )
//            ));
//        }
//
        // Published or not filter
        if($placeSearch->getIs24h() !== null && $placeSearch->getIs24h()){
                        //var_dump($placeSearch->getIs24h());die();
            $boolFilter->addMust(
                new \Elastica\Filter\Term(['is24h' => $placeSearch->getIs24h()])
                //new \Elastica\Filter\Term(['isWifi' => $placeSearch->getIsWifi()])    
            );                        
            
            //$boolFilter->addMust('is24h', $placeSearch->getIs24h());
        }
        
        if($placeSearch->getIsWifi() !== null && $placeSearch->getIsWifi()){
            $boolFilter->addMust(              
                new \Elastica\Filter\Term(['isWifi' => $placeSearch->getIsWifi()])    
            );
        }
        
        if($placeSearch->getIsDelivery() !== null && $placeSearch->getIsDelivery()){
            $boolFilter->addMust(              
                new \Elastica\Filter\Term(['isDelivery' => $placeSearch->getIsDelivery()])    
            );
        }
        
        //$boolFilter->addMust($active);
        
//        $boolFilter->addMust(              
//            new \Elastica\Filter\Term(['place.membershipSubscriptions.statusSubscription' => MembershipStatusType::ACTIVE])    
//        );
        
        
        //var_dump($boolFilter);die();
        //new \Elastica\Query\
        $filtered = new \Elastica\Query\Filtered($baseQuery, $boolFilter);
        $query = \Elastica\Query::create($filtered);
        
        $query->addSort(array('place.membershipSubscriptions.membership.score' => array(
            'order' => 'asc'            
        )));
        
        //$query->addSort(array('rating' => array('order' => 'desc')));
        
        var_dump(json_encode($query->getQuery()));die();
        return $this->find($query);
        //$qSort = \Elastica\Query::create($filtered);
        
        
        //$query = new \Elastica\Query();
        //$query->setQuery($filtered);
        //$query->setQuery($qSort);
        //$query->setQuery($fnScoreQuery);
        
        
        //$fnScoreQuery->setQuery($filtered);
        
        //$qSort->setQuery($filtered);

        //return $this->find($query);
        
        
    }
    
    public function facet($category, $filter)
    {                      
    $boolQuery = new \Elastica\Query\Bool();
    
    $queryStatus = new \Elastica\Query\Match();
    $queryStatus->setFieldQuery('place.status', StatusType::VALIDATED);
    $boolQuery->addMust($queryStatus);                  
    ##AGGREGATION - FACETED##
    $aggregServices = new \Elastica\Aggregation\Terms('services');
    $aggregServices->setField('services.slug');    
    $aggregServices->setSize(0);
    
    $aggregMenu = new \Elastica\Aggregation\Terms('menu');
    $aggregMenu->setField('menu.slug');    
    $aggregMenu->setSize(0);
    
    
    $this->addCollections($boolQuery, $filter);
    var_dump($boolQuery->getParams()['must']);
    $boolFilter = new \Elastica\Filter\Bool();  
    //$active = new \Elastica\Filter\Term(['membershipSubscriptions.m_status' => MembershipStatusType::ACTIVE]);
    $active = new \Elastica\Filter\Term(['membershipSubscriptions.m_status' => MembershipStatusType::ACTIVE]);
    $boolFilter->addMust($active);
    
    //$boolQuery->
    $filtered = new \Elastica\Query\Filtered($boolQuery, $boolFilter);
    $query = \Elastica\Query::create($filtered);
        
    $query->addAggregation($aggregServices);
    $query->addAggregation($aggregMenu);
    
    //$query = $elasticaQuery;    
    //$query->addSort(array('membershipSubscriptions.m_status' => 'desc'));
    $query->addSort(array('membershipSubscriptions.membership.score' => 'desc'));    
      
    //var_dump(json_encode($query->getQuery(), JSON_PRETTY_PRINT));die();
    //$ob = $query->toArray()['query']['filtered']['query']['bool'];
    //var_dump($query->getParams()['query']['filtered']['query']['bool'], $ob, $query->getQuery()['filtered']['query']['bool']);die();
    
    return $elasticaResultSet = $this->findPaginated($query);    
    }
    
    protected function addCollections($query, $filter){                
        foreach($filter->getProperties() as $k => $v){
            $method = 'get' . ucfirst($k);            
            if($filter->$method() != null && !$filter->$method()->isEmpty()){                
                foreach ($filter->$method() as $item){                                    
                    $term = new \Elastica\Query\Match();                
                    $term->setFieldQuery($k . '.slug', $item->getSlug());        
                    $query->addMust($term);
                }
            }
        }        
    }

}