<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
//use Krombox\MainBundle\DBAL\Types\CategoryType as CategoryType; //????????? translation error
use Ivory\GoogleMap\Places\AutocompleteComponentRestriction;

class PhoneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
                //->add('trans','choice', array('choices' => [1 => 'first', 2 => 'second'], 'translation_domain' => 'messages','mapped' => false))
                ->add('number', null, array('label' => 'number'))
                ->add('type', null, array('label' => 'number.type'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\Phone',
            'attr' => ['class' => 'collection']
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'phone';
    }
    
//    public function getCategoriesByType($type){        
//        $query = $this->createQueryBuilder()
//            ->from('KromboxMainBundle:Category', 'c')            
//            ->where('c.type = :categoryType')                
//            ->setParameter('categoryType', $type);
//
//        return $query;
//    }
}
