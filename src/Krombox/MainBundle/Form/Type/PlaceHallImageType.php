<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Krombox\MainBundle\Entity\PlaceHallImage;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class PlaceHallImageType extends ImageType
{            
    const DATA_CLASS = PlaceHallImage::class;
    const TYPE_NAME = 'place_hall_image';
}
