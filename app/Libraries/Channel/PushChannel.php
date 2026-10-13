<?php

namespace App\Libraries\Channel;

use App\Interfaces\ChannelInterface;

/**
 * PushChannel This class definess generic 
 */
 class PushChannel implements ChannelInterface
{    
    /**
     * sender Sender of the Push Notification
     *
     * @var string
     */
    private $sender = 'Zop Dealer';    

    /**
     * namespace Namespace of the tenant. App name is used as namespace.
     *
     * @var string
     */
    private $namespace = "";
    
    /**
     * user_id User Id which is targeted for the Notification;
     *
     * @var mixed
     */
    private $user_id = NULL;
    
    /**
     * content Content of the Notification
     *
     * @var string
     */
    private $content = "";
    
    /**
     * subject Subject of the Notification
     *
     * @var string
     */
    private $subject = "";
    
    /**
     * setSender This sets the sender of the Mail
     *
     * @param  String $name This is the name of the sender.
     * @return void
     */
    public function setSender(String $name )
    {
        $this->sender = $name;
        return $this;
    }

    /**
     * setRecipient This sets the recipient of the Push Notification
     *
     * @param  String $namespace Namespace of the tenant. App name is used as namespace.
     * @param  mixed $user_id : Id of the user for whome the notification is targeted to.
     * @return void
     */
    public function setRecipient(String $namespace, $user_id)
    {
        $this->namespace = $namespace;
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * setContent This sets the content of the Push Notification.
     *
     * @param  mixed $content
     * @return void
     */
    public function setContent($content = "")
    {
        $this->content = $content;
        return $this;
    }
    
    /**
     * setSubject This sets the subject of the Push Notification.
     *
     * @param  mixed $subject
     * @return void
     */
    public function setSubject($subject = ""){
        $this->subject = $subject; 
        return $this;
    }

    public function send()
    {
        try {
            $url = getenv('notification.server.url');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $fields = array(
                "namespace" => $this->namespace,
                "user_id" => $this->user_id,
                "sender"    => $this->sender,
                "content" => $this->content,
                "subject" => $this->subject
            );

            //var_dump($fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
            $result = curl_exec($ch);
            #var_dump(curl_error($ch));
            curl_close($ch);
            #echo(ROOTPATH . '/cert.pem');
            #var_dump($result);
            return $result;

        } catch (\Exception $ex) {
            echo "Push Notification Error : " . $ex->getMessage();
        }
    }


    
    

    
}