<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
//use Krombox\MainBundle\Form\PlaceImageType;

class BusinessHoursExceptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                        
//            ->add('dayStartsAt', null, array('required' => true))
//            ->add('dayEndsAt', null, array('required' => false))
            ->add('day', 'date', array('required' => true, 'data' => new \DateTime()))            
            ->add('startsAt', 'clock_picker', array('required' => false))   
            ->add('endsAt', 'clock_picker', array('required' => false))            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\BusinessHoursException'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'business_hours_exception';
    }
}
