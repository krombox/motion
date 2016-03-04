<?php

namespace Krombox\MainBundle\Validator\Constraints;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Validator("business_hours_exception_validator")
 */
class BusinessHoursConstraintExceptionValidator extends ConstraintValidator
{
    /**
     * @DI\InjectParams({
     *     "manager" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param BookingSchedule                $value
     * @param ProviderAvailabilityConstraint $constraint
     * @throws Exception
     */
    public function validate($value, Constraint $constraint)
    {        
        foreach ($value->getPlace()->getBusinessHoursException() as $bhE)
        {
            if($bhE->getId() == $value->getId()){
                continue;
            }
            
            //foreach (DayFlaggableHelper::getWeekdays() as $day){
                //$method = 'getDay' . $day;
                
            if($value->getDay() == $bhE->getDay())
            {
                if($value->getStartsAt() >= $bhE->getStartsAt() && $bhE->getEndsAt() >= $value->getStartsAt()){                
                    $this->context->buildViolation($constraint->openAtMessage)
                    ->setParameter('%openAt%', $value->getStartsAt()->format('H:i:s'))
                    ->setParameter('%startsAt%', $bhE->getStartsAt()->format('H:i:s'))
                    ->setParameter('%endsAt%', $bhE->getEndsAt()->format('H:i:s'))
                    ->setParameter('%day%', $value->getDay()->format('Y-m-d'))        
                    ->atPath('startsAt')        
                    ->addViolation();
                    break;
                }

                if($value->getEndsAt() >= $bhE->getStartsAt() && $bhE->getEndsAt()  >= $value->getEndsAt()){                
                    $this->context->buildViolation($constraint->closeAtMessage)
                    ->setParameter('%closeAt%', $value->getEndsAt()->format('H:i:s'))
                    ->setParameter('%startsAt%', $bhE->getStartsAt()->format('H:i:s'))
                    ->setParameter('%endsAt%', $bhE->getEndsAt()->format('H:i:s'))
                    ->setParameter('%day%', $value->getDay()->format('Y-m-d'))        
                    ->atPath('endsAt')        
                    ->addViolation();
                    break;
                }
            }                                                                        
        }
    }
}

