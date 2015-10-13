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
    protected $url_path;

    public function getID(){
        return $this->$id;
    }

    public function getTitle(){
        return $this->$title;
    }

    public function getBody(){
        return $this->$body;
    }

    public function getUrlPath(){
        return str_replace(' ', '-', strtolower($this->$title));
    }
}
