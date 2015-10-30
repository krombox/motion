<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Krombox\MainBundle\DBAL\Types\CategoryType as CategoryTypeEnum; //????????? translation error
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;

class KitchenType extends AbstractType
{
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
            'data_class' => 'Krombox\MainBundle\Entity\Kitchen'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'krombox_mainbundle_kitchen';
    }
}
