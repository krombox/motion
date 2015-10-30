<?php

namespace Krombox\MainBundle\Helper;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class PlaceHallImageDirectoryNamer implements DirectoryNamerInterface {
    
    public function directoryName($object, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping) {
        return 'motion/images/place_hall';
    }
    
    public function getDirectoryName(){
        return 'place_hall';
    }
}

