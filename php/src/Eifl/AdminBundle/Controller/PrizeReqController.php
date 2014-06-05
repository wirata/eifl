<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PrizeReqController extends Controller
{
	public function prizeReqAction(Request $request){
		$req_data = $this->getDoctrine()->getRepository('EiflAdminBundle:prizeRequest')
			->findBy(array('status'=>'Pending'),array('requestDate'=>"ASC"));

		if($request->query->get('act')=="Approve"){
			$em = $this->getDoctrine()->getManager();
			$req_data = $this->getDoctrine()->getRepository('EiflAdminBundle:PrizeRequest')
				->find($request->query->get('id'));
			$point_left = $req_data->getMember()->getPoint() - ($req_data->getPoint() * $req_data->getUnit());
			$req_data->getMember()->setPoint($point_left);
			$req_data->setStatus('Approved');
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_prizeReq'));
		}
		elseif($request->query->get('act')=="Reject"){
			$em = $this->getDoctrine()->getManager();
			$req_data = $this->getDoctrine()->getRepository('EiflAdminBundle:PrizeRequest')
				->find($request->query->get('id'));
			$req_data->setStatus('Rejected');
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_prizeReq'));
		}

	return $this->render('EiflAdminBundle:Default:redeemPrize.html.twig', array('data'=>$req_data));
	}
}
