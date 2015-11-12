<?php

namespace Application\Util;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\ServiceManager\ServiceLocatorInterface;

class Mailer
{
    const FROM = 'no-reply@weprono.fr';

    private $body;
    private $transport;

    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->body = new MimeMessage();

        $config = $serviceLocator->get('Configuration');
        $this->transport = new Smtp();
        $options = new SmtpOptions($config['mail']['transport']['options']);
        $this->transport->setOptions($options);
    }

    /**
     * @param string $subject
     * @param string $message
     * @param null|string|array $to
     * @param null|string|array $cc
     * @param null|string|array $bcc
     * @return array
     */
    public function sendMail($subject, $message, $to = null, $cc = null, $bcc = null)
    {
        $return = array(
            'success' => true,
            'msg' => null
        );

        $partBody = new MimePart($this->getPartHeader() . $message . $this->getPartFooter());

        $partBody->type = Mime::TYPE_HTML;
        $partBody->charset = 'utf-8';

        $this->body->setParts(array($partBody));

        $subject = '[' . APPLICATION_NAME . '] ' . $subject;

        $mail = new Message();
        $mail->addFrom(self::FROM);
        $mail->setSubject($subject);
        $mail->setBody($this->body);
        $mail->setTo($to);
        if ($cc) {
            $mail->setCc($cc);
        }
        if ($bcc) {
            $mail->setBcc($bcc);
        }

        try {
            $this->transport->send($mail);
        } catch (\Exception $e) {
            $return['success'] = false;
            $return['msg'] = _('mail.message.not_sent');
            //throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $return;
    }

    private function getPartHeader()
    {
        $html = '<html>' . PHP_EOL;
        $html .= "\t" . '<head>' . PHP_EOL;
        $html .= "\t" . '</head>' . PHP_EOL;
        $html .= "\t" . '<body>' . PHP_EOL;

        return $html;
    }

    private function getPartFooter()
    {
        $html = "\t" . '</body>' . PHP_EOL;
        $html .= '</html>' . PHP_EOL;

        return $html;
    }
}
