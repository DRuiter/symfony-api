<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Post;

use JMS\Serializer\SerializationContext;

class BatchController extends FOSRestController
{
    /**
     * @Post("/api/v1.0/batch/")
     * @return array
     */
    public function postBundle(Request $request){
        $data = array(1,2,3,4, 'wat' => 'wut');
        $view = $this
                    ->view($data, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
