<?php

namespace Krombox\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Krombox\MainBundle\DBAL\Types\MembershipType;

class OrderMembershipType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        //$membership = $data->getPlace()->getMembership()->getType();
        
        $builder                            
                ->add('daysCount', null, array('label' => 'days.count'))
                //->add('membership', 'hidden')
                //->add('membership', 'choice', array('choices' => MembershipType::getPayableChoices($membership),'label' => 'membership.type'))
                //->add('place', 'entity', array('class' => 'Krombox\MainBundle\Entity\Place','property' => 'name' ,'label' => 'membership.type'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\PaymentBundle\Entity\OrderMembership'            
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'order_membership';
    }    
}
