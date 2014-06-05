<?php

namespace Eifl\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EiflMainBundle:Default:index.html.twig');
    }
}
