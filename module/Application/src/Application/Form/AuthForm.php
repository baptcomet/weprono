<?php

namespace Application\Form;

use Application\Form\Filter\AuthFilter;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;

class AuthForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('auth');
        $this->setAttribute('method', 'post');
        $this->setAttributes(array('id' => 'auth', 'role' => 'form'));

        $this->setInputFilter(new AuthFilter());

        // Email
        $email = new Email('email');
        $email->setAttributes(
                array(
                    'id' => 'email',
                    'class' => 'form-control',
                    'placeholder' => 'Adresse email',
                    'required' => true,
                    'autofocus' => true
                )
            );
        $this->add($email);

        // Password
        $password = new Password('encryptedPassword');
        $password->setAttributes(
                array(
                    'id' => 'encryptedPassword',
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                    'required' => true,
                )
            );
        $this->add($password);

        // Submit
        $submit = new Submit('submit');
        $submit->setValue('Connexion');
        $submit->setAttributes(
            array(
                'class' => 'btn btn-primary btn-block'
            )
        );
        $this->add($submit);
    }
}
