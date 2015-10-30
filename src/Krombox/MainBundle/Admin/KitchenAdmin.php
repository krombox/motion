<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Krombox\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Krombox\MainBundle\Form\PlaceImageType;

class KitchenAdmin extends Admin
{
    protected $baseRouteName = 'KromboxMainBundle\Entity\Admin\KitchenAdmin';
    protected $baseRoutePattern = 'kitchen_admin';        

        // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper                              
            ->add('name')                        
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('title')
//            ->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {//if ($this->isGranted('LIST')) {
        $listMapper
            ->addIdentifier('id')    
            ->addIdentifier('name')            
        ;
    //}
    }
    
    public function preUpdate($object) {
        //var_dump($object->getImage()->getFilename());die();
    }
        
}