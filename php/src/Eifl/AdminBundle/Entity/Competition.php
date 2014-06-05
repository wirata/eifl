<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Competition
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_competition")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\CompetitionRepository")
 */
Class Competition
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *@ORM\Column(name="competition_name",type="string")
     */
    public $name;

    /**
     *@ORM\Column(name="type",type="string")
     */
    public $type;

    /**
     *@ORM\Column(name="fee",type="string")
     */
    public $fee;

    /**
     *@ORM\Column(name="date",type="date")
     */
    public $date;

    /**
     *@ORM\Column(name="start_time",type="time")
     */
    public $startTime;

    /**
     *@ORM\Column(name="end_time",type="time")
     */
    public $endTime;

    /**
     *@ORM\Column(name="location",type="string")
     */
    public $location;

    /**
     * @ORM\OneToOne(targetEntity="Eifl\AdminBundle\Entity\Reward", inversedBy="competition")
     * @ORM\JoinColumn(name="reward", referencedColumnName="id")
     */
    public $reward;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @param mixed $fee
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    /**
     * @return mixed
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $reward
     */
    public function setReward($reward)
    {
        $this->reward = $reward;
    }

    /**
     * @return mixed
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }


}