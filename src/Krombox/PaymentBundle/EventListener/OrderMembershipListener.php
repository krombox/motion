<?php

namespace Krombox\PaymentBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\PaymentBundle\Event\OrderMembershipEvent;
use Krombox\PaymentBundle\Event\OrderMembershipEvents;
/**
 * @DI\Service
 */
class OrderMembershipListener
{        
    private $em;
    
    private $security;
    
    private $omManager;

    /**
     * @DI\InjectParams({           
     *     "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *     "security" = @DI\Inject("security.context"),
     *     "omManager" = @DI\Inject("krombox.order_membership_manager")
     * })
     */
    public function __construct($em, $security, $omManager)
    {     
        $this->em = $em;
        $this->security = $security;
        $this->omManager = $omManager;
    }

    /**
     * @DI\Observe(OrderMembershipEvents::POST_SAVE, priority=0)     
     */
    public function onOrderMembershipPostSave(OrderMembershipEvent $event)
    {
        $orderMembership = $event->getOrderMembership();
        $placeMembership = $orderMembership->getPlace()->getMembership();
        
        if($orderMembership->getMembership() === $placeMembership){
            //echo 'SAVE';
            //$expireDate = $this->omManager->extendMembership($orderMembership);
            $expireDate = $this->omManager->extendMembership($orderMembership);            
            //var_dump($expireDate,'extend');die();
        }
        
        $expireDate = $this->omManager->changeMembership($orderMembership);            
        //var_dump($expireDate,'change');die();
            
        //var_dump($orderMembership->getStatus());die();
        //$this->security->isGranted('ROLE_USER_VERIFIED') ? $status = StatusType::VALIDATED : $status = StatusType::PENDING;
        //$place->setStatus($status);                
    }        
}
