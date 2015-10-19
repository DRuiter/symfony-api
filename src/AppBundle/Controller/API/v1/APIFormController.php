<?php

namespace AppBundle\Controller\API\v1;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use AppBundle\Form\API\APIUserForm;
use AppBundle\Entity\UserEntity;

use AppBundle\Form\API\APIContentPageForm;
use AppBundle\Entity\ContentPageEntity;

use AppBundle\Utils\ErrorValidation;

class APIFormController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/forms/user")
     */
    public function getUserForm(){
        $user       = new UserEntity();
        $form       = $this->createForm(new APIUserForm(), $user);

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Get("/api/v1.0/forms/user/{id}")
     */
    public function getUserFormByID($id){
        $em      = $this->getDoctrine()->getManager();
        $user    = $em->find('AppBundle:UserEntity', $id);

        if(!$user) $user = new UserEntity();

        $form           = $this->createForm(new APIUserForm(), $user);

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Get("/api/v1.0/forms/contentpage")
     */
    public function getContentPageForm(){
        $contentPage    = new ContentPageEntity();
        $form = $this->createForm(new APIContentPageForm(), $contentPage);

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Get("/api/v1.0/forms/contentpage/{id}")
     */
    public function getContentPageFormByID($id){
        $em             = $this->getDoctrine()->getManager();
        $contentPage    = $em->find('AppBundle:ContentPageEntity', $id);

        if(!$contentPage) $contentPage = new ContentPageEntity();

        $form           = $this->createForm(new APIContentPageForm(), $contentPage);

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
