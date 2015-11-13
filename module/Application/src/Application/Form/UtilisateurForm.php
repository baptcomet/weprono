<?php

namespace Application\Form;

use Application\Entity\Utilisateur;
use Application\Form\Filter\UtilisateurFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;

class UtilisateurForm extends AbstractForm
{
    const TYPE_ADD = 0;
    const TYPE_EDIT = 1;

    public function __construct(EntityManager $objectManager, $type = self::TYPE_EDIT)
    {
        parent::__construct('auth');
        $this->setAttributes(
            array(
                'method' => 'post',
                'id' => 'auth',
                'role' => 'form'
            )
        );

        $this->setInputFilter(new UtilisateurFilter($objectManager, $type));
        $this->setHydrator(new DoctrineObject($objectManager));

        // Id
        $id = new Hidden('id');
        $this->add($id);

        // Prénom
        $firtname = new Text('prenom');
        $firtname->setAttributes(
                array(
                    'id' => 'prenom',
                    'class' => 'form-control',
                    'placeholder' => 'Prénom',
                    'required' => true,
                    'autofocus' => true
                )
            );
        $this->add($firtname);

        // Nom
        $lastname = new Text('nom');
        $lastname->setAttributes(
                array(
                    'id' => 'nom',
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                    'required' => true,
                    'autofocus' => true
                )
            );
        $this->add($lastname);

        // Email
        $email = new Email('email');
        $email->setAttributes(
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
        $password->setAttributes(
                array(
                    'id' => 'password',
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe',
                    'required' => $type == self::TYPE_ADD,
                    'autocomplete' => false,
                )
            );
        $this->add($password);

        // Password
        $password = new Password('passwordConfirmation');
        $password->setAttributes(
                array(
                    'id' => 'passwordConfirmation',
                    'class' => 'form-control',
                    'placeholder' => 'Confirmation du mot de passe',
                    'required' => $type == self::TYPE_ADD,
                    'autocomplete' => false,
                )
            );
        $this->add($password);

        $role = new Select('role');
        $role->setValueOptions(Utilisateur::getStaticRoleList())
            ->setAttributes(
                array(
                    'id' => 'role',
                    'class' => 'form-control',
                    'required' => true,
                )
            );
        $this->add($role);

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

    /**
     * @return bool
     */
    public function hasIdentificationError()
    {
        return $this->hasError(array('nom', 'prenom', 'email'));
    }

    /**
     * @return bool
     */
    public function hasPasswordError()
    {
        return $this->hasError(array('password', 'passwordConfirmation'));
    }
}
