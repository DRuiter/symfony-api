<?php
namespace AppBundle\Utils;

use Symfony\Component\Form\Form;

class ErrorValidation
{
    public function getFormErrorMessages(Form $form){
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
          if ($form->isRoot()) {
              $errors['#'][] = $error->getMessage();
          } else {
              $errors[] = $error->getMessage();
          }
        }

        foreach ($form->all() as $child) {
          if (!$child->isValid()) {
              $errors[$child->getName()] = $this->getFormErrorMessages($child);
          }
        }

        return $errors;
    }
}
