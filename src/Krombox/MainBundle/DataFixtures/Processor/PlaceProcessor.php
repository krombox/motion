<?php


namespace Krombox\MainBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\PlaceImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Faker\Factory as Faker;

class PlaceProcessor implements ProcessorInterface {

    private $locales = [];
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    protected $faker;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->faker = new Faker();
        $this->locales = $this->container->getParameter('locales');
    }

    /**
     * {@inheritDoc}
     */
    public function preProcess($object)
    {        
        if (!($object instanceof Place)) {
            return;
        }                
        
        $this->insertTranslation($object);
        $this->filterPlaceFilterValue($object);
        //$this->insertImage($object);
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess($object)
    {
        if (!($object instanceof Category)) {
            return;
        }       
    }
    
    protected function insertTranslation($object)
    {
        $faker = Faker::create();       
        
        foreach ($this->locales as $lng){
            $object->translate($lng)->setName($faker->company);
            $object->translate($lng)->setDescription($faker->text);
        }
        
        $object->mergeNewTranslations();
        
        return $object;
    }
    
    protected function insertImage($object)
    {
        $faker = Faker::create();
        
        $placeImage = new PlaceImage();        
        $img_name = $faker->md5 . '.jpg';
        $path = sys_get_temp_dir() .'/' . $img_name;
        file_put_contents($path, file_get_contents($faker->imageUrl(1280, 720)));
        $placeImage->setImage(new UploadedFile($path, $img_name, 'cafe', true));
        $object->setLogo($placeImage);
        
        return $object;
    }
    
    protected function filterPlaceFilterValue($object){
        foreach ($object->getPlaceFilterValues() as $fv){             
            $fvCategories = array_map(function($item){
                return $item->getSlug();
            }, $fv->getPlaceFilterKind()->getCategories()->toArray());
                        
            $objCategories = array_map(function($item){                
                return $item->getSlug();
            }, $object->getCategories());
            
            echo count(array_intersect($fvCategories, $objCategories)) > 0;
            
            if(count(array_intersect($fvCategories, $objCategories)) <= 0){
                $object->removePlaceFilterValue($fv);
            }                                    
        }
    }
}