<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageFileType extends AbstractType
{
    const TYPE_NAME = 'image_file';

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_NAME;
    }

    public function getParent()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'fileField' => 'imageFile',
                'pathField' => 'imagePath',
                'imgclass' => 'tumbnail imgbox'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//        $view->vars['imgclass']   = $options['imgclass'];
//        $view->vars['fileField']   = $options['fileField'];
//        $view->vars['pathField']   = $options['pathField'];
//        $view->vars['fieldreplace'] = array($options['fileField'] => $options['pathField']);
//    }

}

