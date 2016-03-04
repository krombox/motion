<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Form\Type\PlaceImageType;
use Krombox\MainBundle\Form\Type\BusinessHoursType;
use Krombox\MainBundle\Form\Type\PhoneType;
use Krombox\MainBundle\Form\Type\SocialLinkType;
use Krombox\MainBundle\Form\Type\PlaceAddressType;
use Krombox\MainBundle\Form\Type\HallType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
//use Krombox\MainBundle\Entity\Enum\ServicesEnum;
use Krombox\MainBundle\DBAL\Types\CategoryType as CategoryTypeEnum;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Form\Type\PlaceFilterKindValueType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class PlaceType extends AbstractType
{    
    const DATA_CLASS = Place::class;
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('name', null, array('label' => 'name'))                
            //->add('description', null, array('label' => 'description'))        
            ->add('translations', 'krombox_auto_translations')    
            ->add('slug', null, array(
                'required' => true
            ))    
            ->add('categories', 'a2lix_translatedEntity', array(
                'class' => 'Krombox\MainBundle\Entity\Category',
                'translation_property' => 'name',
                'multiple' => true
            ))
                
            //->add('categories')
//                ->add('categories', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Category',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'categories',
//                    'required' => true,
//                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                        return $er->queryCategoriesByType(CategoryTypeEnum::PLACE);
//                    },
//                ))
            //->add('image', 'file', ['required' => false])                                 
//                ->add('image', 'file', [
//                'label'    => false,
//                'attr'     => [
//                    'title'  => 'Choose a file to upload',
//                    // 'data-filename-placement'=>'inside',
//                    'class'  => "btn btn-info btn-sm",
//                    'accept' => '.jpg,.png,.gif|image/jpeg|image/png|image/gif'
//                ],
//                'constraints' => [new Assert\NotBlank(['message' => 'Please add an image'])],
//                'required' => true
//            ])
//            ->add('x', 'hidden', ['mapped' => false])
//            ->add('y', 'hidden', ['mapped' => false])
//            ->add('w', 'hidden', ['mapped' => false])
//            ->add('h', 'hidden', ['mapped' => false])
                //->add('logo','logo')                
                ->add('website', null, array(
                    'label' => 'website',
                    'required' => false
                ))
                ->add('email', null, array(
                    'label' => 'email',
                    'required' => false
                ))          
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
                    'label' => 'phones',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages',
                    //'attr' => array('class' => 'collection')
                ))
                
                ->add('is24h')
                ->add('businessHours', 'collection',array(
                    'type' => new BusinessHoursType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'business_hours',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages',
                    'attr' => array('class' => 'collection')
                ))
                ->add('businessHoursException', 'collection',array(
                    'type' => new BusinessHoursExceptionType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'business_hours_exception',
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
//                ->add('placesLinked', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Place',
//                    'property' => 'name',
//                    'multiple' => true,
////                    'expanded' => true,
//                    'label' => 'places.linked',
//                    'required' => false
//                ))
                ->add('halls', 'collection',array(
                    'type' => new HallType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'label' => 'halls',
                    'required' => false,
                    'by_reference' => false,
                    'options' => array('label' => false),
                    'translation_domain' => 'messages',
                    'attr' => array('class' => 'collection')
                ))
                ->add('city', 'a2lix_translatedEntity', array(
                    'class' => 'Krombox\MainBundle\Entity\City',
                    'translation_property' => 'name',
                    'multiple' => false
                ))
                ->add('address', new PlaceAddressType(), array(
//                    'attr' => array('class' => 'gmap')
                ))
                ->add('save', 'submit', array('label' => 'save', 'attr' => array(
                    'class' => 'btn btn-info'
                ) ))                                
        ;
        
        $formModifier = function (Form $form, $categories = null) {
            $options = array(
                'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue',
                'property' => 'slug',
                'multiple' => true,
                'expanded' => true,
                'label' => false,
                'required' => false,
                'position' => array('after' => 'categories'),
                'query_builder' => function(EntityRepository $repository) use ($categories) {
                    return $repository->queryGet(['categories' => $categories]);
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
            
        $builder->get('categories')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){                
                $categoriesData = $event->getForm()->getData();
                
                $categories = [];
                if(!$categoriesData->isEmpty()){
                    foreach ($categoriesData as $category){
                        $categories[] = $category->getId();
                    }
                }    
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $categories);
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
        return 'place';
    }
    
    public function getCategoriesByType($type){        
        $query = $this->createQueryBuilder()
            ->from('KromboxMainBundle:Category', 'c')            
            ->where('c.type = :categoryType')                
            ->setParameter('categoryType', $type);

        return $query;
    }
}
