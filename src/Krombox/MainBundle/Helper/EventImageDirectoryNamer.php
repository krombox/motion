<?php

namespace Krombox\MainBundle\Helper;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class EventImageDirectoryNamer implements DirectoryNamerInterface {
    
    public function directoryName($object, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping) {
        return 'motion/images/event';
    }
    
    public function getDirectoryName(){
        return 'event';
    }
}

