<?php

namespace Krombox\MainBundle\Helper;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\DBAL\Types\DayType as DayType;
//use Business\SpecialDay;
//use Business\Day;
//use Business\Days;
//use Business\Business;
//use Business\Holidays;
//use Business\DateRange;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;

/**
 * @DI\Service("krombox.business_hours_helper")
 */
class BusinessHoursHelper
{
    private $place;
    
    private $hours = [];
    
    private $exceptions = [];
    
//    public function __construct() {
//        foreach (DayType::getChoices() as $day){
//            $dayName = strtolower(date('D', strtotime($day)));                            
//            $this->hours[$dayName] = [];
//        }
//    }

    public function setPlace(Place $place)
    {
        $this->place = $place;
    }
    
    protected function generateHours()
    {
        foreach (DayType::getChoices() as $day){
            $dayName = strtolower(date('D', strtotime($day)));                            
            $hours[$dayName] = [];
        }
        
        return $hours;
    }

    public function getBusinessHoursSheet($place, $withException = true){
        $weekDays = $this->getCurrentWeekDates();                
        $sheet = [];
        
        foreach ($place->getBusinessHours() as $bh){
            foreach ($weekDays as $key => $day){
                $sheet[$key]['day'] = $day;
                $method = 'getDay' . $day->format('l');
                
                if($bh->$method()){
                    $sheet[$key]['startsAt'][] = $bh->getStartsAt();
                    $sheet[$key]['endsAt'][] = $bh->getEndsAt();
                }
            }
        }                        
        
        if($withException){
            foreach ($place->getBusinessHoursException() as $bhEx){
                foreach ($sheet as $key => $day){                    
                    if($bhEx->getDay()->format('Y-m-d') == $day['day']->format('Y-m-d')){
                        //Erase standart business hours ih exception exist
                        $sheet[$key]['startsAt'] = [];
                        $sheet[$key]['endsAt'] = [];
                        $sheet[$key]['startsAt'][] = $bhEx->getStartsAt();
                        $sheet[$key]['endsAt'][] = $bhEx->getEndsAt();
                        $sheet[$key]['isException'] = true;
                    }                    
                }
            }        
        }
        
        //sort
//        foreach ($sheet as $k => $v){
//            $sheet[$k]['startsAt'] = $this->sort($v['startsAt']);
//            $sheet[$k]['endsAt'] = $this->sort($v['endsAt']);
//        }
        //var_dump($sheet);die();
        return $sheet;
    }
    
    protected function sort($arr, $direction = 'asc')
    {
         usort($arr, function($a, $b) use ($direction) {                        
            if ($a == $b) {
              return 0;
            }
            $direction == 'asc' ? $operator = '>' : $operator = '<';
            
            switch($operator){
                case ">":
                    return $a > $b ? 1 : -1;
                case "<":
                    return $a < $b ? 1 : -1;
            }                        
        }); 
        
        return $arr;
    }

//    public function getBusinessHoursSheet($place, $withException = true){
//        $weekDays = $this->getCurrentWeekDates();                
//        $sheet = [];
//        
//        foreach ($place->getBusinessHours() as $bh){
//            foreach ($weekDays as $key => $day){
//                $sheet[$key]['day'] = $day;
//                $method = 'getDay' . $day->format('l');
//                
//                if($bh->$method()){
//                    $sheet[$key]['startsAt'] = $bh->getStartsAt();
//                    $sheet[$key]['endsAt'] = $bh->getEndsAt();
//                }
//            }
//        }
//        
//        if($withException){
//            foreach ($place->getBusinessHoursException() as $bhEx){
//                foreach ($sheet as $key => $day){                    
//                    if($bhEx->getDay()->format('Y-m-d') == $day['day']->format('Y-m-d')){
//                        $sheet[$key]['startsAt'] = $bhEx->getStartsAt();
//                        $sheet[$key]['endsAt'] = $bhEx->getEndsAt();
//                        $sheet[$key]['isException'] = true;
//                    }                    
//                }
//            }        
//        }
//        //var_dump($sheet);die();
//        return $sheet;
//    }
    
