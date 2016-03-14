<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Entity\City;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class CityType extends AbstractType
{
    const DATA_CLASS = City::class;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
            ->add('translations', 'a2lix_translatedEntity', array(
                'class' => City::class,
                'translation_property' => 'name'                
            ))
        ;
    }        
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => static::DATA_CLASS            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'city';
    }   
}
