<?php

namespace App\Controllers;

use App\Libraries\Channel\EmailChannelFactory;
use App\Libraries\Mailbox\Mailbox;

class MailboxController extends BaseController
{


  function emailtest()
  {

    // $smsFactory = service('channelService')->getInstance("premium")['sms'];
    // $smsFactory->setSender(getenv('twilio.phone'));
    // $smsFactory->setRecipient('+917738938593');
    // $smsFactory->setContent('This content is supposed to be sent to the user');
    // $smsFactory->send();

    $pushFactory = service('channelService')->getInstance("internal")['push'];
    $pushFactory->setSubject("LEAD:RECEIVED");
    $pushFactory->setRecipient('pal', "*");
    $pushFactory->setContent('This is the conent of the queue');
    $pushFactory->send();

    // $emailFactory = service('channelService')->getInstance("internal")['email'];

    //   $emailFactory->init(
    //     getEnv('notification.smtp.host'),
    //     getEnv('notification.smtp.username'),
    //     getEnv('notification.smtp.password'),
    //     getEnv('notification.smtp.port')
    // );

    // $emailFactory->setSubject('Testing Channel');
    // $emailFactory->setContent(view('emails/default_customer_reply', [
    //   'header' => 'header',
    //   'content' => 'content',
    //   'footer' => 'footer',
    //   ]));
    // $emailFactory->setSender('dev@zopsoftware.com', 'Zopsoftware');
    // $emailFactory->setRecipient('dhavalchheda@ymail.com','Dhaval Chheda');
    // $emailFactory->setRecipient('dhavalchheda@zopmedia.com','Dhaval C');
    // $emailFactory->send();

    // $mail = new Mailbox(true);
    // $mail->Subject = 'New Lead Alert';
    // $mail->Body    = '<h1>Email Test</h1> <p>This email was sent through the ZOPDEALER APPLICATION </p>';


    // $mail->setFrom('dev@zopsoftware.com', 'Zopsoftware');
    // $mail->addAddress('dhavalchheda@ymail.com','Dhaval Chheda');    

    // //$mail->addAddress('vikramjeet.thakur@zopmedia.com','Vikram Thakur'); // for multiple enable this
    // //$mail->addReplyTo('vikramjeet.thakur@zopmedia.com', 'Information');
    // //$mail->addCC('dev@zopsoftware.com');
    // // $mail->addBCC('dev@zopsoftware.com');

    // // Attachments
    // // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // //  $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // $mail->Send();

  }
}
