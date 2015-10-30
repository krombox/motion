<?php

namespace Krombox\MainBundle\Helper;

use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class CategoryImageDirectoryNamer implements DirectoryNamerInterface {
    
    public function directoryName($object, \Vich\UploaderBundle\Mapping\PropertyMapping $mapping) {
        return 'motion/images/category';
    }
    
    public function getDirectoryName(){
        return 'category';
    }
}

