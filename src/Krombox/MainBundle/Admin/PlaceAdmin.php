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
use Krombox\MainBundle\Form\Type\PhoneType;
use Krombox\MainBundle\Form\Type\KitchenType;
use Krombox\MainBundle\Form\Type\HallType;
use Krombox\MainBundle\Form\Type\BusinessHoursExceptionType;
use Krombox\MainBundle\Form\Type\PlaceAddressType;

class PlaceAdmin extends Admin
{
    protected $baseRouteName = 'KromboxMainBundle\Entity\Admin\PlaceAdmin';
    protected $baseRoutePattern = 'place_admin';
    
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
         //$entity = new \MyCompany\MyProjectBundle\Entity\Seria();
        //$query = $this->modelManager->getEntityManager($entity)->createQuery('SELECT s FROM MyCompany\MyProjectBundle\Entity\Seria s ORDER BY s.nameASC');

        
        $formMapper
        ->with('Основные')    
            //->add('name')
            //->add('description')    
            ->add('translations', 'a2lix_translations')
            //->remove('translations', 'a2lix_translations', array('fields' => array('slug' => array('mapped' => 'false'))))    
            //->add('placeImages', 'images_dropzone')
//            ->add('placeImages','collection',array(
//                    'type' => new PlaceImageType(),
//                    'allow_add' => true,
//                    'allow_delete' => true,
//                    'label' => 'place_images',
//                    'required' => false,
//                    'by_reference' => false,
//                    'options' => array('label' => false),
//                ))                
//            ->add('categories', null, array(
//                    'class' => 'Krombox\MainBundle\Entity\Category',
//                    'multiple' => true,
//                    //'expanded' => true,
//                    'label' => 'categories',
//                    'required' => true,
//                    'query_builder' => 
//                        function($er) {
//                            return $er->queryCategoriesByType(CategoryType::PLACE);
//                         }
//            ))
            ->add('events', 'sonata_type_model', array(
                    'class' => 'Krombox\MainBundle\Entity\Event',
                    'property' => 'name',
                    'multiple' => true,
                    //'expanded' => true,
                    'label' => 'events',
                    'required' => false
            ))    
            ->add('status')
            //->add('numberOfSeats')    
            ->add('businessHours', 'collection',array(
                    'type' => new BusinessHoursType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'business_hours',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                ))
            ->add('businessHoursException', 'collection',array(
                    'type' => new BusinessHoursExceptionType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'business_hours_exception',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                ))    
            ->add('address', new PlaceAddressType())    
            ->add('user',null,['required' => false])
        ->end()
        ->with('Контакты')
            ->add('phones', 'collection',array(
                    'type' => new PhoneType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'phones',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages'
            ))        
//            ->add('vk_group')
//            ->add('fb_group')
//            ->add('website')
        ->end()        
        ->with('Особенности')
//           ->add('kitchens', 'sonata_type_model', array(
//               'class' => 'Krombox\MainBundle\Entity\Kitchen',
//               'property' => 'name',
//               'multiple' => true,
//               'expanded' => true,
//               'label' => 'kitchens',
//               'translation_domain' => 'messages',
//               'required' => false
//            ))            
//           ->add('is24h', null, array(
//                'label' => '24h',
//                'required' => false
//            ))
//            ->add('isWifi', null, array(
//                'label' => 'wifi',
//                'required' => false                
//            ))
//            ->add('isHookah', null, array(
//                'label' => 'hookah',
//                'required' => false                
//            ))
//            ->add('isLiveMusic', null, array(
//                'label' => 'live.music',
//                'required' => false                
//            ))
//            ->add('isOpenAir', null, array(
//                'label' => 'open.air',
//                'required' => false               
//            ))
//            ->add('isParking', null, array(
//                'label' => 'parking',
//                'required' => false
//            ))
//            ->add('isSmokingLounge', null, array(
//                'label' => 'smoking.lounge',
//                'required' => false
//            ))
//            ->add('isBilliards', null, array(
//                'label' => 'billiards',
//                'required' => false
//            ))
//            ->add('isFaceControl', null, array(
//                'label' => 'face.control',
//                'required' => false
//            ))
//            ->add('isBanquet', null, array(
//                'label' => 'banquet',
//                'required' => false
//            ))
//            ->add('isDanceFloor', null, array(
//                'label' => 'dance.floor',
//                'required' => false
//            ))
//            ->add('isStriptease', null, array(
//                'label' => 'striptease',
//                'required' => false
//            ))
//            ->add('isMeetingHole', null, array(
//                'label' => 'meeting.hole',
//                'required' => false
//            ))
//            ->add('isDiscountSystem', null, array(
//                'label' => 'discount.system',
//                'required' => false
//            ))
//            ->add('isDelivery', null, array(
//                'label' => 'delivery',
//                'required' => false
//            ))
//            ->add('isChildrenMenu', null, array(
//                'label' => 'children.menu',
//                'required' => false
//            ))
//            ->add('isFootballBroadcast', null, array(
//                'label' => 'football.broadcast',
//                'required' => false
//            ))
//            ->add('isTerminalPayment', null, array(
//                'label' => 'termonal.payment',
//                'required' => false
//            ))    
            ->add('placesLinked', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\Place',
                    'translation_property' => 'name',
                    'multiple' => true,
                    'label' => 'places.linked',
                    'required' => false
                ))                 
        ->end()
        ->with('Залы')
            ->add('halls', 'collection',array(
                    'type' => new HallType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'halls',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages'
                ))        
        ->end()
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
    {
        $listMapper
            ->addIdentifier('id')    
            ->addIdentifier('translations')
            //->add('description')    
        ;
    }        
        
}