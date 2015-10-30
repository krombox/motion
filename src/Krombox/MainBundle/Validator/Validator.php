<?php

namespace Vendor\Package;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Validator
{
    public static function validate($object, ExecutionContextInterface $context)
    {
        var_dump($object);die();
    }
}
