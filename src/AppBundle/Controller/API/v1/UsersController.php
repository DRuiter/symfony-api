<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\UserEntity;
use AppBundle\Form\UserForm;
use AppBundle\Utils\ErrorValidation;

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
            //TODO Implement proper error handling with status codes
            $users = array();
        }

        $view = $this
                    ->view($users, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/users/{id}")
     * @return object
     */
    public function getUserByID($id){
        $em = $this->getDoctrine()->getManager();

        if(!isset($id) || !is_numeric($id)){
            throw $this->createNotFoundException('Unspecified ID');
        }

        $user = $em->find('AppBundle:UserEntity', $id);

        if(!$user){
            //TODO Implement proper error handling with status codes
            $user = (object) array();
        }

        $view = $this
                    ->view($user, 200)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Post("/api/v1.0/users/")
     * @return object
     */
    public function postUser(Request $request){
        $user       = new UserEntity();
        $userForm   = new UserForm();
        $builder    = $this->createFormBuilder($user);

        $form = $userForm
                    ->getForm($builder)
                    ->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $view = $this
                        ->view(array(
                            'created'   => true,
                            'id'        => $user->getId()
                        ), 200)
                        ->setFormat('json');

            return $this->handleView($view);
        } else {
            $errorUtil = new ErrorValidation();
            $validator = $this->get('validator');

            $errors = $validator->validate($user);

            $view = $this
                        ->view(array(
                            'created'   => false,
                            'errors'    => $form->getErrors(true),
                            'also-errors' => $errors
                        ), 400)
                        ->setFormat('json');

            return $this->handleView($view);
        }
    }
}
