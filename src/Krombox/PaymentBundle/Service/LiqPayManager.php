<?php

namespace Krombox\PaymentBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use Krombox\MainBundle\Entity\Rating;
use Krombox\MainBundle\Entity\Place;
use Symfony\Component\HttpFoundation\Request;
use Krombox\PaymentBundle\Entity\OrderMembership;

/**
 * @DI\Service("krombox.liqpay_manager")
 */
class LiqPayManager
{
    
    private $em;
    
    private $publicKey;
    
    private $privateKey;
    
    private $liqpay;        

    /**
     * @DI\InjectParams({
     *  "em" = @DI\Inject("doctrine.orm.entity_manager"),
     *  "publicKey" = @DI\Inject("%liqpay_public_key%"),
     *  "privateKey" = @DI\Inject("%liqpay_private_key%")
     * })
     */
    public function __construct($em, $publicKey, $privateKey)
    {
        $this->em = $em;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->liqpay = new \LiqPay($this->publicKey, $this->privateKey);
    }
    
    public function getPaymentStatus(){
        return $this->paymentStatus;
    }

    public function createPaymentForm($object){        
        $form = $this->liqpay->cnb_form(
            $this->generateParameters($object)
        );
        
        return $form;
    }
    
    public function createSignature($object){               
        $signature = $this->liqpay->cnb_signature(
            $this->generateParameters($object)
        );
        
        return $signature;
    }
    
    protected function generateParameters($object){
        return array(
            'version'        => '3',
            'amount'         => $object->getAmount(),
            'currency'       => 'UAH',
            'description'    => 'payment for ' . $object->getUniqueId(). ' order',
            'order_id'       => $object->getUniqueId(),
            'sandbox'        => 1,
            'server_url'     => 'http://93.78.12.177/order/process'     
        );
    }

    public function getData(Request $request){        
        $data = $request->request->get('data');        
        $signature = $request->request->get('signature');                         
        
        return $this->decodeData($data);                
    }
    
//    public function getStatus(Request $request)
//    {
//        $data = $request->request->get('data');        
//        $signature = $request->request->get('signature');
//        
//        
//        $result = $this->requestToArray($request);
//        
//        if(!$this->verify($result))
//            throw new \Exception('Fake request. Signatures differ');
//        
//        return $result['data']['status'];
//    }

    public function verify(Request $request)
    {
        $data = $request->request->get('data');        
        $signature = $request->request->get('signature');                
                
        if(!$data || !$signature){
            throw new \Exception('data or signature parameter not exist');
        }
        
        $checkSignature =  $this->generateSignature($data);
        
        if($signature === $checkSignature){
            return true;
        }
//        
//        
//        $order = $this->em->getRepository(OrderMembership::class)->findOneBy(['uniqueId' => $result['data']['order_id']]);
//        
//        if(!$order)
//            throw new \Exception('Object not found');
//        
//        $orderSignature = $this->createSignature($order);
//        $orderSignature = base64_encode( sha1( 
//                        $this->privateKey .  
//                $resultdata . 
//                $this->privateKey 
//               , 1 ));
//        
//        //var_dump('signatures',$result['signature'], $orderSignature);
//        file_put_contents('signatures', $orderSignature);
//        if($result['signature'] == $orderSignature){
//            file_put_contents('signaturesSAME.txt', true);
//            return true;
//        }
    }
    
    protected function generateSignature($data){
        return base64_encode( sha1($this->privateKey . $data . $this->privateKey , 1 ));
    }
    
    protected function decodeData($data){
        return json_decode(base64_decode($data), true);
    }
} 
