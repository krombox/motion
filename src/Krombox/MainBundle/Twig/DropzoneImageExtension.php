<?php

namespace Krombox\MainBundle\Twig;

use JMS\DiExtraBundle\Annotation as DI;
/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
/**
 * adds some nice features to more easy twig templating
 *
 * @DI\Service("krombox.dropzone_image_extension")
 * @DI\Tag("twig.extension")
 */
class DropzoneImageExtension extends \Twig_Extension
{
    private $imageResolver;

    /**
     * @DI\InjectParams({     
     *     "imageResolver" = @DI\Inject("krombox.dropzone_image_resolver")
     * })
     */
    public function __construct($imageResolver)
    {        
        $this->imageResolver = $imageResolver;
    }

    public function getFunctions()
    {
        return array(            
            'imageUrl' => new \Twig_Function_Method($this, 'imageUrl'),
        );
    }    
    
    public function imageUrl($id, $class)
    {
        return $this->imageResolver->getImagePath($id, $class);
    }   

    public function getName()
    {
        return get_class($this);
    }
    
}
