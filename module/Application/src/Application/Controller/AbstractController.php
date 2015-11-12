<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractController extends AbstractActionController
{
    /**
     * @param null|string $route
     * @return \Zend\Http\Response
     */
    public function nonAutoriseAction($route = null)
    {
        $route = (is_null($route)) ? 'accueil' : $route;

        $this->flashMessenger()->addWarningMessage('Vous n\'êtes pas autorisé à accéder à cette page.');
        return $this->redirect()->toRoute($route);
    }
}
