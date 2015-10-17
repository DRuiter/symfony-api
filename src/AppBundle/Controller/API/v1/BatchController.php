<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpKernel\HttpKernelInterface;

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
        $kernel    = $this->container->get('http_kernel');
        $requests  = array();
        $responses = array();
        $parse     = $request->request->all();

        foreach($parse as $route => $parameters){
            $req = Request::create(
                $route,
                $parameters['method'],
                (isset($parameters['params']) ? $parameters['params'] : array()),
                (isset($parameters['cookie']) ? $parameters['files'] : $request->cookies->all()),
                (isset($parameters['files']) ? $parameters['files'] : $request->files->all()),
                $request->server->all(),
                (isset($parameters['body']) ? $parameters['body'] : null)
            );

            if ($request->getSession()) {
                $req->setSession($request->getSession());
            }

            $requests[$route] = $req;
        }

        foreach($requests as $route => $req){
            $res = $kernel->handle($req, HttpKernelInterface::SUB_REQUEST);
            $responses[$route] = array(
                'code' => $res->getStatusCode(),
                'date' => $res->getDate(),
                'content' => $res->getContent()
            );
        }

        $view = $this
                    ->view($responses, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
