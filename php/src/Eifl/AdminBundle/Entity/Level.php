<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Level
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_level")
 */
Class Level
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\Column(name="level",type="string")
     */
    public $level;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Program", inversedBy="levels")
     * @ORM\JoinColumn(name="program", referencedColumnName="id")
     */
    public $program;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\ClassLevel", mappedBy="level", cascade={"remove"})
     */
    public $classes;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\Lesson", mappedBy="level", cascade={"remove","persist"})
     */
    public $lessons;

    public function __construct(){
        $this->lessons = new ArrayCollection();
    }

    /**
     * Add Lesson
     *
     * @param \Eifl\AdminBundle\Entity\Lesson $lessons
     * @return string
     */
    public function addLessons(\Eifl\AdminBundle\Entity\Lesson $Lesson) {
        $this->lessons[] = $Lesson;
        $lessons->setLevel($this);

        return $this;
    }
    /**
     * set lessons
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $lessons
     * @return string
     */
    public function setLessons(\Doctrine\Common\Collections\ArrayCollection $lessons) {
        foreach ($lessons as $Lesson) {
            $Lesson->setLevel($this);
        }
        $this->lessons = $lessons;
    }
    public function removeLessons(\Eifl\AdminBundle\Entity\Lesson $lesson)
    {
        $this->lessons->removeElement($lesson);
    }

    /**
     * @param string $level
     * @return string
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @param mixed $program
     * @return string
     */
    public function setProgram(\Eifl\AdminBundle\Entity\Program $program)
    {
        $this->program = $program;

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
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * @return mixed
     */
    public function getClasses()
    {
        return $this->classes;
    }
    /**
     * @return mixed
     */
    public function getLessons()
    {
        return $this->lessons;
    }
}