<?php

namespace Krombox\MainBundle\Repository\Search;

use FOS\ElasticaBundle\Repository;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\MainBundle\DBAL\Types\LikeType;

class PlaceRepository extends Repository
{
    public function autocomplete($term, $limit = 10)
    {        
//        if($term != null){                    
//            $prefixQuery = new \Elastica\Query\Prefix();
//            $prefixQuery->setPrefix('tag.name', $term);
//        }
//        else{
//            $prefixQuery = new \Elastica\Query\MatchAll();
//        }
        $fuzzyQuery = new \Elastica\Query\FuzzyLikeThis();
        $fuzzyQuery->addFields(['nameTranslatableRU', 'nameTranslatableEN']);
        $fuzzyQuery->setLikeText($term);
        
        //$baseQuery = $prefixQuery;                
        
        $filtered = new \Elastica\Query\Filtered($fuzzyQuery);

        $query = \Elastica\Query::create($filtered);        
        
        return $this->find($query, $limit);
    }
    
    public function search($category, PlaceSearch $placeSearch)
    {
        // we create a query to return all the articles
        // but if the criteria title is specified, we use it
        
        $boolQuery = new \Elastica\Query\Bool();
        /*Fetch only VALIDATED place*/        
        $queryStatus = new \Elastica\Query\Match();
        $queryStatus->setFieldQuery('place.status', StatusType::VALIDATED);
        $boolQuery->addMust($queryStatus);                      
        
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
        
        $boolQuery->addMust(new \Elastica\Query\Range('businessHours.startsAt',
                array(
                    //'gte' => \Elastica\Util::convertDate($articleSearch->getDateFrom()->getTimestamp()),
                    'lte' => 'now'
                ))
        );
        
//        $boolQuery->addMust(new \Elastica\Query\Range('businessHours.startsAt',
//                array(
//                    //'gte' => \Elastica\Util::convertDate($articleSearch->getDateFrom()->getTimestamp()),
//                    'lte' => 'now'
//                ))
//        );
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
    
    public function facet($filter, $sort)
    {                      
        $boolQuery = new \Elastica\Query\Bool();

        $queryStatus = new \Elastica\Query\Match();
        $queryStatus->setFieldQuery('place.status', StatusType::VALIDATED);
        $boolQuery->addMust($queryStatus);

        $queryCategory = new \Elastica\Query\Match();
        //$queryCategory->setFieldQuery('place.categories.slug', $category->getSlug());
        $queryCategory->setFieldQuery('place.categories.slug', $filter->getCategory()->getSlug());
        $boolQuery->addMust($queryCategory);
        
        $queryCity = new \Elastica\Query\Match();        
        $queryCity->setFieldQuery('place.city.slug', $filter->getCity()->getSlug());
        $boolQuery->addMust($queryCity);

        ##AGGREGATION - FACETED##
        $now = new \DateTime();                           
        $this->addCollections($boolQuery, $filter);

        $boolFilter = new \Elastica\Filter\Bool();

        //Filters
        $businessHoursDayFilter = new \Elastica\Filter\Term(['businessHours.day' . date('l') => true]);
        $businessHoursStartsAtFilter = new \Elastica\Filter\Range('businessHours.startsAtFormatted', array('lte' => $now->format('H:i:s')));
        $businessHoursEndsAtFilter = new \Elastica\Filter\Range('businessHours.endsAtFormatted', array('gte' => $now->format('H:i:s')));
        
        $businessHoursIs24HFilter = new \Elastica\Filter\Term(['is24h' => true]);
        
        $businessHoursExceptionDateFilter = new \Elastica\Filter\Term(['businessHoursException.dayFormatted' => date('Y-m-d')]);
        $businessHoursExceptionStartsAtFilter = new \Elastica\Filter\Range('businessHoursException.startsAtFormatted', array('lte' => $now->format('H:i:s')));
        $businessHoursExceptionEndsAtFilter = new \Elastica\Filter\Range('businessHoursException.endsAtFormatted', array('gte' => $now->format('H:i:s')));
        
        $businessHoursExceptionStartsAtMissingFilter = new \Elastica\Filter\Missing('businessHoursException.startsAtFormatted');                
        $businessHoursExceptionEndsAtMissingFilter = new \Elastica\Filter\Missing('businessHoursException.endsAtFormatted');        
        
        $businessHoursExceptionDateTimeFilter = new \Elastica\Filter\Bool();
        $businessHoursExceptionDateTimeFilter
                ->addMust($businessHoursExceptionDateFilter)
                ->addMust($businessHoursExceptionStartsAtFilter)
                ->addMust($businessHoursExceptionEndsAtFilter)            
        ;
        
        $businessHoursExceptionAllDayClosedFilter = new \Elastica\Filter\Bool();
        $businessHoursExceptionAllDayClosedFilter
                ->addMust($businessHoursExceptionDateFilter)
                ->addMust($businessHoursExceptionStartsAtMissingFilter)
                ->addMust($businessHoursExceptionEndsAtMissingFilter)            
        ;
        
        
        $businessHoursDayTimeFilter = new \Elastica\Filter\Bool();
        $businessHoursDayTimeFilter
                ->addMust($businessHoursDayFilter)
                ->addMust($businessHoursStartsAtFilter)
                ->addMust($businessHoursEndsAtFilter)            
        ;
        #BusinessHours Filter
        $businessHoursFilter = new \Elastica\Filter\Bool();
        $businessHoursFilter->addShould($businessHoursDayTimeFilter);
        #BusinessHoursException Filter
        $businessHoursExceptionFilter = new \Elastica\Filter\Bool();
        $businessHoursExceptionFilter->addShould($businessHoursExceptionDateTimeFilter);
        
        $businessHoursNestedFilter = new \Elastica\Filter\Nested();
        $businessHoursNestedFilter
                ->setFilter($businessHoursFilter)
                ->setPath('place.businessHours')
        ;
        
        $businessHoursExceptionNestedFilter = new \Elastica\Filter\Nested();
        $businessHoursExceptionNestedFilter
                ->setFilter($businessHoursExceptionFilter)
                ->setPath('place.businessHoursException')
        ;
        
        $businessHoursExceptionMissingNestedFilter = new \Elastica\Filter\Nested();
        $businessHoursExceptionMissingNestedFilter
                ->setFilter($businessHoursExceptionAllDayClosedFilter)
                ->setPath('place.businessHoursException')
        ;                                                      
        
        $workingNowFilter = new \Elastica\Filter\Bool();
        $workingNowFilter
                ->addShould($businessHoursNestedFilter)
                ->addShould($businessHoursExceptionNestedFilter)
                ->addShould($businessHoursIs24HFilter)
                ->addMustNot($businessHoursExceptionMissingNestedFilter)                
        ;                
        
        if($filter->getBusinessHours()){
            foreach($filter->getBusinessHours() as $value){
                if($value == 'workingNow'){             
                    $boolFilter->addMust($workingNowFilter);
                }
                if($value == '24/7'){
                    $boolFilter->addMust($businessHoursIs24HFilter);
                }
            }
        }

        //Aggregation
        $aggregFilters = new \Elastica\Aggregation\Terms('filters');    
        $aggregFilters->setField('placeFilterValues.slug');    
        $aggregFilters->setSize(0);

        $aggregBusinessHoursDay = new \Elastica\Aggregation\Filter('businessHoursDay');    
        $aggregBusinessHoursDay->setFilter($businessHoursDayFilter);

        $aggregBusinessHoursStartsAt = new \Elastica\Aggregation\Filter('businessHoursStartsAt');    
        $aggregBusinessHoursStartsAt->setFilter($businessHoursStartsAtFilter);

        $aggregBusinessHoursEndsAtFilter = new \Elastica\Aggregation\Filter('businessHoursEndsAt');
        $aggregBusinessHoursEndsAtFilter->setFilter($businessHoursEndsAtFilter);

        $aggregBusinessHoursStartsAt->addAggregation($aggregBusinessHoursEndsAtFilter);
        $aggregBusinessHoursDay->addAggregation($aggregBusinessHoursStartsAt);

        $aggregBusinessHours = new \Elastica\Aggregation\Filters('businessHours');    
        $aggregBusinessHours->addFilter($workingNowFilter, 'workingNow');
        $aggregBusinessHours->addFilter($businessHoursIs24HFilter, '24/7');              

        $filtered = new \Elastica\Query\Filtered($boolQuery, $boolFilter);
        $query = \Elastica\Query::create($filtered);

        $query->addAggregation($aggregFilters);
        $query->addAggregation($aggregBusinessHours);    
        
        $sortMembership = array('membershipSubscriptions.membership.score' => array(
            'nested_filter' => array('term' => array('membershipSubscriptions.m_status' => MembershipStatusType::ACTIVE)),
            'order' => 'desc'
        ));
        
        $sortRating = array('rating' => array('order' => 'desc'));
        $viewsCount = array('viewsCount' => array('order' => 'desc'));
        
        $sortTypes = array(
            'membership' => array($sortMembership, $sortRating),
            'rating' => array($sortRating),
            'views' => array($viewsCount)
        );
        
//        if(!isset($sortTypes[$sort])){            
//            $sort = 'membership';
//        }        
        foreach ($sortTypes[$sort] as $s){
            $query->addSort($s);
        }
        
        //$query->setFrom(4);
        //$query->addSort(array('rating' => array('order' => 'desc')));

        //var_dump(json_encode($query->getQuery(), JSON_PRETTY_PRINT));die();            
        return $this->findPaginated($query);    
    }
    
    protected function addCollections($query, $filter){                
//        foreach($filter->getProperties() as $k => $v){
//            $method = 'get' . ucfirst($k);    
//            var_dump($method);
//            if($filter->$method() != null && !$filter->$method()->isEmpty()){                
//                foreach ($filter->$method() as $item){                                    
//                    $term = new \Elastica\Query\Match();                
//                    $term->setFieldQuery($k . '.slug', $item->getSlug());        
//                    $query->addMust($term);
//                }
//            }
//        }
//        foreach($filter->getProperties() as $k => $v){
//            $method = 'get' . ucfirst($k);    
            //var_dump($filter);die();
            if($filter->getFilters() != null && !empty($filter->getFilters())){
                foreach ($filter->getFilters() as $item){                                            
                    $term = new \Elastica\Query\Match();                
                    $term->setFieldQuery('placeFilterValues.slug', $item);        
                    $query->addMust($term);
                }
            }
//        }
    }

}