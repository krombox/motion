<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
*   @DI\FormType
*/
class PlaceFilterType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'name'))
            ->add('slug', null, array('label' => 'slug'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\PlaceFilter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'place_filter';
    }      
}
