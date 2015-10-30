<?php

namespace Krombox\MainBundle\Twig;

use Krombox\MainBundle\Helper\RemoteCacheManager;

class MyImagineExtension extends \Twig_Extension
{
    
    /**
     * @var RemoteCacheManager
     */
    private $cacheManager;

    /**
     * Constructor.
     *
     * @param CacheManager $cacheManager
     */
    public function __construct(RemoteCacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('customImagine', array($this, 'customImagineFilter')),
        );
    }

    public function customImagineFilter($path, $filter, array $runtimeConfig = array())
    {        
        
        return new \Twig_Markup(
            $this->cacheManager->getBrowserPath($path, $filter, $runtimeConfig),
            'utf8'
        );
    }

    public function getName()
    {
        return 'custom_imagine_extension';
    }
}
