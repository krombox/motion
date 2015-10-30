<?php

namespace Krombox\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KromboxUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
