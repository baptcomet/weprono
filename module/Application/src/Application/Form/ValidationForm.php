<?php
namespace Application\Form;

use Zend\Form\Element\Submit;

class ValidationForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('validation');

        $yes = new Submit('yes');
        $yes->setValue('Oui')
            ->setAttributes(
                array(
                    'id' => 'yes',
                    'class' => 'btn btn-success',
                    'style' => 'width: 200px;',
                )
            );
        $this->add($yes);

        $no = new Submit('no');
        $no->setValue('Non')
            ->setAttributes(
                array(
                    'id' => 'no',
                    'class' => 'btn btn-danger',
                    'style' => 'width: 200px;',
                )
            );
        $this->add($no);
    }
}
