<?php

namespace Krombox\CommonBundle\Wrapper;

use JMS\DiExtraBundle\Annotation as DI;

abstract class AbstractWrapperFactory implements WrapperFactory
{
    /** @var  WrapperProvider */
    private $provider;

    /**
     * @DI\InjectParams({
     *     "provider"  = @DI\Inject("wrapper_provider"),
     * })
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return WrapperProvider
     */
    protected function getProvider(){
        return $this->provider;
    }

    //abstract public function wrap($date);
    
    public function wrap($data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }
        if (!isset($data)) {
            return null;
        } elseif (is_array($data)) {
            return array_map([$this, 'wrap'], $data);
        } elseif (is_object($data)) {
            return $this->getFactoryFor(get_class($data))->wrap($data);
        } else {
            return null;
        }
    }
}
