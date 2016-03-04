<?php
// ItemFilterType.php
namespace Krombox\MainBundle\Form\Type\Filter;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Entity\Enum\ServicesEnum;
use Krombox\MainBundle\Entity\Enum\RelaxationsEnum;
use Krombox\MainBundle\Form\Model\PlaceFilter;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Form;
use Doctrine\ORM\EntityRepository;
use JMS\DiExtraBundle\Annotation as DI;

class PlaceFilterType extends AbstractType
{
    const TYPE_NAME = 'place_filter';
    const DATA_CLASS = PlaceFilter::CLASS;
    
    private $filterManager;
    
    /**
     * @DI\InjectParams({     
     *  "filterManager" = @DI\Inject("krombox.filter_manager")
     * })
     */
    public function __construct($filterManager)
    {        
        $this->filterManager = $filterManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
            $builder
                    ->add('businessHours', 'choice', array(
                        'choices' => array(
                            'workingNow' => 'workingNow',
                            '24/7' => '24/7'
                        ),
                        'expanded' => true,
                        'multiple' => true
                    ))
            ;
            
            $formModifier = function (Form $form, $categories) {
                //var_dump($categories);die();
//            $options = array(
//                'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue',
//                'property' => 'slug',
//                'multiple' => true,
//                'expanded' => true,
//                'label' => false,
//                'required' => false,
//                'position' => 'first',
//                'query_builder' => function(EntityRepository $repository) use ($categories) {                    
//                    return $repository->queryGet(['categories' => $categories]);
//                }
//            );
            
            $class = 'Krombox\MainBundle\Entity\PlaceFilterValue';
            $this->filterManager->setDataClass($class);
            
            $options = array(
                'class' => $class,                
                'multiple' => true,
                'expanded' => true,
                'label' => false,
                'required' => false,
                'position' => 'first',
                'choices' => $this->filterManager->getFiltersChoiceList(['categories' => $categories])
            );

            $form->add('filters', 'choice_filter', $options);
//            $form->add('filters', 'choice_filter', [
//                'choices' => [
//                    'wifi' => 'wifi',
//                    'parking' => 'parking'
//                ],
//                'expanded' => true,
//                'multiple' => true,
//                'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue'
//            ]);            
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
        
        
//               $builder->add('filters', 'entity_filter', array(
//                    'class' => 'Krombox\MainBundle\Entity\PlaceFilterValue',
//                    'property' => 'slug',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'services',
//                    'required' => false,
//                    'query_builder' => function(EntityRepository $repository) use ($categories) {                    
//                        return $repository->queryPlaceFilterValuesByCategories($categories);
//                    }
//                ))
//                ->add('services', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Service',
//                    'property' => 'slug',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'services',
//                    'required' => false
//                ))
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
//                ->add('menu', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Menu',
//                    'property' => 'slug',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'menu',
//                    'translation_domain' => 'messages',
//                    'required' => false
//                ))
//                ->add('kitchens', 'entity', array(
//                    'class' => 'Krombox\MainBundle\Entity\Kitchen',
//                    'property' => 'name',
//                    'multiple' => true,
//                    'expanded' => true,
//                    'label' => 'kitchens',
//                    'translation_domain' => 'messages',
//                    'required' => false
//                ))
                $builder->add('save','submit')
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
            'csrf_protection' => false,
            'attr' => array('class' => static::TYPE_NAME)
        ));
    }
}