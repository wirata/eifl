<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AdminRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdminRepository extends EntityRepository
{
	public function findActiveAdmin(){
		return $this->getEntityManager()
            ->createQuery(
                "SELECT user FROM EiflAdminBundle:Admin admin LEFT JOIN EiflMainBundle:User user WHERE (user.roles LIKE '%ROLE_ADMIN%' OR user.roles LIKE '%ROLE_TEACHER%' OR user.roles LIKE '%ROLE_ADM%')"
            )
            ->getResult();
	}

	public function findAdminByName($admin){
		return $this->getEntityManager()
            ->createQuery(
                "SELECT user FROM EiflAdminBundle:Admin admin LEFT JOIN EiflMainBundle:User user WHERE (user.roles LIKE '%ROLE_ADMIN%' OR user.roles LIKE '%ROLE_TEACHER%' OR user.roles LIKE '%ROLE_ADM%') AND user.username LIKE :admin"
            )
            ->setParameter('admin','%'.$admin.'%')
            ->getResult();
	}
    public function findTeacherList(){
        return $this->getEntityManager()
            ->createQuery(
                "SELECT user FROM EiflAdminBundle:Admin admin LEFT JOIN EiflMainBundle:User user WHERE (user.roles LIKE '%ROLE_ADMIN%' OR user.roles LIKE '%ROLE_TEACHER%')"
            )
            ->getResult();
    }
}