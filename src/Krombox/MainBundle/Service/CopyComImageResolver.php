<?php

namespace Krombox\MainBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Rating;
use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service("krombox.copy_com_image_resolver")
 */
class CopyComImageResolver
{    
    private $container;

    /**
     * @DI\InjectParams({     
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function getImagePath($path, $type)
    {
        $mapping = $this->container->getParameter('vich_uploader.mappings');
        
        if(!isset($mapping[$type]))
            throw new \Exception ('Mapping ' . $type . ' doesnt exist');
        
        $directoryName = $this->container->get($type. '_directory_namer')->getDirectoryName();                        
        $url = $mapping[$type]['uri_prefix'] . '/' . $directoryName . '/' . $path . '?size=1024';
        
        return $url;
    }
} 
