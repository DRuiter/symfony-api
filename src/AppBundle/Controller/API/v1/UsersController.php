<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\UserEntity;

class UsersController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/users/")
     * @return array
     */
    public function getAllUsers(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:UserEntity')->findAll();

        if(!$users){
            $users = array();
        }

        $view = $this
                    ->view($users, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/users/{id}")
     */
    public function getUserByID($id){
        $em = $this->getDoctrine()->getManager();

        if(!isset($id) || !is_numeric($id)){
            throw $this->createNotFoundException('Unspecified ID');
        }

        $user = $em->find('AppBundle:UserEntity', $id);

        if(!$user){
            $user = (object) array();
        }

        $view = $this
                    ->view($user, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }
}
