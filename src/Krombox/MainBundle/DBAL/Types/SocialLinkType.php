<?php

namespace Krombox\MainBundle\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class SocialLinkType extends AbstractEnumType
{
    //const WEBSITE       = 'website';
    const VKONTAKTE     = 'vkontakte';    
    const FACEBOOK      = 'facebook';
    const TWITTER       = 'twitter';
    const GOOGLEPLUS    = 'google-plus';
    const YOUTUBE       = 'youtube';

    protected static $choices = [
        //self::WEBSITE       => 'Website',
        self::VKONTAKTE     => 'Vkontakte',
        self::FACEBOOK      => 'Facebook',
        self::TWITTER       => 'Twitter',
        self::GOOGLEPLUS    => 'GooglePlus',
        self::YOUTUBE       => 'YouTube'
    ];
}