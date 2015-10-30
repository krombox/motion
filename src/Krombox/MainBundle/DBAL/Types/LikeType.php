<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class LikeType extends AbstractEnumType
{
    const NOT_SET  = 0;
    const DOWN    = 1;
    const UP      = 2;    

    protected static $choices = [
        self::DOWN     => 'Down',
        self::UP       => 'Up',
        self::NOT_SET =>  'Notset'
    ];
}