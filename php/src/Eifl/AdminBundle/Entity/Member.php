<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Member
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_member")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\MemberRepository")
 */
Class Member
{
    /**
     * @ORM\ID
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Eifl\MainBundle\Entity\User", inversedBy="userMemberType")
     * @ORM\JoinColumn(name="User_Member", referencedColumnName="user_id")
     */
    public $userMember;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\ClassDetail", mappedBy="member")
     */
    public $details;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\PrizeRequest", mappedBy="member")
     */
    public $prizeRequest;

    /**
     * @var string
     * @ORM\Column(name="first_name", type="string", length=255)
     */
    public $firstName;

    /**
     * @var string
     * @ORM\Column(name="last_name", type="string", length=255,nullable=true)
     */
    public $lastName;

    /**
     * @var string
     * @ORM\Column(name="address", type="string", length=255)
     */
    public $address;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255)
     */
    public $phone;

    /**
     * @var string
     * @ORM\Column(name="point", type="integer", options={"default":0})
     */
    public $point;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * Prize image
     *
     * @var File
     *
     * @Assert\File(
     *     maxSize = "10M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 10MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    protected $image;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
    }

    /**
     * Sets image.
     *
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image = null)
    {
        $this->image = $image;
        // check if we have an old image path
        if (isset($this->path)) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getImage()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getImage()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getImage()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getImage()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->image = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $address
     * @return string
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @param string $firstName
     * @return string
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     * @return string
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @param string $phone
     * @return string
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

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
     * @param mixed $userMember
     * @return string
     */
    public function setUserMember($userMember)
    {
        $this->userMember = $userMember;

        return $this;
    }
    /**
     * @param mixed $prizeRequest
     * @return string
     */
    public function setPrizeRequest($prizeRequest)
    {
        $this->prizeRequest = $prizeRequest;

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
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getUserMember()
    {
        return $this->userMember;
    }

    /**
     * @return mixed
     */
    public function getPrizeRequest()
    {
        return $this->prizeRequest;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }
    /**
     * @return string
     */
    public function getPath()
    {
       return $this->path;
    }

    function getCompleteName()
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}