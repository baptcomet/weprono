<?php
if (!isset($_SERVER['SERVER_ADDR'])) {
    $_SERVER['SERVER_ADDR'] = '127.0.0.1';
}

switch ($_SERVER['SERVER_ADDR']) {
    case '127.0.0.1':
    case '::1':
        define('APPLICATION_ENV', 'development');
        define('APPLICATION_CACHE', false);
        break;
    case '178.62.66.77':
        define('APPLICATION_ENV', 'production');
        define('APPLICATION_CACHE', true);
        break;
    default:
        die('Erreur de configuration');
        break;
}

define('APPLICATION_NAME', 'We Prono');

return array(
    'modules' => array(
        'Application',
        'DoctrineModule',
        'DoctrineORMModule',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor'
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local,local.' . APPLICATION_ENV . '}.php'
        ),
        'cache_dir' => './data/cache',
        'config_cache_enabled' => APPLICATION_CACHE,
        'module_map_cache_enabled' => APPLICATION_CACHE,
        'config_cache_key' => md5('config'),
        'module_map_cache_key' => md5('module_map'),
        'check_dependencies' => true,
    ),
);
