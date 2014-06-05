<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Lesson
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_lesson")
 */
 Class Lesson
 {
 	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     *@ORM\Column(name="lesson_name",type="string")
     */
    public $lesson;
    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Level", inversedBy="lessons")
     * @ORM\JoinColumn(name="level", referencedColumnName="id")
     */
    public $level;
    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\MainDetail", mappedBy="lesson", cascade={"remove"})
     */
    public $details;
    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\Report", mappedBy="lesson", cascade={"remove"})
     */
    public $reports;

    public function __construct(){
        $this->details = new ArrayCollection();
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
        $reports->setLesson($this);

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
            $report->setLesson($this);
        }
        $this->reports = $reports;
    }

    public function removeReports(\Eifl\AdminBundle\Entity\Report $report)
    {
        $this->reports->removeElement($report);
    }

    /**
     * Add details
     *
     * @param \Eifl\AdminBundle\Entity\MainDetail $details
     * @return string
     */
    public function addDetails(\Eifl\AdminBundle\Entity\MainDetail $detail) {
        $this->details[] = $detail;
        $details->setLesson($this);

        return $this;
    }
    /**
     * set details
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $details
     * @return string
     */
    public function setDetails(\Doctrine\Common\Collections\ArrayCollection $details) {
        foreach ($details as $Detail) {
            $Detail->setLesson($this);
        }
        $this->details = $details;
    }
    public function removeDetails(\Eifl\AdminBundle\Entity\MainDetail $detail)
    {
        $this->details->removeElement($detail);
    }

    /**
     * @param string $lesson
     * @return string
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * @param mixed $level
     * @return string
     */
    public function setLevel(\Eifl\AdminBundle\Entity\Level $level)
    {
        $this->level = $level;

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
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->program;
    }
    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }
 }