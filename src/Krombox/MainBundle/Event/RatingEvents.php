<?php

namespace Krombox\MainBundle\Event;

final class RatingEvents
{
    const PRE_SAVE  = 'rating.pre_save';
    const POST_SAVE = 'rating.post_save';
    const PRE_REMOVE = 'rating.pre_remove';
    const POST_REMOVE = 'rating.post_remove';
}