<?php
namespace Application;

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Connexion' => 'Application\Controller\ConnexionController',
            'Application\Controller\User' => 'Application\Controller\UserController',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'encryptedPassword',
            ),
        ),
    ),
    'router' => array(
        'routes' => array(
            'accueil' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/:action][/]',
                    'defaults' => array(
                        'module' => 'Application',
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'connexion' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/connexion[/:action][/]',
                    'defaults' => array(
                        'module' => 'Application',
                        'controller' => 'Application\Controller\Connexion',
                        'action' => 'index',
                    ),
                ),
            ),
            'password-recovery' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/reset-password/[:token][/]',
                    'defaults' => array(
                        'module' => 'Application',
                        'controller' => 'Application\Controller\Index',
                        'action' => 'reinitialisation-mdp',
                    ),
                ),
            ),
            'utilisateurs' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/utilisateurs[/:action][/]',
                    'constraints' => array(
                        'action' => 'list|add',
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'module' => 'Application',
                        'controller' => 'Application\Controller\User',
                        'action' => 'list',
                    )
                )
            ),
            'utilisateur' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/utilisateur[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => 'edit|detail|delete|disable|enable',
                        'id' => '[0-9]*',
                    ),
                    'defaults' => array(
                        'module' => 'Application',
                        'controller' => 'Application\Controller\User',
                        'action' => 'detail',
                        'id' => 0,
                    )
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'fr_FR',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
