<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\CommonBundle\Model\Helper\DayFlaggableHelper;
//use Krombox\MainBundle\Form\PlaceImageType;

class BusinessHoursType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        DayFlaggableHelper::buildFields($builder);
//        $builder
//            ->add('dayMonday', null, array('required' => false))
//            ->add('dayTuesday', null, array('required' => false))
//            ->add('dayWednesday', null, array('required' => false))
//            ->add('dayThursday', null, array('required' => false))
//            ->add('dayFriday', null, array('required' => false))
//            ->add('daySaturday', null, array('required' => false))
//            ->add('daySunday', null, array('required' => false))                            
//        ;
        
        $builder
            ->add('startsAt', 'clock_picker', array('required' => true))   
            ->add('endsAt', 'clock_picker', array('required' => true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\BusinessHours',
            'attr' => ['class' => 'collection-item-holder']
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'business_hours';
    }
}
