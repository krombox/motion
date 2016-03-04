<?php

namespace Krombox\CommonBundle\Model\Helper;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class DayFlaggableHelper
{
    const STARTS_FROM = 'Monday';

    public static function getWeekdays($startsFrom = null)
    {
        if (!isset($startsFrom)) {
            $startsFrom = self::STARTS_FROM;
        }
        $timestamp = strtotime('next ' . $startsFrom);
        $days      = array();
        for ($i = 0; $i < 7; $i++) {
            $txt                    = strftime('%A', $timestamp);
            $days[strtolower($txt)] = $txt;
            $timestamp              = strtotime('+1 day', $timestamp);
        }

        return $days;
    }

    public static function buildFields(FormBuilderInterface $builder)
    {
        $days = self::getWeekdays();
        foreach ($days as $key => $label) {
            $builder->add('day' . ucwords($key), 'checkbox', array('label' => $label, 'required' => false));
        }
    }

    public static function fillFields($entity, $val)
    {
        $pa = PropertyAccess::createPropertyAccessor();
        foreach (self::getWeekdays() as $key => $label) {
            $pa->setValue($entity, 'day' . ucwords($key), $val);
        }
    }

    public static function countFields($entity, $val)
    {
        $count = 0;
        $pa = PropertyAccess::createPropertyAccessor();
        foreach (self::getWeekdays() as $key => $label) {
            if($pa->getValue($entity, 'day' . ucwords($key)) == $val){
                $count++;
            }
        }
        return $count;
    }
    
    public static function nextDay($currentDay)
    {
       return date('l', strtotime(' +1 day', strtotime($currentDay)));
    }
} 
