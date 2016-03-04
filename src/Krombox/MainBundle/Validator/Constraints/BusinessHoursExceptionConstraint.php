<?php

namespace Krombox\MainBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class BusinessHoursExceptionConstraint extends Constraint
{
    public $openAtMessage = 'Open time %openAt% can`t be between %startsAt% and %endsAt% on %day%';
    public $closeAtMessage = 'Close time %closeAt% can`t be between %startsAt% and %endsAt% on %day%';    

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    public function validatedBy(){
        return 'business_hours_exception_validator';
    }
}
