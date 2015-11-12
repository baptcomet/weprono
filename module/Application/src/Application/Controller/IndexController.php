<?php
namespace Application\Controller;

use Application\Entity\User;
use Application\Form\AuthForgottenPasswordForm;
use Application\Form\AuthForm;
use Application\Form\ResetPasswordForm;
use Application\Form\UserForm;
use Application\Util\Mailer;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Crypt\Hash;
use Zend\Http\Request;
use Zend\Math\Rand;
use Zend\Session\Container;
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

    public function connexionAction()
    {
        $this->layout('layout/connexion');

        $form = new AuthForm();

        /** @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /** @var AuthenticationService $authenticator */
                $authenticator = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                /** @var \DoctrineModule\Authentication\Adapter\ObjectRepository $adapter */
                $adapter = $authenticator->getAdapter();
                $adapter->setIdentity($form->getData()['email']);
                $adapter->setCredential(
                    Hash::compute('sha256', $form->getData()['encryptedPassword'])
                );

                /** @var \Zend\Authentication\Result $authResult */
                $authResult = $authenticator->authenticate();
                if ($authResult->isValid()) {
                    if ($this->identity()->isDisabled()) {
                        $authenticator->clearIdentity();

                        $this->flashMessenger()
                            ->addErrorMessage('Votre compte a été désactivé, vous ne pouvez pas vous connecter.');
                    }
                    return $this->redirect()->toRoute('accueil');
                } else {
                    $this->flashMessenger()
                        ->addErrorMessage('L\'authentification a échouée, l\'email ou le mot de passe saisi est incorrect.');
                }
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
    }

    public function deconnexionAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();

        $sessionManager = Container::getDefaultManager();
        $sessionManager->destroy();

        $this->flashMessenger()
            ->addSuccessMessage('Vous avez bien été déconnecté(e).');

        return $this->redirect()->toRoute('accueil');
    }

    public function inscriptionAction()
    {
        $this->layout('layout/connexion');

        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $form = new UserForm($em);
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

                $em->persist($user);
                $em->flush();

                $this->flashMessenger()
                    ->addSuccessMessage('Votre inscription a bien été prise en compte.<br/>Vous pouvez dès à présent vous connecter.');

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

                return $this->redirect()->toRoute('accueil');
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
    }

    public function mdpOublieAction()
    {
        $this->layout('layout/connexion');

        $form = new AuthForgottenPasswordForm();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /** @var EntityManager $em */
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                /** @var User $user */
                $user = $em->getRepository('Application\Entity\User')
                    ->findOneBy(array('email' => $form->getData()['email']));
                if (is_null($user)) {
                    $this->flashMessenger()
                        ->addErrorMessage('Aucun utilisateur correspondant à cette adresse email n\'a été trouvé.');
                } else {
                    $user->setToken(Rand::getString(32, $user::getStaticTokenPattern()));

                    $em->persist($user);
                    $em->flush();

                    $subject = 'Demande de réinitialisation de votre mot de passe';

                    $viewMessage = new ViewModel();
                    $viewMessage->setTemplate('mail/utilisateur-reinitialisation-mdp')
                        ->setVariables(
                            array(
                                'user' => $user,
                            )
                        )->setTerminal(true);

                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                    $message = $viewRender->render($viewMessage);

                    $to = $user->getEmail();

                    $mailer = new Mailer($this->getServiceLocator());
                    $sendMail = $mailer->sendMail($subject, $message, $to);

                    if ($sendMail['success']) {
                        $this->flashMessenger()
                            ->addSuccessMessage('Un message vous a été envoyé à votre adresse comportant un lien de réinitialisation du mot de passe');

                        return $this->redirect()->toRoute('accueil');
                    } else {
                        $this->flashMessenger()->addErrorMessage($sendMail['msg']);
                    }
                }
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
    }

    public function reinitialisationMdpAction()
    {
        $this->layout('layout/connexion');

        $token = $this->params()->fromRoute('token', false);
        if (!$token) {
            return $this->redirect()->toRoute('unauthorized');
        }
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $form = new ResetPasswordForm($em);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /** @var User $user */
                $user = $em->getRepository('Application\Entity\User')
                    ->findOneBy(array('email' => $form->getData()['email']));

                if (is_null($user) || $user->getToken() != $token) {
                    $this->flashMessenger()
                        ->addErrorMessage('L\'adresse email ou le token est incorrect.');
                } else {
                    $user->setEncryptedPassword(
                        Hash::compute('sha256', $form->getData()['password'])
                    );
                    $user->setToken(null);

                    $em->persist($user);
                    $em->flush();

                    $subject = 'Réinitialisation de votre mot de passe';

                    $viewMessage = new ViewModel();
                    $viewMessage->setTemplate('mail/utilisateur-maj-mdp')
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

                    $this->flashMessenger()
                        ->addSuccessMessage('Votre mot de passe a bien été modifié.');

                    return $this->redirect()->toRoute('accueil');
                }
            }
        }

        $view = new ViewModel();
        return $view->setVariables(
            array(
                'form' => $form,
            )
        );
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
