<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Report
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_report", uniqueConstraints={
 * @ORM\UniqueConstraint(name="report_lesson", columns={"lesson", "class_detail"})})
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\ReportRepository")
 */
Class Report
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\ClassDetail", inversedBy="reports")
     * @ORM\JoinColumn(name="class_detail",referencedColumnName="id")
     */
    public $classDetail;
    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Lesson", inversedBy="reports")
     * @ORM\JoinColumn(name="lesson", referencedColumnName="id")
     */
    public $lesson;
    /**
     * @ORM\Column(name="score",type="string", options={"default":0})
     */
    public $score;

    /**
     * @param string $classDetail
     * @return string
     */
    public function setClassDetail($classDetail)
    {
        $this->classDetail = $classDetail;

        return $this;
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
     * @param string $score
     * @return string
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassDetail()
    {
        return $this->classDetail;
    }
    /**
     * @return mixed
     */
    public function getLesson()
    {
        return $this->lesson;
    }
    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }
}