<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Form\Type\PlaceFilterVariantType;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Krombox\MainBundle\Form\Type\PhoneType;
use Krombox\MainBundle\Form\Type\SocialLinkType;
use Krombox\MainBundle\Form\Type\PlaceAddressType;
use Krombox\MainBundle\Entity\Place;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class PlaceProfileType extends AbstractType
{    
    const DATA_CLASS = Place::class;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name', null, array('label' => 'name'))
//            ->add('description', null, array('label' => 'description'))                                                     
            ->add('translations', 'krombox_auto_translations')
            ->add('slug', null, array(
                'required' => true
            ))            
            //->add('categories')
            ->add('categories', 'a2lix_translatedEntity', array(
                'class' => 'Krombox\MainBundle\Entity\Category',
                'translation_property' => 'name',
                'multiple' => true
            ))    
            ->add('website')
            ->add('email')
            ->add('socialLinks', 'collection',array(
                    'type' => new SocialLinkType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'social links',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages',
                    'attr' => array('class' => 'collection')
                ))
            ->add('phones', 'collection',array(
                'type' => new PhoneType(),
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'options' => array('label' => false),
                'translation_domain' => 'messages',
                'attr' => array('class' => 'collection')
                ))
            ->add('placesLinked', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\Place',
                    'translation_property' => 'name',
                    'multiple' => true,
                    'label' => 'places.linked',
                    'required' => false
                ))
            ->add('city', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\City',
                    'translation_property' => 'name',
                    'multiple' => false
                ))    
            ->add('address', new PlaceAddressType(), array('label' => 'address'))
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
        return 'place_profile';
    }        
}
