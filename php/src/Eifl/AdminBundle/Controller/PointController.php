<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Eifl\AdminBundle\Entity\Member;

class PointController extends Controller
{
	public function pointAction(Request $request){
		$class_select = $this->createFormBuilder()
			->add('class','entity', array(
				'label'=>'Class',
				'class'=>'EiflAdminBundle:ClassLevel',
				'group_by'=>'level.program.program',
				'property'=>'class',
				'empty_value'=>'Choose Class'
			))
			->add('save','submit', array(
				'label'=>'Add Point'
			))
			->getForm();

		$class_select->handleRequest($request);

		if($class_select->isValid()){
			// exit(\Doctrine\Common\Util\Debug::dump($request->request->get('student-select'))); 
			$em = $this->getDoctrine()->getManager();
			$member_select = $this->getDoctrine()->getRepository('EiflAdminBundle:Member')
				->find($request->request->get('student-select'));

			$point_now = $member_select->getPoint() + $request->request->get('point');

			$member_select->setPoint($point_now);
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_point'));
		}
		return $this->render('EiflAdminBundle:Default:point.html.twig',array('class_select'=>$class_select->createView()));
	}

	public function pointMemberAction(Request $request){
		if($request->isXmlHttpRequest()){
			$idClass = $request->request->get('id_class');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                ->find($idClass);
            $result = array();
            
            foreach ($fetchdata->getClassDetails() as $class_detail) {
            	$students = array();
                $students['id'] = $class_detail->getMember()->getId();
                $students['name'] = $class_detail->getMember()->getCompleteName();
                $result[] = $students;
            }

            return new Response(json_encode($result));
		}
	}
}
