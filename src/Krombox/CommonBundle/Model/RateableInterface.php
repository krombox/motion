<?php

namespace Krombox\CommonBundle\Model;

interface RateableInterface
{
    function getRating();

    function setRating($rating);

    function getRatingCount();

    function setRatingCount($count);
}