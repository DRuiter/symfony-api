<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

use JMS\Serializer\SerializationContext;

use AppBundle\Entity\UserEntity;
use AppBundle\Form\UserForm;

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
     * @return object
     */
    public function getUserByID($id){
        $em = $this->getDoctrine()->getManager();

        if(!isset($id) || !is_numeric($id)){
            $view = $this
                    ->view(array('error' => 'Unspecified or incorrect ID'), 400)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $user = $em->find('AppBundle:UserEntity', $id);

        if(!$user){
            $view = $this
                    ->view(array('error' => 'Cannot find user with ID: '.$id), 404)
                    ->setFormat('json');
        } else {
            $view = $this
                    ->view($user, 200)
                    ->setFormat('json');
        }


        return $this->handleView($view);
    }

    /**
     * @Post("/api/v1.0/users/")
     * @return object
     */
    public function postUser(Request $request){
        $user       = new UserEntity();
        $userForm   = new UserForm();

        $form = $this
                    ->createForm($userForm, $user)
                    ->handleRequest($request);

        var_dump($user);die;

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
            $view = $this
                        ->view(array(
                            'created'   => false,
                            'errors'    => $form->getErrors(true)
                        ), 400)
                        ->setFormat('json');

            return $this->handleView($view);
        }
    }

    /**
     * @Put("/api/v1.0/users/{id}")
     */
    public function putUserByID(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $userForm   = new UserForm();

        if(!isset($id) || !is_numeric($id)){
            $view = $this
                    ->view(array('error' => 'Unspecified or incorrect ID'), 400)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $user = $em->find('AppBundle:UserEntity', $id);

        if(!$user){
            $view = $this
                    ->view(array('error' => 'Cannot find user with ID: '.$id), 404)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        // Switched to manually patching the entity, for some reason the form was
        // seen as invalid, even though no errors were returned in $form->getErrors(true)
        // or any other errors from the validator for that matter.
        $updates = $request->request->all();

        if(isset($updates['firstName'])) $user->setFirstName($updates['firstName']);
        if(isset($updates['lastName'])) $user->setLastName($updates['lastName']);
        if(isset($updates['gender'])) $user->setGender($updates['gender']);
        if(isset($updates['email'])) $user->setEmail($updates['email']);

        $validator  = $this->container->get('validator');
        $errors     = $validator->validate($user);

        if(count($errors) <= 0){
            $em->merge($user);
            $em->flush();

            $view = $this
                        ->view(array(
                            'updated'   => true,
                            'id'        => $user->getId()
                        ), 200)
                        ->setFormat('json');
        } else {
            $view = $this
                        ->view(array(
                            'updated'   => false,
                            'errors'    =>$errors
                        ), 400)
                        ->setFormat('json');
        }

        return $this->handleView($view);
    }

    /**
     * @Delete("/api/v1.0/users/{id}")
     */
    public function deleteUserByID($id){
        $em = $this->getDoctrine()->getManager();
        $userForm   = new UserForm();

        if(!isset($id) || !is_numeric($id)){
            $view = $this
                    ->view(array('error' => 'Unspecified or incorrect ID'), 400)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $user = $em->find('AppBundle:UserEntity', $id);

        if(!$user){
            $view = $this
                    ->view(array('error' => 'Cannot find user with ID: '.$id), 404)
                    ->setFormat('json');
        } else {
            $em->remove($user);
            $em->flush();

            $view = $this
                        ->view(array(
                            'deleted'   => true,
                            'id'        => $id
                        ), 200)
                        ->setFormat('json');
        }

        return $this->handleView($view);
    }
}
