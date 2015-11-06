<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class ChoiceFilterType extends AbstractType
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
    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {                
        parent::finishView($view, $form, $options);
        $this->filterManager->setDataClass($options['class']);
        $this->filterValueKinds = $this->filterManager->getFilterValuesAssocList();
        //var_dump($this->filterValueKinds);die();
        foreach ($view->children as $child){            
            isset($this->filterValueKinds[$child->vars['value']]) ? $label = $this->filterValueKinds[$child->vars['value']] : $label = 'unkhown';
            $child->vars['filter_kind'] = $label;
        }              
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('class');
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'choice_filter';
    }
}