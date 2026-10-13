<?php
namespace App\Libraries\Mailbox;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailbox extends PHPMailer {

  public function __construct() {
    parent::__construct(new PHPMailer(true));
  }

  public function send() {
    try {
      // $this->SMTPDebug = SMTP::DEBUG_OFF; // SMTP::DEBUG_SERVER; 
      $this->isHTML(true);
      
      // Specify the SMTP settings.
      $this->isSMTP();
      $this->Host = getEnv('mailbox.smtp.host');
      $this->Username = getEnv('mailbox.smtp.username');
      $this->Password = getEnv('mailbox.smtp.password');
      $this->Port = getEnv('mailbox.smtp.port');
      $this->SMTPSecure = 'tls';
      $this->SMTPAuth = true;

      if (getEnv('mailbox.smtp.configset')) {
        $this->addCustomHeader('X-SES-CONFIGURATION-SET', getEnv('mailbox.smtp.configset'));
      }

      parent::send();
    } catch (phpmailerException $e) {
      echo "An error occurred. {$e->errorMessage()}", PHP_EOL;
    } catch (Exception $e) {
      echo "Email not sent. {$this->ErrorInfo}", PHP_EOL;
    }
  }

   public function validateBody(){}
   
   public function logEmailInfo(){}
   
}