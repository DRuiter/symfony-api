<?php

namespace AppBundle\Entity;

/**
 * @Entity
 * @Table(name="contentpages")
 */
class ContentPage
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length="100", unique=true)
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var string
     */
    protected $urlPath;

    /**
     * @return integer
     */
    public function getID(){
        return $this->$id;
    }

    /**
     * @return string
     */
    public function getTitle(){
        return $this->$title;
    }

    /**
     * @return string
     */
    public function getBody(){
        return $this->$body;
    }

    /**
     * @return string
     */
    public function getUrlPath(){
        return str_replace(' ', '-', strtolower($this->$title));
    }
}
