<?php
namespace AppBundle\Utils;

use Symfony\Component\Form\Form;

class ErrorValidation
{
    public function getErrorMessages(Form $form){
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
              $errors[$child->getName()] = $this->getErrorMessages($child);
          }
        }

        return $errors;
    }

    public function parseFormErrors(Form $form){
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
                $errors[$child->getName()] = $child->getErrors();
            }
        }

        return $errors;
    }
}
