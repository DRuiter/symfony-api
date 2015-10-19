<?php
namespace AppBundle\Form\API;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class APIUserForm extends AbstractType
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
            ->add('email', 'email', array())
            ->add('firstName', 'text', array())
            ->add('lastName', 'text', array())
            ->add('gender', 'choice', array(
                'choices'   => array('m' => 'Male', 'f' => 'Female')
            ));

        return $builder;
    }

    public function getName(){
        return 'APIUserForm';
    }
}
