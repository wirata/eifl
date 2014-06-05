<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MainDetail
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_main_detail")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\MainDetailRepository")
 */
Class MainDetail
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Lesson", inversedBy="details")
     * @ORM\JoinColumn(name="lesson", referencedColumnName="id")
     */
    public $lesson;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\ClassLevel", inversedBy="mainDetails")
     * @ORM\JoinColumn(name="class", referencedColumnName="id")
     */
    public $class;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Admin", inversedBy="mainDetails")
     * @ORM\JoinColumn(name="teacher", referencedColumnName="id")
     */
    public $admin;

    /**
     * @param mixed $lesson
     * @return string
     */
    public function setLesson(\Eifl\AdminBundle\Entity\Lesson $lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }
    /**
     * @param mixed $class
     * @return string
     */
    public function setClass(\Eifl\AdminBundle\Entity\ClassLevel $class)
    {
        $this->class = $class;

        return $this;
    }
    /**
     * @param mixed $admin
     * @return string
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getLesson()
    {
        return $this->lesson;
    }
    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @return string
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}