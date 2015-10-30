<?php

namespace Krombox\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class UserAdmin extends Admin
{
    private $container;
    
    public function getNewInstance()
    {        
        $instance = parent::getNewInstance();
        $instance->setEnabled(true);        

        return $instance;
    }
    
    public function configure()
    {        
        $this->container = $this->getConfigurationPool()->getContainer();
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            //->add('name')
            //->add('surname')
            ->add('username')
            ->add('email');
            if(!$this->getSubject()->getId())
                $formMapper->add('plainPassword','text', array('required' => false));
                
            $formMapper
                ->add('locked', null, array('required' => false))
                ->add('enabled', null, array('required' => false))
                ->add('roles','choice', array(
                    'choices' => $this->getRoles(),
                    'multiple' => true,
                    'expanded' => false,
                    'required' => false
                ))
            //->add('createdAt')
            //->add('alert', AlertType::TYPE_NAME)
            ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('name')
            //->add('surname')
            ->add('username')
            ->add('email')
            //->add('createdAt')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            //->add('name')
            ->add('surname')
            ->add('createdAt')
        ;
    }
    
    protected function getContainer(){
        $container = $this->getConfigurationPool()->getContainer();
    }

    protected function getRoles(){
        $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);

        foreach ($roles as $role) {
            $theRoles[$role] = $role;
        }
        
        return $theRoles;
    }
    
    public function prePersist($object) {
        $object->setPlainPassword($object->getPlainPassword());
        
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($object);        
    }
    
    public function preUpdate($object) {        
        $object->setPlainPassword($object->getPlainPassword());
        
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($object);        
    }
}


