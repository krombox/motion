<?php

namespace Krombox\MainBundle\Handler;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Krombox\MainBundle\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */

/**
 * @DI\Service("krombox.upload_handler")
 */
class UploadHandler {
    
    protected $adapter;
    
    /**
     * @DI\InjectParams({     
     *     "adapter" = @DI\Inject("krombox.flickr_adapter")
     * })
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function upload($obj, $fieldName)
    {                
        // nothing to upload
        if (null == $file = $this->getUploadedFile($obj, $fieldName)) {
            return;
        }
                
        if(!$data = $this->adapter->write($file->getRealPath())){
            return;
        }
        
        $obj->setPath($data['path']);
        $obj->setImageId($data['imageId']);        
        
        return true;
    }
    
    public function remove($obj, $fieldName)
    {
        // nothing to upload
        if (null == $file = $this->getUploadedFile($obj, $fieldName)) {
            return;
        }
        
        $this->adapter->delete($obj->getImageId());
    }

        protected function getUploadedFile($obj, $fieldName)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $file = $accessor->getValue($obj, $fieldName);
        
        if($file == null || !$file instanceof UploadedFile){
            return null;
        }
        
        return $file;
    }
}
