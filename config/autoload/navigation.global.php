<?php
/**
 * Pour ajouter un icone : index 'icon' : element complet en valeur
 * Pour ne pas afficher de dropdown : index 'dropdown' : false
 */
return array(
    'navigation' => array(
        // Main Menu
        'default' => array(
            array(
                // Utilisé dans le menu sur le nom de l'app
                'id' => 'home',
                'label' => 'Accueil',
                'title' => 'Pronostics entre amis sur la NBA',
                'route' => 'accueil',
                'controller' => 'Application\Controller\Index',
                'action' => 'index',
                'visible' => false,
                'icon' => '<i class="fa fa-accueil fa-fw"></i>&nbsp; ',
            ),
            array(
                'label' => 'Utilisateurs',
                'route' => 'utilisateurs',
                'action' => 'list',
                'controller' => 'Application\Controller\User',
                'dropdown' => false,
                'pages' => array(
                    array(
                        'label' => 'Liste',
                        'route' => 'utilisateurs',
                        'controller' => 'Application\Controller\User',
                        'action' => 'list',
                        'icon' => '<i class="fa fa-users fa-fw text-primary"></i>&nbsp; ',
                    ),
                    array(
                        'label' => 'Création',
                        'route' => 'utilisateurs',
                        'controller' => 'Application\Controller\User',
                        'action' => 'add',
                        'icon' => '<i class="fa fa-user-plus fa-fw text-success"></i>&nbsp; ',
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'detail',
                        'visible' => false,
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'edit',
                        'visible' => false,
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'disable',
                        'visible' => false,
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'enable',
                        'visible' => false,
                    ),
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'delete',
                        'visible' => false,
                    ),
                ),
            ),
            array(
                'label' => 'Mon compte',
                'title' => 'Mon compte',
                'route' => 'utilisateur',
                'action' => 'detail',
                'controller' => 'Application\Controller\User',
                'params' => array('id' => 0),
                'icon' => '<i class="fa fa-user fa-fw text-primary"></i>&nbsp; ',
                'label-class' => 'hidden-sm',
                'right' => true,
                'pages' => array(
                    array(
                        'controller' => 'Application\Controller\User',
                        'route' => 'utilisateur',
                        'action' => 'edit',
                        'params' => array('id' => 0),
                        'visible' => false,
                    ),
                )
            ),
            array(
                'label' => 'Déconnexion',
                'title' => 'Déconnexion',
                'route' => 'accueil',
                'action' => 'deconnexion',
                'controller' => 'Application\Controller\Index',
                'icon' => '<i class="fa fa-sign-out fa-fw text-danger"></i>&nbsp; ',
                'label-class' => 'hidden-sm',
                'right' => true,
            ),
        ),

        // Login
        'connexion' => array(
            array(
                'label' => 'Connexion',
                'title' => 'Connexion',
                'route' => 'accueil',
                'action' => 'connexion',
                'controller' => 'Application\Controller\Index',
            ),
            array(
                'label' => 'Mot de passe oublié',
                'title' => 'Mot de passe oublié',
                'route' => 'accueil',
                'action' => 'mdp-oublie',
                'controller' => 'Application\Controller\Index',
            ),
            array(
                'label' => 'Inscription',
                'title' => 'Inscription',
                'route' => 'accueil',
                'action' => 'inscription',
                'controller' => 'Application\Controller\Index',
            ),
            array(
                'title' => 'Réinitialisation du mot de passe',
                'route' => 'password-recovery',
                'action' => 'reinitialisation-mdp',
                'controller' => 'Application\Controller\Index',
                'visible' => false,
            ),
        ),

        // Header
        'utilisateurs' => array(
            array(
                'id' => 'index',
                'label' => 'Retour à la liste',
                'title' => 'Liste des utilisateurs',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateurs',
                'action' => 'list',
                'class' => 'btn btn-default',
                'icon' => '<i class="fa fa-chevron-left fa-fw"></i>&nbsp; ',
            ),
            array(
                'label' => 'Création',
                'title' => 'Création d\'un utilisateur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateurs',
                'action' => 'add',
                'class' => 'btn btn-success',
                'icon' => '<i class="fa fa-user-plus fa-fw"></i>&nbsp; ',
            ),
        ),
        'utilisateur' => array(
            array(
                'id' => 'detail',
                'label' => 'Détail',
                'title' => 'Détail de l\'utilisateur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'detail',
                'icon' => '<i class="fa fa-info-circle fa-fw"></i>&nbsp; ',
            ),
            array(
                'id' => 'edit',
                'label' => 'Modifier',
                'title' => 'Modifier l\'utilisateur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'edit',
                'icon' => '<i class="fa fa-pencil-square-o fa-fw"></i>&nbsp; ',
            ),
            array(
                'id' => 'disable',
                'label' => 'Désactiver',
                'title' => 'Désactivation l\'utilisateur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'disable',
                'icon' => '<i class="fa fa-eye-slash fa-fw"></i>&nbsp; ',
            ),
            array(
                'id' => 'enable',
                'label' => 'Activer',
                'title' => 'Activation de l\'utilisataeur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'enable',
                'icon' => '<i class="fa fa-eye fa-fw"></i>&nbsp',
            ),
            array(
                'id' => 'delete',
                'label' => 'Supprimer',
                'title' => 'Supprimer l\'utilisateur',
                'controller' => 'Application\Controller\User',
                'route' => 'utilisateur',
                'action' => 'delete',
                'icon' => '<i class="fa fa-trash-o fa-fw"></i>&nbsp; ',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        ),
    ),
);
