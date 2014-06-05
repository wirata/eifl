<?php

namespace Eifl\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Entity
 * @ORM\Table(name="tbl_page")
 * @ORM\Entity(repositoryClass="Eifl\AdminBundle\Entity\PageRepository")
 */
Class Page
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="page_title", type="string")
     */
    public $pageTitle;

    /**
     * @ORM\Column(name="content", type="text",nullable=true)
     */
    public $content;

    /**
     * @param string $pageTitle
     * @return string
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;

        return $this;
    }
    /**
     * @param text $content
     * @return text
     */
    public function setContent($content)
    {
        $this->content = $content;

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
    public function getPageTitle()
    {
        return $this->pageTitle;
    }
    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }
}