<?php
namespace AppBundle\Form\Type;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('email', 'email', array('required' => true))
            ->add('emailCheck', 'email', array('required' => true))
            ->add('password', 'password', array('required' => true))
            ->add('passwordCheck', 'password', array('required' => true))
            ->add('firstName', 'text', array('required' => true))
            ->add('lastName', 'text', array('required' => true))
            ->add('gender', 'choice', array(
                'choices'   => array('m' => 'Male', 'f' => 'Female'),
                'required'  => true
            ))
            ->add('save', 'submit');
    }

    public function newAction(Request $request){
        $task = new Task();

        $form = $this->createFormBuilder($task)
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // ... perform some action, such as saving the task to the database

            return true;
            //return $this->redirectToRoute('task_success');
        }

        return $this->render('default/user-form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function getName(){
        return 'user';
    }
}
