<?php

namespace Application\Form;

use Application\Form\Filter\LigueFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Element\File;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;

class LigueForm extends AbstractForm
{
    public function __construct(EntityManager $objectManager)
    {
        parent::__construct('ligue');
        $this->setAttributes(
            array(
                'method' => 'post',
                'id' => 'auth',
                'role' => 'form'
            )
        );

        $this->setInputFilter(new LigueFilter($objectManager));
        $this->setHydrator(new DoctrineObject($objectManager));

        // Id
        $id = new Hidden('id');
        $this->add($id);

        // Nom
        $nom = new Text('nom');
        $nom->setLabel('Nom')
            ->setLabelAttributes(array('class', 'control-label'));
        $nom->setAttributes(
                array(
                    'id' => 'nom',
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                    'required' => true,
                )
            );
        $this->add($nom);

        // Image
        $image = new File('image');
        $image->setLabel('Image')
            ->setLabelAttributes(array('class', 'control-label'));
        $image->setAttributes(
                array(
                    'id' => 'image',
                    'class' => 'form-control',
                    'placeholder' => 'Image',
                )
            );
        $this->add($image);

        // Date début
        $dateDebut = new Text('dateDebut');
        $dateDebut->setLabel('Début de la ligue')
            ->setLabelAttributes(array('class', 'control-label'));
        $dateDebut->setAttributes(
                array(
                    'id' => 'dateDebut',
                    'class' => 'form-control',
                    'required' => true,
                )
            );
        $this->add($dateDebut);

        // Date fin
        $dateFin = new Text('dateFin');
        $dateFin->setLabel('Fin de la ligue')
            ->setLabelAttributes(array('class', 'control-label'));
        $dateFin->setAttributes(
                array(
                    'id' => 'dateFin',
                    'class' => 'form-control',
                    'required' => true,
                )
            );
        $this->add($dateFin);

        // Submit
        $submit = new Submit('submit');
        $submit->setValue('Enregistrer');
        $submit->setAttributes(
            array(
                'class' => 'btn btn-primary'
            )
        );
        $this->add($submit);
    }
}
