<?php

namespace Krombox\MainBundle\Service;
use Krombox\MainBundle\Entity\Place;

class RedisManager {


    private $redis;
    
    private $securityContext;
    
    public function __construct($redis, $secirityContext) {
        $this->redis = $redis;
        $this->securityContext = $secirityContext;
        
    }
    
    public function setPlaceUserLike($placeId, $likeValue){
        $user = $this->securityContext->getToken()->getUser();
        $currentValue = $this->redis->get('place:' . $placeId . ':user:' . $user->getId() . ':like');
                //var_dump($currentValue);die();
        //echo $currentValue . ' current' . PHP_EOL;
        $this->redis->set('place:' . $placeId . ':user:' . $user->getId() . ':like', $likeValue);
        
        //var_dump($currentValue, $likeValue);
        if($currentValue == $likeValue && $currentValue !== NULL)
            return;
        
        if($likeValue == Place::LIKE_POSSITIVE){//echo 'positive';
            $this->redis->hincrby('place:' . $placeId . ':likes', 'positive', 1);
        }
        else{
            $this->redis->hincrby('place:' . $placeId . ':likes', 'negative', 1);
            //echo 'negative';
        }
        
        if($currentValue == NULL)
            return;
        
        if($likeValue == Place::LIKE_POSSITIVE)
            $this->redis->hincrby('place:' . $placeId . ':likes', 'negative', -1);
        else
            $this->redis->hincrby('place:' . $placeId . ':likes', 'positive', -1);        
    }
    
    public function unsetPlaceUserLike($placeId){
        $user = $this->securityContext->getToken()->getUser();
        $userVote = $this->redis->get('place:' . $placeId . ':user:' . $user->getId() . ':like');
        $this->redis->del('place:' . $placeId . ':user:' . $user->getId() . ':like');
        
        if($userVote)
            $this->redis->hincrby('place:' . $placeId . ':likes', 'positive', -1);
        else
            $this->redis->hincrby('place:' . $placeId . ':likes', 'negative', -1);
        
        return true;
    }

        public function getPlaceLikes($placeId){
        $fields = ['positive', 'negative'];
        $result = $this->redis->hmget('place:' . $placeId . ':likes', $fields);
        /*TODO Change*/
        $result[0] ? $placeLikes['positive'] = $result[0] : $placeLikes['positive'] = 0;
        $result[1] ? $placeLikes['negative'] = $result[1] : $placeLikes['negative'] = 0;
        //-$placeLikes['negative'] = $result[1];
        $placeLikes['total'] = $placeLikes['positive'] + $placeLikes['negative'];
        
        $placeLikes['persent'] = 100;
        
        //echo $placeLikes['total'];
        if($placeLikes['total'])
            $placeLikes['persent'] = $placeLikes['positive'] / $placeLikes['total'] * 100;
        //var_dump($placeLikes);
        return $placeLikes;
    }
    
    public function getPlaceUserLike($placeId){
        $user = $this->securityContext->getToken()->getUser();
        $result = $this->redis->get('place:' . $placeId . ':user:' . $user->getId() .':like');
        //var_dump($result);
        return $result;
    }
}

