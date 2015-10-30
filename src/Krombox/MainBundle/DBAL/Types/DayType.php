<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class DayType extends AbstractEnumType
{    
    const MONDAY     = 'monday';
    const TUESDAY    = 'tuesday';
    const WEDNESDAY  = 'wednesday';
    const THURSDAY   = 'thursday';
    const FRIDAY     = 'friday';
    const SATURDAY   = 'saturday';
    const SUNDAY     = 'sunday';
    

    public static $choices = [        
        self::MONDAY    => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY  => 'Wednesday',
        self::THURSDAY  => 'Thursday',
        self::FRIDAY  => 'Friday',
        self::SATURDAY  => 'Saturday',
        self::SUNDAY  => 'Sunday'
    ];
    
    public static $shortLabels = [        
        self::MONDAY    => 'mon',
        self::TUESDAY => 'tue',
        self::WEDNESDAY  => 'Wednesday',
        self::THURSDAY  => 'Thursday',
        self::FRIDAY  => 'Friday',
        self::SATURDAY  => 'Saturday',
        self::SUNDAY  => 'Sunday'
    ];
    
    
}