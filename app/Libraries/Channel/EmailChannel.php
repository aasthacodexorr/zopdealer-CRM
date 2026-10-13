<?php

namespace App\Libraries\Channel;

use App\Interfaces\ChannelInterface;
use phpDocumentor\Reflection\Types\Boolean;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * EmailChannel This class definess generic 
 */
abstract class EmailChannel extends PHPMailer implements ChannelInterface
{



    /**
     * __construct Construct accepts smpt credentials for the server. Must be overriden by the child classes.
     *
     * @param  String $host
     * @param  String $user
     * @param  String $password
     * @param  String $port
     * @param  String $secure
     * @param  bool $auth
     * @return void
     */
    public function __construct(String $host, String $user, String $password, String $port, String $secure = 'tls', bool $auth = true)
    {
        parent::__construct(new PHPMailer(true));

        //SMTP Settings
        $this->isHTML(true);
        $this->isSMTP();
        $this->Host = $host;
        $this->Username = $user;
        $this->Password = $password;
        $this->Port = $port;
        $this->SMTPSecure = $secure;
        $this->SMTPAuth = $auth;
    }

        
        /**
         * incoming_hostname
         *
         * @var string
         */
        private $incoming_hostname = '';    
            
        /**
         * incoming_port
         *
         * @var string
         */
        private $incoming_port = '993';        
        /**
         * incoming_secure
         *
         * @var string
         */
        private $incoming_secure = 'ssl';


    /**
     * setConfigHeader These sets any custom headers for the mail functionality
     *
     * @param  mixed $header
     * @param  mixed $value
     * @return void
     */
    public function setConfigHeader($header, $value)
    {
        $this->addCustomHeader($header,  $value);
        return $this;
    }

    /**
     * setContent This sets the content of the Body.
     *
     * @param  mixed $content
     * @return void
     */
    public function setContent($content = "")
    {
        $this->Body = $content;
        return $this;
    }

    /**
     * setSubject This sets the subject of the Body
     *
     * @param  mixed $subject
     * @return void
     */
    public function setSubject($subject = "")
    {
        $this->Subject = $subject;
        return $this;
    }

    /**
     * setSender This sets the sender of the Mail
     *
     * @param  mixed $email_address
     * @param  mixed $name
     * @return void
     */
    public function setSender($email_address, $name = "")
    {
        $this->setFrom($email_address, $name);
        return $this;
    }

    /**  
    * setReplyTo Add a "Reply-To" address.
    *
     * @param string $email_address The email address to reply to
     * @param string $name
     * 
    * @return void
    */     
    public function setReplyTo($email_address, $name = "")
    {
        $this->addReplyTo($email_address, $name);
        return $this;
    }

    /**
     * setRecipient This sets the recipient of the Mail
     *
     * @param  mixed $email_address
     * @param  mixed $name
     * @return void
     */
    public function setRecipient($email_address, $name = "")
    {
        $this->addAddress($email_address, $name);
        return $this;
    }

    /**
     * setCC This sets the cc of the Mail
     *
     * @param  mixed $email_address
     * @param  mixed $name
     * @return void
     */
    public function setCC($email_address, $name = "")
    {
        $this->addCC($email_address, $name);
        return $this;
    }

    
    /**
     * setHTML This method sets the content type.
     *
     * @param  mixed $flag
     * @return void
     */
    public function setHTML($flag){
        $this->isHTML($flag);
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
            parent::send();
            $response = ['success' => 'Email has been sent.'];
        } catch (\Exception $ex) {
            $response =  ['errors' => $ex->getMessage()];
        } finally {
            return $response;
        }
    }



    /**
     * init
     *
     * @param  mixed $outgoing_hostname
     * @param  mixed $user
     * @param  mixed $password
     * @param  mixed $outgoing_port
     * @param  mixed $outgoing_secure
     * @param  mixed $auth
     * @param  mixed $incoming_hostname
     * @param  mixed $incoming_port
     * @param  mixed $incoming_secure
     * @return void
     */
    abstract function init(
        String $outgoing_hostname,
        String $user,
        String $password,
        String $outgoing_port = '465',
        String $outgoing_secure = 'ssl',
        bool $auth = true,
        String $incoming_hostname = '',
        String $incoming_port = '993',
        String $incoming_secure = 'ssl'
    );
}
