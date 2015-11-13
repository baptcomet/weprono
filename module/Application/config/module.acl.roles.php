<?php
use Application\Util\RoleList;

return array(
    'roles' => array(
        RoleList::ROLE_ADMINISTRATEUR => array(
            'Application\Controller\Index',
            'Application\Controller\Connexion',
            'Application\Controller\Utilisateur',
        ),
        RoleList::ROLE_UTILISATEUR => array(
            'Application\Controller\Index' => array('index', 'non-autorise'),
            'Application\Controller\Connexion',
            'Application\Controller\Utilisateur' => array('index', 'detail', 'edit'),
        ),
        RoleList::ROLE_INVITE => array(
            'Application\Controller\Index' => array('index', 'non-autorise'),
            'Application\Controller\Connexion',
        ),
    ),
);
