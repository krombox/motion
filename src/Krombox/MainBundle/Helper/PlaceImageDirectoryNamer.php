<?php

namespace Krombox\MainBundle\Helper;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class PlaceImageDirectoryNamer implements DirectoryNamerInterface {
    
    public function directoryName($object, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping) {
        return 'motion/images/place';
    }
    
    public function getDirectoryName(){
        return 'place';
    }
}

