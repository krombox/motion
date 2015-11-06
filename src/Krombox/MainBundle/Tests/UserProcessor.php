<?php


namespace Krombox\MainBundle\Tests;

use Nelmio\Alice\ProcessorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use FOS\UserBundle\Model\UserInterface;

class UserProcessor implements ProcessorInterface {

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
    }

    /**
     * {@inheritDoc}
     */
    public function postProcess($object)
    {
        if (!($object instanceof UserInterface)) {
            return;
        }

        /** @var \FOS\UserBundle\Propel\UserManager $manager */
        $manager = $this->container->get('fos_user.user_manager');
        $manager->updateUser($object);
    }
}