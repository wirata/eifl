<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Eifl\AdminBundle\Form\Type\ProgramType;
use Eifl\AdminBundle\Form\Type\ProgramLessonType;
use Eifl\AdminBundle\Form\Type\ClassLevelType;
use Eifl\AdminBundle\Form\Type\LevelType;
use Eifl\AdminBundle\Form\Type\StudentsType;
use Eifl\AdminBundle\Entity\Program;
use Eifl\AdminBundle\Entity\ClassDetail;
use Eifl\AdminBundle\Entity\MainDetail;
use Eifl\AdminBundle\Entity\Level;
use Eifl\AdminBundle\Entity\Lesson;
use Eifl\AdminBundle\Entity\Report;
use Eifl\AdminBundle\Entity\Page;
use Eifl\AdminBundle\Entity\ClassLevel;
use Doctrine\Common\Collections\ArrayCollection;

class ProgramController extends Controller
{
	public function programAction(Request $request)
    {
        //fetch data program from database
        $allPrograms = $this->getDoctrine()->getRepository('EiflAdminBundle:Program')
            ->findAll();

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
        $teacherprogramform = $this->createFormBuilder()
            ->add("program","entity", array(
                "class"=>'EiflAdminBundle:Level',
                "empty_value"=>'Choose Level',
                "group_by"=>'program.program',
                "property"=>'level',
                "attr"=> array(
                    "class"=>'form_teacher_program'
                )
            ))
            ->getForm();
        $programform = $this->createFormBuilder()
            ->add("program","entity", array(
                "class"=>'EiflAdminBundle:Program',
                "property"=>'program',
                "empty_value"=>'Choose Program',
                "attr"=> array(
                    "class"=>'form_select_program'
                )
            ))
            ->getForm();
        $lessonform = $this->createFormBuilder()
            ->add("program","entity", array(
                "class"=>'EiflAdminBundle:Level',
                "empty_value"=>'Choose Level',
                "group_by"=>'program.program',
                "property"=>'level',
                "attr"=> array(
                    "class"=>'form_lesson_program'
                )
            ))
            ->getForm();

        $program = new Program();

        //create new program form
        $newProgram = $this->createForm(new ProgramType(),$program);
        //add handle request for new program form
        $newProgram->handleRequest($request);
        
        // $class = new ClassLevel();

        //create new class form
        $newClass = $this->createForm(new ClassLevelType());
        //add handle request for new class form
        $newClass->handleRequest($request);

        //create new lesson form
        $newLesson = $this->createForm(new ProgramLessonType());
        //add handle request for new lesson form
        $newLesson->handleRequest($request);

        //create new level form
        $newLevel = $this->createFormBuilder()
            ->add('program','entity', array(
                "class"=>'EiflAdminBundle:Program',
                "property"=>'program',
                "attr" => array(
                    "class"=>'new_level_program'
                )
            ))
            ->add('levels','collection', array(
                'type'=> new LevelType(),
                'allow_add'=>true,
                'allow_delete'=>true,
                'by_reference' => false,
                'required'=>true,
            ))
            ->add('save','submit', array(
                'label'=>'Save'
            ))
            ->getForm();
        //add handle request for new level form
        $newLevel->handleRequest($request);

        if($newProgram->isValid()){ 
            $em = $this->getDoctrine()->getManager();
            $page = new Page();
            $em->persist($program);
            $page->setPageTitle($program->getProgram());
            $em->persist($page);
            $em->flush();
               
            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        elseif($newClass->isValid()){
            // loop to check the classes
            foreach ($newClass->getData()->getClass() as $class) {
                $addnewclass = new ClassLevel();

                $addnewclass->setLevel($newClass->getData()->getLevel());
                $addnewclass->setClass($class['class']);

                $em = $this->getDoctrine()->getManager();
                $em->persist($addnewclass);
                $em->flush();

                //get all lesson
                $find_lesson = $this->getDoctrine()->getRepository('EiflAdminBundle:Lesson')->findAll();

                foreach ($find_lesson as $lesson) {
                    $new_main_detail = new MainDetail();

                    $new_main_detail->setLesson($lesson);
                    $new_main_detail->setClass($addnewclass);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($new_main_detail);
                    $em->flush();
                }
                
            }
            
            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        elseif($newLevel->isValid()){
            foreach ($newLevel->get('levels')->getData() as $level) {

                $addnewlevel = new Level();

                $addnewlevel->setProgram($newLevel->get('program')->getData());
                $addnewlevel->setLevel($level->getLevel());

                $em = $this->getDoctrine()->getManager();
                $em->persist($addnewlevel);
                $em->flush();
            }
            
            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        elseif($newLesson->isValid()){
            foreach ($newLesson->get('lessons')->getData() as $lesson) {

                $addnewlesson = new Lesson();

                $addnewlesson->setLevel($newLesson->get('program')->getData());
                $addnewlesson->setLesson($lesson->getLesson());

                $em = $this->getDoctrine()->getManager();
                $em->persist($addnewlesson);
                $em->flush();

                //get all class in this program
                $find_class = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')->find($newLesson->get('program')->getData()->getId());
                
                if(!is_null($find_class->getClasses())){
                    // exit(\Doctrine\Common\Util\Debug::dump($find_class->getClasses()));
                    foreach ($find_class->getClasses() as $class) {
                        // exit(\Doctrine\Common\Util\Debug::dump($class->getClassDetails()));
                        $new_main_detail = new MainDetail();

                        $new_main_detail->setLesson($addnewlesson);
                        $new_main_detail->setClass($class);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($new_main_detail);
                        $em->flush();
                        
                        foreach ($class->getClassDetails() as $detail) {
                            $report = new Report();

                            $report->setClassDetail($detail);
                            $report->setLesson($addnewlesson);

                            $em = $this->getDoctrine()->getManager();
                            $em->persist($report);
                            $em->flush();
                            // exit(\Doctrine\Common\Util\Debug::dump($detail));
                        }
                    }
                }

            }
            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }

        //check if there is edit program function
        if($request->query->get('func')=='edit_program'){
            $id_program = $request->query->get('id_program');

            $program_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Program')
                ->findOneBy(array('id'=>$id_program));

            $editProgram = $this->createFormBuilder()
                ->add('id','text', array(
                    'label'=>'Program Code',
                    'data'=>$program_search->getId()
                ))
                ->add('program','text', array(
                    'label'=>'Program Name',
                    'data'=>$program_search->getProgram()
                ))
                ->add('save','submit', array(
                    'label'=>'Save',
                ))
                ->getForm(); 

                $editProgram->handleRequest($request);

                if($editProgram->isValid()){
                    $em = $this->getDoctrine()->getManager();
                    //set id and program name
                    $program_search->setId($editProgram->get('id')->getData());
                    $program_search->setProgram($editProgram->get('program')->getData());

                    $em->flush();

                    return $this->redirect($this->generateUrl('eifl_admin_program'));
                }
            return $this->render('EiflAdminBundle:Default:program.html.twig',array("edit_program_form"=>$editProgram->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_lesson_form"=>$newLesson->createView(), "new_program_form"=>$newProgram->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, 'edit_p'=>true));
        }
        //check if there is edit level function
        elseif($request->query->get('func')=='edit_level')
        {
            $id_level = $request->query->get('id_level');

            $level_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')
                ->findOneBy(array('id'=>$id_level));

            $editLevel = $this->createFormBuilder()
                ->add('level','text', array(
                    'label'=>'Level name',
                    'data'=>$level_search->getLevel()
                ))
                ->add('save','submit', array(
                    'label'=>'Save'
                ))
                ->getForm();

            return $this->render('EiflAdminBundle:Default:program.html.twig',array("edit_level_form"=>$editLevel->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_lesson_form"=>$newLesson->createView(), "new_program_form"=>$newProgram->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, 'edit_l'=>true));
        }
        //check if there is delete program function
        elseif($request->query->get('func')=='delete_program'){
            $id_program = $request->query->get('id_program');

            $program_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Program')
                ->findOneBy(array('id'=>$id_program));

            $page = $this->getDoctrine()->getRepository('EiflAdminBundle:Page')
                ->findOneBy(array('pageTitle'=>$program_search->getProgram()));

            $em = $this->getDoctrine()->getManager();
            $em->remove($program_search);
            $em->remove($page);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        elseif($request->query->get('func')=='delete_level'){
            $id_level = $request->query->get('id_level');

            $level_search = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')
                ->findOneBy(array('id'=>$id_level));

            $em = $this->getDoctrine()->getManager();
            $em->remove($level_search);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        //check if there is set teacher function
        elseif($request->query->get('func')=='set_teacher'){
            $id_lesson = $request->query->get('id_lesson');
            $class_id = $request->query->get('id_class');

            $teacher_admin = $this->getDoctrine()->getRepository('EiflAdminBundle:Admin')
                ->findBy(array('position'=>'Teacher'));


            $adm_teacher = array();
            foreach ($teacher_admin as $teacher) {
                $adm_teacher[] = $teacher->getUserAdmin();
            }

            $query_lesson = $this->getDoctrine()->getRepository('EiflAdminBundle:Lesson')->find($id_lesson);
            $query_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')->find($class_id);
            $LessonName = $query_lesson->getLesson();
            $ClassName = $query_class->getClass();

            $teacher_list = $this->getDoctrine()->getRepository('EiflAdminBundle:Admin')->findTeacherList();

            $select_teacher_form = $this->createFormBuilder()
                ->add("teacher","entity", array(
                    "class"=>'EiflAdminBundle:Admin',
                    "property"=>'userAdmin.username',
                    "query_builder" => function(\Eifl\AdminBundle\Entity\AdminRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->where('a.position = ?1')
                                ->orwhere('a.position = ?2')
                                ->setParameter(1, 'Teacher')
                                ->setParameter(2, 'Principal')
                                ->add('orderBy', 'a.position ASC');
                      }
                ))
                ->add('save', 'submit', array(
                    'label'=>'Save',
                ))
                ->getForm();

            $select_teacher_form->handleRequest($request);

            if($select_teacher_form->isValid()){
                $em = $this->getDoctrine()->getManager(); 
                
                $search = $this->getDoctrine()->getRepository('EiflAdminBundle:MainDetail')
                    ->findOneBy(array('lesson'=>$id_lesson,'class'=>$class_id));
                
                $search->setAdmin($select_teacher_form->get('teacher')->getData());
 
                $em->flush();

                return $this->redirect($this->generateUrl('eifl_admin_program'));
            }

            return $this->render('EiflAdminBundle:Default:program.html.twig',array("lesson_name"=>$LessonName, "class_name"=>$ClassName ,"select_teacher_form"=>$select_teacher_form->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, "select_T"=>true));

        }
        //check if there is delete teacher function
        elseif($request->query->get('func')=='delete_teacher'){
            $id_lesson = $request->query->get('id_lesson');
            $class_id = $request->query->get('id_class');

            $em = $this->getDoctrine()->getManager(); 
            $search = $this->getDoctrine()->getRepository('EiflAdminBundle:MainDetail')
                ->findOneBy(array('lesson'=>$id_lesson,'class'=>$class_id));

            $search->setAdmin(null);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        //check if there is add students function
        elseif ($request->query->get('func')=="add_student") {
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

                return $this->redirect($this->generateUrl('eifl_admin_program'));
            }

            return $this->render('EiflAdminBundle:Default:program.html.twig',array("add_student_form"=>$studentsForm->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, "add_S"=>true));
        }
        //check if there is edit_class func
        elseif ($request->query->get('func')=='edit_class') {
            $em = $this->getDoctrine()->getManager();
            $id_C = $request->query->get('id_class');

            $search_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                ->find($id_C);
            $edit_class_form = $this->createFormBuilder()
                ->add('class','text', array(
                    'label'=>'Class Name',
                    'data'=>$search_class->getClass()
                ))
                ->add('save', 'submit', array(
                    'label'=>'Save'
                ))
                ->getForm();

            $edit_class_form->handleRequest($request);

            if($edit_class_form->isValid()){
                $search_class->setClass($edit_class_form->get('class')->getData());
                $em->flush();

                return $this->redirect($this->generateUrl('eifl_admin_program'));
            }
            return $this->render('EiflAdminBundle:Default:program.html.twig',array("edit_class_form"=>$edit_class_form->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, "edit_c"=>true));
        }
        //check if there is delete_class func
        elseif ($request->query->get('func')=='delete_class') {
            $id_C = $request->query->get('id_class');
            $search_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                ->find($id_C);
            $em = $this->getDoctrine()->getManager();
            $em->remove($search_class);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        //check if there is edit_lesson func
        elseif ($request->query->get('func')=='edit_lesson') {
            $em = $this->getDoctrine()->getManager();
            $lesson_id = $request->query->get('id_lesson');

            $search_lesson = $this->getDoctrine()->getRepository('EiflAdminBundle:Lesson')
                ->find($lesson_id);

            $edit_lesson_form = $this->createFormBuilder()
                ->add('lesson','text', array(
                    'label'=>'Lesson Name',
                    'data'=>$search_lesson->getLesson(),
                ))
                ->add('save','submit', array(
                    'label'=>'Save'
                ))
                ->getForm();

            $edit_lesson_form->handleRequest($request);

            if($edit_lesson_form->isValid()){
                $search_lesson->setLesson($edit_lesson_form->get('lesson')->getData());

                $em->flush();

                return $this->redirect($this->generateUrl('eifl_admin_program'));
            }
            return $this->render('EiflAdminBundle:Default:program.html.twig',array("edit_lesson_form"=>$edit_lesson_form->createView(),"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms, "edit_ls"=>true));
        }
        //check if there is delete_lesson func
        elseif ($request->query->get('func')=='delete_lesson') {
            $lesson_id = $request->query->get('id_lesson');
            $search_lesson = $this->getDoctrine()->getRepository('EiflAdminBundle:Lesson')
                ->find($lesson_id);
            $em = $this->getDoctrine()->getManager();
            $em->remove($search_lesson);
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        //check if there is class_detail func
        elseif ($request->query->get('func')=='class_detail') {
            $id_C = $request->query->get('id_class');

            $detail_class = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassDetail')
                ->findBy(array('class'=>$id_C));

            // exit(\Doctrine\Common\Util\Debug::dump($result));

            return $this->render('EiflAdminBundle:Default:program.html.twig',array("detail_c"=>$detail_class,"levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms,'view_detail'=>true));
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

            return $this->redirect($this->generateUrl('eifl_admin_program'));
        }
        return $this->render('EiflAdminBundle:Default:program.html.twig',array("levelform"=>$levelform->createView(), "programform"=>$programform->createView(), "teacherprogramform"=>$teacherprogramform->createView(), "lessonform"=>$lessonform->createView(), "new_program_form"=>$newProgram->createView(), "new_lesson_form"=>$newLesson->createView(), "new_class_form"=>$newClass->createView(), "new_level_form"=>$newLevel->createView(), 'programs'=>$allPrograms));
    }

    public function getClassLevelAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idLevel = $request->request->get('id_level');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')
                ->find($idLevel);

            $result = array();
            $class = array();

            foreach ($fetchdata->getClasses() as $class_level) {
                $class['id'] = $class_level->getId();
                $class['class'] = $class_level->getClass();
                $class_detail = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassDetail')
                    ->findBy(array('class'=>$class_level->getId()));

                // exit(\Doctrine\Common\Util\Debug::dump($class_detail));
                $class['students'] = count($class_detail);
                $result[] = $class;
            }

            return new Response(json_encode($result));
        }
    }

    public function getLevelProgramAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idProgram = $request->request->get('id_program');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:Program')
                ->find($idProgram);

            $result = array();
            $level = array();
            

            foreach ($fetchdata->getlevels() as $programlevel) {
                $level['id'] = $programlevel->getId();
                $level['level'] = $programlevel->getlevel();
                $classlevel = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                    ->findBy(array('level'=>$programlevel->getId()));
                $class = array();

                foreach ($classlevel as $classes) {     
                    $class[] = $classes->getClass();    
                }
                $level['classes'] = $class;

                $result[] = $level;
            }
            // exit(\Doctrine\Common\Util\Debug::dump($fetchdata));

            return new Response(json_encode($result));
        }
    }
    public function getLessonProgramAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idProgram = $request->request->get('id_program');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')
                ->find($idProgram);

            $result = array();
            $lesson = array();
            

            foreach ($fetchdata->getLessons() as $programlesson) {
                $lesson['id'] = $programlesson->getId();
                $lesson['lesson'] = $programlesson->getLesson();
                $result[] = $lesson;
            }
            // exit(\Doctrine\Common\Util\Debug::dump($result));

            return new Response(json_encode($result));
        }
    }
    public function getTeacherProgramAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idLevel = $request->request->get('id_level');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:Level')
                ->find($idLevel);

            $result = array();
            $class = array();
            
            
            foreach ($fetchdata->getClasses() as $programclass) {
                $class['id'] = $programclass->getId();
                $class['class'] = $programclass->getClass();
                $result[] = $class;
            }
            
            // exit(\Doctrine\Common\Util\Debug::dump($result));
            return new Response(json_encode($result));
        }
    }
    public function getTeacherClassAction(Request $request){
        if($request->isXmlHttpRequest()){
            $idClass = $request->request->get('id_class');

            $fetchdata = $this->getDoctrine()->getRepository('EiflAdminBundle:ClassLevel')
                ->find($idClass);

            $result = array();
          
            foreach ($fetchdata->getLevel()->getLessons() as $classLesson) {
                $lesson = array();
                $lesson['id'] = $classLesson->getId();
                $lesson['lesson'] = $classLesson->getLesson();

                $search = $this->getDoctrine()->getRepository('EiflAdminBundle:MainDetail')
                    ->findOneBy(array('lesson'=>$classLesson->getId(),'class'=>$idClass));
                

                if(!is_null($search->getAdmin())){
                    $lesson['teacher'] = $search->getAdmin()->getUserAdmin()->getUsername();
                }
                else{
                    $lesson['teacher'] = $search->getAdmin();
                }
                $result[] = $lesson;
            }
            
            // exit(\Doctrine\Common\Util\Debug::dump($result));   
            return new Response(json_encode($result));
        }
    }
}