<?php
namespace AppBundle\Form\API;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class APIContentPageForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\ContentPageEntity'
        ));
    }

    public function getForm(FormBuilderInterface $builder){
        $builder = $this->buildForm($builder, array());

        return $builder->getForm();
    }

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('title', 'text', array('required' => true))
            ->add('body', 'textarea', array('required' => true));

        return $builder;
    }

    public function getName(){
        return 'APIContentPageForm';
    }
}
