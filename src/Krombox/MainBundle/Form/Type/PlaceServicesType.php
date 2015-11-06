<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\ServicesType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceServicesType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {                    
//                ->add('services', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Service',
//                    'property' => 'name',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'services',
//                    'required' => false
//                ))
        $formModifier = function (Form $form, $categories = null) {
            $options = array(
                'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue',
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
            
//        $builder->get('categories')->addEventListener(
//            FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier){                
//                $categoriesData = $event->getForm()->getData();
//                
//                $categories = [];
//                if(!$categoriesData->isEmpty()){
//                    foreach ($categoriesData as $category){
//                        $categories[] = $category->getId();
//                    }
//                }    
//                // since we've added the listener to the child, we'll have to pass on
//                // the parent to the callback functions!
//                $formModifier($event->getForm()->getParent(), $categories);
//            }
//        );
            
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
                    'attr' => array('class' => 'collection'),
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
        return 'place_services';
    }        
}
