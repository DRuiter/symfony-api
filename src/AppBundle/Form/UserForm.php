<?php
namespace AppBundle\Form;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\UserEntity;

class UserForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserEntity',
        ));
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('email', 'email', array('required' => true))
            ->add('emailCheck', 'email', array('required' => true, 'mapped' => false))
            ->add('password', 'password', array('required' => true, 'mapped' => false))
            ->add('passwordCheck', 'password', array('required' => true, 'mapped' => false))
            ->add('firstName', 'text', array('required' => true))
            ->add('lastName', 'text', array('required' => true))
            ->add('gender', 'choice', array(
                'choices'   => array('m' => 'Male', 'f' => 'Female'),
                'required'  => true
            ))
            ->add('save', 'submit', array('label' => 'Create User'));

        return $builder;
    }

    public function newAction(Request $request){
        $user = new UserEntity();

        $form = $this->createFormBuilder($user)
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
            ->add('save', 'submit', array('label' => 'Create User'))
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
