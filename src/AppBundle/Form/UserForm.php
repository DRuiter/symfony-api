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
            'data_class'        => 'AppBundle\Entity\UserEntity'
        ));
    }

    public function getForm(FormBuilderInterface $builder){
        $builder = $this->buildForm($builder, array());

        return $builder->getForm();
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('email', 'repeated', array(
                'type' => 'email',
                'invalid_message' => 'The email fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'E-Mail'),
                'second_options' => array('label' => 'Repeat E-Mail'),
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'mapped' => false
            ))
            ->add('firstName', 'text', array('required' => true))
            ->add('lastName', 'text', array('required' => true))
            ->add('gender', 'choice', array(
                'choices'   => array('m' => 'Male', 'f' => 'Female'),
                'required'  => true
            ))
            ->add('save', 'submit', array('label' => 'Create User'));

        return $builder;
    }

    public function getName(){
        return 'user';
    }
}
