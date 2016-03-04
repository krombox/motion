<?php

namespace Krombox\MainBundle\Validator\Constraints;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Validator("business_hours_validator")
 */
class BusinessHoursConstraintValidator extends ConstraintValidator
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
        foreach ($value->getPlace()->getBusinessHours() as $bh)
        {
            if($bh->getId() == $value->getId()){
                continue;
            }
            
            foreach (DayFlaggableHelper::getWeekdays() as $day){
                $method = 'getDay' . $day;
                
                if($value->$method() && $bh->$method())
                {
                    if($value->getStartsAt() >= $bh->getStartsAt() && $bh->getEndsAt() >= $value->getStartsAt()){                
                        $this->context->buildViolation($constraint->openAtMessage)
                        ->setParameter('%openAt%', $value->getStartsAt()->format('H:i:s'))
                        ->setParameter('%startsAt%', $bh->getStartsAt()->format('H:i:s'))
                        ->setParameter('%endsAt%', $bh->getEndsAt()->format('H:i:s'))
                        ->setParameter('%day%', $day)        
                        ->atPath('startsAt')        
                        ->addViolation();
                        break;
                    }

                    if($value->getEndsAt() >= $bh->getStartsAt() && $bh->getEndsAt()  >= $value->getEndsAt()){                
                        $this->context->buildViolation($constraint->closeAtMessage)
                        ->setParameter('%closeAt%', $value->getEndsAt()->format('H:i:s'))
                        ->setParameter('%startsAt%', $bh->getStartsAt()->format('H:i:s'))
                        ->setParameter('%endsAt%', $bh->getEndsAt()->format('H:i:s'))
                        ->setParameter('%day%', $day)        
                        ->atPath('endsAt')        
                        ->addViolation();

                        break;
                    }
                }
            }                                                            
        }
//        return;
//        //die();
//        var_dump('business hours validator' . $value->getId()) . '-';return;
//        if (!$value instanceof Booking) {
//            throw new Exception('bad entity');
//        }
//        $start = $value->getStartsAt();
//        $end   = $value->getEndsAt();
//        if ($start >= $end) {
//            $this->context->addViolationAt('endsAt', $constraint->endsMessage);
//        }
//        if($value->getChangedHours() >= $value->getInitialHours() && $value->getChangedHoursReason()){
//            $this->context->addViolationAt('changedHourseReason', $constraint->deniedChangedHoursReasonMessage);
//        }
//        // new booking rules
//        if (!$value->getId()){
//            if($start < new \DateTime()) {
//                $this->context->addViolationAt('startsAt', $constraint->dateOldMessage);
//            }
//            $h = Constants::BOOKING_MIN_HOURS_IN_ADVANCE;
//            if($start < new \DateTime(sprintf('+%d hours', $h))){
//                $this->context->addViolationAt('startsAt', $constraint->dateMinHoursInAdvance, ['%hours%' => $h]);
//            }
//            if ($book = $this->validateProvider($value)) {
//                $this->context->addViolation(
//                    $constraint->busyMessage,
//                    [
//                        '%provider%' => $value->getProvider(),
//                        '%from%' => $book->getStartsAt()->format('d M Y H:i'),
//                        '%to%' =>   $book->getEndsAt()->format('d M Y H:i')
//                    ]
//                );
//            }
//        }
    }
}

