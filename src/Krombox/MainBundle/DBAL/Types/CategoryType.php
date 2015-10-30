<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class CategoryType extends AbstractEnumType
{
    const PLACE     = 1;
    const EVENT     = 2;    

    protected static $choices = [
        self::PLACE    => 'Place',
        self::EVENT => 'Event'        
    ];
}