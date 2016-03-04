<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Krombox\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PlaceFilterValueAdmin extends Admin
{
    protected $baseRouteName = 'KromboxMainBundle\Entity\Admin\PlaceFilterValueAdmin';
    protected $baseRoutePattern = 'place_filter_value_admin';        

        // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper                              
            //->add('name')
                ->add('translations', 'a2lix_translations')
            ->add('slug')
            ->add('placeFilterKind', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\PlaceFilterKind',
                    'translation_property' => 'name',
                    'multiple' => false,
                    'label' => 'place filter kind',
                    'required' => true
                ))    
            //->add('description')
            //->add('type')            
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
            ->addIdentifier('translations')
            //->add('type')
        ;
    //}
    }
    
    public function preUpdate($object) {
        //var_dump($object->getImage()->getFilename());die();
    }
        
}