<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class PaymentStatusType extends AbstractEnumType
{
    const PENDING   = 'pending';
    const SUCCESS = 'success';
    const FAILURE      = 'failure';
    const PROCESSING = 'processing';
    const SANDBOX = 'sandbox';
    const CASH_WAIT = 'cash_wait';    

    protected static $choices = [
        self::PENDING    => 'Pending',
        self::SUCCESS => 'Success',
        self::FAILURE      => 'Failure',
        self::PROCESSING => 'Processing',
        self::SANDBOX => 'Sandbox',
        self::CASH_WAIT => 'Cash_wait'
    ];
    
//    protected static $choicesReversed = [
//        => 'Pending',
//        self::SUCCESS => 'Success',
//        self::FAILURE      => 'Failure',
//        self::PROCESSING => 'Processing',
//        self::SANDBOX => 'Sandbox',
//        self::CASH_WAIT => 'Cash_wait'
//    ];
    
    public static function getConstByValue($value){
        $class = new \ReflectionClass('Krombox\MainBundle\DBAL\Types\PaymentStatusType');
        return $class->getConstant(strtoupper($value));
        var_dump($value, $class->getConstant(strtoupper($value)));        
    }
}