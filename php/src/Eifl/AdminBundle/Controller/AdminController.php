<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Eifl\AdminBundle\Form\Type\UserAdminType;
use Eifl\AdminBundle\Entity\Admin;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller{
	public function administrationAction(Request $request){
        //get all Active UserAdmin
        $admin_all = $this->getDoctrine()
            ->getRepository('EiflAdminBundle:Admin')
            ->findActiveAdmin();

        //prepare new admin form
        //create a form based on useradminform
        $newform = $this->createForm(new UserAdminType());
        //set a handler for newform to receive return values
        $newform->handleRequest($request);

        //check if there are formhandle request
        if($newform->isValid()){
        	if($newform->get('roles')->getData()=="ROLE_ADMIN"){
        		$position = 'Principal';
        	}
        	elseif($newform->get('roles')->getData()=="ROLE_TEACHER"){
        		$position = 'Teacher';
        	}
        	else{
        		$position = 'Adm';
        	}
            $email = $newform->get('username')->getData()."@email.com";

            $admin = new Admin();

            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->createUser();

            $user->setUsername(ucfirst($newform->get('username')->getData()));
            $user->setEmail($email);
            $user->setPlainPassword($newform->get('password')->getData());
            $user->setRoles(array($newform->get('roles')->getData()));
            $user->setEnabled($newform->get('enabled')->getData());
            $userManager->updateUser($user);

            $admin->setUserAdmin($user);
            $admin->setPosition($position);

            $em = $this->getDoctrine()->getManager();
            $em->persist($admin);
            $em->flush();

//            exit(\Doctrine\Common\Util\Debug::dump($admin));

            return $this->redirect($this->generateUrl('eifl_admin_administration'));
        }
        if($request->query->get('admin')){ //if there is search admin
            $search = $request->query->get('admin');

            //find admin by name
            $admin_all = $this->getDoctrine()->getRepository('EiflAdminBundle:Admin')->findAdminByName(ucfirst($search));

            return $this->render('EiflAdminBundle:Default:administration.html.twig', array('newform'=>$newform->createView(),'admin_list'=>$admin_all));
        }
        elseif($request->query->get('func')){ //if there is edit or delete admin function
        	$id_user = $request->query->get('id_user');

        	if($request->query->get('func')=='edit'){ 
        		$admin = $this->getDoctrine()->getRepository('EiflAdminBundle:Admin')->findOneBy(array('userAdmin'=>$id_user));
				$role = $admin->getUserAdmin()->getRoles();
				//create admin edit form based on admin information from database
				$editform = $this->createFormBuilder()
					->add('username','text',array(
							'label'=>'Admin Name',
							'data'=>$admin->getUserAdmin()->getUsername()
						))
					->add('roles','choice', array(
							'choices'=>array(
								'ROLE_ADMIN' => 'Principal',
			                    'ROLE_TEACHER' => 'Teacher',
			                    'ROLE_ADM'    => 'Adm',
							),
							'label'=>'Position',
							'data'=>$role[0],
							'empty_value' => 'Set your status',
						))
					->add('enabled', 'choice', array(
			                'choices'  => array(
			                    '1' => 'Active',
			                    '0' => 'Inactive',
			                ),
			                'label'=>'Status',
			                'data'=>$admin->getUserAdmin()->isEnabled(),
			                'empty_value' => 'Set your status',
		                ))
					->add('password','repeated',array(
							'type' => 'password',
							'invalid_message'=> "Password didn't match.",
							'first_options'=> array('label'=>'New Password'),
							'second_options'=> array('label'=>'Retype New Password'),
							'required'=>false,
						))
					->add('save','submit', array(
		                'label'=>'Save',
		                ))
					->getForm();

				$editform->handleRequest($request);

				if($editform->isValid()){
					$admin_edit = $this->getDoctrine()->getRepository('EiflAdminBundle:Admin')->findOneBy(array('userAdmin'=>$id_user));

				    if (!$admin_edit) {
				        throw $this->createNotFoundException(
				            'No admin found for id '.$id_user
				        );
				    }
				    else{
				    	$user = $this->getDoctrine()->getRepository('EiflMainBundle:User')->find($id_user);
				    }

				    if($editform->get("roles")->getData()=="ROLE_ADMIN"){
		                $position = "Principal";
		            }
		            elseif($editform->get("roles")->getData()=="ROLE_TEACHER"){
		                $position = "Teacher";
		            }
		            elseif($editform->get("roles")->getData()=="ROLE_ADM"){
		                $position = "Adm";
		            }
		            
		            if($editform->get('password')->getData()!=""){
		            	$user->setPlainPassword($editform->get('password')->getData());
		            }

				    $user->setUsername(ucfirst($editform->get('username')->getData()));
				    $user->setRoles(array($editform->get('roles')->getData()));
				    $user->setEnabled($editform->get('enabled')->getData());
				    $admin_edit->setPosition($position);
				    $this->get('fos_user.user_manager')->updateUser($user, false);

				    $this->getDoctrine()->getManager()->flush();

				    return $this->redirect($this->generateUrl('eifl_admin_administration'));
				}

				return $this->render('EiflAdminBundle:Default:administration.html.twig', array('editform'=>$editform->createView(),'newform'=>$newform->createView(),'admin_list'=>$admin_all,'edit'=>true));
        	}
        	elseif($request->query->get('func')=='delete'){
	        	$em = $this->getDoctrine()->getManager();
		        $admin = $em->getRepository('EiflMainBundle:User')->find($id_user);

		        $em->remove($admin);
		        $em->flush();

		        return $this->redirect($this->generateUrl('eifl_admin_administration'));
        	}
        }
        else{
	       	return $this->render('EiflAdminBundle:Default:administration.html.twig', array('newform'=>$newform->createView(),'admin_list'=>$admin_all));
        }
    }
}