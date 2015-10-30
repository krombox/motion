<?php

namespace Krombox\PaymentBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Krombox\PaymentBundle\Entity\OrderMembership;

class OrderMembershipEvent extends Event
{
    protected $orderMembership;

    public function __construct(OrderMembership $orderMembership)
    {
        $this->orderMembership = $orderMembership;
    }

    public function getOrderMembership()
    {
        return $this->orderMembership;
    }
}

