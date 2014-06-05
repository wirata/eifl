<?php

namespace Eifl\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EiflMemberBundle:Default:index.html.twig', array('name' => $name));
    }
}
