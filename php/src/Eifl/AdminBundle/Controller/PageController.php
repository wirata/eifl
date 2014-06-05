<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
	public function pageAction(Request $request){
		$page_form = $this->createFormBuilder()
			->add('pageTitle','entity',array(
				'label' => 'Page',
				'class' => 'EiflAdminBundle:Page',
				'property' => 'pageTitle',
				'empty_value' => 'Choose Page'
			))
			->add('contenttext', 'textarea', array(
		        'attr' => array(
		            'class' => 'tinymce',
		        )
		    ))
			->add('save','submit',array(
				'label'=>'Save'
			))
			->add('reset','reset',array(
				'label'=>'Reset'
			))
		    ->getForm();
		$page_form->handleRequest($request);

		if($page_form->isValid()){
			
		}
		return $this->render('EiflAdminBundle:Default:page.html.twig', array('page_form'=>$page_form->createView()));
	}
}
