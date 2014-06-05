<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScoreController extends Controller
{
	public function scoreAction(Request $request){
		//get active user
		$user = $this->container->get('security.context')->getToken()->getUser();

		// $class_taken = $this->getDoctrine()->getRepository('EiflAdminBundle:MainDetail')
			// ->findBy(array('admin'=>$user->getId()));
        $class_taken = $this->getDoctrine()->getRepository('EiflAdminBundle:Report')
            ->findClassTaken($user->getId());

        // exit(\Doctrine\Common\Util\Debug::dump($class_taken));
		if($request->query->get('func')=="set_score"){
            //get all parameters from GET
            $id_c = $request->query->get('id_class');
            $id_l = $request->query->get('id_lesson');
            $id_s = $request->query->get('id_student');
            //create a form to input student score
            $score_form = $this->createFormBuilder()
                ->add('score','text', array(
                    'label'=>'Score'
                ))
                ->add('submit','submit', array(
                    'label'=>'Submit'
                ))
                ->getForm();

            $score_form->handleRequest($request);

            if($score_form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $class_s = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassDetail')
                    ->findOneBy(array('class'=>$id_c,'member'=>$id_s));
                   
                $report_s = $this->getDoctrine()->getRepository('EiflAdminBundle:Report')
                    ->findOneBy(array('classDetail'=>$class_s->getId(),'lesson'=>$id_l));

                $report_s->setScore($score_form->get('score')->getData());
                $em->flush();

                return $this->redirect($this->generateUrl('eifl_admin_score'));
            }

            return $this->render('EiflAdminBundle:Default:score.html.twig',array("set_score_form"=>$score_form->createView(),"class_taken"=>$class_taken,"set_score"=>true));
        }

		return $this->render('EiflAdminBundle:Default:score.html.twig',array("class_taken"=>$class_taken));
	}
	public function programClassAction(Request $request){
		if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.context')->getToken()->getUser();

            $idClass = $request->request->get('id_class');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                ->find($idClass);
            $result = array();

            $program = $fetchdata->getLevel()->getProgram();
            $result['id_class'] = $idClass;
            $result['program_name'] = $program->getProgram();
            
            $lesson_teach = $this->getDoctrine()->getRepository('EiflAdminBundle:MainDetail')
                ->findBy(array('class'=>$idClass,'admin'=>$user), array('lesson'=>'ASC'));
            // exit(\Doctrine\Common\Util\Debug::dump($lesson_teach)); 
            $lessons = array();
            foreach ($lesson_teach as $lsn) {
                $temp = array();
                $temp['id'] = $lsn->getLesson()->getId();
                $temp['name'] = $lsn->getLesson()->getLesson();
                $lessons[] = $temp;
            }
            $result['lessons'] = $lessons;
            // $result['lesson_name'] = $lesson_teach->getLesson()->getLesson();
            // $result['lesson_id'] = $lesson_teach->getLesson()->getId();
            $students = array();

            foreach ($fetchdata->getClassDetails() as $class_detail) {
                $temp = array();
                $score = array();
                $temp['id'] = $class_detail->getMember()->getId();
                $temp['name'] = $class_detail->getMember()->getCompleteName();
                

                foreach ($class_detail->getReports() as $report) {
                    $score[$report->getLesson()->getLesson()] = $report->getScore();
                }
                $temp['score'] = $score;
                $students[] = $temp;
            }
            $result['students'] = $students;

			// exit(\Doctrine\Common\Util\Debug::dump($result['students'][0]['score'])); 
            
            return new Response(json_encode($result));
        }
	}
}
