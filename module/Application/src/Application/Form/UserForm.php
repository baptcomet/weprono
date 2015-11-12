<?php

namespace Application\Form;

use Application\Entity\User;
use Application\Form\Filter\UserFilter;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\Form\Element\Email;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Password;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;
use Zend\Form\Element\Text;

class UserForm extends AbstractForm
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

        $this->setInputFilter(new UserFilter($objectManager, $type));
        $this->setHydrator(new DoctrineObject($objectManager));

        // Id
        $id = new Hidden('id');
        $this->add($id);

        // Prénom
        $firtname = new Text('prenom');
        $firtname->setLabel('Prénom')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label'
                )
            )
            ->setAttributes(
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
        $lastname->setLabel('Nom')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label'
                )
            )
            ->setAttributes(
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
                    'required' => $type == self::TYPE_ADD,
                    'autocomplete' => false,
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
                    'required' => $type == self::TYPE_ADD,
                    'autocomplete' => false,
                )
            );
        $this->add($password);

        $role = new Select('role');
        $role->setValueOptions(User::getStaticRoleList())
            ->setLabel('Sélectionner le rôle de cet utilisateur')
            ->setLabelAttributes(
                array(
                    'class' => 'control-label',
                )
            )
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