    public function getCurrentWeekDates()
    {                
        for ($i = 1; $i <= 7; $i++){
            $today = new \DateTime();
            $days[] = $today->setISODate($today->format('o'), $today->format('W'), $i);        
        }
        
        return $days;
    }
    
    public function isWorkingNow($place)
    {
        $now = new \DateTime();
        $currentDayName = date('l');
        
        $isOpenException = false;
        foreach ($place->getBusinessHoursException() as $bhE)
        {
            if($bhE->getDay()->format('Y-m-d') == $now->format('Y-m-d') && !$bhE->getStartsAt() && !$bhE->getEndsAt()){
                return false;
            }
            if($bhE->getDay()->format('Y-m-d') == $now->format('Y-m-d') && $bhE->getStartsAt() <= $now && $now <= $bhE->getEndsAt()){
                $isOpenException = true;
            }
        }
        
        if($isOpenException) return true;
        
        #24/7
        if($this->is24h($place)) return true;
        
        foreach ($place->getBusinessHours() as $bh){
            $method = 'getDay' . $currentDayName;
            #work this day and time            
            //var_dump($bh->$method(), $bh->getStartsAt(), $bh->getEndsAt(), $now);
            if($bh->$method() && $bh->getStartsAt() <= $now && $now <= $bh->getEndsAt()){
                return true;
            }
        }
        return false;
    }
    
    protected function getClosestOpenHours($place)
    {
        $now = new \DateTime();
        $day = $now->format('l');        
        $dayOpenMin = new \DateTime('+10 years');//Dummy
        
        foreach ($place->getBusinessHours() as $bh){
            $endsAt = $bh->getEndsAt();
            $startsAt = $bh->getStartsAt();            
            
            for($i = 1; $i <= 7; $i++)
            {
                $method = 'getDay' . ucfirst($day);
                $dayOpen = new \Datetime($day);
                $dayOpen->setTime($startsAt->format('H'), $startsAt->format('i'), $startsAt->format('s'));
                
                if($bh->$method() && $dayOpen > $now){
                    //Looking for soonest open hours
                    if($dayOpen < $dayOpenMin){
                        $dayOpenMin = $dayOpen;
                    }
                }
                
                $day = DayFlaggableHelper::nextDay($day);
            }
        }
        
        return $dayOpenMin;
    }

    protected function getClosestShutdownHours($place)
    {
        $now = new \DateTime();
        $day = $now->format('l');
        $method = 'getDay' . ucfirst($day);        
        
        //BusinessHoursException
        foreach ($place->getBusinessHoursException() as $bhE)
        {
            $endsAt = $bhE->getEndsAt();
            $startsAt = $bhE->getStartsAt();
            
            if($bhE->getDay()->format('Y-m-d') == $now->format('Y-m-d')){
                if($bhE->getStartsAt() <= $now && $now <= $bhE->getEndsAt()){
                    //echo 'dd';die();
//                    if($endsAt > $endsAtMax){//Looks this will not need after violation
//                        $endsAtMax = $endsAt;
//                    }
//                    if($endsAtAfterMidnight = $this->getClosesestShutdownHoursAfterMidnight($place)){
//                        return $endsAtAfterMidnight;
//                    }
                    return $endsAt;
                    //$closestCloseHours =  $endsAt;
                }
            }
        }
        
        //BusinessHours
        foreach ($place->getBusinessHours() as $bh){
            if($bh->$method()){
                $endsAt = $bh->getEndsAt();
                $startsAt = $bh->getStartsAt();
                
                if($bh->getStartsAt() <= $now && $now <= $bh->getEndsAt()){
                    //echo 'dd';die();
//                    if($endsAt > $endsAtMax){//Looks this will not need after violation
//                        $endsAtMax = $endsAt;
//                    }
                    return $endsAt;
                    
                    
                }
            }
        }                
    }
    
