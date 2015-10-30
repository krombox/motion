<?php

namespace Krombox\CommonBundle\Model\Helper;

use Krombox\CommonBundle\Model\RateableInterface;
use Krombox\Constants;

final class RatingHelper
{
    public static function addRating(RateableInterface $obj, $rating)
    {
        if ($rating > Constants::RATING_MAX) {
            throw new \Exception($rating . ' exceeds the maximum rating');
        } elseif ($rating > 0) {
            $count = $obj->getRatingCount();
            $sum   = ($obj->getRating() * $count) + $rating;
            $count++;
            $obj->setRatingCount($count);
            $obj->setRating($sum / $count);
        }
    }

    public static function subRating(RateableInterface $obj, $rating)
    {
                
        if ($rating > Constants::RATING_MAX) {
            throw new \Exception($rating . ' exceeds the maximum rating');
        } elseif ($rating > 0) {
            $count = $obj->getRatingCount();
            $sum   = ($obj->getRating() * $count) - $rating;
            $count--;
            if ($count <= 0) {
                $obj->setRatingCount(0);
                $obj->setRating(0);
            } else {
                $obj->setRatingCount($count);
                $obj->setRating($sum / $count);
            }
        }
    }

} 
