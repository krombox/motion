<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Krombox\MainBundle\Form\Type\ServicesType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Entity\PlaceFilterValue;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceServicesType extends AbstractType
{    
    const DATA_CLASS = Place::class;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $formModifier = function (Form $form, $categories = null) {
            $options = array(
                'class' => PlaceFilterValue::class,
                'property' => 'slug',
                'multiple' => true,
                'expanded' => true,
                'label' => false,
                'required' => false,
                'position' => 'first',
                'query_builder' => function(EntityRepository $repository) use ($categories) {                    
                    return $repository->queryPlaceFilterValuesByCategories($categories);
                }
            );

            $form->add('placeFilterValues', 'entity_filter', $options);
        };
        
        $builder
                ->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();                
                $categories = [];                
                foreach ($data->getCategories() as $category){
                    $categories[] = $category->getId();
                }

                $formModifier($event->getForm(), $categories);                
            }
        );              
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
        return 'place_services';
    }        
}
