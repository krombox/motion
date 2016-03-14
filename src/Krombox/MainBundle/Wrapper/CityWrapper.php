<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\Serializer\Annotation as Serializer;

class CityWrapper
{
    /** @Serializer\Groups({"full", "lite"}) */
    private $id;
    /** @Serializer\Groups({"full", "lite"}) */
    private $name;
    /** @Serializer\Groups({"full", "lite"}) */
    private $slug;
   
    

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
