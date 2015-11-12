<?php

namespace Application\Form\Filter;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class AuthForgottenPasswordFilter extends InputFilter
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
    }
}
