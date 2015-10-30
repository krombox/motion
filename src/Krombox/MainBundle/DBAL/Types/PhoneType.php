<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class PhoneType extends AbstractEnumType
{
    const RESEPTION       = 1;
    const DELIVERY        = 2;    
    const RESERVATION     = 3;    

    protected static $choices = [
        self::RESEPTION    => 'Reception',
        self::DELIVERY => 'Delivery',
        self::RESERVATION => 'Reservation'
    ];
}