<?php


namespace Krombox\MainBundle\DataFixtures\Processor;

use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;
use Krombox\MainBundle\Entity\Category;

class CategoryProcessor implements ProcessorInterface {

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function preProcess($object)
    {        
        if (!($object instanceof Category)) {
            return;
        }
//      
        //$object->translate()->setName($object->getName());
        //$object->translate()->setDescription($object->getDescription());
        
        //var_dump($object->getTranslations());die();
        //$em  = $this->container->get('doctrine.orm.entity_manager');
       // $em->persist($object);
        $object->mergeNewTranslations();
        //$em->flush();
        //echo $object->getName();die();
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess($object)
    {
        if (!($object instanceof Category)) {
            return;
        }
        
//        var_dump('yeah',$object);die();
//        /** @var \FOS\UserBundle\Propel\UserManager $manager */
//        $manager = $this->container->get('fos_user.user_manager');
//        $manager->updateUser($object);
    }
}