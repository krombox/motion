<?php

namespace Krombox\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Krombox\CommonBundle\Model\Traits\RateableEntity;
use Krombox\MainBundle\DBAL\Types\StatusType;
use Krombox\MainBundle\DBAL\Types\DayType;
use Krombox\MainBundle\DBAL\Types\MembershipType;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\MainBundle\DBAL\Types\LikeType;
use \DateTime;
use \DateInterval;
use Cocur\Slugify\Slugify;
use Krombox\CommonBundle\Model\RateableInterface;

class Place implements RateableInterface
{    
    use ORMBehaviors\Timestampable\Timestampable;                
    use ORMBehaviors\Sluggable\Sluggable;
    use RateableEntity;           
    
    public function getSluggableFields()
    {
        return ['hash','name' ];
    }

    /**
     * @var integer
     */
    private $id;        


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }   
    
    /**
     * @var \Krombox\UserBundle\Entity\User
     */
    private $user;


    /**
     * Set user
     *
     * @param \Krombox\UserBundle\Entity\User $user
     * @return Place
     */
    public function setUser(\Krombox\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Krombox\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeImages;

    /**
     * Constructor
     */
    public function __construct()
    {                
        /*Changed in postPersist event*/        
        $this->placeImages = new \Doctrine\Common\Collections\ArrayCollection();        
        $this->phones = new \Doctrine\Common\Collections\ArrayCollection();
        $this->halls = new \Doctrine\Common\Collections\ArrayCollection();
        $hash = md5(time() + rand(1, 99));
        $this->setHash($hash);
        $this->rating = 0;
        $this->ratingCount = 0;
        $this->is24h = false;
        $this->status = StatusType::PENDING;
    }

    /**
     * Add placeImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $placeImages
     * @return Place
     */
    public function addPlaceImage(\Krombox\MainBundle\Entity\PlaceImage $placeImages)
    {               
        $this->placeImages[] = $placeImages;
        $placeImages->setPlace($this);
                
        return $this;
    }

    /**
     * Remove placeImages
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $placeImages
     */
    public function removePlaceImage(\Krombox\MainBundle\Entity\PlaceImage $placeImages)
    {
        $this->placeImages->removeElement($placeImages);
        //$placeImages->setPlace(null);
    }

    /**
     * Get placeImages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceImages()
    {
        return $this->placeImages;
    }    
    /**
     * @var string
     */
    //private $phone;

    /**
     * @var boolean
     */
    private $is24h;

    /**
     * @var string
     */
    private $website;  

    /**
     * Set is24h
     *
     * @param boolean $is24h
     * @return Place
     */
    public function setIs24h($is24h)
    {
        $this->is24h = $is24h;

        return $this;
    }

    /**
     * Get is24h
     *
     * @return boolean 
     */
    public function getIs24h()
    {
        return $this->is24h;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Place
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }    
    
    /**
     * @var StatusType
     */
    private $status;


    /**
     * Set status
     *
     * @param StatusType $status
     * @return Place
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return StatusType 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $businessHours;


    /**
     * Add businessHours
     *
     * @param \Krombox\MainBundle\Entity\BusinessHours $businessHours
     * @return Place
     */
    public function addBusinessHour(\Krombox\MainBundle\Entity\BusinessHours $businessHours)
    {
        $this->businessHours[] = $businessHours;
        $businessHours->setPlace($this);
        
        return $this;
    }

    /**
     * Remove businessHours
     *
     * @param \Krombox\MainBundle\Entity\BusinessHours $businessHours
     */
    public function removeBusinessHour(\Krombox\MainBundle\Entity\BusinessHours $businessHours)
    {
        $this->businessHours->removeElement($businessHours);
    }

    /**
     * Get businessHours
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessHours()
    {
        return $this->businessHours;
    }    
    
//    protected function getCurrentWeekDateByDay($day)
//    {
//        $days = array_flip(DayType::getChoices());
//
//        $today = new \DateTime();
//        $today->setISODate($today->format('o'), $today->format('W'), $days[ucfirst($day)]);
//        
//        return $today;
//    }
//
//    public function getWeekDays(){
//        $weekDays = [];
//        
//        $i = 1;
//        
//        foreach (DayType::getChoices() as $day){            
//            $weekDays[$i]['day'] = $this->getCurrentWeekDateByDay($day);            
//            $i++;
//        }        
//        return $weekDays;
//    }
//    
//    protected function getExceptionByDay($day){
//        //var_dump($day);die();
//        $exception = array();
//        
//        foreach ($this->getBusinessHoursException() as $bHoursEx){           
//            //echo $bHoursEx->getDay()->format('Y-m-d');
//            //var_dump($bHoursEx->getDay()->format('Y-m-d'), $day->format('Y-m-d'));
//            //die('dd');
//            if($bHoursEx->getDay()->format('Y-m-d') == $day->format('Y-m-d')){
//                $exception['fromTime'] = $bHoursEx->getFromTime();
//                $exception['toTime'] = $bHoursEx->getToTime();
//                
//                return $exception;
//            }
//            
//        }
//        
//        return false;
//    }
//
//    public function getBusinessHoursSheet(){
//        //$businessHoursSheet = array_flip(DayType::getChoices());
//        $businessHoursSheet = $this->getWeekDays();
//        //var_dump($businessHoursSheet);die();
//        foreach ($this->getBusinessHours() as $bHours){
//            //var_dump($bHours->getFromDay());die();
//            for($i = $bHours->getFromDay(); $i <= $bHours->getToDay(); $i++){
////                $businessHoursSheet[$i]['exception'] = false;
////                $businessHoursSheet[$i]['exception']  = $this->getExceptionByDay($businessHoursSheet[$i]['day']);
//                //$businessHoursSheet[DayType::getReadableValue($i)] = ['fromTime' => $bHours->getFromTime(),'toTime' => $bHours->getToTime()];
//                $businessHoursSheet[$i]['fromTime'] = $bHours->getFromTime();
//                $businessHoursSheet[$i]['toTime']   = $bHours->getToTime();
//                
//                //var_dump($businessHoursSheet);die();
//            }
//        }
//        
//        foreach ($businessHoursSheet as $key => $sheet){            
//            //var_dump($sheet);die();
//            $businessHoursSheet[$key]['exception'] = false;
//            $businessHoursSheet[$key]['exception']  = $this->getExceptionByDay($sheet['day']);
//        }
//        
//        
//        //$businessHoursSheet = ['monday' => []];
//        //var_dump($businessHoursSheet);die();
//        
//        return $businessHoursSheet;
//    }
//
//    protected function isWorkingDay(){        
//        $currentDay = $this->getCurrentDay();        
//        
//        foreach ($this->getBusinessHours() as $bHours){
//            
//            $fromDay = $bHours->getFromDay();
//            $toDay = $bHours->getToDay();
//            
//            if ($fromDay > $toDay){
//                if ($currentDay > $fromDay){
//                    $toDay+=7;
//                }
//                if ($currentDay < $fromDay){                    
//                    $fromDay-=7;
//                }
//            }
//                                                
//            if($fromDay <= $currentDay && $toDay >= $currentDay)
//                return true;                        
//        }
//        
//        return false;
//    }
//       
//    
//    protected function isWorkingTime(){
//        
//        $now = $this->getCurrentTime();                        
//        
//        foreach ($this->getBusinessHours() as $bHours){
//            $fromTime = $bHours->getFromTime();
//            $toTime = $bHours->getToTime();            
//            //var_dump($bHours->getFromDay(), $this->getCurrentDay());die();
//            if ($fromTime > $toTime && $bHours->getFromDay() !== $this->getCurrentDay()){
//                if ($now > $fromTime){
//                    $toTime = $toTime->add(new \DateInterval('PT24H'));
//                }
//                if ($now < $fromTime){                    
//                    $fromTime = $fromTime->sub(new \DateInterval('PT24H'));
//                }
//            }
//            
//            //var_dump($fromTime->format('d H:i:s'), $now->format('d H:i:s'), $toTime->format('d H:i:s'));
//            if($fromTime <= $now && $toTime >= $now)
//                return true;                                    
//        }
//        
//        return false;
//    }
//    
//    protected function isExceptionTime(){
//        $currentDate = new \DateTime();
//        $isExceptionDay = false;
//        $isExceptionTime = false;
//        
//        foreach ($this->getBusinessHoursException() as $bHoursEx){
//            //var_dump($currentDate->format('m-d'), $bHoursEx->getDay()->format('m-d'));
//            if($currentDate->format('m-d') == $bHoursEx->getDay()->format('m-d')){
//                //echo 'not work';
//                $isExceptionDay = true;
//            }
//                        
//            if($currentDate->format('H:i:s') >= $bHoursEx->getFromTime()->format('H:i:s') && $currentDate->format('H:i:s') <= $bHoursEx->getToTime()->format('H:i:s') ){
//                //var_dump($currentDate->format('H:i:s'), $bHoursEx->getFromTime()->format('H:i:s'));
//                $isExceptionTime = true;                
//            }
//        }
//        
//        //var_dump('isExceptionDay', $isExceptionDay, 'isExceptionTime', $isExceptionTime);
//        if($isExceptionDay && $isExceptionTime)
//            return true;
//            
//        return false;    
//    }
//
//        public function isWorkingNow(){
//        /*TODO Make Validation for case if set from and to day the same(e.g from Monday to Monday) and fromTime > toTime*/
//        if($this->getIs24h())
//            return true;
//        /*TODO must be required*/
//        if(!$this->getBusinessHours())
//            return false;
//                
//        if($this->isWorkingDay() && $this->isWorkingTime() && !$this->isExceptionTime())
//            return true;
//        
//        return false;
//    }
//
//    protected function getCurrentDay(){        
//        $currentDay = date('w', time());
//
//        if($currentDay == 0) $currentDay = 7;
//
//        return $currentDay;
//    }
//    
//    protected function getCurrentTime(){
//       return $datetime = new \DateTime();
//       //return $datetime->format('H:i:s');
//    }        

    /**
     * Set latLng
     *
     * @param string $latLng
     * @return Place
     */
//    public function setMap($map)
//    {
//        $this->setLat($map['lat']);
//        $this->setLng($map['lng']);
//        $this->setAddress($map['address']);        
//        return $this;
//    }

    /**
     * Get latLng
     *
     * @return string 
     */
//    public function getMap()
//    {        
//        return array('lat'=>$this->getLat(),'lng'=>$this->getLng(), 'address' => $this->getAddress());
//    }

    /**
     * @var string
     */
    //private $latLng;


    /**
     * @var string
     */
    private $address;


    /**
     * Set address
     *
     * @param string $address
     * @return Place
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }   

//    /**
//     * @var string
//     */
//    private $lat;
//
//    /**
//     * @var string
//     */
//    private $lng;
//
//
//    /**
//     * Set lat
//     *
//     * @param string $lat
//     * @return Place
//     */
//    public function setLat($lat)
//    {
//        $this->lat = $lat;
//
//        return $this;
//    }
//
//    /**
//     * Get lat
//     *
//     * @return string 
//     */
//    public function getLat()
//    {
//        return $this->lat;
//    }
//
//    /**
//     * Set lng
//     *
//     * @param string $lng
//     * @return Place
//     */
//    public function setLng($lng)
//    {
//        $this->lng = $lng;
//
//        return $this;
//    }
//
//    /**
//     * Get lng
//     *
//     * @return string 
//     */
//    public function getLng()
//    {
//        return $this->lng;
//    }
    
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;


    /**
     * Set name
     *
     * @param string $name
     * @return Place
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Place
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @var boolean
     */
    private $isWifi;


    /**
     * Set isWifi
     *
     * @param boolean $isWifi
     * @return Place
     */
    public function setIsWifi($isWifi)
    {
        $this->isWifi = $isWifi;

        return $this;
    }

    /**
     * Get isWifi
     *
     * @return boolean 
     */
    public function getIsWifi()
    {
        return $this->isWifi;
    }
    
    public function getCategoriesName(){
        $categories = [];
        foreach($this->getCategories() as $cat){
            $categories[] = $cat->getName();
        }
        
        return $categories;
    }
    /**
     * @var boolean
     */
    private $isHookah;

    /**
     * @var boolean
     */
    private $isLiveMusic;

    /**
     * @var boolean
     */
    private $isOpenAir;

    /**
     * @var boolean
     */
    private $isParking;

    /**
     * @var boolean
     */
    private $isSmokingLounge;

    /**
     * @var boolean
     */
    private $isBilliards;

    /**
     * @var boolean
     */
    private $isFaceControl;

    /**
     * @var boolean
     */
    private $isBanquet;

    /**
     * @var boolean
     */
    private $isDanceFloor;

    /**
     * @var boolean
     */
    private $isStriptease;

    /**
     * @var boolean
     */
    private $isMeetingHole;


    /**
     * Set isHookah
     *
     * @param boolean $isHookah
     * @return Place
     */
    public function setIsHookah($isHookah)
    {
        $this->isHookah = $isHookah;

        return $this;
    }

    /**
     * Get isHookah
     *
     * @return boolean 
     */
    public function getIsHookah()
    {
        return $this->isHookah;
    }

    /**
     * Set isLiveMusic
     *
     * @param boolean $isLiveMusic
     * @return Place
     */
    public function setIsLiveMusic($isLiveMusic)
    {
        $this->isLiveMusic = $isLiveMusic;

        return $this;
    }

    /**
     * Get isLiveMusic
     *
     * @return boolean 
     */
    public function getIsLiveMusic()
    {
        return $this->isLiveMusic;
    }

    /**
     * Set isOpenAir
     *
     * @param boolean $isOpenAir
     * @return Place
     */
    public function setIsOpenAir($isOpenAir)
    {
        $this->isOpenAir = $isOpenAir;

        return $this;
    }

    /**
     * Get isOpenAir
     *
     * @return boolean 
     */
    public function getIsOpenAir()
    {
        return $this->isOpenAir;
    }

    /**
     * Set isParking
     *
     * @param boolean $isParking
     * @return Place
     */
    public function setIsParking($isParking)
    {
        $this->isParking = $isParking;

        return $this;
    }

    /**
     * Get isParking
     *
     * @return boolean 
     */
    public function getIsParking()
    {
        return $this->isParking;
    }

    /**
     * Set isSmokingLounge
     *
     * @param boolean $isSmokingLounge
     * @return Place
     */
    public function setIsSmokingLounge($isSmokingLounge)
    {
        $this->isSmokingLounge = $isSmokingLounge;

        return $this;
    }

    /**
     * Get isSmokingLounge
     *
     * @return boolean 
     */
    public function getIsSmokingLounge()
    {
        return $this->isSmokingLounge;
    }

    /**
     * Set isBilliards
     *
     * @param boolean $isBilliards
     * @return Place
     */
    public function setIsBilliards($isBilliards)
    {
        $this->isBilliards = $isBilliards;

        return $this;
    }

    /**
     * Get isBilliards
     *
     * @return boolean 
     */
    public function getIsBilliards()
    {
        return $this->isBilliards;
    }

    /**
     * Set isFaceControl
     *
     * @param boolean $isFaceControl
     * @return Place
     */
    public function setIsFaceControl($isFaceControl)
    {
        $this->isFaceControl = $isFaceControl;

        return $this;
    }

    /**
     * Get isFaceControl
     *
     * @return boolean 
     */
    public function getIsFaceControl()
    {
        return $this->isFaceControl;
    }

    /**
     * Set isBanquet
     *
     * @param boolean $isBanquet
     * @return Place
     */
    public function setIsBanquet($isBanquet)
    {
        $this->isBanquet = $isBanquet;

        return $this;
    }

    /**
     * Get isBanquet
     *
     * @return boolean 
     */
    public function getIsBanquet()
    {
        return $this->isBanquet;
    }

    /**
     * Set isDanceFloor
     *
     * @param boolean $isDanceFloor
     * @return Place
     */
    public function setIsDanceFloor($isDanceFloor)
    {
        $this->isDanceFloor = $isDanceFloor;

        return $this;
    }

    /**
     * Get isDanceFloor
     *
     * @return boolean 
     */
    public function getIsDanceFloor()
    {
        return $this->isDanceFloor;
    }

    /**
     * Set isStriptease
     *
     * @param boolean $isStriptease
     * @return Place
     */
    public function setIsStriptease($isStriptease)
    {
        $this->isStriptease = $isStriptease;

        return $this;
    }

    /**
     * Get isStriptease
     *
     * @return boolean 
     */
    public function getIsStriptease()
    {
        return $this->isStriptease;
    }

    /**
     * Set isMeetingHole
     *
     * @param boolean $isMeetingHole
     * @return Place
     */
    public function setIsMeetingHole($isMeetingHole)
    {
        $this->isMeetingHole = $isMeetingHole;

        return $this;
    }

    /**
     * Get isMeetingHole
     *
     * @return boolean 
     */
    public function getIsMeetingHole()
    {
        return $this->isMeetingHole;
    }
    /**
     * @var string
     */
    private $hash;


    /**
     * Set hash
     *
     * @param string $hash
     * @return Place
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }   
    
//    public function getUpLikesCount(){
//        $count = 0;                
//        
//        foreach ($this->getLikes() as $like){
//            if($like->getRate() == LikeType::UP)
//                $count++;
//        }
//        
//        return $count;
//    }
//    
//    public function getDownLikesCount(){
//        $count = 0;                
//        
//        foreach ($this->getLikes() as $like){
//            if($like->getRate() == LikeType::DOWN)
//                $count++;
//        }
//        
//        return $count;
//    }
//
//    public function calculateLikesPersent(){
//        $up = $this->getUpLikesCount();
//        $down = $this->getDownLikesCount();        
//        $total = $up + $down;        
//        $persent = 100;
//        
//        if($total)
//            $persent = ($up / $total) * 100;
//        
//        return $persent;
//    }

//    public function getLikesPersent(){
//        $up = $this->getUpLikesCount();
//        $down = $this->getDownLikesCount();        
//        $total = $up + $down;        
//        $persent = 100;
//        
//        if($total)
//            $persent = ($up / $total) * 100;
//        
//        return $persent;
//    }
    
//    public function getUserLike($user){
//        $userLike = LikeType::NOT_SET;
//        
//        foreach($this->getLikes() as $like){
//            if($like->getUser() == $user)
//                $userLike = $like->getRate();
//        }
//       
//        return $userLike;
//    }
    
    public function getUserRating($user)
    {        
        foreach($this->getRatings() as $rating){
            if($rating->getUser() == $user)
                return $rating->getRate();
        }
       
        return null;
    }
    /**
     * @var integer
     */
    private $numberOfSeats;

    /**
     * @var integer
     */
    private $birthdayDiscount;

    /**
     * @var boolean
     */
    private $isDiscountSystem;

    /**
     * @var boolean
     */
    private $isDelivery;


    /**
     * Set numberOfSeats
     *
     * @param integer $numberOfSeats
     * @return Place
     */
    public function setNumberOfSeats($numberOfSeats)
    {
        $this->numberOfSeats = $numberOfSeats;

        return $this;
    }

    /**
     * Get numberOfSeats
     *
     * @return integer 
     */
    public function getNumberOfSeats()
    {
        return $this->numberOfSeats;
    }

    /**
     * Set birthdayDiscount
     *
     * @param integer $birthdayDiscount
     * @return Place
     */
    public function setBirthdayDiscount($birthdayDiscount)
    {
        $this->birthdayDiscount = $birthdayDiscount;

        return $this;
    }

    /**
     * Get birthdayDiscount
     *
     * @return integer 
     */
    public function getBirthdayDiscount()
    {
        return $this->birthdayDiscount;
    }

    /**
     * Set isDiscountSystem
     *
     * @param boolean $isDiscountSystem
     * @return Place
     */
    public function setIsDiscountSystem($isDiscountSystem)
    {
        $this->isDiscountSystem = $isDiscountSystem;

        return $this;
    }

    /**
     * Get isDiscountSystem
     *
     * @return boolean 
     */
    public function getIsDiscountSystem()
    {
        return $this->isDiscountSystem;
    }

    /**
     * Set isDelivery
     *
     * @param boolean $isDelivery
     * @return Place
     */
    public function setIsDelivery($isDelivery)
    {
        $this->isDelivery = $isDelivery;

        return $this;
    }

    /**
     * Get isDelivery
     *
     * @return boolean 
     */
    public function getIsDelivery()
    {
        return $this->isDelivery;
    }
    /**
     * @var integer
     */
    private $serviceComission;

    /**
     * @var boolean
     */
    private $isChildrenMenu;

    /**
     * @var boolean
     */
    private $isFootballBroadcast;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placesLinked;


    /**
     * Set serviceComission
     *
     * @param integer $serviceComission
     * @return Place
     */
    public function setServiceComission($serviceComission)
    {
        $this->serviceComission = $serviceComission;

        return $this;
    }

    /**
     * Get serviceComission
     *
     * @return integer 
     */
    public function getServiceComission()
    {
        return $this->serviceComission;
    }

    /**
     * Set isChildrenMenu
     *
     * @param boolean $isChildrenMenu
     * @return Place
     */
    public function setIsChildrenMenu($isChildrenMenu)
    {
        $this->isChildrenMenu = $isChildrenMenu;

        return $this;
    }

    /**
     * Get isChildrenMenu
     *
     * @return boolean 
     */
    public function getIsChildrenMenu()
    {
        return $this->isChildrenMenu;
    }

    /**
     * Set isFootballBroadcast
     *
     * @param boolean $isFootballBroadcast
     * @return Place
     */
    public function setIsFootballBroadcast($isFootballBroadcast)
    {
        $this->isFootballBroadcast = $isFootballBroadcast;

        return $this;
    }

    /**
     * Get isFootballBroadcast
     *
     * @return boolean 
     */
    public function getIsFootballBroadcast()
    {
        return $this->isFootballBroadcast;
    }

    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $events;


    /**
     * Add events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     * @return Place
     */
    public function addEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events[] = $events;
        

        return $this;
    }

    /**
     * Remove events
     *
     * @param \Krombox\MainBundle\Entity\Event $events
     */
    public function removeEvent(\Krombox\MainBundle\Entity\Event $events)
    {
        $this->events->removeElement($events);
    }

    /**
     * Get events
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvents()
    {
        return $this->events;
    }
    /**
     * @var boolean
     */
    private $isTerminalPayment;


    /**
     * Set isTerminalPayment
     *
     * @param boolean $isTerminalPayment
     * @return Place
     */
    public function setIsTerminalPayment($isTerminalPayment)
    {
        $this->isTerminalPayment = $isTerminalPayment;

        return $this;
    }

    /**
     * Get isTerminalPayment
     *
     * @return boolean 
     */
    public function getIsTerminalPayment()
    {
        return $this->isTerminalPayment;
    }

    /**
     * Add placesLinked
     *
     * @param \Krombox\MainBundle\Entity\Place $placesLinked
     * @return Place
     */
    public function addPlacesLinked(\Krombox\MainBundle\Entity\Place $placesLinked)
    {
        $this->placesLinked[] = $placesLinked;

        return $this;
    }

    /**
     * Remove placesLinked
     *
     * @param \Krombox\MainBundle\Entity\Place $placesLinked
     */
    public function removePlacesLinked(\Krombox\MainBundle\Entity\Place $placesLinked)
    {
        $this->placesLinked->removeElement($placesLinked);
    }

    /**
     * Get placesLinked
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlacesLinked()
    {
        return $this->placesLinked;
    }
    
    public function getOwner(){
        return $this->getUser();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $businessHoursException;


    /**
     * Add businessHoursException
     *
     * @param \Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException
     * @return Place
     */
    public function addBusinessHoursException(\Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException)
    {
        $this->businessHoursException[] = $businessHoursException;
        $businessHoursException->setPlace($this);

        return $this;
    }

    /**
     * Remove businessHoursException
     *
     * @param \Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException
     */
    public function removeBusinessHoursException(\Krombox\MainBundle\Entity\BusinessHoursException $businessHoursException)
    {
        $this->businessHoursException->removeElement($businessHoursException);
    }

    /**
     * Get businessHoursException
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBusinessHoursException()
    {
        return $this->businessHoursException;
    }
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $phones;


    /**
     * Add phones
     *
     * @param \Krombox\MainBundle\Entity\Phone $phones
     * @return Place
     */
    public function addPhone(\Krombox\MainBundle\Entity\Phone $phones)
    {        
        $phones->setPlace($this);
        $this->phones[] = $phones;                                
        
        return $this;
    }

    /**
     * Remove phones
     *
     * @param \Krombox\MainBundle\Entity\Phone $phones
     */
    public function removePhone(\Krombox\MainBundle\Entity\Phone $phones)
    {
        $this->phones->removeElement($phones);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $kitchens;


    /**
     * Add kitchens
     *
     * @param \Krombox\MainBundle\Entity\Kitchen $kitchens
     * @return Place
     */
    public function addKitchen(\Krombox\MainBundle\Entity\Kitchen $kitchens)
    {
        $this->kitchens[] = $kitchens;

        return $this;
    }

    /**
     * Remove kitchens
     *
     * @param \Krombox\MainBundle\Entity\Kitchen $kitchens
     */
    public function removeKitchen(\Krombox\MainBundle\Entity\Kitchen $kitchens)
    {
        $this->kitchens->removeElement($kitchens);
    }

    /**
     * Get kitchens
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKitchens()
    {
        return $this->kitchens;
    }        
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $halls;


    /**
     * Add halls
     *
     * @param \Krombox\MainBundle\Entity\Hall $halls
     * @return Place
     */
    public function addHall(\Krombox\MainBundle\Entity\Hall $halls)
    {
        $halls->setPlace($this);
        $this->halls[] = $halls;

        return $this;
    }

    /**
     * Remove halls
     *
     * @param \Krombox\MainBundle\Entity\Hall $halls
     */
    public function removeHall(\Krombox\MainBundle\Entity\Hall $halls)
    {
        $this->halls->removeElement($halls);
    }

    /**
     * Get halls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHalls()
    {
        return $this->halls;
    }                          
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ratings;   

    /**
     * Add ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     * @return Place
     */
    public function addRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings[] = $ratings;

        return $this;
    }

    /**
     * Remove ratings
     *
     * @param \Krombox\MainBundle\Entity\Rating $ratings
     */
    public function removeRating(\Krombox\MainBundle\Entity\Rating $ratings)
    {
        $this->ratings->removeElement($ratings);
    }

    /**
     * Get ratings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRatings()
    {
        return $this->ratings;
    }    
    
    /**
     * @var string
     */
    private $vkGroup;

    /**
     * @var string
     */
    private $fbGroup;


    /**
     * Set vkGroup
     *
     * @param string $vkGroup
     * @return Place
     */
    public function setVkGroup($vkGroup)
    {
        $this->vkGroup = $vkGroup;

        return $this;
    }

    /**
     * Get vkGroup
     *
     * @return string 
     */
    public function getVkGroup()
    {
        return $this->vkGroup;
    }

    /**
     * Set fbGroup
     *
     * @param string $fbGroup
     * @return Place
     */
    public function setFbGroup($fbGroup)
    {
        $this->fbGroup = $fbGroup;

        return $this;
    }

    /**
     * Get fbGroup
     *
     * @return string 
     */
    public function getFbGroup()
    {
        return $this->fbGroup;
    }    
    /**
     * @var \Krombox\MainBundle\Entity\PlaceImage
     */
    private $logo;


    /**
     * Set logo
     *
     * @param \Krombox\MainBundle\Entity\PlaceImage $logo
     * @return Place
     */
    public function setLogo(\Krombox\MainBundle\Entity\PlaceImage $logo = null)
    {
        $this->logo = $logo;
        //$logo->setPlace($this);

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Krombox\MainBundle\Entity\PlaceImage 
     */
    public function getLogo()
    {
        return $this->logo;
    }    

    /**
     * Get membership
     *
     * @return \Krombox\MainBundle\Entity\MembershipSubscription 
     */
    public function getMembership()
    {
        foreach ($this->getMembershipSubscriptions() as $membership){
            if($membership->getStatus() == MembershipStatusType::ACTIVE){
                return $membership->getMembership();
            }
        }
    }
    
    /**
     * Get membershipSubscription
     *
     * @return \Krombox\MainBundle\Entity\MembershipSubscription 
     */
    public function getActiveMembershipSubscription()
    {
        foreach ($this->getMembershipSubscriptions() as $membership){
            if($membership->getStatus() == MembershipStatusType::ACTIVE){
                return $membership;
            }
        }
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $membershipSubscriptions;


    /**
     * Add membershipSubscriptions
     *
     * @param \Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions
     * @return Place
     */
    public function addMembershipSubscription(\Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions)
    {
        $this->membershipSubscriptions[] = $membershipSubscriptions;

        return $this;
    }

    /**
     * Remove membershipSubscriptions
     *
     * @param \Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions
     */
    public function removeMembershipSubscription(\Krombox\MainBundle\Entity\MembershipSubscription $membershipSubscriptions)
    {
        $this->membershipSubscriptions->removeElement($membershipSubscriptions);
    }

    /**
     * Get membershipSubscriptions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembershipSubscriptions()
    {
        return $this->membershipSubscriptions;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $menu;


    /**
     * Add menu
     *
     * @param \Krombox\MainBundle\Entity\Menu $menu
     * @return Place
     */
    public function addMenu(\Krombox\MainBundle\Entity\Menu $menu)
    {
        $this->menu[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param \Krombox\MainBundle\Entity\Menu $menu
     */
    public function removeMenu(\Krombox\MainBundle\Entity\Menu $menu)
    {
        $this->menu->removeElement($menu);
    }

    /**
     * Get menu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenu()
    {
        return $this->menu;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $serviceMany;


    /**
     * Add serviceMany
     *
     * @param \Krombox\MainBundle\Entity\ServiceMany $serviceMany
     * @return Place
     */
    public function addServiceMany(\Krombox\MainBundle\Entity\ServiceMany $serviceMany)
    {
        $this->serviceMany[] = $serviceMany;

        return $this;
    }

    /**
     * Remove serviceMany
     *
     * @param \Krombox\MainBundle\Entity\ServiceMany $serviceMany
     */
    public function removeServiceMany(\Krombox\MainBundle\Entity\ServiceMany $serviceMany)
    {
        $this->serviceMany->removeElement($serviceMany);
    }

    /**
     * Get serviceMany
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiceMany()
    {
        return $this->serviceMany;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $services;


    /**
     * Add services
     *
     * @param \Krombox\MainBundle\Entity\Service $services
     * @return Place
     */
    public function addService(\Krombox\MainBundle\Entity\Service $services)
    {
        $this->services[] = $services;

        return $this;
    }

    /**
     * Remove services
     *
     * @param \Krombox\MainBundle\Entity\Service $services
     */
    public function removeService(\Krombox\MainBundle\Entity\Service $services)
    {
        $this->services->removeElement($services);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServices()
    {
        return $this->services;
    }
          
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $placeFilterValues;


    /**
     * Add placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     * @return Place
     */
    public function addPlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues[] = $placeFilterValues;

        return $this;
    }

    /**
     * Remove placeFilterValues
     *
     * @param \Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues
     */
    public function removePlaceFilterValue(\Krombox\MainBundle\Entity\PlaceFilterValue $placeFilterValues)
    {
        $this->placeFilterValues->removeElement($placeFilterValues);
    }

    /**
     * Get placeFilterValues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlaceFilterValues()
    {
        return $this->placeFilterValues;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;


    /**
     * Add categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     * @return Place
     */
    public function addCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \Krombox\MainBundle\Entity\Category $categories
     */
    public function removeCategory(\Krombox\MainBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
