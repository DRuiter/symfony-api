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

use AppBundle\Entity\ContentPageEntity;
use AppBundle\Form\API\APIContentPageForm;
use AppBundle\Form\ContentPageForm;

class ContentPagesController extends FOSRestController
{
    /**
     * @Get("/api/v1.0/contentpages/")
     * @return array
     */
    public function getAllContentPages(){
        $em = $this->getDoctrine()->getManager();

        $contentPages = $em->getRepository('AppBundle:ContentPageEntity')->findAll();

        if(!$contentPages){
            $contentPages = array();
        }

        $view = $this
                    ->view($contentPages, Response::HTTP_OK)
                    ->setFormat('json');

        return $this->handleView($view);
    }

    /**
     * @Get("/api/v1.0/contentpages/{id}")
     */
    public function getContentPage($id){
        $em = $this->getDoctrine()->getManager();

        if(!isset($id) || !is_numeric($id)){
            throw $this->createNotFoundException('Unspecified ID');
        }

        $contentPage = $em->find('AppBundle:ContentPageEntity', $id);

        if(!$contentPage){
            $error = array(
                'errors' => array('Couldn\'t find contentPage with ID: '.$id)
            );

            $view = $this
                        ->view($error, Response::HTTP_NOT_FOUND)
                        ->setFormat('json');
        } else {
            $view = $this
                        ->view($contentPage, Response::HTTP_OK)
                        ->setFormat('json');
        }

        return $this->handleView($view);
    }

    /**
     * @Put("/api/v1.0/contentpages/{id}")
     */
    public function putContentPageByID(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $contentPageForm   = new APIContentPageForm();

        if(!isset($id) || !is_numeric($id)){
            $view = $this
                    ->view(array('error' => 'Unspecified or incorrect ID'), Response::HTTP_BAD_REQUEST)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $contentpage = $em->find('AppBundle:ContentPageEntity', $id);

        if(!$contentpage){
            $view = $this
                    ->view(array('error' => 'Cannot find Content Page with ID: '.$id), Response::HTTP_NOT_FOUND)
                    ->setFormat('json');

            return $this->handleView($view);
        }


        // Switched to manually patching the entity, for some reason the form was
        // seen as invalid, even though no errors were returned in $form->getErrors(true)
        // or any other errors from the validator for that matter.
        if($request->request->get('APIContentPageForm')){
            $updates = $request->request->get('APIContentPageForm');
        } else {
            $updates = $request->request->all();
        }

        if(isset($updates['title'])) $contentpage->setTitle($updates['title']);
        if(isset($updates['body'])) $contentpage->setBody($updates['body']);

        $validator  = $this->container->get('validator');
        $errors     = $validator->validate($contentpage);

        if(count($errors) <= 0){
            $em->persist($contentpage);
            $em->flush();

            $view = $this
                        ->view(array(
                            'updated'   => true,
                            'id'        => $contentpage->getId()
                        ), Response::HTTP_OK)
                        ->setFormat('json');
        } else {
            $view = $this
                        ->view(array(
                            'updated'   => false,
                            'errors'    => $errors
                        ), Response::HTTP_BAD_REQUEST)
                        ->setFormat('json');
        }

        return $this->handleView($view);
    }

    /**
     * @Post("/api/v1.0/contentpages/")
     * @return object
     */
    public function postContentPage(Request $request){
        $contentpage       = new ContentPageEntity();
        $contentpageForm   = new APIContentPageForm();

        $form = $this
                    ->createForm($contentpageForm, $contentpage)
                    // Using submit here, handleRequest invalidates the form
                    // without any errors for some reason.
                    ->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($contentpage);
            $em->flush();

            $view = $this
                        ->view(array(
                            'created'   => true,
                            'id'        => $contentpage->getId()
                        ), Response::HTTP_CREATED)
                        ->setFormat('json');

            return $this->handleView($view);
        } else {
            $errorValidation = new ErrorValidation();
            $view = $this
                        ->view(array(
                            'created'   => false,
                            'errors'    => $errorValidation->getFormErrorMessages($form)
                        ), Response::HTTP_BAD_REQUEST)
                        ->setFormat('json');

            return $this->handleView($view);
        }
    }

    /**
     * @Delete("/api/v1.0/contentpages/{id}")
     */
    public function deleteContentPageByID($id){
        $em                 = $this->getDoctrine()->getManager();
        $contentPageForm    = new APIContentPageForm();

        if(!isset($id) || !is_numeric($id)){
            $view = $this
                    ->view(array('error' => 'Unspecified or incorrect ID'), Response::HTTP_BAD_REQUEST)
                    ->setFormat('json');

            return $this->handleView($view);
        }

        $contentpage = $em->find('AppBundle:ContentPageEntity', $id);

        if(!$contentpage){
            $view = $this
                    ->view(array('error' => 'Cannot find contentpage with ID: '.$id), Response::HTTP_NOT_FOUND)
                    ->setFormat('json');
        } else {
            $em->remove($contentpage);
            $em->flush();

            $view = $this
                        ->view(array(
                            'deleted'   => true,
                            'id'        => $id
                        ), Response::HTTP_OK)
                        ->setFormat('json');
        }

        return $this->handleView($view);
    }
}
