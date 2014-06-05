<?php

namespace Eifl\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScoreController extends Controller
{
	public function scoreAction(Request $request){
		$user = $this->container->get('security.context')->getToken()->getUser();

		return $this->render('EiflMemberBundle:Default:score.html.twig',array('user'=>$user));
	}
}
