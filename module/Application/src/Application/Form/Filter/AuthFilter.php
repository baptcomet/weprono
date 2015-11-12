<?php

namespace Application\Form\Filter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class AuthFilter extends InputFilter
{
    public function __construct()
    {
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
            )->attach(new EmailAddress());
        $this->add($email);

        // Password
        $password = new Input('encryptedPassword');
        $password->setRequired(true);
        $password->getFilterChain()->attach(new StringTrim());
        $password->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(
                new StringLength(
                    array(
                        'min' => 6,
                        'max' => 32
                    )
                )
            );
        $this->add($password);
    }
}
