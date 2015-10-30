<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class MembershipType extends AbstractEnumType
{
    const STANDART  = 'standart';
    const SILVER    = 'silver';
    const GOLD      = 'gold';    

    protected static $choices = [
        self::STANDART     => 'standart',
        self::SILVER       => 'silver',
        self::GOLD         => 'gold'
    ];
    
    public static $score = [
        self::STANDART => 0,
        self::SILVER   => 1,
        self::GOLD     => 2
    ];


    protected static $payableChoices = [        
        self::SILVER       => 'silver',
        self::GOLD         => 'gold'
    ];
    
    public static function getPayableChoices($type = 0){
        $choices = [];
        foreach (static::$payableChoices as $key => $choice){
            //var_dump($key);die();
            if($key >= $type){
                $choices[] = $choice;
            }
        }
        return $choices;
    }
}