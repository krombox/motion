<?php

namespace Krombox\CommonBundle\Model;

interface VisitableInterface
{
    function getRating();

    function setRating($rating);

    function getRatingCount();

    function setRatingCount($count);
}