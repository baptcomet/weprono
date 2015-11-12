<?php
use Application\Util\RoleList;

return array(
    'roles' => array(
        RoleList::ROLE_ADMINISTRATEUR => array(
            'Application\Controller\Index' => array('index', 'deconnexion', 'non-autorise'),
            'Application\Controller\User' /*=> array('index', 'add', 'detail', 'edit', '')*/,
        ),
        RoleList::ROLE_UTILISATEUR => array(
            'Application\Controller\Index' => array('index', 'deconnexion', 'non-autorise'),
            'Application\Controller\User' => array('index', 'detail', 'edit'),
        ),
        RoleList::ROLE_INVITE => array(
            'Application\Controller\Index' => array('index', 'connexion', 'mdp-oublie', 'inscription', 'reinitialisation-mdp', 'non-autorise'),
        ),
    ),
);
