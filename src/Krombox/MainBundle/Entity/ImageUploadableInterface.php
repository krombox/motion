<?php

namespace Krombox\MainBundle\Entity;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
interface ImageUploadableInterface 
{    
    public function setPath($path);
    
    public function getPath();
    
    public function setImageId($imageId);
    
    public function getImageId();
}
