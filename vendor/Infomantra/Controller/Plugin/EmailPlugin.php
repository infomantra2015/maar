<?php

namespace Infomantra\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\Mime\Mime;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;
use Zend\View\Model\ViewModel;

class EmailPlugin extends AbstractPlugin {

    private $useSMTP = false;
    private $smtpName = 'localhost';
    private $smtpHost = 'localhost';
    private $smtpPort = '25';
    private $smtpConnectionClass = 'plain';
    private $smtpUsername = '';
    private $smtpPassword = '';
    private $smtpSsl = '';
    private $mailBody = 'Custom Default Message';
    private $mailFrom = 'noreply@infomantra.com';
    private $mailSubject = 'Custom Subject';
    private $mailFromNickName = 'Info Mantra Support Team';
    private $mailTo = 'arvind.singh.2110@gmail.com';
    private $mailSenderType = 'user';
    private $mailCc = array();
    private $mailBcc = array();
    private $fileNames = array();
    private $filePaths = array();
    private $params = array();
    private $templateFile = '';
    private $templatePath = '';

    public function getServiceLocator() {
        return $this->getController()->getServiceLocator();
    }

    public function getUseSMTP() {
        return $this->useSMTP;
    }

    public function getSmtpName() {
        return $this->smtpName;
    }

    public function getSmtpHost() {
        return $this->smtpHost;
    }

    public function getSmtpPort() {
        return $this->smtpPort;
    }

    public function getSmtpConnectionClass() {
        return $this->smtpConnectionClass;
    }

    public function getSmtpUsername() {
        return $this->smtpUsername;
    }

    public function getSmtpPassword() {
        return $this->smtpPassword;
    }

    public function getSmtpSsl() {
        return $this->smtpSsl;
    }

    public function getMailBody() {
        return $this->mailBody;
    }

    public function getMailFrom() {
        return $this->mailFrom;
    }

    public function getMailSubject() {
        return $this->mailSubject;
    }

    public function getMailFromNickName() {
        return $this->mailFromNickName;
    }

    public function getMailTo() {
        return $this->mailTo;
    }

    public function getMailSenderType() {
        return $this->mailSenderType;
    }

    public function getMailCc() {
        return $this->mailCc;
    }

    public function getMailBcc() {
        return $this->mailBcc;
    }

    public function getFileNames() {
        return $this->fileNames;
    }

    public function getFilePaths() {
        return $this->filePaths;
    }

    public function getParams() {
        return $this->params;
    }

    public function getTemplateFile() {
        return $this->templateFile;
    }

    public function getTemplatePath() {
        return $this->templatePath;
    }

    public function setUseSMTP($useSMTP) {
        $this->useSMTP = $useSMTP;
    }

    public function setSmtpName($smtpName) {
        $this->smtpName = $smtpName;
    }

    public function setSmtpHost($smtpHost) {
        $this->smtpHost = $smtpHost;
    }

    public function setSmtpPort($smtpPort) {
        $this->smtpPort = $smtpPort;
    }

    public function setSmtpConnectionClass($smtpConnectionClass) {
        $this->smtpConnectionClass = $smtpConnectionClass;
    }

    public function setSmtpUsername($smtpUsername) {
        $this->smtpUsername = $smtpUsername;
    }

    public function setSmtpPassword($smtpPassword) {
        $this->smtpPassword = $smtpPassword;
    }

    public function setSmtpSsl($smtpSsl) {
        $this->smtpSsl = $smtpSsl;
    }

    public function setMailBody($mailBody) {
        $this->mailBody = $mailBody;
    }

    public function setMailFrom($mailFrom) {
        $this->mailFrom = $mailFrom;
    }

    public function setMailSubject($mailSubject) {
        $this->mailSubject = $mailSubject;
    }

    public function setMailFromNickName($mailFromNickName) {
        $this->mailFromNickName = $mailFromNickName;
    }

    public function setMailTo($mailTo) {
        $this->mailTo = $mailTo;
    }

    public function setMailSenderType($mailSenderType) {
        $this->mailSenderType = $mailSenderType;
    }

    public function setMailCc($mailCc) {
        $this->mailCc = $mailCc;
    }

    public function setMailBcc($mailBcc) {
        $this->mailBcc = $mailBcc;
    }

    public function setFileNames($fileNames) {
        $this->fileNames = $fileNames;
    }

