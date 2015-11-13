<?php
namespace Application\Controller;

use Application\Entity\User;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
//        if (is_null($this->identity())) {
//            return $this->redirect()->toRoute('accueil', array('action' => 'connexion'));
//        }
        $view = new ViewModel();
        return $view;
    }

    public function monCompteAction()
    {
        /** @var User $user */
        $user = $this->identity();
        if (!$user) {
            return $this->redirect()->toRoute('unauthorized');
        }

        return $this->redirect()->toRoute('utilisateur', array('action' => 'detail', 'id' => $user->getId()));
    }
}
