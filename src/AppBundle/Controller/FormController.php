<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use AppBundle\Form\UserForm;
use AppBundle\Entity\UserEntity;

use AppBundle\Form\ContentPageForm;
use AppBundle\Entity\ContentPageEntity;

use AppBundle\Utils\ErrorValidation;

class FormController extends FOSRestController
{
    /**
     * @Get("/forms/user")
     */
    public function getUserForm(){
        $user       = new UserEntity();
        $builder    = $this->createFormBuilder($user);
        $form       = new UserForm();

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->BuildForm($builder, array())->getForm()->createView()
        ));
    }

    /**
     * @Post("/forms/user")
     */
    public function postUserForm(Request $request){
        $user       = new UserEntity();
        $userForm   = new UserForm();
        $builder    = $this->createFormBuilder($user);

        $form = $userForm
              ->getForm($builder)
              ->handleRequest($request);

        if ($form->isValid()) {
            $user->setPassword($request->request->get('password'));

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
            $validator        = $this->get('validator');
            $errorValidator   = new ErrorValidation();

            $errors = $validator->validate($user);

            $view = $this
                      ->view(array(
                          'created'         => false,
                          'form-errors'     => $errorValidator->getErrorMessages($form),
                          'entity-errors'   => $errors
                      ), 400)
                      ->setFormat('json');

            return $this->handleView($view);
        }
    }

    /**
     * @Get("/forms/contentpage")
     */
    public function getContentPageForm(){
        $contentPage    = new ContentPageEntity();
        $builder        = $this->createFormBuilder($contentPage);
        $form           = new ContentPageForm();

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->BuildForm($builder, array())->getForm()->createView()
        ));
    }

    /**
     * @Post("/forms/contentpage")
     */
    public function postContentPageForm(Request $request){
        $contentPage       = new ContentPageEntity();
        $contentPageForm   = new ContentPageForm();
        $builder    = $this->createFormBuilder($contentPage);

        $form = $contentPageForm
              ->getForm($builder)
              ->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contentPage);
            $em->flush();

            $view = $this
                      ->view(array(
                          'created'   => true,
                          'id'        => $contentPage->getId()
                      ), 200)
                      ->setFormat('json');

            return $this->handleView($view);
        } else {
            $validator        = $this->get('validator');
            $errorValidator   = new ErrorValidation();

            $errors = $validator->validate($contentPage);

            $view = $this
                      ->view(array(
                          'created'         => false,
                          'form-errors'     => $errorValidator->getErrorMessages($form),
                          'entity-errors'   => $errors
                      ), 400)
                      ->setFormat('json');

            return $this->handleView($view);
        }
    }
}
