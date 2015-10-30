<?php

namespace Krombox\MainBundle\Repository\Search;

use FOS\ElasticaBundle\Repository;
use Krombox\MainBundle\Model\PlaceSearch;
use Krombox\MainBundle\DBAL\Types\StatusType;

class EventRepository extends Repository
{
    public function search($category)
    {
        // we create a query to return all the articles
        // but if the criteria title is specified, we use it
        
        $boolQuery = new \Elastica\Query\Bool();
        /*Fetch only VALIDATED place*/
        $queryStatus = new \Elastica\Query\Match();
        $queryStatus->setFieldQuery('event.status', StatusType::VALIDATED);
        $boolQuery->addMust($queryStatus);
        
        if($category !== null){
            $queryCategory = new \Elastica\Query\Match();
            $queryCategory->setFieldQuery('event.categories.slug', $category);
            $boolQuery->addMust($queryCategory);
        }        
        
//        if($placeSearch->getBirthdayDiscount() != null && $placeSearch->getBirthdayDiscount() > 0 ){
//           $queryRange = new \Elastica\Query\Range();
//           $queryRange->setParam('place.birthdayDiscount', ['gte' => 1]);
//           $boolQuery->addMust($queryRange);
//        }
//        
//        if(($placeSearch->getName() != null || $placeSearch->getCategories() != null ) && $placeSearch != null){
//            
//            if($placeSearch->getName() != null){
//                $query = new \Elastica\Query\Match();
//                $query->setFieldQuery('place.name', $placeSearch->getName());
//                $query->setFieldFuzziness('place.name', 1);
//                $query->setFieldMinimumShouldMatch('place.name', '10%');
//                $boolQuery->addMust($query);
//            }
//            
//            if($placeSearch->getCategories() != null){  
//                foreach ($placeSearch->getCategories() as $cat){                
//                    $categories[] = $cat->getName();                
//                }
//                $queryCategories = new \Elastica\Query\Terms();
//                $queryCategories->setTerms('place.categories', $categories);
//                $boolQuery->addShould($queryCategories);
//            }
//                                    
        //} 
        else {
            $query = new \Elastica\Query\MatchAll();
        }
         $baseQuery = $boolQuery;         

        // then we create filters depending on the chosen criterias
        $boolFilter = new \Elastica\Filter\Bool();

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
//        if($placeSearch->getIs24h() !== null && $placeSearch->getIs24h()){
//                        //var_dump($placeSearch->getIs24h());die();
//            $boolFilter->addMust(
//                new \Elastica\Filter\Term(['is24h' => $placeSearch->getIs24h()])
//                //new \Elastica\Filter\Term(['isWifi' => $placeSearch->getIsWifi()])    
//            );                        
//            
//            //$boolFilter->addMust('is24h', $placeSearch->getIs24h());
//        }
//        
//        if($placeSearch->getIsWifi() !== null && $placeSearch->getIsWifi()){
//            $boolFilter->addMust(              
//                new \Elastica\Filter\Term(['isWifi' => $placeSearch->getIsWifi()])    
//            );
//        }
//        
//        if($placeSearch->getIsDelivery() !== null && $placeSearch->getIsDelivery()){
//            $boolFilter->addMust(              
//                new \Elastica\Filter\Term(['isDelivery' => $placeSearch->getIsDelivery()])    
//            );
//        }                        

        $filtered = new \Elastica\Query\Filtered($baseQuery, $boolFilter);

        $query = \Elastica\Query::create($filtered);

        return $this->find($query);
    }

}