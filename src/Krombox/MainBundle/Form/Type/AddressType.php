<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\CommonBundle\Model\Traits\WithDataClass;
use Krombox\MainBundle\Entity\Event;
use JMS\DiExtraBundle\Annotation as DI;

class AddressType extends AbstractType
{
    use WithDataClass;
    const TYPE_NAME = 'address';    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('lng','hidden', array('label' => false))
            ->add('lat','hidden', array('label' => false))
            /*->add('country','hidden', array('label' => false))    
            ->add('state','hidden', array('label' => false))
            ->add('city','hidden', array('label' => false))    
            ->add('street','hidden', array('label' => false))    
            ->add('streetNumber','hidden', array('label' => false))*/    
            ->add('formatted',null, array('label' => false))            
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
     * @param OptionsResolverInterface $resolver
     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {        
//        $resolver->setDefaults(array(
//            'data_class' => $this->getDataClass()
//        ));
//    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::TYPE_NAME;
    }        
}
