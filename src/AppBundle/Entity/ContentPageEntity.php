<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="contentpages")
 */
class ContentPageEntity
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
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 100
     * )
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1
     * )
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
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(){
        return $this->title;
    }

    /**
     * @return ContentPageEntity
     */
    public function setTitle($title){
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(){
        return $this->body;
    }

    /**
     * @return ContentPageEntity
     */
    public function setBody($body){
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrlPath(){
        return str_replace(' ', '-', strtolower($this->title));
    }
}
