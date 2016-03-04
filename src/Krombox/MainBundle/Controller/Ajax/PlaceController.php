<?php

namespace Krombox\MainBundle\Controller\Ajax;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Krombox\MainBundle\Entity\Place;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController as RestController;

use Buzz\Browser;
use Buzz\Client\Curl;
use MatthiasNoback\MicrosoftOAuth\AccessTokenProvider;
use MatthiasNoback\MicrosoftTranslator\MicrosoftTranslator;

/**
 * @author Roman Kapustian <ikrombox@gmail.com>
 */
class PlaceController extends RestController
{
    /**     
     * @View()          
     */
    public function getPlacesAutocompleteAction(Request $request)
    {
        $query = $request->query->get('q');        
        //$em = $this->getDoctrine()->getManager();
        $elasticaManager = $this->container->get('fos_elastica.manager');
        
        $places = $elasticaManager->getRepository(Place::class)->autocomplete($query);
        
        $factory = $this->get('krombox.place.wrapper_factory');
        $results = [];
        
        foreach ($places as $place){
            $results[] = $factory->wrap($place);
        }
        
        $view = $this->view($results, 200);
        
        //return $this->handleView($view);
        return $results;
        
        //var_dump(json_encode($results));die();
    }
    
    /**     
     * @View()          
     */   
    public function getTranslationAction(Request $request)
    {
        $browser = new Browser(new Curl());
        //$browser->getClient()->setVerifyPeer(false);
        
        $clientId = '88b2226a-d8fe-4ec0-a22b-f43446575ba4';
        $clientSecret = 'UC92IU1JhnFYpFQE43+M9TLuPX1Z3pZZX7xuH/EHNyU=';
        
        $query = $request->query->get('q');    
        
        $accessTokenProvider = new AccessTokenProvider($browser, $clientId, $clientSecret);        
        $translator = new MicrosoftTranslator($browser, $accessTokenProvider);        
        $translatedString = $translator->translate('This is a test', 'uk');
        return [];
    }
}
