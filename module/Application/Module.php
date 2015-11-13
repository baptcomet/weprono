<?php
namespace Application;

use Application\Util\Acl;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManager;
use Zend\Validator\AbstractValidator;

class Module
{
    /** @var ACL */
    private $acl;

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $eventManager = $app->getEventManager();

        $utilisateur = $e->getApplication()
            ->getServiceManager()
            ->get('Zend\Authentication\AuthenticationService')
            ->getIdentity();

        $this->acl = new Acl();
        $this->acl->initialize($utilisateur);
        //on passe l'objet ACL à la vue pour plus tard
        $e->getViewModel()->acl = $this->acl;

        $eventManager->attach('route', array($this, 'checkAcl'));

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $translator = $app->getServiceManager()->get('translator');
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zendframework/resources/languages/fr/Zend_Validate.php'
        );
        $translator->setLocale('fr_FR')->setFallbackLocale('fr_FR');
        AbstractValidator::setDefaultTranslator($translator);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function ($serviceManager) {
                    /** @var ServiceManager $serviceManager */
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @param MvcEvent $e
     */
    public function checkAcl(MvcEvent $e)
    {
        $route = $e->getRouteMatch();

        $controller = $route->getParam('controller');
        $action = $route->getParam('action');

        if (!$this->acl->isAllowed($controller, $action)) {
            $url = $e->getRouter()->assemble(array('controller' => 'index', 'action' => 'non-autorise'), array('name' => 'accueil'));
            /** @var Response $response */
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
        }
    }
}
