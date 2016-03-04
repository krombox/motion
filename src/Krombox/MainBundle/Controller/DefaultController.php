<?php

namespace Krombox\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Krombox\MainBundle\Entity\Place;
use Krombox\MainBundle\Form\PlaceType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;

class DefaultController extends Controller
{
    
    /**
     * @FW\Route("/place/{slug}/pay", name="place_pay")
     * @FW\Security("is_granted('ROLE_USER')")
     * @FW\Template          
     */
    public function placePayAction(Place $place)
    {   
        $paymentArr = [
            'amt' => 15.25,
            'ccy' => 'UAH',
            'details' => $place->getName(),
            'ext_details' => $place->getId(),
            'pay_way' => 'privat24',
            'order' => sha1($place->getHash().time()),
            //'merchant' => '103854'            
        ];
        
        //$payment = implode('&', $paymentArr);
        $payment = '';
        
        foreach ($paymentArr as $key => $val){
            $payment .= $key . '=' . $val . '&';
        }
        
        $paymentArr['merchant'] = '103854';
        
        $payment .= 'merchant=103854';
        
        //var_dump($payment);die();
        $pass = '0sE7BV1KDYP8Srytg9sYiFnX2O4dQl6Z';
        
        $signature = sha1(md5($payment.$pass));
        $paymentArr['signature'] = $signature;
        
        //$em = $this->getDoctrine()->getManager();
        //file_put_contents('privatapiresponse.txt', 'ddddd');
        
        return compact('paymentArr');
    }
    
    /**
     * @FW\Route("/pay/response", name="place_pay_response")
     * @FW\Template      
     */
    public function placePayResponseAction(Request $request)
    {        
        $payment = $request->request->get('payment');  
        file_put_contents('privatapiresponse.txt', $payment);
        
    }
    
    public function addTranslationAction($id, $locale){
        $em = $this->getDoctrine()->getManager();
        
        $place = $em->find(Place::class, $id);
        $place->setName('NAME ' . $locale);        

        $place->setTranslatableLocale($locale); // Change la locale
        $em->persist($place); //Persist this entity in en_EN
        $em->flush();
        
        return $this->redirect($this->generateUrl('get_place', array('locale' => $locale, 'id' => $place->getId())));
    }

        public function placeGetAction($id, $locale){
        $em = $this->getDoctrine()->getManager();
        $place = $em->getRepository(Place::class)->findOneBy(['id' => $id]);
        $place->setTranslatableLocale($locale);
        $em->refresh($place);
        
        return $this->render('KromboxMainBundle:Default:show.html.twig', array('entity' => $place));
                
    }
    
    public function newPlaceAction(Request $request){
        $place = new Place();
        $form = $this->createForm(new PlaceType(), $place);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            //var_dump($place->getPlaceImages()[0]->getFileName());die();
            //var_dump($place->translate('ru')->getName());die();
            $em = $this->getDoctrine()->getManager();
            $em->persist($place);
            $place->mergeNewTranslations();
            $em->flush();

            return $this->redirectToRoute('task_success');
        }
        
        return $this->render('KromboxMainBundle:Default:create.html.twig', array(
            'form' => $form->createView()            
        ));
    }
    
    /**
     * @FW\Route("/flickr", name="flickr")
     * @FW\Template      
     */
    public function flickrAction()
    {
        $client = $this->get('rezzza.flickr.client');
        $client->getMetadata()->setOauthAccess('72157664175622642-7fef830dbafcfb1a', '4b1d0243f360bca2');
        
//        $metadata = new \Rezzza\Flickr\Metadata('29f44a3c5eb65e3be1e7ec4dfe6e4a5e', 'f554a6fb7c6b76ce');
//        $metadata->setOauthAccess('72157664175622642-7fef830dbafcfb1a', '4b1d0243f360bca2');
//        
//        $factory  = new \Rezzza\Flickr\ApiFactory($metadata, new \Rezzza\Flickr\Http\GuzzleAdapter());       
        
        
//        $xml = $factory->call('flickr.photos.getInfo', array(
//            'photo_id' => 1337,
//        ));
        
//        $xml = $factory->call('flickr.photos.getInfo', array(
//            'photo_id' => '24850822755'            
//        ));
//        
//        //$xml = simplexml_load_string($xml->asXML());
//        $json = json_encode($xml);
//        $array = json_decode($json,TRUE);
//        
//        var_dump($array);die();
        //echo 'ddsddsds';die();
        $result = $client->upload('scr.png', null, null, null, 1);
        var_dump($result);
        //$xml = $factory->upload('apple-touch-icon.png', 'my title');
                
    }
}
