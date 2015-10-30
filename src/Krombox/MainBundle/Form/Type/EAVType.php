<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Krombox\MainBundle\Form\DataTransformer\EAVTransformer;

use JMS\DiExtraBundle\Annotation as DI;


class EAVType extends AbstractType
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
        $transformer = new EAVTransformer($this->om);
        
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
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('class' => 'dropzone_input hidden')
        ));
    }
    
    public function getParent() {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eav';
    }
}
