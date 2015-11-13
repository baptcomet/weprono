<?php
return array(
    'navigation' => array(
        // Main Menu
        'default' => array(
            array(
                // Utilisé dans le menu sur le nom de l'app
                'id' => 'home',
                'route' => 'accueil',
                'controller' => 'Application\Controller\Index',
                'action' => 'index',
            ),
            array(
                'route' => 'utilisateurs',
                'action' => 'list',
                'controller' => 'Application\Controller\User',
                'pages' => array(
                    array(
                        'route' => 'utilisateurs',
                        'controller' => 'Application\Controller\User',
                        'action' => 'list',
                    ),
                    array(
                        'route' => 'utilisateurs',
                        'controller' => 'Application\Controller\User',
                        'action' => 'add',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'detail',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'edit',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'disable',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'enable',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'delete',
                    ),
                ),
            ),
            array(
                'route' => 'utilisateur',
                'action' => 'detail',
                'controller' => 'Application\Controller\User',
                'params' => array('id' => 0),
                'pages' => array(
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'edit',
                        'params' => array('id' => 0),
                    ),
                )
            ),
            array(
                'route' => 'connexion',
                'action' => 'deconnexion',
                'controller' => 'Application\Controller\Connexion',
            ),
        ),

        // Login
        'connexion' => array(
            array(
                'route' => 'connexion',
                'action' => 'index',
                'controller' => 'Application\Controller\Connexion',
            ),
            array(
                'route' => 'connexion',
                'action' => 'mdp-oublie',
                'controller' => 'Application\Controller\Connexion',
            ),
            array(
                'route' => 'connexion',
                'action' => 'inscription',
                'controller' => 'Application\Controller\Connexion',
            ),
            array(
                'route' => 'password-recovery',
                'action' => 'reinitialisation-mdp',
                'controller' => 'Application\Controller\Connexion',
            ),
        ),

        // Header
        'utilisateurs' => array(
            array(
                'id' => 'index',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateurs',
                'action' => 'list',
            ),
            array(
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateurs',
                'action' => 'add',
            ),
        ),
        'utilisateur' => array(
            array(
                'id' => 'detail',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'detail',
            ),
            array(
                'id' => 'edit',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'edit',
            ),
            array(
                'id' => 'disable',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'disable',
            ),
            array(
                'id' => 'enable',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'enable',
            ),
            array(
                'id' => 'delete',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'delete',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        ),
    ),
);
