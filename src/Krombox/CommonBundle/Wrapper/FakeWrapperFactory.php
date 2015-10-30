<?php

namespace Krombox\CommonBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;
use Doctrine\Common\Persistence\ObjectManager;

class FakeWrapperFactory extends AbstractWrapperFactory
{
    public function wrap($data)
    {
        return $data;
    }
}
