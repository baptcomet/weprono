<?php

namespace Application\Controller;

use Application\Entity\Repository\UserRepository;
use Application\Entity\User;
use Application\Form\DeleteForm;
use Application\Form\UserForm;
use Application\Form\ValidationForm;
use Application\Util\Mailer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Zend\Crypt\Hash;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function listAction()
    {
        /** @var User $user */
        $user = $this->identity();

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var UserRepository $userRepo */
        $userRepo = $em->getRepository('Application\Entity\User');
        /** @var ArrayCollection $users */

        if ($user->getRole() == User::ROLE_ADMINISTRATEUR) {
            $users = $userRepo->findAll();
        } else {
            $users = $userRepo->findEnabled();
        }
        $view = new ViewModel();
        return $view->setVariables(
            array(
                'users' => $users,
            )
        );
    }

    public function detailAction()
    {
        $routeId = $this->params()->fromRoute('id');

        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var User $user */
        $user = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager')
            ->getRepository('Application\Entity\User')
            ->find($id);

        if (is_null($user)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'user' => $user,
                'id' => $routeId,
            )
        );
    }

    public function addAction()
    {
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new UserForm($em, UserForm::TYPE_ADD);
        $user = new User();

        $form->bind($user);
        $user->setRole(User::DEFAULT_ROLE);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $user->setEncryptedPassword(
                    Hash::compute('sha256', $user->getPassword())
                );
                $user->setDisabled(false);
                // TODO : Garder les rôles ?
                $user->setRole($user::ROLE_ADMINISTRATEUR);

                $em->persist($user);
                $em->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Le compte a bien été créé.<br/>' .
                        'L\'utilisateur peut dès à présent se connecter.');

                $subject = 'Votre compte a bien été créé';

                $viewMessage = new ViewModel();
                $viewMessage->setTemplate('mail/utilisateur-nouveau')
                    ->setVariables(
                        array(
                            'user' => $user,
                        )
                    )->setTerminal(true);

                $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                $message = $viewRender->render($viewMessage);

                $to = $user->getEmail();

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

    public function editAction()
    {
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var User $user */
        $user = $this->identity();
        if ($user->getId() != $id && $user->getRole() != User::ROLE_ADMINISTRATEUR) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var User $user */
        $user = $em->getRepository('Application\Entity\User')
            ->find($id);
        if (is_null($user)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $form = new UserForm($em);
        $form->bind($user);

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
                    $user->setEncryptedPassword(
                        Hash::compute('sha256', $user->getPassword())
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
                            'user' => $user,
                        )
                    )->setTerminal(true);

                $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                $message = $viewRender->render($viewMessage);

                $to = $user->getEmail();

                $mailer = new Mailer($this->getServiceLocator());
                $mailer->sendMail($subject, $message, $to);

                return $this->redirect()->toRoute('utilisateur', array('action' => 'detail', 'id' => $id));
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'user' => $user,
                'id' => $routeId,
                'form' => $form,
            )
        );
    }

    public function disableAction()
    {
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var User $user */
        $user = $em->getRepository('Application\Entity\User')
            ->find($id);

        if (is_null($user)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $form = new ValidationForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost('yes')) {
                $user->setDisabled(true);
                $em->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('L\'utilisateur a bien été désactivé.');
                return $this->redirect()->toRoute('utilisateurs');
            } else {
                $this->flashMessenger()
                    ->addWarningMessage('Vous avez annulé la désactivation de l\'utilisateur.');
                return $this->redirect()->toRoute('utilisateurs');
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'user' => $user,
                'id' => $routeId,
                'form' => $form,
            )
        );
    }

    public function enableAction()
    {
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var User $user */
        $user = $em->getRepository('Application\Entity\User')
            ->find($id);

        if (is_null($user)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $user->setDisabled(false);
        $em->flush();

        $this->flashMessenger()
            ->addSuccessMessage('L\'utilisateur a bien été activé.');

        return $this->redirect()->toRoute('utilisateurs');
    }

    public function deleteAction()
    {
        $routeId = (int)$this->params()->fromRoute('id');
        $id = $routeId == 0 ? $this->identity()->getId() : $routeId;

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var User $user */
        $user = $em->getRepository('Application\Entity\User')
            ->find($id);

        if (is_null($user)) {
            $this->flashMessenger()
                ->addErrorMessage('L\'utilisateur n\'existe pas.');
            return $this->redirect()->toRoute('accueil');
        }

        $form = new DeleteForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($request->getPost('yes')) {
                $em->remove($user);
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
                'user' => $user,
                'id' => $routeId,
                'form' => $form,
            )
        );
    }
}
