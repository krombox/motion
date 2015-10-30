<?php

namespace Krombox\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Krombox\MainBundle\Entity\Rating;

class RatingEvent extends Event
{
    protected $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    public function getRating()
    {
        return $this->rating;
    }
}

