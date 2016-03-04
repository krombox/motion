<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\MyTagType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;
use Krombox\MainBundle\DBAL\Types\CategoryType as CategoryTypeEnum;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name', null, array('label' => 'name'))
//            ->add('description', 'textarea', array('label' => 'description'))
            ->add('translations', 'krombox_auto_translations')    
            ->add('startDate')    
            ->add('endDate',null, array('required' => false))
            ->add('startTime','clock_picker', array('required' => true))
            ->add('endTime', 'clock_picker', array('required' => false))    
//            ->add('start','collot_datetime', array('pickerOptions' => array(
//                'format' => 'dd.mm.yyyy hh:ii',
//                'language' => 'ru'
//            )))
//            ->add('end','collot_datetime', array('pickerOptions' => array(
//                'format' => 'dd.mm.yyyy hh:ii',
//                'language' => 'ru'
//            )))
//            ->add('start')
//            ->add('end')
            //->add('status')
            ->add('priceLow', null, array('required' => false, 'label' => 'price.low'))
            ->add('priceHigh', null, array('required' => false, 'label' => 'price.high'))        
            ->add('tags', 'my_tag')
            ->add('image', 'file', ['required' => false])                 
            //->add('tags', 'collection', array('type' => 'my_tag', 'allow_add'    => true))
//                ->add('save', 'submit', array('label' => 'save', 'attr' => array(
//                    'class' => 'btn btn-info'
//                ) ))
//            ->add('categories', null, array(
//                    'class' => 'Krombox\MainBundle\Entity\Category',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'categories',
//                    'required' => true,
//                    'query_builder' => 
//                        function($er) {
//                            return $er->queryCategoriesByType(CategoryTypeEnum::EVENT);
//                         }
//            ))
            ->add('place', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\Place',
                    'translation_property' => 'name',                    
                    'label' => 'place',
                    'required' => false
                ))    
            //->add('place', null, array('required' => false, 'property' => 'name'))
            ->add('address', new EventAddressType(), array())
                ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\Event'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'krombox_mainbundle_event';
    }
}
