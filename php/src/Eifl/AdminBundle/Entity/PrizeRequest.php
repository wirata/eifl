<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * PrizeRequest
 *
 * @ORM\Entity()
 * @ORM\Table(name="tbl_prize_request")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\PrizeRequestRepository")
 */
Class PrizeRequest
{
	/**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Eifl\AdminBundle\Entity\Member", inversedBy="prizeRequest")
     * @ORM\JoinColumn(name="member", referencedColumnName="id")
     */
    protected $member;

    /**
     * @var string
     * @ORM\Column(name="prize", type="string")
     */
    protected $prize;

    /**
     * @var integer
     * @ORM\Column(name="point", type="integer")
     */
    protected $point;

    /**
     * @var integer
     * @ORM\Column(name="unit", type="integer")
     */
    protected $unit;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", options={"default":"Pending"})
     */
    protected $status;

    /**
     * @var datetime
     * @ORM\Column(name="request_date", type="datetime")
     */
    protected $requestDate;

    /**
     * @ORM\PrePersist
     */
    public function setRequestDate()
    {
        $this->requestDate = new \DateTime("now");
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
     * @param string $prize
     * @return string
     */
    public function setPrize($prize)
    {
        $this->prize = $prize;

        return $this;
    }
    /**
     * @param integer $point
     * @return integer
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }
    /**
     * @param integer $unit
     * @return integer
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }
    /**
     * @param string $status
     * @return string
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }
    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize;
    }
    /**
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }
    /**
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }
    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @return date
     */
    public function getRequestDate()
    {
        return $this->requestDate;
    }
}