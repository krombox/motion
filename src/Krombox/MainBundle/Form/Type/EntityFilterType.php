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
class EntityFilterType extends AbstractType
{
    private $filterValueKinds;
    
    public function __construct(array $filterValueKinds = []) {
        $this->filterValueKinds = [
            'wifi' => 'service',
            'parking' => 'service',
            'asian'   => 'menu',
            'sushi' => 'menu',
            'ukrainian' => 'menu'
        ]
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            var_dump($event->getData()->isEmpty());die();
//            $product = $event->getData();
//            $form = $event->getForm();
//
//            
//            if (!$product || null === $product->getId()) {
//                $form->add('name', 'text');
//            }
//        });
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        
    }
    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        parent::finishView($view, $form, $options);
        
        foreach ($view->children as $child){
            //var_dump($child->vars);die();
            $child->vars['filter_kind'] = $this->filterValueKinds[$child->vars['label']];
        }
        return true;
        var_dump($view->children);die();
        
        foreach ($form->getData() as $v){
            var_dump($v);die();
        }die();
        foreach ($view->vars['choices'] as $choice) {
            $additionalAttributes = array();
            foreach ($options['option_attributes'] as $attributeName => $choicePath) {
                $additionalAttributes[$attributeName] = $this->propertyAccessor->getValue($choice->data, $choicePath);
            }

            $choice->attr = array_replace(isset($choice->attr) ? $choice->attr : array(), $additionalAttributes);
        }       

    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'choices' => array(
//                'm' => 'Male',
//                'f' => 'Female',
//            )
//        ));
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