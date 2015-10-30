<?php

namespace Krombox\PaymentBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Rating;
use Krombox\MainBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Request;
use Krombox\PaymentBundle\Entity\OrderMembership;
use Krombox\MainBundle\Entity\MembershipSubscription;
use Krombox\MainBundle\DBAL\Types\MembershipStatusType;
use Krombox\Constants;

/**
 * @DI\Service("krombox.order_membership_manager")
 */
class OrderMembershipManager
{
    
    private $em;    

    /**
     * @DI\InjectParams({
     *  "em" = @DI\Inject("doctrine.orm.entity_manager")     
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;        
    }
    
//    public function calculateExrpireDate(OrderMembership $orderMembership){
//        $placeMembership = $orderMembership->getPlace()->getMembership();        
//        return $placeMembership->getEndsAt()->modify('+ ' . $orderMembership->getDaysCount() . ' day');
//    }
    
    //public function calculateExrpireDate(MembershipSubscription $membership, $daysCount){
    public function calculateExrpireDate($fromDate, $daysCount)
    {              
        $from = clone $fromDate;
        return $from->modify('+ ' . $daysCount . ' days');//TODO http://stackoverflow.com/questions/15486402/doctrine2-orm-does-not-save-changes-to-a-datetime-field                
    }
    
    public function extendMembership(OrderMembership $orderMembership)
    {        
        $placeMembershipSubscription = $orderMembership->getPlace()->getActiveMembershipSubscription();
        $expireDate = $this->calculateExrpireDate($placeMembershipSubscription->getEndsAt(), $orderMembership->getDaysCount());        
        $placeMembershipSubscription->setEndsAt($expireDate);
        
        $this->em->persist($placeMembershipSubscription);
        $this->em->flush();                
        return true;        
    }
    
    public function changeMembership(OrderMembership $orderMembership)
    {        
        $place = $orderMembership->getPlace();
        $additionalDays = $this->calculateAdditionalDays($orderMembership);
        $days = $additionalDays + $orderMembership->getDaysCount();
        $expireDate = $this->calculateExrpireDate(new \DateTime(), $days);
        //var_dump($additionalDays,'additinaldays', $days, $orderMembership->getDaysCount(), $expireDate);die();
        $this->deactivatePlaceMembership($place);
        
        $membershipSubscriptionNew = new MembershipSubscription();
        $membershipSubscriptionNew->setEndsAt($expireDate)
                                   ->setPlace($orderMembership->getPlace())
                                   ->setMembership($orderMembership->getMembership())
        ;
        
        $this->em->persist($membershipSubscriptionNew);
        $this->em->flush();
        
        return true;        
    }
    
    protected function deactivatePlaceMembership(Place $place)
    {
        foreach ($place->getMembershipSubscriptions() as $subscription){
            if($subscription->getStatus() == MembershipStatusType::ACTIVE){
                $subscription->setStatus(MembershipStatusType::INACTIVE);
                $subscription->setEndsAt(new \DateTime("now"));
            }
            $this->em->persist($subscription);            
        }        
        $this->em->flush();
    }

    public function calculateAdditionalDays(OrderMembership $orderMembership){
        $placeMembershipSubscription = $orderMembership->getPlace()->getActiveMembershipSubscription();                        
        $leftDays =  $this->calculateLeftDays($placeMembershipSubscription);
        $orderMembershipPrice = $orderMembership->getMembership()->getPrice();
        $subscruptionMembershipPrice = $placeMembershipSubscription->getMembership()->getPrice();
        
        if($orderMembershipPrice <= 0){ return $leftDays;}
        
        $additionalDays = floor(($leftDays * $subscruptionMembershipPrice) / $orderMembershipPrice);                
        return $additionalDays;
    }

    protected function calculateLeftDays(MembershipSubscription $membership){
        if(!$membership->getEndsAt()) return 0;
        
        $interval = $membership->getEndsAt()->diff(new \DateTime());
        //var_dump($interval);
        if($interval->h >= Constants::DAY_MIN_HOURS){
            return $interval->d + 1;
        }
        
        return $interval->d;
    }
} 
