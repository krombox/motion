<?php

namespace Krombox\MainBundle\Entity\Enum;

use Krombox\CommonBundle\Model\Enum\AbstractEnum;

class RelaxationsEnum extends AbstractEnum
{
    protected static $choices = [        
        'isLiveMusic' => 'Live Music',        
        'isStriptease' => 'Striptease',
        'isSportBroadcast' => 'Sport Broadcast',
        'isBilliards' => 'Billiards',
        'isGameConsole' => 'Game console',
        'isBoardGame' => 'Board Game'
    ];        
}
