<?php

namespace Application\Form;

use Application\Form\Filter\AuthForgottenPasswordFilter;
use Zend\Form\Element\Email;
use Zend\Form\Element\Submit;

class AuthForgottenPasswordForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('auth');
        $this->setAttribute('method', 'post');
        $this->setAttributes(array('id' => 'auth', 'role' => 'form'));

        $this->setInputFilter(new AuthForgottenPasswordFilter());

        // Email
        $email = new Email('email');
        $email->setLabel('Adresse email')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label'
                )
            )
            ->setAttributes(
                array(
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Adresse email',
                    'required' => true,
                    'autofocus' => true
                )
            );
        $this->add($email);

        // Submit
        $submit = new Submit('submit');
        $submit->setValue('Valider');
        $submit->setAttributes(
            array(
                'class' => 'btn btn-lg btn-primary btn-block'
            )
        );
        $this->add($submit);
    }
}
