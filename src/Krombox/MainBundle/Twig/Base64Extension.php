<?php

namespace Krombox\MainBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * adds some nice features to more easy twig templating
 *
 * @DI\Service("base64_extension")
 * @DI\Tag("twig.extension")
 */
class Base64Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('base64encode', array($this, 'base64encode')),
            new \Twig_SimpleFilter('base64decode', array($this, 'base64decode')),
            new \Twig_SimpleFilter('base64encodefile', array($this, 'base64encodefile')),
            new \Twig_SimpleFilter('base64decodefile', array($this, 'base64decodefile')),
        );
    }

    public function base64encode($string)
    {
        return base64_encode($string);
    }

    public function base64decode($string)
    {
        return base64_decode($string);
    }

    public function base64encodefile($path)
    {
        return base64_encode(file_get_contents($path));
    }

    public function base64decodefile($path)
    {
        return base64_decode(file_get_contents($path));
    }


    public function getName()
    {
        return 'base64_extension';
    }
}