<?php

namespace Application\Controller;

use Application\Entity\Ligue;
use Application\Entity\Repository\GameRepository;
use Application\Entity\Utilisateur;
use Application\Form\DeleteForm;
use Application\Form\LigueForm;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LigueController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();

        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        if ($utilisateur->getRole() == Utilisateur::ROLE_ADMINISTRATEUR) {
            $ligues = $entityManager->getRepository('Application\Entity\Ligue')->findAll();
            $liguesCrees = new ArrayCollection();
        } else {
            $ligues = $utilisateur->getLigues();
            $liguesCrees = $utilisateur->getLiguesCrees();
        }
        
        $view = new ViewModel();
        return $view->setVariables(
            array(
                'ligues' => $ligues,
                'liguesCrees' => $liguesCrees,
            )
        );
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var GameRepository $gameRepo */
        $gameRepo = $entityManager->getRepository('Application\Entity\Game');

        //if ($id == 99) {
        //    $gameRepo->apiRetrieveGamesBySeason(2015);
        //}

        /** @var Ligue $ligue */
        $ligue = $entityManager->getRepository('Application\Entity\Ligue')->find($id);
        if (is_null($ligue)) {
            $this->flashMessenger()
                ->addErrorMessage('La ligue n\'existe pas.');
            return $this->redirect()->toRoute('ligues');
        }
        $tonight = new DateTime();
        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval('P1D'));
        $yesterdaysGames = $gameRepo->findByDate($yesterday);
        $tonightsGames = $gameRepo->findByDate($tonight);

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'ligue' => $ligue,
                'yesterdaysGames' => $yesterdaysGames,
                'tonightsGames' => $tonightsGames,
                'id' => $id,
            )
        );
    }
    
    public function creerAction()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();

        $form = new LigueForm($entityManager);
        $ligue = new Ligue();

        $form->bind($ligue);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ligue->setCreateur($utilisateur);
                $ligue->addJoueur($utilisateur);
                $entityManager->persist($ligue);
                $entityManager->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('La ligue a bien été créé.');
                return $this->redirect()->toRoute('ligues');
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
    }

    public function modifierAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();

        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var Ligue $ligue */
        $ligue = $entityManager->getRepository('Application\Entity\Ligue')
            ->find($id);
        if (is_null($ligue)) {
            $this->flashMessenger()
                ->addErrorMessage('La ligue n\'existe pas.');
            return $this->redirect()->toRoute('ligues');
        }

        $form = new LigueForm($entityManager);
        $form->bind($ligue);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('La ligue a bien été modifiée.');
                return $this->redirect()->toRoute('ligue', array('action' => 'detail', 'id' => $id));
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'utilisateur' => $utilisateur,
                'id' => $id,
                'form' => $form,
            )
        );
    }

    public function supprimerAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        /** @var EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var Ligue $ligue */
        $ligue = $entityManager->getRepository('Application\Entity\Ligue')->find($id);

        if (is_null($ligue)) {
            $this->flashMessenger()
                ->addErrorMessage('La ligue n\'existe pas.');
            return $this->redirect()->toRoute('ligues');
        }

        $form = new DeleteForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost('yes')) {
                $entityManager->remove($ligue);
                $entityManager->flush();
                $this->flashMessenger()
                    ->addSuccessMessage('La ligue a bien été supprimée.');
                return $this->redirect()->toRoute('ligues');
            } else {
                $this->flashMessenger()
                    ->addWarningMessage('Vous avez annulé la suppression de la ligue.');
                return $this->redirect()->toRoute('ligues');
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'ligue' => $ligue,
                'id' => $id,
                'form' => $form,
            )
        );
    }
}
