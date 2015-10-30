<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class KitchenType extends AbstractEnumType
{
    const EUROPEAN       = 1;
    const UKRAINIAN      = 2;    
    const ASIAN          = 3;    
    const ITALIAN        = 4;
    const JAPANESE       = 5;    

    protected static $choices = [
        self::EUROPEAN    => 'Europian',
        self::UKRAINIAN => 'Ukrainian',
        self::ASIAN => 'Asian',
        self::ITALIAN => 'Italian',
        self::JAPANESE => 'Japanese',
    ];
}