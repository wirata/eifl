<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_class")
 */
Class ClassLevel
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Level", inversedBy="classes")
     * @ORM\JoinColumn(name="level", referencedColumnName="id")
     */
    public $level;

    /**
     *@ORM\Column(name="class",type="string")
     */
    public $class;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\MainDetail", mappedBy="class", cascade={"remove"})
     */
    public $mainDetails;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\ClassDetail", mappedBy="class", cascade={"remove"})
     */
    public $classDetails;

    public function __construct(){
        $this->mainDetails = new ArrayCollection();
        $this->classDetails = new ArrayCollection();
    }

    public function __toString()
    {
        return strval($this->level);
    }

    /**
     * Add mainDetails
     *
     * @param \Eifl\AdminBundle\Entity\MainDetail $mainDetails
     * @return string
     */
    public function addMainDetails(\Eifl\AdminBundle\Entity\MainDetail $detail) {
        $this->mainDetails[] = $detail;
        $mainDetails->setClass($this);

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
            $Detail->setClass($this);
        }
        $this->mainDetails = $details;
    }
    public function removeMainDetails(\Eifl\AdminBundle\Entity\MainDetail $detail)
    {
        $this->mainDetails->removeElement($detail);
    }

    /**
     * Add classDetails
     *
     * @param \Eifl\AdminBundle\Entity\ClassDetail $classDetails
     * @return string
     */
    public function addClassDetails(\Eifl\AdminBundle\Entity\ClassDetail $detail) {
        $this->classDetails[] = $detail;
        $classDetails->setClass($this);

        return $this;
    }
    /**
     * set classDetails
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $details
     * @return string
     */
    public function setClassDetails(\Doctrine\Common\Collections\ArrayCollection $details) {
        foreach ($details as $Detail) {
            $Detail->setClass($this);
        }
        $this->classDetails = $details;
    }
    public function removeClassDetails(\Eifl\AdminBundle\Entity\ClassDetail $detail)
    {
        $this->classDetails->removeElement($detail);
    }

    public function setLevel(\Eifl\AdminBundle\Entity\Level $level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @param mixed $class
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
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    public function getMainDetails()
    {
        return $this->mainDetails;
    }
    public function getClassDetails()
    {
        return $this->classDetails;
    }
}