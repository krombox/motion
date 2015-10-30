<?php

namespace Krombox\PaymentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as FW;
use Krombox\PaymentBundle\Entity\OrderMembership;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Krombox\MainBundle\DBAL\Types\PaymentStatusType;
use Krombox\MainBundle\DBAL\Types\StatusType;

class OrderMembershipController extends Controller
{
    /**
     * @FW\Route("/order/membership/{uniqueId}/pay", name="order_membership_pay")
     * @FW\Template()
     */
    public function payAction(OrderMembership $order)
    {   
        //$em = $this->getDoctrine()->getManager();
        $liqpayManager = $this->get('krombox.liqpay_manager');
        $form = $liqpayManager->createPaymentForm($order);
        
        return compact('form');        
    }
    
    /**
     * @FW\Route("/order/process", name="order_process")     
     */
    public function processAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->get('data');
        $signature = $request->request->get('signature');
        
        file_put_contents('liqpay.txt', $data);
        file_put_contents('liqpay_sig.txt', $signature);
        //die();
        $liqpayManager = $this->get('krombox.liqpay_manager');        
        
        if($liqpayManager->verify($request)){
            $data = $liqpayManager->getData($request);
            
            $order = $em->getRepository(OrderMembership::class)->findOneBy(['uniqueId' => $data['order_id']]);
        
            if(!$order) throw new NotFoundHttpException();

            $order->setStatus($data['status']);
            $em->persist($order);
            $em->flush();

            return new Response(200);
        }
        
        return new Response('Request not valid', 500);
    }
}
