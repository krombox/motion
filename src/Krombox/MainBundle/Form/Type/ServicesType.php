<?php

namespace Krombox\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
* @DI\FormType
*/
class ServicesType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
                ->add('birthdayDiscount', null, array(
                    'label' => 'birthday.discount',
                    'required' => false
                ))
                ->add('serviceComission', null, array(
                    'label' => 'service.comission',
                    'required' => false
                ))
                ->add('is24h', null, array(
                    'label' => '24h',
                    'required' => false
                ))
                ->add('isHookah', null, array(
                    'label' => 'hookah',
                    'required' => false
                ))
                ->add('isLiveMusic', null, array(
                    'label' => 'liveMusic',
                    'required' => false
                ))
                ->add('isOpenAir', null, array(
                    'label' => 'openAir',
                    'required' => false
                ))
                ->add('isParking', null, array(
                    'label' => 'parking',
                    'required' => false
                ))
                ->add('isSmokingLounge', null, array(
                    'label' => 'smoking.lounge',
                    'required' => false
                ))
                ->add('isBilliards', null, array(
                    'label' => 'billiards',
                    'required' => false
                ))
                ->add('isFaceControl', null, array(
                    'label' => 'face.control',
                    'required' => false
                ))
                ->add('isBanquet', null, array(
                    'label' => 'banquet',
                    'required' => false
                ))
                ->add('isDanceFloor', null, array(
                    'label' => 'dance.floor',
                    'required' => false
                ))
                ->add('isStriptease', null, array(
                    'label' => 'striptease',
                    'required' => false
                ))
                ->add('isMeetingHole', null, array(
                    'label' => 'meeting.hole',
                    'required' => false
                ))
                ->add('isWifi', null, array(
                    'label' => 'wifi',
                    'required' => false
                ))
                ->add('isDiscountSystem', null, array(
                    'label' => 'discount.system',
                    'required' => false
                ))
                ->add('isDelivery', null, array(
                    'label' => 'delivery',
                    'required' => false
                ))
                ->add('isChildrenMenu', null, array(
                    'label' => 'children.menu',
                    'required' => false
                ))
                ->add('isSportBroadcast', null, array(
                    'label' => 'sport.broadcast',
                    'required' => false
                ))
                ->add('isTerminalPayment', null, array(
                    'label' => 'terminal.payment',
                    'required' => false
                ))
                ->add('isGameConsole', null, array(
                    'label' => 'game.console',
                    'required' => false
                ))
                ->add('isBoardGame', null, array(
                    'label' => 'board.game',
                    'required' => false
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Krombox\MainBundle\Entity\Service',
            'translation_domain' => 'messages'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'services';
    }        
}
