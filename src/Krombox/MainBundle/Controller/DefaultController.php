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
}
