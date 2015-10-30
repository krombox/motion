<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\HallType;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceHallType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
                ->add('halls', 'collection',array(
                    'type' => new HallType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'halls',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'attr' => array('class' => 'collection')
                ))
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
        return 'place_hall';
    }        
}
