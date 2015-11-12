<?php
namespace Application\Form;

use Zend\Form\Element\Submit;

class DeleteForm extends AbstractForm
{
    /**
     * Formulaires de suppression
     */
    public function __construct()
    {
        parent::__construct('delete');
        $this->setAttribute('method', 'post');
        $this->setAttributes(array('id' => 'delete', 'role' => 'form'));

        // Non
        $no = new Submit('no');
        $no->setValue('Annuler');
        $no->setAttributes(array('id' => 'no', 'class' => 'btn btn-default'));
        $this->add($no);

        // Oui
        $yes = new Submit('yes');
        $yes->setValue('Supprimer définitivement');
        $yes->setAttributes(array('id' => 'yes', 'class' => 'btn btn-danger'));
        $this->add($yes);
    }
}
