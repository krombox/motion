<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceBusinessHoursType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
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
//                ->add('save', 'submit', array('label' => 'save', 'attr' => array(
//                    'class' => 'btn btn-info'
//                ) ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\Place'
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
