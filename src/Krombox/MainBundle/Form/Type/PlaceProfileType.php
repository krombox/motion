<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PlaceFilterVariantType;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class PlaceProfileType extends AbstractType
{    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'name'))
            ->add('description', null, array('label' => 'description'))
//            ->add('placeFiltersVariants', 'collection', array(
//                'type' => new PlaceFilterVariantType(),
//                'allow_add'    => true,
//                'allow_delete' => true,
//                'prototype'    => true,
//                'attr' => array('class' => 'collection')
//            ))    
            //->add('image', 'logo', ['required' => false])                                                 
            ->add('logo','logo')
            ->add('categories')    
//            ->add('categories', 'entity', array(
//                'class' => 'Krombox\MainBundle\Entity\Category',
//                'multiple' => true,
//                'expanded' => true,
//                'label' => 'categories',
//                'required' => true,
//                'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                    return $er->queryCategoriesByType(CategoryTypeEnum::PLACE);
//                },
//            ))
//            ->add('services', 'entity', array(
//                'class' => 'Krombox\MainBundle\Entity\Service',
//                'property' => 'name',
//                'multiple' => true,
//                'expanded' => true,
//                'label' => 'services',
//                'required' => false
//            ))
            ->add('address', new PlaceAddressType(), array('label' => 'address'))
            ->add('placesLinked', 'entity', array(
                'class' => 'Krombox\MainBundle\Entity\Place',
                'property' => 'name',
                'multiple' => true,
                //'expanded' => true,
                'label' => 'places.linked',
                'required' => false
            ))            
                
//            ->add('save', 'submit', array('label' => 'next', 'attr' => array(
//                'class' => 'btn btn-info'
//            ) ))
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
        return 'place_profile';
    }
    
    public function getCategoriesByType($type){        
        $query = $this->createQueryBuilder()
            ->from('KromboxMainBundle:Category', 'c')            
            ->where('c.type = :categoryType')                
            ->setParameter('categoryType', $type);

        return $query;
    }
}
