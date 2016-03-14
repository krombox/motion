<?php

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
namespace Krombox\MainBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Rating;
use Krombox\MainBundle\Entity\Place;

/**
 * @DI\Service("krombox.dropzone_image_resolver")
 */
class DropzoneImageResolver {
    private $em;

    /**
     * @DI\InjectParams({     
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;
    }
    
    public function getImagePath($id, $class)
    {
        
        $entity = $this->em->getRepository('Krombox\MainBundle\Entity\PlaceHallImage')->findOneBy(['id' => $id]);
        
        //var_dump($id,  $entity);die('getImagePath');
        if(!$entity)
        {
            return 'noimage.png';
        }
        
        return $entity->getPath();
        
    }
}
