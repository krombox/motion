<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\Serializer\Annotation as Serializer;

class PlaceWrapper
{
    /** @Serializer\Groups({"full", "lite"}) */
    private $id;
    /** @Serializer\Groups({"full", "lite"}) */
    private $name;        
    

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
