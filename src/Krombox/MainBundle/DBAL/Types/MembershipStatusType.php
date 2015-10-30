<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MembershipStatusType extends AbstractEnumType
{
    const ACTIVE  = 'active';
    const INACTIVE    = 'inactive';    

    protected static $choices = [
        self::ACTIVE     => 'Active',
        self::INACTIVE       => 'Inactive'        
    ];               
}