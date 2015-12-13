<?php

namespace Application\Form\Filter;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\UniqueObject;
use Zend\Filter\Digits;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\I18n\Validator\DateTime;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Callback;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class LigueFilter extends InputFilter
{
    // TODO toute la classe : nom + image + date début + date fin + joueurs
    public function __construct(EntityManager $objectManager)
    {
        // Nom
        $nom = new Input('nom');
        $nom->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StripTags());
        $nom->getValidatorChain()
            ->attach(new NotEmpty())
            ->attach(new StringLength(
                array(
                    'max' => 50,
                )
            ));
        $this->add($nom);

        // Image
        $image = new Input('image');
        $image->setRequired(false);
        // TODO
        $this->add($image);

        // Date début
        $dateDebut = new Input('dateDebut');
        $dateDebut->setRequired(true);
            //->getValidatorChain()
            //->attach(new DateTime(array('format' => 'd/m/Y')));
        // TODO
        $this->add($dateDebut);

        // Date fin
        $dateFin = new Input('dateFin');
        $dateFin->setRequired(true);
            //->getValidatorChain()
            //->attach(new DateTime(array('format' => 'd/m/Y')));
        // TODO
        $this->add($dateFin);

        // TODO joueurs
    }
}
