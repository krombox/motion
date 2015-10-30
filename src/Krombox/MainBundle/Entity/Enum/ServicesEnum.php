<?php

namespace Krombox\MainBundle\Entity\Enum;

use Krombox\CommonBundle\Model\Enum\AbstractEnum;

class ServicesEnum extends AbstractEnum
{
    protected static $choices = [
        '1' => 'Wi-Fi',
        '3' => 'Parking',
        'isDelivery' => 'Delivery',
        'isHookah'  => 'Hookah',
        //'isLiveMusic' => 'Live Music',
        'isOpenAir'   => 'Open air',
        'isSmokingLounge' => 'Smoking Lounge',
        'isDanceFloor'   => 'Dance floor',
        //'isStriptease' => 'Striptease',
        //'isSportBroadcast' => 'Sport Broadcast'        
    ];        
}
