<?php

namespace Krombox\CommonBundle\Model\Enum;

abstract class AbstractEnum 
{
    public static function getChoices()
    {
        return static::$choices;
    }
}