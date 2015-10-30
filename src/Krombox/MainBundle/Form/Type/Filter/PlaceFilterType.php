<?php
// ItemFilterType.php
namespace Krombox\MainBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Entity\Enum\ServicesEnum;
use Krombox\MainBundle\Entity\Enum\RelaxationsEnum;
use Krombox\MainBundle\Form\Model\PlaceFilter;

class PlaceFilterType extends AbstractType
{
    const TYPE_NAME = 'place_filter';
    const DATA_CLASS = PlaceFilter::CLASS;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//                ->add('is24h', 'filter_boolean')
//                ->add('website','filter_text')
//                ->add('phone','filter_text')
//                ->add('name','filter_text')
//                ->add('services', 'choice',
//                    [
//                        'required' => false,
//                        'multiple' => true,
//                        'expanded' => true,
//                        'choices'  => ServicesEnum::getChoices(),
//                        'attr'     => [
//                            'data-placeholder' => 'Skills',
//                            'data-type' => 'filter'
//                        ]
//                    ]
//                )
                ->add('services', 'entity', array(
                    'class' => 'Krombox\MainBundle\Entity\Service',
                    'property' => 'slug',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'services',
                    'required' => false
                ))
//                ->add('relaxations', 'choice',
//                    [
//                        'required' => false,
//                        'multiple' => true,
//                        'expanded' => true,
//                        'choices'  => RelaxationsEnum::getChoices(),
//                        'attr'     => [
//                            'data-placeholder' => 'Relaxations'
//                        ]
//                    ]
//                )
                ->add('menu', 'entity', array(
                    'class' => 'Krombox\MainBundle\Entity\Menu',
                    'property' => 'slug',
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'menu',
                    'translation_domain' => 'messages',
                    'required' => false
                ))
//                ->add('kitchens', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Kitchen',
//                    'property' => 'name',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'kitchens',
//                    'translation_domain' => 'messages',
//                    'required' => false
//                ))
                ->add('save','submit')
        ;        
    }

    public function getName()
    {
        return static::TYPE_NAME;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => static::DATA_CLASS,
            'method' => 'GET',
            'csrf_protection' => false
        ));
    }
}