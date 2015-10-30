<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Menu;

/**
* @DI\FormType
*/
class MenuType extends AbstractType
{
    const DATA_CLASS = Menu::class;
    const DATA_TYPE = 'menu';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
            ->add('type', null, array('label' => 'kitchen.type'))                
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
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
        return static::DATA_TYPE;
    }
}
