<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Form\UserForm;
use AppBundle\Entity\UserEntity;

use AppBundle\Form\ContentPageForm;
use AppBundle\Entity\ContentPageEntity;

class FormController extends Controller
{
    /**
     * @Route("/forms/user", name="user-form")
     */
    public function userFormAction(Request $request){
        $user       = new UserEntity();
        $builder    = $this->createFormBuilder($user);
        $form       = new UserForm();

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->BuildForm($builder, array())->getForm()->createView()
        ));
    }

    /**
     * @Route("/forms/contentpage", name="contentpage-form")
     */
    public function contentPageFormAction(Request $request){
        $contentPage    = new ContentPageEntity();
        $builder        = $this->createFormBuilder($contentPage);
        $form           = new ContentPageForm();

        return $this->render('forms/default-form.html.twig', array(
            'form' => $form->BuildForm($builder, array())->getForm()->createView()
        ));
    }
}
