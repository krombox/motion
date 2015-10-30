<?php

namespace Krombox\MainBundle\Form\Type\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlaceSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name',null,array(
//                'required' => false,
//                'label' => 'name'
//            ))
//            ->add('categories', 'choice', array(
//                    'choices' => ['кофейни' => 'кофейни','рестораны' => 'рестораны'],
//                    //'class' => 'Krombox\MainBundle\Entity\Category',
//                    //'property' => 'name',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'categories',
//                    'required' => false
//            ))
            ->add('is24h','checkbox', array(
                'required' => false,
                'label' => '24h'
            ))
            ->add('isWifi','checkbox', array(
                'required' => false,
                'label' => 'wifi'
            ))
            ->add('isDelivery','checkbox', array(
                'required' => false,
                'label' => 'delivery'
            ))    
            ->add('isWorkingNow','checkbox', array(
                'required' => false,
                'label' => 'isWorkingNow'
            ))
            ->add('birthdayDiscount','checkbox', array(
                'required' => false,
                'label' => 'birthday.discount'
            ))
//            ->add('dateFrom', 'date', array(
//                'required' => false,
//                'widget' => 'single_text',
//            ))
//            ->add('dateTo', 'date', array(
//                'required' => false,
//                'widget' => 'single_text',
//            ))
//            ->add('isPublished','choice', array(
//                'choices' => array('false'=>'non','true'=>'oui'),
//                'required' => false,
//            ))
            ->add('search','submit')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            // avoid to pass the csrf token in the url (but it's not protected anymore)
            'csrf_protection' => false,
            'data_class' => 'Krombox\MainBundle\Model\PlaceSearch'
        ));
    }

    public function getName()
    {
        return 'place_search_type';
    }
}