    public function setFilePaths($filePaths) {
        $this->filePaths = $filePaths;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function setTemplateFile($templateFile) {
        $this->templateFile = $templateFile;
    }

    public function setTemplatePath($templatePath) {
        $this->templatePath = $templatePath;
    }

    private function _setMailOptions($mailOptions) {

        if (array_key_exists('useSMTP', $mailOptions)) {
            $this->setUseSMTP($mailOptions['useSMTP']);
        }
        if (array_key_exists('mailTo', $mailOptions)) {
            $this->setMailTo($mailOptions['mailTo']);
        }
        if (array_key_exists('mailCc', $mailOptions)) {
            $this->setMailCc($mailOptions['mailCc']);
        }
        if (array_key_exists('mailBcc', $mailOptions)) {
            $this->setMailBcc($mailOptions['mailBcc']);
        }
        if (array_key_exists('mailFrom', $mailOptions)) {
            $this->setMailFrom($mailOptions['mailFrom']);
        }
        if (array_key_exists('mailFromNickName', $mailOptions)) {
            $this->setMailFromNickName($mailOptions['mailFromNickName']);
        }
        if (array_key_exists('mailSubject', $mailOptions)) {
            $this->setMailSubject($mailOptions['mailSubject']);
        }
        if (array_key_exists('mailBody', $mailOptions)) {
            $this->setMailBody($mailOptions['mailBody']);
        }
        if (array_key_exists('sender_type', $mailOptions)) {
            $this->setMailSenderType($mailOptions['sender_type']);
        }
        if (array_key_exists('fileNames', $mailOptions)) {
            $this->setFileNames($mailOptions['fileNames']);
        }
        if (array_key_exists('filePaths', $mailOptions)) {
            $this->setFilePaths($mailOptions['filePaths']);
        }
        if (array_key_exists('params', $mailOptions)) {
            $this->setParams($mailOptions['params']);
        }
        if (array_key_exists('templateFile', $mailOptions)) {
            $this->setTemplateFile($mailOptions['templateFile']);
            $this->setMailBody($this->_readTemplate());
        }
    }

    /**
     * Read Mail template
     * 
     * @access private
     * @author Arvind Siingh <arving.singh.2110@gmail.com>
     * 
     * @return string
     */
    private function _readTemplate() {

        $templateFile = $this->getTemplatePath() . $this->getTemplateFile() . ".phtml";
        $view = new PhpRenderer();
        $resolver = new TemplateMapResolver();

        $resolver->setMap(array(
            'mailTemplate' => $templateFile
        ));
        $view->setResolver($resolver);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('mailTemplate');
        $viewModel->setVariables($this->getParams());
        $content = $view->render($viewModel);
        return $content;
    }

    /**
     * Send mail
     * 
     * @access public
     * @author Arvind Siingh <arving.singh.2110@gmail.com>
     * @param type $mailOptions Mail content
     * @throws \Exception
     */
    public function sendMail($mailOptions = array()) {

        $this->_setMailOptions($mailOptions);

        $mailBody = $this->getMailBody();
        $fileNames = $this->getFileNames();
        $filePaths = $this->getFilePaths();
        $mailCc = $this->getMailCc();
        $mailBcc = $this->getMailBcc();

        $text = new Part($mailBody);
        $text->type = Mime::TYPE_HTML;
        $mailBodyParts = new MimeMessage();
        $mailBodyParts->addPart($text);

        if (!empty($fileNames) && !empty($filePaths)) {
            foreach ($filePaths as $key => $filePath) {
                $file = new Part(file_get_contents($filePath));
                $file->encoding = Mime::ENCODING_BASE64;
                $file->type = finfo_file(finfo_open(), $filePath, FILEINFO_MIME_TYPE);
                $file->disposition = Mime::DISPOSITION_ATTACHMENT;
                $file->filename = $fileNames[$key];
                $mailBodyParts->addPart($file);
            }
        }

        $mail = new Message();
        $mail->setBody($mailBodyParts);
        $mail->setFrom($this->getMailFrom(), $this->getMailFromNickName());
        $mail->addTo($this->getMailTo());
        $mail->setSubject($this->getMailSubject());

        if (!empty($mailCc)) {
            $mail->addCc($mailCc);
        }
        if (!empty($mailBcc)) {
            $mail->addBcc($mailBcc);
        }

        $transport = new SmtpTransport();
        $transport->setOptions($this->_configureMail());

        try {
            $transport->send($mail);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Configure mail SMTP settings
     * 
     * @access private
     * @author Arvind Siingh <arving.singh.2110@gmail.com>
     * 
     * @return void
     */
    private function _configureMail() {

        $options = array();

        if ($this->getUseSMTP() === false) {
            $options = new SmtpOptions(array(
                "name" => $this->getSmtpName(),
                "host" => $this->getSmtpHost(),
                "port" => $this->getSmtpPort()
            ));
        } else {
            $options = new SmtpOptions(array(
                'name' => $this->getSmtpName,
                'host' => $this->getSmtpHost,
                'port' => $this->getSmtpPort,
                'connection_class' => $this->getSmtpConnectionClass(),
                'connection_config' => array(
                    'ssl' => $this->getSmtpSsl(),
                    'username' => $this->getSmtpUsername(),
                    'password' => $this->getSmtpPassword()
                )
            ));
        }

        return $options;
    }

}
