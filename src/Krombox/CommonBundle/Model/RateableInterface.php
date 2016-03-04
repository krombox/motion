<?php

namespace Krombox\CommonBundle\Model;

interface RateableInterface
{
    public function getVisitableId();
    
    public function getVisitableType();

    function getViewsCount();

    function setViewsCount($count);
}