<?php

namespace Krombox\MainBundle\Helper;

use Liip\ImagineBundle\Binary\Loader\LoaderInterface;
use Imagine\Image\ImagineInterface;
use Liip\ImagineBundle\Model\Binary;
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Liip\ImagineBundle\Exception\Binary\Loader\NotLoadableException;

class RemoteStreamLoader implements LoaderInterface
{
    /**
     * @var ExtensionGuesserInterface
     */
    protected $extensionGuesser;
    
     public function __construct(){        
        $this->extensionGuesser =  ExtensionGuesser::getInstance();
    }

    public function find($path)
    {        
        $imageFile = file_get_contents($path);
        
        if(!$imageFile)
            throw new NotLoadableException(sprintf('Source image not found in "%s"', $path));
        
        $mimeType = image_type_to_mime_type(exif_imagetype($path));
        //var_dump($mimeType);die();
        return new Binary(
            $imageFile,
            $mimeType,
            $this->extensionGuesser->guess($mimeType)
        );
    }
}
