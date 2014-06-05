<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Eifl\AdminBundle\Entity\ClassDetail;
use Eifl\AdminBundle\Entity\Report;

class ClassManageController extends Controller
{
	public function manageAction(Request $request){
		//create form
        $levelform = $this->createFormBuilder()
            ->add("program","entity", array(
                "class"=>'EiflAdminBundle:Level',
                "empty_value"=>'Choose Level',
                "group_by"=>'program.program',
                "property"=>'level',
                "attr"=> array(
                    "class"=>'form_select_level'
                )
            ))
            ->getForm();
           
        //check if there is add students function
        if ($request->query->get('func')=="add_student") {
            $id_C = $request->query->get('id_class');

            $data = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')->find($id_C);
            $program_id = $data->getLevel()->getProgram()->getId();

            //create students form for add students to class
            $studentsForm = $this->createFormBuilder()
                ->add("member","entity", array(
                    "class"=>'EiflAdminBundle:member',
                    "property"=>'completeName',
                    "multiple"=>true,
                    "expanded"=>true,
                    "query_builder" => function(\Eifl\AdminBundle\Entity\MemberRepository $er) use ( $program_id )  {
                        return $er->createQueryBuilder('m')
                                ->leftJoin('m.details','cd')
                                ->where('cd.member IS NULL')
                                ->orwhere('cd.program != :id')
                                ->setParameter('id', $program_id);
                      }
                ))
                ->add('save','submit', array(
                    'label'=>'Save'
                ))
                ->getForm();
            //add handle request for students form
            $studentsForm->handleRequest($request);

            if($studentsForm->isValid()){
                $class_s = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                    ->find($id_C);
                $program_s = $class_s->getLevel()->getProgram();

                foreach ($studentsForm->get('member')->getData() as $member) {
                    $class_D = new ClassDetail();

                    $em = $this->getDoctrine()->getManager();
                    $class_D->setProgram($program_s);
                    $class_D->setClass($class_s);
                    $class_D->setMember($member);
                    
                    $em->persist($class_D);
                    $em->flush();
                    
                    foreach ($class_s->getLevel()->getLessons() as $lesson) {
                        $report = new Report();

                        $report->setLesson($lesson);
                        $report->setClassDetail($class_D);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($report);
                        $em->flush();
                    }
                }

                return $this->redirect($this->generateUrl('eifl_admin_class_adm'));
            }

            return $this->render('EiflAdminBundle:Default:class_adm.html.twig',array("add_student_form"=>$studentsForm->createView(),"levelform"=>$levelform->createView(), "add_S"=>true));
        }
        //check if there is class_detail func
        elseif ($request->query->get('func')=='class_detail') {
            $id_C = $request->query->get('id_class');

            $detail_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassDetail')
                ->findBy(array('class'=>$id_C));

            // exit(\Doctrine\Common\Util\Debug::dump($result));

            return $this->render('EiflAdminBundle:Default:class_adm.html.twig',array("detail_c"=>$detail_class,"levelform"=>$levelform->createView(), 'view_detail'=>true));
        }
        //check if there is remove_student function
        elseif ($request->query->get('func')=='remove_student') {
            $id_C = $request->query->get('id_class');
            $student_id = $request->query->get('id_student');

            $detail_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassDetail')
                ->findOneBy(array('class'=>$id_C,'member'=>$student_id));

            $em = $this->getDoctrine()->getManager();
            $em->remove($detail_class);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_class_adm'));
        }
        return $this->render('EiflAdminBundle:Default:class_adm.html.twig',array("levelform"=>$levelform->createView()));
	}
}
