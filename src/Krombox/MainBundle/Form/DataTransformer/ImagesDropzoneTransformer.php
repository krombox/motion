<?php

namespace Krombox\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Krombox\MainBundle\Entity\PlaceHallImage;

class ImagesDropzoneTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;
    
    private $separator = ',';

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (PlaceImage) to a string (id).
     *
     * @param  PlaceImage|null $collection
     * @return string
     */
    public function transform($collection)
    {   
        if($collection == null) return;
        $result = array();
        if($collection != null){
            foreach ($collection as $item) {                
                echo $item->getId();
            }
        }
        //var_dump('transformer',$collection);
//        if ($collection) {
//            foreach ($collection as $item) {                
//                $result[] = $item->getId();
//            }
//        }
        
        return $collection;
        //return implode($this->separator, $result);
    }

    /**
     * Transforms a string (id) to an object (PlaceImage).
     *
     * @param  string $ids
     *
     * @return PlaceImage[]|null
     *
     * @throws TransformationFailedException if object (PlaceImage) is not found.
     */
    public function reverseTransform($ids)
    {
        
        //var_dump($ids);die('transformer');
        
        
        
        if(!$ids)
            return null;
        
        $ids = explode($this->separator, $ids);
                
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($ids as $id) {
            if ($tmp = $this->om->getRepository(PlaceHallImage::class)->findOneBy(['id' => $id])) {                
                //var_dump($tmp);die();
                $collection->add($tmp);
            }
        }                

        return $collection;
    }     
}
