<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Form\Type\HallType;
use Krombox\MainBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceHallType extends AbstractType
{    
    const DATA_CLASS = Place::class;
    
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
                ->add('logo','logo')
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
        return 'place_hall';
    }        
}
