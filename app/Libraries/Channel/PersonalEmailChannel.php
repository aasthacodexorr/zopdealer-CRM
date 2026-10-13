<?php

namespace App\Libraries\Channel;

/**
 * PersonalEmailChannel This method is used when the mail is sent by Personal Account of the User
 */
class PersonalEmailChannel extends EmailChannel
{
    public function __construct()
    {

        
    }

    public function init(
        String $outgoing_hostname,
        String $user,
        String $password,
        String $outgoing_port = '465',
        String $outgoing_secure = 'ssl',
        bool $auth = true,
        String $incoming_hostname = '',
        String $incoming_port = '993',
        String $incoming_secure = 'ssl'
        ){
        parent::__construct(
            $outgoing_hostname,
            $user,
            $password,
            $outgoing_port,
            $outgoing_secure
        );

        $this->incoming_hostname = $incoming_hostname;
        $this->incoming_port = $incoming_port;
        $this->incoming_secure = $incoming_secure;

        return $this;
    }

    /**
     * send This function sends the Mail
     *
     * @return void
     */
    public function send()
    {
        $response = [];
        try {
            $response = parent::send();
            $this->save_mail($this);
            
        } catch (\Exception $ex) {
            $response = ['errors' => $ex->getMessage()];
            return ['errors' => $ex->getMessage()];
        } finally {
            return $response;
        }
    }

    //Function to call which uses the PHP imap_*() functions to save messages
    private function save_mail($mail)
    {

        $mbox = imap_open("{".$this->incoming_hostname .":".$this->incoming_port."/imap/ssl". getenv('email.validate.url') ."}", $this->Username, $this->Password, OP_HALFOPEN)
        or die("can't connect: " . imap_last_error());

        $path = null;

        $list = imap_getmailboxes($mbox, "{".$this->incoming_hostname .":".$this->incoming_port."/imap/ssl". getenv('email.validate.url') ."}", '*');
        foreach ($list as $single_mailbox_path) {
            if (preg_match('/(Sent)/mi', $single_mailbox_path->name)) {
                $path = $single_mailbox_path->name;
                break;
            }
        }
        
        //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
        $imapStream = imap_open($path, $this->Username, $this->Password);
        $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
        imap_close($imapStream);
        return $result;
    }
}
