<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\ContentPageEntity;

class ContentPagesController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/contentpages/")
     * @return array
     */
    public function getAllContentPages(){
        $em = $this->getDoctrine()->getManager();

        $contentPages = $em->getRepository('AppBundle:ContentPageEntity')->findAll();

        if(!$contentPages){
            $contentPages = array();
        }

        $view = $this
                    ->view($contentPages, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/contentpages/{id}")
     */
    public function getContentPage($id){
        $em = $this->getDoctrine()->getManager();

        if(!isset($id) || !is_numeric($id)){
            throw $this->createNotFoundException('Unspecified ID');
        }

        $contentPage = $em->find('AppBundle:ContentPageEntity', $id);

        if(!$contentPage){
            $contentPage = (object) array();
        }

        $view = $this
                    ->view($contentPage, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
