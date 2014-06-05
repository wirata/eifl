<?php

namespace Eifl\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Eifl\AdminBundle\Entity\PrizeRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends Controller
{
	public function indexAction(Request $request){
		$user = $this->container->get('security.context')->getToken()->getUser();

		$prize_list = $this->getDoctrine()->getRepository('EiflAdminBundle:Prize')
			->findAll();

		$redeem_status = $this->getDoctrine()->getRepository('EiflAdminBundle:PrizeRequest')
			->findBy(array(),array('requestDate'=>'desc'));

		$redeem_form = $this->createFormBuilder()
			->add('prize','entity',array(
				'label'=>'Prize',
				'class'=>'EiflAdminBundle:Prize',
				'property'=>'name',
				'empty_value'=>'Select Prize'
			))
			->add('point','number',array(
				'label' => 'Point Required',
				'attr' => array(
					'disabled' => 'disabled',
				)
			))
			->add('unit','integer', array(
				'label' => 'Unit',
				'data' => 1,
				'attr'=>array(
					'min'=>1,
					'max'=>100
				)
			))
			->add('save','submit',array(
				'label' => 'Send Request',
			))
			->getForm();

		$redeem_form->handleRequest($request);

		if($redeem_form->isValid()){
			$point_req = new PrizeRequest();

			$point_req->setMember($user->getUserMemberType());
			$point_req->setPrize($redeem_form->get('prize')->getData()->getName());
			$point_req->setPoint($redeem_form->get('prize')->getData()->getPoint());
			$point_req->setUnit($redeem_form->get('unit')->getData());
			$point_req->setStatus("Pending");

			$em = $this->getDoctrine()->getManager();
			$em->persist($point_req);
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_member_profile'));
		}

		// 
		return $this->render('EiflMemberBundle:Default:profile.html.twig',array('user'=>$user, 'redeem_form'=>$redeem_form->createView(),'prize_list'=>$prize_list,'redeem_status'=>$redeem_status));
	}

	public function pointAction(Request $request){
		if($request->isXmlHttpRequest()){
            $id_prize = $request->request->get('id_prize');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:Prize')
                ->find($id_prize);

            $result = array();
            $result['point'] = $fetchdata->getPoint();

            return new Response(json_encode($result));
        }
	}
	public function imageAction(){
		$em = $this->getDoctrine()->getManager();
		$request = $this->getRequest();
		$user = $this->container->get('security.context')->getToken()->getUser();
		$uploadDir=dirname($this->container->getParameter('kernel.root_dir')) . '/web/uploads/images';

		$files = $request->files;

		foreach ($files as $uploadedFile) {
		    $name = $uploadedFile->getClientOriginalName();
		    $file = $uploadedFile->move($uploadDir, $name);
		}
        $user->getUserMemberType()->setPath($files->get('p_img')->getClientOriginalName());
        $em->flush();

        return $this->redirect($this->generateUrl('eifl_member_profile'));
        // exit(\Doctrine\Common\Util\Debug::dump($files->get('p_img')->getClientOriginalName()));
	}
	public function checkAction(Request $request){
		if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.context')->getToken()->getUser();
            $result = array();
            foreach ($user->getUserMemberType()->getPrizeRequest() as $prize) {
            	if($prize->getStatus()=="Pending"){
            		return new Response(json_encode(array("result"=>true)));
            	}
            }
			return new Response(json_encode(array("result"=>false)));
        }
	}
}
