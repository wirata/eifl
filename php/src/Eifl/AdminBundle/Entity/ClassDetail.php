<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ClassDetail
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_class_detail",uniqueConstraints={
 *   @ORM\UniqueConstraint(name="student_program", columns={"program", "student"})})
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\ClassDetailRepository")
 */
Class ClassDetail
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Program", inversedBy="details")
     * @ORM\JoinColumn(name="program", referencedColumnName="id")
     */
    public $program;
    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\ClassLevel", inversedBy="classDetails")
     * @ORM\JoinColumn(name="class", referencedColumnName="id")
     */
    public $class;
    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Member", inversedBy="details")
     * @ORM\JoinColumn(name="student", referencedColumnName="id")
     */
    public $member;
    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\Report", mappedBy="classDetail", cascade={"remove"})
     */
    public $reports;

    public function __construct(){
        $this->reports = new ArrayCollection();
    }

    /**
     * Add Report
     *
     * @param \Eifl\AdminBundle\Entity\Report $reports
     * @return string
     */
    public function addReports(\Eifl\AdminBundle\Entity\Report $report) {
        $this->reports[] = $report;
        $reports->setClassDetail($this);

        return $this;
    }
    
    /**
     * set reports
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $reports
     * @return string
     */
    public function setReports(\Doctrine\Common\Collections\ArrayCollection $reports) {
        foreach ($reports as $report) {
            $report->setClassDetail($this);
        }
        $this->reports = $reports;
    }

    public function removeReports(\Eifl\AdminBundle\Entity\Report $report)
    {
        $this->reports->removeElement($report);
    }

    /**
     * @param string $program
     * @return string
     */
    public function setProgram($program)
    {
        $this->program = $program;

        return $this;
    }
    /**
     * @param string $member
     * @return string
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }
    /**
     * @param string $class
     * @return string
     */
    public function setClass($class)
    {
        $this->class = $class;

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
     * @return mixed
     */
    public function getProgram()
    {
        return $this->program;
    }
    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }
    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }
    /**
     * @return mixed
     */
    public function getReports()
    {
        return $this->reports;
    }
}