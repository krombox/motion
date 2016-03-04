<?php

namespace Krombox\MainBundle\Event;

final class PlaceEvents
{
    const PRE_CREATE  = 'place.pre_create';
    const PRE_UPDATE  = 'place.pre_update';
    
    const PRE_SAVE  = 'place.pre_save';
    const POST_SAVE = 'place.post_save';
    const PRE_REMOVE = 'place.pre_remove';
    const POST_REMOVE = 'place.post_remove';
}