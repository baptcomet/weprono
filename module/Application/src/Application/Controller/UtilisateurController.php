<?php

namespace Application\Controller;

use Application\Entity\Repository\UtilisateurRepository;
use Application\Entity\Utilisateur;
use Application\Form\DeleteForm;
use Application\Form\UtilisateurForm;
use Application\Util\Mailer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Zend\Crypt\Hash;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UtilisateurController extends AbstractActionController
{
    public function indexAction()
    {
        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var UtilisateurRepository $utilisateurRepo */
        $utilisateurRepo = $em->getRepository('Application\Entity\Utilisateur');
        /** @var ArrayCollection $users */

        if ($utilisateur->getRole() == Utilisateur::ROLE_ADMINISTRATEUR) {
            $utilisateurs = $utilisateurRepo->findAll();
        } else {
            $utilisateurs = $utilisateurRepo->findEnabled();
        }
        $view = new ViewModel();
        return $view->setVariables(
            array(
                'utilisateurs' => $utilisateurs,
            )
        );
    }

    public function detailAction()
    {
        $routeId = $this->params()->fromRoute('id');

        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')
            ->getRepository('Application\Entity\Utilisateur')
            ->find($id);

        if (is_null($utilisateur)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'utilisateur' => $utilisateur,
                'id' => $routeId,
            )
        );
    }

    public function creerAction()
    {
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new UtilisateurForm($em, UtilisateurForm::TYPE_ADD);
        $utilisateur = new Utilisateur();

        $form->bind($utilisateur);
        $utilisateur->setRole(Utilisateur::DEFAULT_ROLE);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $utilisateur->setEncryptedPassword(
                    Hash::compute('sha256', $utilisateur->getPassword())
                );
                $utilisateur->setDisabled(false);
                // TODO : Garder les rôles ?
                $utilisateur->setRole($utilisateur::ROLE_ADMINISTRATEUR);

                $em->persist($utilisateur);
                $em->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Le compte a bien été créé.<br/>' .
                        'L\'utilisateur peut dès à présent se connecter.');

                $subject = 'Votre compte a bien été créé';

                $viewMessage = new ViewModel();
                $viewMessage->setTemplate('mail/utilisateur-nouveau')
                    ->setVariables(
                        array(
                            'user' => $utilisateur,
                        )
                    )->setTerminal(true);

                $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                $message = $viewRender->render($viewMessage);

                $to = $utilisateur->getEmail();

                $mailer = new Mailer($this->getServiceLocator());
                $mailer->sendMail($subject, $message, $to);

                return $this->redirect()->toRoute('utilisateurs');
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
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var Utilisateur $utilisateur */
        $utilisateur = $this->identity();
        if ($utilisateur->getId() != $id && $utilisateur->getRole() != Utilisateur::ROLE_ADMINISTRATEUR) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var Utilisateur $utilisateur */
        $utilisateur = $em->getRepository('Application\Entity\Utilisateur')
            ->find($id);
        if (is_null($utilisateur)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $form = new UtilisateurForm($em);
        $form->bind($utilisateur);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $unchanged = false;
            if ($post['password'] == '' && $post['passwordConfirmation'] == '') {
                $unchanged = true;
            }
            $form->setData($post);
            if ($form->isValid()) {
                if (!$unchanged) {
                    $utilisateur->setEncryptedPassword(
                        Hash::compute('sha256', $utilisateur->getPassword())
                    );
                }

                $em->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('L\'utilisateur a bien été modifié.');

                $subject = 'Votre compte a été mis à jour';

                $viewMessage = new ViewModel();
                $viewMessage->setTemplate('mail/utilisateur-maj')
                    ->setVariables(
                        array(
                            'user' => $utilisateur,
                        )
                    )->setTerminal(true);

                $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                $message = $viewRender->render($viewMessage);

                $to = $utilisateur->getEmail();

                $mailer = new Mailer($this->getServiceLocator());
                $mailer->sendMail($subject, $message, $to);

                return $this->redirect()->toRoute('utilisateur', array('action' => 'detail', 'id' => $id));
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'utilisateur' => $utilisateur,
                'id' => $routeId,
                'form' => $form,
            )
        );
    }

    public function supprimerAction()
    {
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var Utilisateur $utilisateur */
        $utilisateur = $em->getRepository('Application\Entity\Utilisateur')
            ->find($id);

        if (is_null($utilisateur)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $form = new DeleteForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost('yes')) {
                $em->remove($utilisateur);
                $em->flush();
                $this->flashMessenger()
                    ->addSuccessMessage('L\'utilisateur a bien été supprimé.');
                return $this->redirect()->toRoute('utilisateurs');
            } else {
                $this->flashMessenger()
                    ->addWarningMessage('Vous avez annulé la suppression de l\'utilisateur.');
                return $this->redirect()->toRoute('utilisateurs');
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'utilisateur' => $utilisateur,
                'id' => $routeId,
                'form' => $form,
            )
        );
    }
}
