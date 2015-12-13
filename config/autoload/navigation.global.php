<?php
return array(
    'navigation' => array(

        // NAVBAR
        'default' => array(
            array(
                'id' => 'accueil',
                'route' => 'accueil',
                'controller' => 'Application\Controller\Index',
                'action' => 'index',
            ),
            array(
                'id' => 'ligues',
                'label' => 'Ligues',
                'route' => 'ligues',
                'action' => 'index',
                'controller' => 'Application\Controller\Ligue',
            ),
            array(
                'id' => 'utilisateurs',
                'label' => 'Utilisateurs',
                'route' => 'utilisateurs',
                'action' => 'index',
                'controller' => 'Application\Controller\Utilisateur',
            ),
            array(
                'id' => 'mon-compte',
                'label' => 'Mon compte',
                'icon' => '<i class="fa fa-user fa-fw text-info"></i>&nbsp;',
                'route' => 'utilisateur',
                'action' => 'detail',
                'controller' => 'Application\Controller\Utilisateur',
                'params' => array('id' => 0),
                'pages' => array(
                    array(
                        'controller' => 'Application\Controller\Utilisateur',
                        'route' => 'utilisateur',
                        'action' => 'edit',
                        'params' => array('id' => 0),
                    ),
                )
            ),
            array(
                'id' => 'deconnexion',
                'label' => 'Déconnexion',
                'icon' => '<i class="fa fa-sign-out text-danger fa-fw"></i>&nbsp;',
                'route' => 'connexion',
                'action' => 'deconnexion',
                'controller' => 'Application\Controller\Connexion',
            ),
            array(
                'id' => 'connexion',
                'label' => 'Connexion',
                'icon' => '<i class="fa fa-sign-in fa-fw"></i>&nbsp;',
                'route' => 'connexion',
                'action' => 'index',
                'controller' => 'Application\Controller\Connexion',
            ),
        ),

        // LIGUE
        'ligue' => array(
            array(
                'id' => 'index',
                'label' => 'Liste',
                'title' => 'Ligues',
                'controller' => 'Application\Controller\Ligue',
                'route' => 'ligues',
                'action' => 'index',
            ),
            array(
                'id' => 'creer',
                'label' => 'Créer',
                'title' => 'Créer une ligue',
                'controller' => 'Application\Controller\Ligue',
                'route' => 'ligues',
                'action' => 'creer',
            ),
            array(
                'id' => 'detail',
                'label' => 'Détail',
                'title' => 'Détail',
                'icon' => '<i class="fa fa-info-circle text-info fa-fw"></i>',
                'controller' => 'Application\Controller\Ligues',
                'route' => 'ligue',
                'action' => 'detail',
            ),
            array(
                'id' => 'modifier',
                'label' => 'Modifier',
                'title' => 'Modifier',
                'icon' => '<i class="fa fa-pencil text-warning fa-fw"></i>',
                'controller' => 'Application\Controller\Ligue',
                'route' => 'ligue',
                'action' => 'modifier',
            ),
            array(
                'id' => 'supprimer',
                'label' => 'Supprimer',
                'title' => 'Supprimer',
                'icon' => '<i class="fa fa-trash text-danger fa-fw"></i>',
                'controller' => 'Application\Controller\Ligue',
                'route' => 'ligue',
                'action' => 'supprimer',
            ),
        ),

        // UTILISATEUR
        'utilisateur' => array(
            array(
                'id' => 'index',
                'label' => 'Liste',
                'title' => 'Utilisateurs',
                'controller' => 'Application\Controller\Utilisateur',
                'route' => 'utilisateurs',
                'action' => 'index',
            ),
            array(
                'id' => 'creer',
                'label' => 'Créer',
                'title' => 'Créer un utilisateur',
                'controller' => 'Application\Controller\Utilisateur',
                'route' => 'utilisateurs',
                'action' => 'creer',
            ),
            array(
                'id' => 'detail',
                'label' => 'Détail',
                'title' => 'Détail',
                'icon' => '<i class="fa fa-info-circle text-info fa-fw"></i>',
                'controller' => 'Application\Controller\Utilisateur',
                'route' => 'utilisateur',
                'action' => 'detail',
            ),
            array(
                'id' => 'modifier',
                'label' => 'Modifier',
                'title' => 'Modifier',
                'icon' => '<i class="fa fa-pencil text-warning fa-fw"></i>',
                'controller' => 'Application\Controller\Utilisateur',
                'route' => 'utilisateur',
                'action' => 'modifier',
            ),
            array(
                'id' => 'supprimer',
                'label' => 'Supprimer',
                'title' => 'Supprimer',
                'icon' => '<i class="fa fa-trash text-danger fa-fw"></i>',
                'controller' => 'Application\Controller\Utilisateur',
                'route' => 'utilisateur',
                'action' => 'supprimer',
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Navigation\Service\NavigationAbstractServiceFactory'
        ),
    ),
);
