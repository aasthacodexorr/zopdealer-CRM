<?php

namespace App\Libraries\Channel;

use App\Interfaces\ChannelInterface;
use Twilio\Rest\Client;

/**
 * SMSChannel This class definess generic 
 */
class SMSChannel implements ChannelInterface
{

    /**
     * sender Sender of the SMS
     *
     * @var string
     */
    private $sender = "";


    /**
     * content Content of the SMS
     *
     * @var string
     */
    private $content = "";

    /**
     * recipient Recipient of the SMS
     *
     * @var string
     */
    private $recipient = "";

    /**
     * sid
     *
     * @var string
     */
    private $sid = "";

    /**
     * Set the value of sid
     *
     * @return  self
     */
    public function setSid($sid)
    {
        $this->sid = $sid;

        return $this;
    }

    /**
     * auth_token
     *
     * @var string
     */
    private $auth_token = "";


    /**
     * additional_recipients
     *
     * @var array
     */
    private $additional_recipients = [];

    /**
     * Set the value of auth_token
     *
     * @return  self
     */
    public function setAuth_token($auth_token)
    {
        $this->auth_token = $auth_token;

        return $this;
    }



    /**
     * setSender This sets the sender of the SMS
     *
     * @param  String $sender This is the number of the sender.
     * @return void
     */
    public function setSender(String $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * setRecipient This sets the recipient of the SMS
     *
     * @param  String $recipient Recipient is the internation form number for the User.
     * @return void
     */
    public function setRecipient(String $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * setContent This sets the content of the SMS
     *
     * @param  mixed $content
     * @return void
     */
    public function setContent($content = "")
    {
        $this->content = $content;
        return $this;
    }

    public function setAdditionalRecipients(array $additional_recipients = [])
    {
        $this->additional_recipients = $additional_recipients;
        return $this;
    }

    public function send()
    {
        try {
            $client = new Client($this->sid, $this->auth_token);
            $client->messages->create($this->recipient, ['from' => $this->sender, 'body' => $this->content]);

            if (!empty($this->additional_recipients)) {
                foreach ($this->additional_recipients as $recipient) {
                    $client->messages->create($recipient, ['from' => $this->sender, 'body' => $this->content]);
                }
            }
        } catch (\Exception $ex) {
            echo "Exception was thrown : " . $ex->getMessage();
        }
    }
}
