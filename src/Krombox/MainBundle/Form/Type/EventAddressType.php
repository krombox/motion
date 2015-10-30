<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\Entity\EventAddress;
use JMS\DiExtraBundle\Annotation as DI;

/**
*   @DI\FormType
*/
class EventAddressType extends AddressType
{  
    const DATA_CLASS = EventAddress::class;    
}
