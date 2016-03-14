<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Krombox\CommonBundle\Model\Traits\WithDataClass;
/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
class ImageType extends AbstractType
{   
    use WithDataClass;
    const TYPE_NAME = 'image';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('image','file', array('label' => '(Image file)'))            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->getDataClass(),
            'csrf_protection' => false,
        ));
    }
    
    public function getName() 
    {
        return self::TYPE_NAME;
    }
}
