<?php
namespace Application\Controller;

use Application\Entity\Utilisateur;
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
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();
        if (!$utilisateur) {
            return $this->redirect()->toRoute('unauthorized');
        }

        return $this->redirect()->toRoute('utilisateur', array('action' => 'detail', 'id' => $utilisateur->getId()));
    }
}
