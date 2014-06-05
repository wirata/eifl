<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_reward")
 */
Class Reward
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Eifl\AdminBundle\Entity\Competition", mappedBy="reward", cascade="remove")
     */
    public $competition;

    /**
     *@ORM\Column(name="1st",type="string")
     */
    public $firstWinner;

    /**
     *@ORM\Column(name="2nd",type="string")
     */
    public $secondWinner;

    /**
     *@ORM\Column(name="3th",type="string")
     */
    public $thirdWinner;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $competition
     */
    public function setCompetition($competition)
    {
        $this->competition = $competition;
    }

    /**
     * @return mixed
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * @param mixed $firstWinner
     */
    public function setFirstWinner($firstWinner)
    {
        $this->firstWinner = $firstWinner;
    }

    /**
     * @return mixed
     */
    public function getFirstWinner()
    {
        return $this->firstWinner;
    }

    /**
     * @param mixed $secondWinner
     */
    public function setSecondWinner($secondWinner)
    {
        $this->secondWinner = $secondWinner;
    }

    /**
     * @return mixed
     */
    public function getSecondWinner()
    {
        return $this->secondWinner;
    }

    /**
     * @param mixed $thirdWinner
     */
    public function setThirdWinner($thirdWinner)
    {
        $this->thirdWinner = $thirdWinner;
    }

    /**
     * @return mixed
     */
    public function getThirdWinner()
    {
        return $this->thirdWinner;
    }

    
}