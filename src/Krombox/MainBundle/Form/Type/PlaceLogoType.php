<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Krombox\CommonBundle\Model\Traits\WithDataClass;
use Krombox\MainBundle\Entity\PlaceImage;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Form\Type\LogoType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @DI\FormType
 */
class PlaceLogoType extends AbstractType
{   
    use WithDataClass;    
    const DATA_CLASS = Place::class;
    const TYPE_NAME = 'place_logo';
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', 'logo')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->getDataClass(),
                'csrf_protection' => false                
            )
        );
    }
    
    public function getName()
    {
        return self::TYPE_NAME;
    }
}
