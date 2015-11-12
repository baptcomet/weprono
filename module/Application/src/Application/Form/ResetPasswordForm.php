<?php
namespace Application\Form;

use Application\Form\Filter\UserFilter;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element\Email;
use Zend\Form\Element\Password;
use Zend\Form\Element\Submit;

class ResetPasswordForm extends AbstractForm
{
    public function __construct(EntityManager $objectManager)
    {
        parent::__construct('auth');
        $this->setAttribute('method', 'post');
        $this->setAttributes(array('id' => 'auth', 'role' => 'form'));

        $this->setInputFilter(new UserFilter($objectManager, UserForm::TYPE_EDIT));

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
                )
            );
        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel('Mot de passe')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label',
                )
            )
            ->setAttributes(
                array(
                    'id' => 'password',
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                    'required' => true,
                )
            );
        $this->add($password);

        // Password
        $password = new Password('passwordConfirmation');
        $password->setLabel('Confirmation du mot de passe')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label',
                )
            )
            ->setAttributes(
                array(
                    'id' => 'passwordConfirmation',
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                    'required' => true,
                )
            );
        $this->add($password);

        // Submit
        $submit = new Submit('submit');
        $submit->setValue('Modifier');
        $submit->setAttributes(
            array(
                'class' => 'btn btn-lg btn-primary btn-block'
            )
        );
        $this->add($submit);
    }
}
