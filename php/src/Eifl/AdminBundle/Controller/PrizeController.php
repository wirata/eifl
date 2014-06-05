<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Eifl\AdminBundle\Form\Type\PrizeType;
use Eifl\AdminBundle\Entity\Prize;

class PrizeController extends Controller
{
	public function prizeAction(Request $request){
		$prize_list = $this->getDoctrine()->getRepository('EiflAdminBundle:Prize')
			->findAll();

		$prize = new Prize();
		$new_prize = $this->createForm(new PrizeType(),$prize);

		$new_prize->handleRequest($request);

		if($new_prize->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($prize);
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_prize'));
		}
		if($request->query->get('func')=='rm_prize'){
			$rm_p = $this->getDoctrine()->getRepository('EiflAdminBundle:Prize')
				->find($request->query->get('id_prize'));
			$em = $this->getDoctrine()->getManager();
			$em->remove($rm_p);
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_prize'));
		}
		elseif($request->query->get('func')=='edit_prize'){
			
			$edit_p = $this->getDoctrine()->getRepository('EiflAdminBundle:Prize')
				->find($request->query->get('id_prize'));

			$edit_form = $this->createFormBuilder()
				->add('image','file',array(
	                'label'=>'Image',
	                'required'=>false
	            ))
	            ->add('name','text',array(
	                'label'=>'Name',
	                'data'=>$edit_p->getName()
	            ))
	            ->add('point','text',array(
	                'label'=>'Point/Unit',
	                'data'=>$edit_p->getPoint()
	            ))
	            ->add('unit','text',array(
	                'label'=>'Unit',
	                'data'=>$edit_p->getUnit()
	            ))
	            ->add('save','submit',array(
	                'label'=>'Save'
	            ))
	            ->getForm();

			$edit_form->handleRequest($request);

			if($edit_form->isValid()){
				$em = $this->getDoctrine()->getManager();
				if(!is_null($edit_form->get('image')->getData())){
					$edit_p->setImage($edit_form->get('image')->getData());
				}
				$edit_p->setName($edit_form->get('name')->getData());
				$edit_p->setPoint($edit_form->get('point')->getData());
				$edit_p->setUnit($edit_form->get('unit')->getData());

				
				$em->flush();
				return $this->redirect($this->generateUrl('eifl_admin_prize'));
			}
			return $this->render('EiflAdminBundle:Default:prize.html.twig',array('edit_form'=>$edit_form->createView(),'new_prize_form'=>$new_prize->createView(),'prize_list'=>$prize_list,'edit_p'=>true));
		}
		return $this->render('EiflAdminBundle:Default:prize.html.twig',array('new_prize_form'=>$new_prize->createView(),'prize_list'=>$prize_list));
	}
}
