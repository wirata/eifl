<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Eifl\AdminBundle\Entity\Competition;
use Eifl\AdminBundle\Entity\Reward;
use Eifl\AdminBundle\Form\Type\CompetitionType;

class CompetitionController extends Controller
{
	public function competitionAction(Request $request){
		$competition_list = $this->getDoctrine()->getRepository('EiflAdminBundle:Competition')
			->findAll();

		$new_comp = $this->createForm(new CompetitionType());

		$new_comp->handleRequest($request);

		if($new_comp->isValid()){
			$em = $this->getDoctrine()->getManager();
			$comp = new Competition();
			$reward = new Reward();

			$comp->setName($new_comp->get('name')->getData());
			$comp->setLocation($new_comp->get('location')->getData());
			$comp->setType($new_comp->get('type')->getData());
			$comp->setFee(ucfirst($new_comp->get('fee')->getData()));
			$comp->setDate($new_comp->get('date')->getData());
			$comp->setStartTime($new_comp->get('stime')->getData());
			$comp->setEndTime($new_comp->get('etime')->getData());

			$reward->setFirstWinner($new_comp->get('firstwinner')->getData());
			$reward->setSecondWinner($new_comp->get('secondwinner')->getData());
			$reward->setThirdWinner($new_comp->get('thirdwinner')->getData());
			$em->persist($reward);
			
			$comp->setReward($reward);
			$em->persist($comp);
			
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_competition'));
		}
		//check if there is edit competition function
		if($request->query->get('func')=="edit"){
			$id = $request->query->get('id_comp');
			$competition_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Competition')
				->findOneBy(array('id'=>$id));

			$edit_comp_form = $this->createFormBuilder()
				->add('name','text',array(
					'label'=>'Competition Name',
					'data'=>$competition_search->getName()
				))
				->add('type','choice',array(
					'label'=>'Type',
					'choices'=>array(
						'TO'=>'Try Out',
						'PC'=>'Public Competition'
					),
					'data'=>$competition_search->getType()
				))
				->add('date','date',array(
					'label'=>'Date',
					'data'=>$competition_search->getDate()
				))
				->add('fee','text',array(
	                'label'=>'Registration Fee',
	                'data'=>$competition_search->getFee()
	            ))
				->add('stime','time',array(
					'label'=>'Start Time',
					'data'=>$competition_search->getStartTime()
				))
				->add('etime','time',array(
					'label'=>'End Time',
					'data'=>$competition_search->getEndTime()
				))
				->add('location','text',array(
					'label'=>'location',
					'data'=>$competition_search->getLocation()
				))
				->add('firstwinner','text',array(
					'label'=>'1st Prize',
					'data'=>$competition_search->getReward()->getFirstWinner()
				))
				->add('secondwinner','text',array(
					'label'=>'2nd Prize',
					'data'=>$competition_search->getReward()->getSecondWinner()
				))
				->add('thirdwinner','text',array(
					'label'=>'3th Prize',
					'data'=>$competition_search->getReward()->getThirdWinner()
				))
				->add('save','submit',array(
					'label'=>'Save'
				))
				->getForm();

			$edit_comp_form->handleRequest($request);

			if($edit_comp_form->isValid()){
				$em = $this->getDoctrine()->getManager();
				$competition_search->setName($edit_comp_form->get('name')->getData());
				$competition_search->setLocation($edit_comp_form->get('location')->getData());
				$competition_search->setType($edit_comp_form->get('type')->getData());
				$competition_search->setFee(ucfirst($edit_comp_form->get('fee')->getData()));
				$competition_search->setDate($edit_comp_form->get('date')->getData());
				$competition_search->setStartTime($edit_comp_form->get('stime')->getData());
				$competition_search->setEndTime($edit_comp_form->get('etime')->getData());
				$competition_search->getReward()->setFirstWinner($edit_comp_form->get('firstwinner')->getData());
				$competition_search->getReward()->setSecondWinner($edit_comp_form->get('secondwinner')->getData());
				$competition_search->getReward()->setThirdWinner($edit_comp_form->get('thirdwinner')->getData());

				$em->flush();

				return $this->redirect($this->generateUrl('eifl_admin_competition'));
			}
			return $this->render('EiflAdminBundle:Default:competition.html.twig',array('edit_comp_form'=>$edit_comp_form->createView(),'new_comp_form'=>$new_comp->createView(),'competition_list'=>$competition_list,'edit_func'=>true));
		}
		//check if there is delete competition function
		elseif ($request->query->get('func')=='rm') {
			$id = $request->query->get('id_comp');
			$em = $this->getDoctrine()->getManager();
			$competition_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Competition')
				->findOneBy(array('id'=>$id));
			$em->remove($competition_search);
			$em->flush();

			return $this->redirect($this->generateUrl('eifl_admin_competition'));
		}
		//check if there is add competitor function
		return $this->render('EiflAdminBundle:Default:competition.html.twig',array('new_comp_form'=>$new_comp->createView(),'competition_list'=>$competition_list));
	}
}
