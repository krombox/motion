<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\Common\Persistence\ObjectManager;

use Krombox\MainBundle\Form\DataTransformer\ImagesDropzoneTransformer;

class ImagesDropzoneType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {                
        $transformer = new ImagesDropzoneTransformer($this->om);
        
        $builder                            
                ->addModelTransformer($transformer)            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array(
//            'data_class' => 'Krombox\MainBundle\Entity\MyTag',
//        ))            
//            ;
//    }
    
    public function finishView(\Symfony\Component\Form\FormView $view, \Symfony\Component\Form\FormInterface $form, array $options) {
        parent::finishView($view, $form, $options);
        
        $view->vars['class'] = $options['class'];
    }


//    public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        
//    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'dropzone_input hidden')
        ));
        
        $resolver->setRequired('class');
    }
    
    public function getParent() {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'images_dropzone';
    }
}
