<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Krombox\MainBundle\Helper\FilterHelper;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class EntityFilterType extends AbstractType
{
    private $em;
    
    private $filterManager;
    
    private $filterValueKinds;

    /**
     * @DI\InjectParams({
     *  "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "filterManager" = @DI\Inject("krombox.filter_manager")
     * })
     */
    public function __construct($em, $filterManager)
    {
        $this->em = $em;
        $this->filterManager = $filterManager;
    }
        
    
//    public function __construct(array $filterValueKinds = []) {
//        $this->filterValueKinds = [
//            'wifi' => 'service',
//            'parking' => 'service',
//            'live-music' => 'service',
//            'asian'   => 'menu',
//            'sushi' => 'menu',
//            'ukrainian' => 'menu',
//            'restaurant' => 'place-type',
//            'cafe' => 'place-type'
//        ]
//        ;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $modelTransformer = new \Krombox\MainBundle\Form\DataTransformer\EAVTransformer($this->em);
//        $builder->addModelTransformer($modelTransformer);
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
    }
    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {                
        parent::finishView($view, $form, $options);
        $this->filterManager->setDataClass($options['class']);
        $this->filterValueKinds = $this->filterManager->getFilterValuesAssocList();
        
        foreach ($view->children as $child){            
            isset($this->filterValueKinds[$child->vars['label']]) ? $label = $this->filterValueKinds[$child->vars['label']] : $label = 'unkhown';
            $child->vars['filter_kind'] = $label;
        }              
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'entity_filter';
    }
}