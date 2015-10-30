<?php

namespace Krombox\MainBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * adds some nice features to more easy twig templating
 *
 * @DI\Service("krombox.copy_com_image_extension")
 * @DI\Tag("twig.extension")
 */
class CopyComImageExtension extends \Twig_Extension
{            
    private $imageResolver;

    /**
     * @DI\InjectParams({     
     *     "imageResolver" = @DI\Inject("krombox.copy_com_image_resolver")
     * })
     */
    public function __construct($imageResolver)
    {        
        $this->imageResolver = $imageResolver;
    }

    public function getFunctions()
    {
        return array(            
            'generateImageUrl' => new \Twig_Function_Method($this, 'generateImageUrl'),
        );
    }    
    
    public function generateImageUrl($path, $type)
    {
        return $this->imageResolver->getImagePath($path, $type);
    }   

    public function getName()
    {
        return get_class($this);
    }

}