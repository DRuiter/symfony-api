<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

use JMS\Serializer\SerializationContext;

class ContentPageController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/contentpages/")
     * @return array
     */
    public function getAllContentPages(Request $request){
        $data = array(1,2,3,4, 'wat' => 'wut');
        $view = $this
                    ->view($data, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/contentpage/{id}")
     */
    public function getContentPage(Request $request, $id){
        return new Response('<html><body>'.$slug.'</body></html>');
    }
}
