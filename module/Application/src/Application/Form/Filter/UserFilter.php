<?php

namespace Application\Form\Filter;

use Application\Form\UserForm;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\UniqueObject;
use Zend\Filter\Digits;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Callback;
use Zend\Validator\EmailAddress;
use Zend\Validator\Identical;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class UserFilter extends InputFilter
{
    public function __construct(EntityManager $objectManager, $type)
    {
        // Firstname
        $firstname = new Input('prenom');
        $firstname->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $firstname->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'max' => 50,
                )
            ));
        $this->add($firstname);

        // Lastname
        $lastname = new Input('nom');
        $lastname->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $lastname->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'max' => 50,
                )
            ));
        $this->add($lastname);

        //Unique email validator
        $uniqueUserEmail = new UniqueObject(
            array(
                'object_manager' => $objectManager,
                'object_repository' => $objectManager->getRepository('Application\Entity\User'),
                'fields' => 'email',
                'use_context' => true
            )
        );
        $uniqueUserEmail->setMessage('L\'email renseigné est déjà utilisé par un autre utilisateur.', 'objectNotUnique');

        // Email
        $email = new Input('email');
        $email->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $email->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(
                new StringLength(
                    array(
                        'max' => 255
                    )
                )
            )->attach(new EmailAddress())
            ->attach($uniqueUserEmail);
        $this->add($email);

        $editCallback = new Callback(
            function ($value) {
                if ($value == '') {
                    return true;
                }

                /** @var StringLength $validator */
                $validator = new StringLength(
                    array(
                        'min' => 6,
                        'max' => 32
                    )
                );
                return $validator->isValid($value);
            }
        );
        $editCallback->setMessage('Le mot de passe doit contenir entre 6 et 32 caractères.');

        // Password
        $password = new Input('password');
        $password->setRequired($type == UserForm::TYPE_ADD)
            ->setContinueIfEmpty(true);
        $password->getFilterChain()->attach(new StringTrim());
        $password->getValidatorChain()
            ->attach(
                $type == UserForm::TYPE_ADD ?
                    new StringLength(
                        array(
                            'min' => 6,
                            'max' => 32
                        )
                    ) :
                    $editCallback
            );
        $this->add($password);

        // Password Confirmation
        $passwordConfirmation = new Input('passwordConfirmation');
        $passwordConfirmation->setRequired($type == UserForm::TYPE_ADD)
            ->setContinueIfEmpty(true);
        $passwordConfirmation->getFilterChain()->attach(new StringTrim());
        $passwordConfirmation->getValidatorChain()
            ->attach(
                $type == UserForm::TYPE_ADD ?
                    new StringLength(
                        array(
                            'min' => 6,
                            'max' => 32
                        )
                    ) :
                    $editCallback
            )
            ->attach(new Identical('password'));
        $this->add($passwordConfirmation);

        $role = new Input('role');
        $role->setRequired(false);
        $role->getFilterChain()->attach(new Digits());
        $this->add($role);
    }
}
