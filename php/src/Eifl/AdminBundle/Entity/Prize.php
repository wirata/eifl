<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Prize
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="tbl_prize")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\PrizeRepository")
 */
Class Prize
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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

    /**
     *@ORM\Column(name="name",type="string")
     */
    public $name;

    /**
     *@ORM\Column(name="point",type="integer")
     */
    public $point;

    /**
     *@ORM\Column(name="unit",type="integer")
     */
    public $unit;

    /**
     * @ORM\OneToMany(targetEntity="Eifl\AdminBundle\Entity\PrizeRequest", mappedBy="prize")
     */
    public $prizeRequest;

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
        if ($image = $this->getAbsolutePath()) {
            unlink($image);
        }
    }

	/**
     * @param string $name
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param mixed $prizeRequest
     * @return string
     */
    public function setPrizeRequest($prizeRequest)
    {
        $this->prizeRequest = $prizeRequest;

        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getPrizeRequest()
    {
        return $this->prizeRequest;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get image.
     *
     * @return UploadedFile
     */
    public function getImage()
    {
       return $this->image;
    }
    /**
     * @return string
     */
    public function getName()
    {
       return $this->name;
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
}