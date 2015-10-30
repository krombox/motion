<?php

namespace Krombox\MainBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Krombox\MainBundle\Entity\MyTag;

class MyTagTransformer implements DataTransformerInterface
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
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($collection)
    {
        $result = array();
        
        if ($collection) {
            foreach ($collection as $item) {
                $result[] = $item->getName();
            }
        }
        
        return implode($this->separator, $result);
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $number
     *
     * @return Issue|null
     *
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($tags)
    {
        if(!$tags)
            return null;
        
        $tags = explode($this->separator, $tags);
        
        $ret = array();
        $collection = new \Doctrine\Common\Collections\ArrayCollection();
        
        foreach ($tags as $t) {
            if ($tmp = $this->om->getRepository(MyTag::class)->findOneBy(['name' => $t])) {                
                $collection->add($tmp);
            } else {
                $tag = new MyTag();
                $tag->setName($t);
                $this->om->persist($tag);
                $this->om->flush();
                /*Add ne tag to collection*/
                $collection->add($tag);
            }
        }                

        return $collection;
    }     
}
