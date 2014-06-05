<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Admin
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_admin")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\AdminRepository")
 */
Class Admin
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Eifl\MainBundle\Entity\User", inversedBy="userAdminType")
     * @ORM\JoinColumn(name="User_Admin", referencedColumnName="user_id")
     */
    public $userAdmin;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\MainDetail", mappedBy="admin",cascade={"remove"})
     */
    public $mainDetails;

    public function __construct(){
        $this->mainDetails = new ArrayCollection();
    }

    /**
     * Add mainDetails
     *
     * @param \Eifl\AdminBundle\Entity\MainDetail $mainDetails
     * @return string
     */
    public function addMainDetails(\Eifl\AdminBundle\Entity\MainDetail $detail) {
        $this->mainDetails[] = $detail;
        $mainDetails->setAdmin($this);

        return $this;
    }
    /**
     * set mainDetails
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $details
     * @return string
     */
    public function setMainDetails(\Doctrine\Common\Collections\ArrayCollection $details) {
        foreach ($details as $Detail) {
            $Detail->setAdmin($this);
        }
        $this->mainDetails = $details;
    }
    public function removeMainDetails(\Eifl\AdminBundle\Entity\MainDetail $detail)
    {
        $this->mainDetails->removeElement($detail);
    }

	/**
	 * @ORM\Column(name="position", type="string")
     * @var string
	 */
	public $position;

    /**
     * @param string $position
     * @return string
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param mixed $userAdmin
     * @return string
     */
    public function setUserAdmin($userAdmin)
    {
        $this->userAdmin = $userAdmin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getUserAdmin()
    {
        return $this->userAdmin;
    }

    /**
     * @return string
     */
    public function getMainDetails()
    {
        return $this->mainDetails;
    }
}