    protected function getClosesestShutdownHoursAfterMidnight($place)
    {
        $now = new \DateTime();        
        $tomorrow = clone $now;
        $tomorrow->modify('+1 day');
        $nextday = $tomorrow->format('l');
        $startsAtMidnight = new \DateTime('00:00:00');        
        $method = 'getDay' . ucfirst($nextday);        
                
        foreach ($place->getBusinessHours() as $bh){            
            $endsAt = $bh->getEndsAt();            
            $startsAt = $bh->getStartsAt();     
            
            if($bh->$method()){        
                if($bh->getStartsAt() == $startsAtMidnight){                                                                                    
//                        if($endsAt > $endsAtMax){
//                            $endsAtMax = $endsAt;
//                        }
                    return $endsAt;                        
                }
            }
        }
        
        return;
        //$r = new \DateTime($endsAtMax->format('H:i:s'));
        //var_dump($endsAtMax, 'beforereturn');
        //return $r;        
    }

    public function closeIn($place)
    {                    
        if(!$this->isWorkingNow($place)){
            return;
        }          
        
        $closeHours = $this->getClosestShutdownHours($place);

        if($closeHours && $closeHours->format('H:i:s') == '23:59:59')
        {
            if($endsAtAfterMidnight = $this->getClosesestShutdownHoursAfterMidnight($place))
            {                
                $shutdownHours = clone $endsAtAfterMidnight;
                $shutdownHours->modify('+1 day');

                return $shutdownHours;
            }                
        }

        return $closeHours;        
    }
    
    public function openIn($place)
    {
        return $this->getClosestOpenHours($place);
        return new \DateTime('+1 hour');
        //var_dump('dddddddddddddddddddddddd');die('????');
        if($this->isWorkingNow($place)){
            return;
        }
        
        return $this->getClosestOpenHours($place);
    }

    public function is24h($place)
    {
        return $place->getIs24h();
    }

    public function isWorkingNowOld($place)
    {
        $days = [];
        $holidays = [];
        $today = new \DateTime();
        $this->setPlace($place);
        
        //Holidays
        foreach (Days::toArray() as $key => $day){
            $dayName = strtoupper(Days::toString($day));
            $nextDayName = strtoupper(date('l',  strtotime($dayName . " + 1 day")));
            $dateOfWeek = $today->setISODate($today->format('o'), $today->format('W'), $day); 
            
            foreach ($place->getBusinessHoursException() as $bhE){                
                if($dateOfWeek->format('Y-m-d') === $bhE->getDay()->format('Y-m-d')){
                    if($bhE->getStartsAt() && $bhE->getEndsAt()){
                        $days[$dayName]['business_hours'][] = [$bhE->getStartsAt()->format('H:i'), $bhE->getEndsAt()->format('H:i')];
                        $days[$dayName]['isException'] = true;
                        continue;
                    }
                    $holidays[] = $bhE->getDay();
                }
            }        
        }
        
        $holidaysFinal = new Holidays($holidays);

        //Common days
        foreach (Days::toArray() as $key => $day){
            foreach ($place->getBusinessHours() as $bh){
                $dayName = strtoupper(Days::toString($day));
                $nextDayName = strtoupper(date('l',  strtotime($dayName . " + 1 day")));                                
                $method = 'getDay' . $dayName;
                
                if($bh->$method() && !isset($days[$dayName]['isException']))
                {
                    if($bh->getStartsAt() > $bh->getEndsAt())//IF Open late
                    {
                        $days[$dayName]['business_hours'][] = [$bh->getStartsAt()->format('H:i'), '23:59:59'];                                                
                        $days[$nextDayName]['business_hours'][] = ['00:00', $bh->getEndsAt()->format('H:i')];
                        continue;                        
                    }
                    $days[$dayName]['business_hours'][] = [$bh->getStartsAt()->format('H:i'), $bh->getEndsAt()->format('H:i')];
                }
            }
        }
        
        $r = new \ReflectionClass('Business\Days');        
        $daysFinal = [];
        foreach ($days as $key => $day){            
            $dayName = $r->getConstant($key);
            $daysFinal[] = new Day($dayName, $day['business_hours']);
        }
        
//        foreach ($daysFinal as $d){
//            var_dump($d);
//        }
        // Create a new Business instance
        //var_dump($daysFinal);die();
        $business = new Business($daysFinal, $holidaysFinal);
        //var_dump($today);
        return $business->within(new \DateTime());
        //var_dump($daysFinal, $holidaysFinal);die();
        
        //}
    }
            
