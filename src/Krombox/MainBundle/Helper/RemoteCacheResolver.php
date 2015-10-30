<?php

namespace Krombox\MainBundle\Helper;

use Liip\ImagineBundle\Imagine\Cache\Resolver\WebPathResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Liip\ImagineBundle\Binary\BinaryInterface;
use Liip\ImagineBundle\Imagine\Cache\Resolver\ResolverInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Routing\RequestContext;
use Goutte\Client;

class RemoteCacheResolver implements ResolverInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var RequestContext
     */
    protected $requestContext;

    /**
     * @var string
     */
    protected $webRoot;
    /**
     * @var string
     */
    protected $cachePrefix;

    /**
     * @param Filesystem $filesystem
     * @param RequestContext $requestContext
     * @param string $webRootDir
     * @param string $cachePrefix
     */
    public function __construct(
        Filesystem $filesystem,
        RequestContext $requestContext,
        $webRootDir,
        $cachePrefix = 'media/cache'
    ) {
        $this->filesystem = $filesystem;
        $this->requestContext = $requestContext;

        $this->webRoot = rtrim(str_replace('//', '/', $webRootDir), '/');
        $this->cachePrefix = ltrim(str_replace('//', '/', $cachePrefix), '/');
        $this->cacheRoot = $this->webRoot.'/'.$this->cachePrefix;
    }

    /**
     * {@inheritDoc}
     */
    public function resolve($path, $filter)
    {           
        $path = basename(parse_url($path, PHP_URL_PATH));       
        
        return sprintf('%s/%s',
            $this->getBaseUrl(),
            $this->getFileUrl($path, $filter)
        );
    }

    /**
     * {@inheritDoc}
     */
    public function isStored($path, $filter)
    {//TODO implement
        //return true;
        //return false;
        $path = basename(parse_url($path, PHP_URL_PATH));
        //var_dump($this->filesystem->exists($this->getFilePath($path, $filter)));
        return $this->filesystem->exists($this->getFilePath($path, $filter));
    }

    /**
     * {@inheritDoc}
     */
    public function store(BinaryInterface $binary, $path, $filter)
    {        
//        $base64_img = base64_encode($binary->getContent());
//        
//        $client = new Client();                
//        $params = array(
//            'key' => '3374fa58c672fcaad8dab979f7687397',
//            'source' => $base64_img,
//            'format' => 'json'
//        );
//        
//        $client->request('POST', 'http://ultraimg.com/api/1/upload', $params);
        $imgName = basename(parse_url($path, PHP_URL_PATH));
        
        $this->getFilePath($path, $filter);
                
        $this->filesystem->dumpFile(
            $this->getFilePath($imgName, $filter),
            $binary->getContent()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function remove(array $paths, array $filters)
    {
        if (empty($paths) && empty($filters)) {
            return;
        }

        if (empty($paths)) {
            $filtersCacheDir = array();
            foreach ($filters as $filter) {
                $filtersCacheDir[] = $this->cacheRoot.'/'.$filter;
            }

            $this->filesystem->remove($filtersCacheDir);

            return;
        }

        foreach ($paths as $path) {
            foreach ($filters as $filter) {
                $this->filesystem->remove($this->getFilePath($path, $filter));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getFilePath($path, $filter)
    {
        return $this->webRoot.'/'.$this->getFileUrl($path, $filter);
    }

    /**
     * {@inheritDoc}
     */
    protected function getFileUrl($path, $filter)
    {
        // crude way of sanitizing URL scheme ("protocol") part
        $path = str_replace('://', '---', $path);
        
        

        return $this->cachePrefix.'/'.$filter.'/'.ltrim($path, '/');
    }
    
    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        $port = '';
        if ('https' == $this->requestContext->getScheme() && $this->requestContext->getHttpsPort() != 443) {
            $port =  ":{$this->requestContext->getHttpsPort()}";
        }

        if ('http' == $this->requestContext->getScheme() && $this->requestContext->getHttpPort() != 80) {
            $port =  ":{$this->requestContext->getHttpPort()}";
        }

        $baseUrl = $this->requestContext->getBaseUrl();
        if ('.php' == substr($this->requestContext->getBaseUrl(), -4)) {
            $baseUrl = pathinfo($this->requestContext->getBaseurl(), PATHINFO_DIRNAME);
        }
        $baseUrl = rtrim($baseUrl, '/\\');

        return sprintf('%s://%s%s%s',
            $this->requestContext->getScheme(),
            $this->requestContext->getHost(),
            $port,
            $baseUrl
        );
    }
}