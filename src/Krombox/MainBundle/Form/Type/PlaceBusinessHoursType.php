<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Krombox\MainBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceBusinessHoursType extends AbstractType
{   
    const DATA_CLASS = Place::class;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('is24h')
                ->add('businessHours', 'collection',array(
                    'type' => new BusinessHoursType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'business_hours',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages',
                    'attr' => array(
                        'class' => 'collection',
                    ),
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => static::DATA_CLASS
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'place_business_hours';
    }        
}
