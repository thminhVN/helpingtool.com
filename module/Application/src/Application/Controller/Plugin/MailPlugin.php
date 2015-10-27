<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Message;

class MailPlugin extends AbstractPlugin
{

    protected $smtpOptions;

    private function _config()
    {
        $this->smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
        $this->smtpOptions->setHost('mail.starseed.fr')
            ->setConnectionClass('login')
            ->setName('mail.starseed.fr')
            ->setConnectionConfig(array(
            'username' => 'sendmail.starseed',
            'password' => 'Mp84HB9cg',
            // 'ssl' => 'tls',
            'port' => 587
        ));
        
        return $this;
    }

    public function send($fromEmail, $toEmail, $subject, $body, $attachFiles = null, $attachFolder = "", $prefixAttachFile = "")
    {
        $this->_config();
        // Create separate alternative parts object
        $htmlPart = new MimePart($body);
        $htmlPart->type = "text/html";
        
        // Create plain text part
        $stripTagsFilter = new \Zend\Filter\StripTags();
        $textContent = str_ireplace(array(
            "<br />",
            "<br>"
        ), "\r\n", $body);
        $textContent = $stripTagsFilter->filter($textContent);
        $textPart = new MimePart($textContent);
        $textPart->type = "text/plain";
        
        if ($attachFiles != null) {
            $alternatives = new MimeMessage();
            $alternatives->setParts(array(
                $textPart,
                $htmlPart
            ));
            $alternativesPart = new MimePart($alternatives->generateMessage());
            $alternativesPart->type = "multipart/alternative;\n boundary=\"" . $alternatives->getMime()->boundary() . "\"";
            
            // Add to a MIME message
            $mimeMessage = new MimeMessage();
            $mimeMessage->addPart($alternativesPart);
            
            foreach ($attachFiles as $file_attach) {
                $fileContents = file_get_contents($attachFolder . $file_attach, 'r');
                $attachment->type = \Zend\Mime\Mime::TYPE_OCTETSTREAM;
                $attachment->filename = $prefixAttachFile . time() . $file_attach;
                $attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
                $attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
                
                $mimeMessage->addPart($attachment);
            }
        } else {
            $mimeMessage = new MimeMessage();
            $mimeMessage->setParts(array(
                $textPart,
                $htmlPart
            ));
            // $mimeMessage = $body;
        }
        
        $message = new Message();
        $message->setEncoding('UTF-8');

        $mails = array();
        if(is_string($toEmail)){
            $mails[] = $toEmail;
        } else if(is_array($toEmail)) {
            $mails = $toEmail;
        }
        foreach ($mails as $email) {
            $message->addTo($email);
        }
        $message->setFrom($fromEmail)->setSubject($subject);
        $message->setBody($mimeMessage);
        $message->getHeaders()
            ->get('content-type')
            ->setType('multipart/alternative');
        
        $transport = new \Zend\Mail\Transport\Smtp($this->smtpOptions);
        
        try {
            $response = $transport->send($message);
            $response = array(
                'error' => 0,
                'message' => 'success'
            );
        } catch (\Zend\Mail\Exception\RuntimeException $ex) {
            $ex->getMessage();
            $response = array(
                'error' => 1,
                'message' => $ex->getMessage()
            );
        }
        return $response;
    }
}

?>