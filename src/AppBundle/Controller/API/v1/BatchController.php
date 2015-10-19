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
    public function postBatch(Request $request){
        $kernel    = $this->container->get('http_kernel');
        $requests  = array();
        $responses = array();
        $parse     = $request->request->all();

        foreach($parse as $parameters){
            if(isset($parameters['params']) && is_string($parameters['params'])){
                $parameters['params'] = unserialize($parameters['params']);
            }

            $req = Request::create(
                $parameters['route'],
                $parameters['method'],
                (isset($parameters['params']) ? $parameters['params'] : array()),
                (isset($parameters['cookie']) ? $parameters['cookie'] : $request->cookies->all()),
                (isset($parameters['files']) ? $parameters['files'] : $request->files->all()),
                $request->server->all(),
                (isset($parameters['body']) ? $parameters['body'] : null)
            );

            if ($request->getSession()) {
                $req->setSession($request->getSession());
            }

            if(!isset($requests[$parameters['method']])){
                $requests[$parameters['method']] = array();
            }

            $requests[$parameters['method']][$parameters['route']] = $req;
        }

        foreach($requests as $method => $value){
            foreach($value as $route => $req){
                if(!isset($responses[$method])){
                    $responses[$method] = array();
                }

                $res = $kernel->handle($req, HttpKernelInterface::SUB_REQUEST, true);

                $responses[$method][$route] = array(
                    'code' => $res->getStatusCode(),
                    'date' => $res->getDate(),
                    'content' => json_decode($res->getContent(), true)
                );
            }
        }

        $view = $this
                    ->view($responses, Response::HTTP_OK)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
