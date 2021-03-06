<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Krombox\MainBundle\Form\DataTransformer\MyTagTransformer;

class MyTagType extends AbstractType
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
        $transformer = new MyTagTransformer($this->om);
        
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
    
    public function getParent() {
        return 'text';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'my_tag';
    }
}
