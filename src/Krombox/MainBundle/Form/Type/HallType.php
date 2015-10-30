<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HallType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
                ->add('name', null, array('label' => 'hall.name'))
                ->add('numberOfSeats', null, array('label' => 'number.of.seats'))
                ->add('placeHallImages', 'images_dropzone')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\Hall',
            //'attr' => ['class' => 'collection-item-holder']
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'hall';
    }    
}
