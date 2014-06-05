<?php

namespace Eifl\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
	public function eventAction(Request $request){
		$comp_list = $this->getDoctrine()->getRepository('EiflAdminBundle:Competition')
			->findAvailableCompetition();

		// exit(\Doctrine\Common\Util\Debug::dump($comp_list));
		return $this->render('EiflMemberBundle:Default:event.html.twig', array('comp_list'=>$comp_list));
	}
}
