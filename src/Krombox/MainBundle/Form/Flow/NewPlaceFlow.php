<?php

namespace Krombox\MainBundle\Form\Flow;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Krombox\MainBundle\Form\Type\PlaceProfileType;
use Krombox\MainBundle\Form\Type\PlaceBusinessHoursType;
use Krombox\MainBundle\Form\Type\PlaceServicesType;
use Krombox\MainBundle\Form\Type\PlaceHallType;

class NewPlaceFlow extends FormFlow {

    public function getName() {
        return 'newPlace';
    }

    protected function loadStepsConfig() {
        return array(
            array(
                'label' => 'profile - step 1',
                'form_type' => new PlaceProfileType(),
            ),
            array(
                'label' => 'business hours - step 2',
                'form_type' => new PlaceBusinessHoursType(),
            ),
            array(
                'label' => 'services && contacts - step 3',
                'form_type' => new PlaceServicesType(),
//                'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
//                    return $estimatedCurrentStepNumber > 1;
//                },
            ),
            array(
                'label' => 'halls - step 4',
                'form_type' => new PlaceHallType(),
            )            
        );
    }

}