    public function isWorkingNow2($place)
    {
        $this->setPlace($place);//???????
        
        $days = [];
        $r = new \ReflectionClass('Business\Days');
        
        
        foreach ($place->getBusinessHours() as $bh){            
            foreach (Days::toArray() as $key => $day){                
                $dayName = strtoupper(Days::toString($day));
                $nextDayName = strtoupper(date('l',  strtotime($dayName . " + 1 day")));                                
                $method = 'getDay' . $dayName;                                                           
                //TODO AFTER MIDNIGHT                
                
                if($exception = $this->isExceptionDay($day))
                {                        
                    if($exception->getStartsAt() > $exception->getEndsAt())
                    {
                        if(array_intersect([$exception->getStartsAt()->format('H:i'), '00:00'], $days[$dayName])){
                            echo 'in_array';
                            var_dump($days[$dayName], [$exception->getStartsAt()->format('H:i'), '00:00']);die();
                            $days[$dayName][] = [$exception->getStartsAt()->format('H:i'), '00:00'];                                                
                            $days[$nextDayName][] = ['00:00', $exception->getEndsAt()->format('H:i')];
                            continue;
                        }
                    }

                    $days[$dayName][] = [$exception->getStartsAt()->format('H:i'), $exception->getEndsAt()->format('H:i')];var_dump($days);die();

                }
                    
                if($bh->$method())
                {                                                                                      
                    if($bh->getStartsAt() > $bh->getEndsAt())
                    {
                        $days[$dayName][] = [$bh->getStartsAt()->format('H:i'), '00:00'];
                                                //var_dump($days);die();
                        $days[$nextDayName][] = ['00:00', $bh->getEndsAt()->format('H:i')];
                        continue;                        
                    }
                    $days[$dayName][] = [$bh->getStartsAt()->format('H:i'), $bh->getEndsAt()->format('H:i')];
                }
            }
        }
        
//        foreach($place->getBusinessHoursException() as $bhE){
//            if($bhE->getStartsAt()){
//                $dayName = strtoupper($bhE->getDay()->format('l')); 
//                $nextDayName = strtoupper(date('l',  strtotime($dayName . " + 1 day")));
//                
//                if($bhE->getStartsAt() > $bhE->getEndsAt())
//                {
//                    var_dump(in_array([$bhE->getStartsAt()->format('H:i'), '00:00'], $days[$dayName]), 'diff');
//                    if(!in_array([$bhE->getStartsAt()->format('H:i'), '00:00'], $days[$dayName])){
//                        $days[$dayName][] = [$bhE->getStartsAt()->format('H:i'), '00:00'];                                                
//                        $days[$nextDayName][] = ['00:00', $bhE->getEndsAt()->format('H:i')];
//                        continue;
//                    }
//                }
//                
//                $days[$dayName][] = [$bhE->getStartsAt()->format('H:i'), $bhE->getEndsAt()->format('H:i')];
//                //unset($days[$nextDayName][0]);
//            }
//        }
        
        var_dump($days, $holidaysFinal);die();
    }
//    public function isWorkingNow($place)
//    {
//        $bh = new \BusinessHours();
//        $hours = $this->generateHours();
//        $exceptions = [];
//        $r = new \ReflectionClass('Krombox\MainBundle\DBAL\Types\DayType');
//        //$id = $r->getConstant($thing);
//        
//        foreach ($place->getBusinessHours() as $bh){            
//            foreach (DayType::getChoices() as $key => $day){
//                $dn = strtoupper($day);
//                echo $r->getConstant($dn);die();
//                //echo constant("DayType::$dn");die();
//                $dayName = strtolower(date('D', strtotime($day)));                
//                $method = 'getDay' . $day;                
//                //TODO AFTER MIDNIGHT
//                if($bh->$method()) $hours[$dayName] = [$bh->getStartsAt()->format('H:i') . '-' . $bh->getEndsAt()->format('H:i')];
//            }
//        }
//        
//        foreach($place->getBusinessHoursException() as $bhE){
//            if($bhE->getStartsAt()){
//                $exceptions = array(
//                    $bhE->getDay()->format('m/d') => array($bhE->getStartsAt()->format('H:i') . '-' . $bhE->getEndsAt()->format('H:i'))                    
//                );
//            }
//        }
//        
//        $businessHours = new \BusinessHours($hours, $exceptions);
//        //var_dump($businessHours);
//        return $businessHours->is_open();
//        
//        
//        
////        $this->populateBusinessHours($place);die();
////        
////        
////        
////        
////        $this->setPlace($place);
////        /*TODO Make Validation for case if set from and to day the same(e.g from Monday to Monday) and fromTime > toTime*/        
////        if($place->getIs24h()) return true;            
////        /*TODO must be required*/
////        if(!$place->getBusinessHours()) return false;        
////        $isExceptionDayTime = $this->isWorkingExceptionDayTime();        var_dump($isExceptionDayTime);
////        if($isExceptionDayTime == null) return false;
////        if($isExceptionDayTime) return true;
////        
////        if($this->isWorkingDay() && $this->isWorkingTime());return true;               
////        
////        return false;
//    }        

