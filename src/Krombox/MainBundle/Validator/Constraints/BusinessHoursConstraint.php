<?php

namespace Krombox\MainBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class BusinessHoursConstraint extends Constraint
{
    public $openAtMessage = 'Open time %openAt% can`t be between %startsAt% and %endsAt% on %day%';
    public $closeAtMessage = 'Close time %closeAt% can`t be between %startsAt% and %endsAt% on %day%';
    public $endsMessage = 'The end date must be after the start';
    public $daysMessage = 'You must define at least one day';
    public $dateOldMessage = 'You can\'t define an old date';
    public $dateMinHoursInAdvance = 'You must book at least %hours% hours in advance';
    public $busyMessage = '%provider% isn\'t available from %from% to %to%';
    public $deniedChangedHoursReasonMessage = 'You can\'t define changed hours reason because changed hours must be less than initial hours';

    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }

    public function validatedBy(){
        return 'business_hours_validator';
    }
}
