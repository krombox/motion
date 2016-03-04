<?php

namespace Krombox\MainBundle\Adapter;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Rezzza\Flickr\ApiFactory as Client;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */

/**
 * @DI\Service("krombox.flickr_adapter")
 */
class FlickrAdapter implements AdapterInterface
{    
    /**
     * Constructor.
     *
     * @param Client $client
     * @param string $prefix
     */
    
    private $client;
    
    private $photoid = null;
    /**
     * @DI\InjectParams({     
     *     "client" = @DI\Inject("rezzza.flickr.client")
     * })
     */
    //public function __construct(Client $client, $prefix = null)
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->client->getMetadata()->setOauthAccess('72157664175622642-7fef830dbafcfb1a', '4b1d0243f360bca2');
        //$this->setPathPrefix($prefix);
    }
    
    public function write($path)
    {                        
        $result = $this->client->upload($path, null, null, null, 1);
        
        if($result->err){
            return;
            //throw new \Exception($result->err->attributes()->msg);
        }
                    
        //die('normalize next');
        return $this->normalizeResponse($result, $path);
    }
    
    public function delete($id)
    {
        $result = $this->client->call('flickr.photos.delete', ['photo_id' => $id]);
        
        if($result->err){
            return;
            //throw new \Exception($result->err->attributes()->msg);
        }
        
        return true;
        //var_dump($result);die();
    }

    


    /**
     * Get all the meta data of a file or directory.
     *
     * @param string $path
     *
     * @return array|false
     */
    public function getMetadata($path)
    {
        echo $path;
        
        if(!$this->photoid){
            return false;
        }
        
        $result['photoid'] = $this->photoid;
        $this->photoid = null;
        return $result;
        //$location = $this->applyPathPrefix($path);
        //$object = $this->client->getMetadata($location);
//        if ( ! $object) {
//            return false;
//        }
//        return $this->normalizeResponse($object, $path);        
    }   
    
    protected function normalizeResponse($response)
    {                        
        $result = $this->client->call('flickr.photos.getInfo', ['photo_id' => $response->photoid]);
        $photoInfo = $result->photo->attributes();
        $path = 'https://farm' . $photoInfo['farm'] .'.staticflickr.com/' . $photoInfo['server'] .'/' . $photoInfo['id'] . '_' . $photoInfo['originalsecret'] .'_o.' . $photoInfo['originalformat'];
        
        $data['imageId'] = $response->photoid;        
        $data['path'] = $path;        
                
        return $data;
    }       
}
