<?php

namespace Krombox\MainBundle\Entity\Traits;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
trait ImageUploadableEntity {
    
    protected $path;
    
    protected $imageId;
    
    public function setPath($path){
        $this->path = $path;
    }
    
    public function getPath(){
        return $this->path;
    }
    
    public function setImageId($imageId){
        $this->imageId = $imageId;                
    }
    
    public function getImageId(){
        return $this->imageId;
    }
}
