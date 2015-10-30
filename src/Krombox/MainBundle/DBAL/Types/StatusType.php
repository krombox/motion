<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class StatusType extends AbstractEnumType
{
    const PENDING   = 'pending';
    const VALIDATED = 'validated';
    const TEST      = 'test';    
    
//    const PENDING    = 'pending';
//    const VALIDATED = 'validated';
//    const TEST  = 'test';

    protected static $choices = [
        self::PENDING    => 'Pending',
        self::VALIDATED => 'Validated',
        self::TEST  => 'Test'        
    ];
}