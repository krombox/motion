<?php

namespace Krombox\CommonBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ImageTypeExtension extends AbstractTypeExtension
{
    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'file';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', 'file', [
                'label'    => false,
                'attr'     => [
                    'title'  => 'Choose a file to upload',
                    // 'data-filename-placement'=>'inside',
                    'class'  => "btn btn-info btn-sm",
                    'accept' => '.jpg,.png,.gif|image/jpeg|image/png|image/gif'
                ],
                'constraints' => [new Assert\NotBlank(['message' => 'Please add an image'])],
                'required' => true
            ])
            ->add('x', 'hidden', ['mapped' => false])
            ->add('y', 'hidden', ['mapped' => false])
            ->add('w', 'hidden', ['mapped' => false])
            ->add('h', 'hidden', ['mapped' => false])
            //the best crop ever made
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $file = $form->get('image')->getData();
                    $x = $form->get('x')->getData();
                    $y = $form->get('y')->getData();
                    $w = $form->get('w')->getData();
                    $h = $form->get('h')->getData();
                    if ($file) {
                        $path = $file->getRealPath();
                        $this->fixImageOrientation($path);
                        if ($w && $h) {
                            try {
                                $imagine = new \Imagine\Gd\Imagine();
                                $original = $imagine->open($path);
                                $cropPoint = new \Imagine\Image\Point($x, $y);
                                $tmpPath = $path . '.' . $file->guessExtension();
                                $original
                                    ->crop($cropPoint, new \Imagine\Image\Box($w, $h))
                                    ->save($tmpPath);
                                file_put_contents($path, file_get_contents($tmpPath));
                                unlink($tmpPath);
                            } catch (\Imagine\Exception\RuntimeException $exception) {
                                //nothing to do
                            }
                        }
                    }
                }
            );
    }

    function fixImageOrientation($path)
    {
        $info   = getimagesize($path);
        if($info['mime']!= "image/jpeg"){
            return;
        }
        $exif = exif_read_data($path);
        if (exif_imagetype($path) != IMAGETYPE_JPEG) {
            return;
        }
        if (empty($exif['Orientation'])) {
            return;
        }

        $image = imagecreatefromjpeg($path);
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($image, $path);
    }
}

