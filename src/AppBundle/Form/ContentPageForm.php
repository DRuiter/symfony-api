<?php
namespace AppBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\ContentPageEntity;

class ContentPageForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContentPageEntity',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('title', 'text', array('required' => true))
            ->add('body', 'textarea', array('required' => true))
            ->add('save', 'submit', array('label' => 'Create Content Page'));

        return $builder;
    }

    public function newAction(Request $request){
        $contentPage = new ContentPageEntity();

        $builder = $this->createFormBuilder($contentPage);

        $form = $this
            ->buildForm($builder, array())
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('is-valid');
        }

        return $this->render('default/default-form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function getName(){
        return 'user';
    }
}
