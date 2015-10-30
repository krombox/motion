<?php

namespace Krombox\MainBundle\Wrapper;

use JMS\Serializer\Annotation as Serializer;

class EventWrapper
{
    /** @Serializer\Groups({"full", "lite"}) */
    private $id;
    /** @Serializer\Groups({"full", "lite"}) */
    private $name;
    /** @Serializer\Groups({"full", "lite"}) */
    private $title;
    /** @Serializer\Groups({"full", "lite"}) */
    private $description;
    /** @Serializer\Groups({"full", "lite"}) */
    private $start;
    /** @Serializer\Groups({"full", "lite"}) */
    private $end;
    /** @Serializer\Groups({"full", "lite"}) */
    private $priceLow;
    /** @Serializer\Groups({"full", "lite"}) */
    private $priceHigh;
    

    public function __construct($data)
    {
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
}
