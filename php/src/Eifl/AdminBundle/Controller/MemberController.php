<?php

namespace Eifl\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Eifl\AdminBundle\Entity\Member;
use Eifl\AdminBundle\Form\Type\UserMemberType;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends Controller
{
	public function usermemberAction(Request $request)
    {
        //get all user member
        $member_all = $this->getDoctrine()
            ->getRepository('EiflAdminBundle:Member')
            ->findActiveMember();

        //create a form based on useradminform
        $newform = $this->createForm(new UserMemberType());
        //set a handler for useradminform to receive return values
        $newform->handleRequest($request);

        if($newform->isValid()){
            //generate email manually
            $email = $newform->get('username')->getData()."@email.com";

            $usermember = new Member();
            //get user manager and create new user
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->createUser();
            //save all the value from form->get data
            $user->setUsername($newform->get('username')->getData());
            $user->setEmail($email);
            $user->setPlainPassword($newform->get('password')->getData());
            $user->setRoles(array('ROLE_USER'));
            $user->setEnabled(1); //user was set to enabled for the first time by this code
            //save to table user
            $userManager->updateUser($user);

            //create new member based on $user
            $usermember->setUserMember($user);
            $usermember->setFirstName($newform->get('firstname')->getData());
            $usermember->setLastName($newform->get('lastname')->getData());
            $usermember->setAddress($newform->get('address')->getData());
            $usermember->setPhone($newform->get('phone')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($usermember);
            //save to table member
            $em->flush();

            return $this->redirect($this->generateUrl('eifl_admin_usermember'));
        }

        if($request->query->get('member')){ //check if there is search member
            $search = $request->query->get('member');

            //find member by name
            $member_all = $this->getDoctrine()->getRepository('EiflAdminBundle:Member')->findMemberByName(ucfirst($search));

            return $this->render('EiflAdminBundle:Default:usermember.html.twig', array('newform'=>$newform->createView(),'member_list'=>$member_search));
        }
        elseif($request->query->get('func')){
        	$id_user = $request->query->get('id_user');
        	if($request->query->get('func')=='edit'){ //if there are edit member
        		$member = $this->getDoctrine()->getRepository('EiflAdminBundle:Member')->findOneBy(array('userMember'=>$id_user));

				$editform = $this->createFormBuilder()
				    ->add('username','text', array(
				    		'label'=>'Username',
				    		'data'=>$member->getUserMember()->getUsername()
				    	))
				    ->add('firstname','text', array(
				    		'label'=>'First Name',
				    		'data'=>$member->getFirstName()
				    	))
				    ->add('lastname','text', array(
				    		'label'=>'Last Name',
				    		'data'=>$member->getLastName()
				    	))
				    ->add('address','textarea', array(
				    		'label'=>'Address',
				    		'attr'=>array(
			                    'cols'=>'50',
			                    'rows'=>'5',
			                    'class'=>'no-resize'
		               		),
		               		'data'=>$member->getAddress()
				    	))
				    ->add('phone','text', array(
				    		'label'=>'Phone Number',
				    		'data'=>$member->getPhone()
				    	))
				    ->add('enabled', 'choice', array(
		                'choices'  => array(
		                    '1' => 'Active',
		                    '0' => 'Inactive',
		                ),
		                'label'=>'Status',
		                'empty_value' => 'Set your status',
		                'data'=>$member->getUserMember()->isEnabled()
		            ))
				    ->add('save','submit', array(
				    		'label'=>'Save',
				    	))
				    ->add('password','repeated',array(
							'type' => 'password',
							'first_options'=> array('label'=>'New Password'),
							'second_options'=> array('label'=>'Confirm New Password'),
							'invalid_message'=> "Password didn't match",
							'required'=>false,
						))
				    ->getForm();

				$editform->handleRequest($request);

				if($editform->isValid()){
					$member_edit = $this->getDoctrine()->getRepository('EiflAdminBundle:Member')->findOneBy(array('userMember'=>$id_user));

					if (!$member_edit) {
				        throw $this->createNotFoundException(
				            'No member found for id '.$id_user
				        );
				    }
				    else{
				    	$user = $this->getDoctrine()->getRepository('EiflMainBundle:User')->find($id_user);
				    }

				    if($editform->get('password')->getData()!=""){
		            	$user->setPlainPassword($editform->get('password')->getData());
		            }

		            $user->setUsername($editform->get('username')->getData());
		            $user->setEnabled($editform->get('enabled')->getData());
		            $member_edit->setFirstName($editform->get('firstname')->getData());
		            $member_edit->setLastName($editform->get('lastname')->getData());
		            $member_edit->setAddress($editform->get('address')->getData());
		            $member_edit->setPhone($editform->get('phone')->getData());

		            $this->get('fos_user.user_manager')->updateUser($user, false);

				    $this->getDoctrine()->getManager()->flush();

				    return $this->redirect($this->generateUrl('eifl_admin_usermember'));
				}
				return $this->render('EiflAdminBundle:Default:usermember.html.twig', array('newform'=>$newform->createView(),'editform'=>$editform->createView(),'member_list'=>$member_all,'edit'=>true));
        	}
        	elseif($request->query->get('func')=='delete'){ //if there is delete admin
        		$em = $this->getDoctrine()->getManager();
		        $member = $em->getRepository('EiflMainBundle:User')->find($id_user);

		        $em->remove($member);
		        $em->flush();

		        return $this->redirect($this->generateUrl('eifl_admin_usermember'));
        	}
        }
        else{ //if there is no GET request
	        return $this->render('EiflAdminBundle:Default:usermember.html.twig',array('newform'=>$newform->createView(),'member_list'=>$member_all));
        }
    }
}
