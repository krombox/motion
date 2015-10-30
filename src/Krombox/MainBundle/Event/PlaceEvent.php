<?php

namespace Krombox\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Krombox\MainBundle\Entity\Place;

class PlaceEvent extends Event
{
    protected $place;

    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    public function getPlace()
    {
        return $this->place;
    }
}

