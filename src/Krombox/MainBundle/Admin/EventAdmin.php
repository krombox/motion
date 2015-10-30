<?php
// src/Acme/DemoBundle/Admin/PostAdmin.php

namespace Krombox\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Krombox\MainBundle\DBAL\Types\CategoryType;

class EventAdmin extends Admin
{
    protected $baseRouteName = 'KromboxMainBundle\Entity\Admin\EventAdmin';
    protected $baseRoutePattern = 'event_admin';
    
    public function getNewInstance()
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.context')->getToken()->getUser();
        $instance = parent::getNewInstance();
        $instance->setUser($user);        

        return $instance;
    }

        // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
        ->with('Основные')    
            ->add('name')
            ->add('description')
            //->add('start', 'sonata_type_datetime_picker', ['attr' => ['class' => 'datepicker']])
            ->add('startDate')    
            ->add('endDate',null, array('required' => false))
            ->add('startTime','clock_picker', array('required' => true))
            ->add('endTime', 'clock_picker', array('required' => false))
            ->add('priceLow', null, array('required' => false, 'label' => 'price.low'))
            ->add('priceHigh', null, array('required' => false, 'label' => 'price.high'))    
            //->add('end','collot_datetime')    
            ->add('status')
            ->add('tags','my_tag')
            //->add('user',null,['required' => false])
        ->end()        
//            ->add('place', 'sonata_type_model', array(
//                'class' => 'Krombox\MainBundle\Entity\Place',
//                'property' => 'name',
//                'multiple' => true,
//                //'expanded' => true,
//                'label' => 'places.linked.with',
//                'required' => false
//            ))
        ->add('place', 'sonata_type_model', array(                
                'label' => 'place.linked.with',
                'property' => 'name',
                'required' => false
            ))        
//        ->add('categories', null, array(
//                    'class' => 'Krombox\MainBundle\Entity\Category',
//                    'multiple' => true,
//                    //'expanded' => true,
//                    'label' => 'categories',
//                    'required' => true,
//                    'query_builder' => 
//                        function($er) {
//                            return $er->queryCategoriesByType(CategoryType::EVENT);
//                         }
//            ))
        ->add('image', 'file', ['required' => false])
        ->add('map','map', array('required' => false))    
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('name')
//            ->add('author')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')    
            ->addIdentifier('name')
            ->add('description')
            ->add('status')    
        ;
    }
    
//    public function preUpdate($object) {        
//    }
        
}