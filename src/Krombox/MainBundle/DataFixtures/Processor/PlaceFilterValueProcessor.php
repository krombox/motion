<?php


namespace Krombox\MainBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Krombox\MainBundle\Entity\PlaceFilterValue;
use Krombox\MainBundle\Entity\PlaceImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Faker\Factory as Faker;

class PlaceFilterValueProcessor implements ProcessorInterface {

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
    }

    /**
     * {@inheritDoc}
     */
    public function preProcess($object)
    {        
        if (!($object instanceof PlaceFilterValue)) {
            return;
        }
        
        //var_dump($object->);die();
        
        //var_dump($object->getBusinessHours());die();
        
        //$this->insertTranslation($object);
        //$this->insertImage($object);
                
//        $placeImage = new PlaceImage();
//        $placeImage->setImage(new UploadedFile(__DIR__ ."/../Resources/augustin.jpg", 'augustin.jpg'));
//        $object->setLogo($placeImage);        
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
        
        $object->translate('en')->setName($faker->word);
        $object->translate('en')->setDescription($faker->text);
        
        $object->translate('ru')->setName($faker->word);
        $object->translate('ru')->setDescription($faker->text);
        
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
}