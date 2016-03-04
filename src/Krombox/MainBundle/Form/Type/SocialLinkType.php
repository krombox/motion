<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Entity\SocialLink;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class SocialLinkType extends AbstractType
{
    const DATA_CLASS = SocialLink::class;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder                            
                ->add('url', null, array('label' => 'url'))
                ->add('type', null, array('label' => 'number.type'))
        ;
    }        
    
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => static::DATA_CLASS,
            'attr' => ['class' => 'collection']
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'social_link';
    }   
}
