<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PhoneType;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class ContactsType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
                ->add('website', null, array(
                    'label' => 'website',
                    'required' => false
                ))
                ->add('fb_group', null, array(
                    'label' => 'fb_group',
                    'required' => false
                ))
                ->add('vk_group', null, array(
                    'label' => 'vk_group',
                    'required' => false
                ))                
                ->add('phones', 'collection',array(
                    'type' => new PhoneType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'phones',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),                    
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
        return 'contacts';
    }        
}
