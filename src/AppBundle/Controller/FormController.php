<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use AppBundle\Form\UserForm;
use AppBundle\Entity\UserEntity;

use AppBundle\Form\ContentPageForm;
use AppBundle\Entity\ContentPageEntity;

class FormController extends Controller
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
}
