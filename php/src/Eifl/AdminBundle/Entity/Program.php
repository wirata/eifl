<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Program
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_program")
 */
Class Program
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="string")
     */
    protected $id;

    /**
     *@ORM\Column(name="program_name",type="string")
     */
    public $program;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\Level", mappedBy="program", cascade={"remove","persist"})
     */
    public $levels;

    

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\ClassDetail", mappedBy="program", cascade={"remove","persist"})
     */
    public $details;

    public function __construct(){
        $this->levels = new ArrayCollection();
        $this->details = new ArrayCollection();
    }
    public function __toString()
    {
        return strval($this->program);
    }

    /**
     * @param string $id
     * @return string
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add details
     *
     * @param \Eifl\AdminBundle\Entity\ClassDetail $details
     * @return string
     */
    public function addDetails(\Eifl\AdminBundle\Entity\ClassDetail $detail) {
        $this->details[] = $detail;
        $details->setProgram($this);

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
            $Detail->setProgram($this);
        }
        $this->details = $details;
    }
    public function removeDetails(\Eifl\AdminBundle\Entity\ClassDetail $details)
    {
        $this->details->removeElement($details);
    }

    /**
     * Add Level
     *
     * @param \Eifl\AdminBundle\Entity\Level $levels
     * @return string
     */
    public function addLevels(\Eifl\AdminBundle\Entity\Level $level) {
        $this->levels[] = $level;
        $levels->setProgram($this);

        return $this;
    }
    
    /**
     * set levels
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $levels
     * @return string
     */
    public function setLevels(\Doctrine\Common\Collections\ArrayCollection $levels) {
        foreach ($levels as $level) {
            $level->setProgram($this);
        }
        $this->levels = $levels;
    }

    public function removeLevels(\Eifl\AdminBundle\Entity\Level $level)
    {
        $this->levels->removeElement($level);
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
     * @param string $detail
     * @return string
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

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
    public function getProgram()
    {
       return $this->program;
    }

    /**
     * @return string
     */
    public function getLevels()
    {
       return $this->levels;
    }
    // /**
    //  * @return string
    //  */
    // public function getLessons()
    // {
    //    return $this->lessons;
    // }
    /**
     * @return string
     */
    public function getDetail()
    {
       return $this->detail;
    }
}