    protected function isWorkingDay()
    {
        $dayOfWeek = date('l');                
        $previousDayOfweek = date('l', strtotime('1 day ago ' . $dayOfWeek));
        
        foreach ($this->place->getBusinessHours() as $bh){                        
            $method = 'getDay' . $dayOfWeek;            
            if($bh->$method()) return true;            
            if($bh->getStartsAt() >  $bh->getEndsAt()){
                $method = 'getDay' . $previousDayOfweek;                
                if($bh->$method()) return true;                
            }            
        }
        
        return false;
    }
    
    protected function isWorkingTime()
    {
        $today = new \DateTime();        
        $previousDayOfweek = date('l', strtotime('1 day ago ' . date('l')));
        
        foreach ($this->place->getBusinessHours() as $bh){                                    
            $startsAt = $bh->getStartsAt();
            $endsAt = $bh->getEndsAt();            
            $method = 'getDay' . $previousDayOfweek;                
            
            if ($startsAt > $endsAt && $bh->$method()){                
                if ($today > $startsAt){
                    $endsAt = $endsAt->add(new \DateInterval('PT24H'));
                }
                if ($today < $startsAt){                    
                    $startsAt = $startsAt->sub(new \DateInterval('PT24H'));
                }
            }
            
            if($startsAt <= $today && $endsAt >= $today)
                return true;                                    
        }
        
        return false;
    }

    protected function isWorkingExceptionDayTime()
    {
        $today = new \DateTime();        
        foreach ($this->place->getBusinessHoursException() as $bhEx){
            //var_dump($today->format('Y-m-d'), $bhEx->getDay()->format('Y-m-d'));
            if($today->format('Y-m-d') == $bhEx->getDay()->format('Y-m-d')){
                //IF not working ALL day
                if($bhEx->getIsClosed()){ return null;}
                //IF time now is between wxception time                
                if($bhEx->getStartsAt()->format('H:i:s') <= $today->format('H:i:s') && $today->format('H:i:s') <= $bhEx->getEndsAt()->format('H:i:s')) return true;
            }            
        }
        return false;
    }
} 
