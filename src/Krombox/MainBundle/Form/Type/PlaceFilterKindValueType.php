<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
*   @DI\FormType
*/
class PlaceFilterKindValueType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('placeFilterValue');
//        $builder->add('placeFilterValue','entity', array(            
//            'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue',
//            'property' => 'name'
//            )
//        );
//        $builder->addEventListener(
//            FormEvents::PRE_SET_DATA,
//            function (FormEvent $event) use ($user) {
//                $form = $event->getForm();
//
////                $form->add('name', 'text');
////                die();
//            }
//        );
//        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
           'data_class' => 'Krombox\MainBundle\Entity\PlaceFilterKindValue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'place_filter_kind_value';
    }      
}
