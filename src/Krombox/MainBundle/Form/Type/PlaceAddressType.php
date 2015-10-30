<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Entity\PlaceAddress;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class PlaceAddressType extends AddressType
{  
    const DATA_CLASS = PlaceAddress::class;            
}
