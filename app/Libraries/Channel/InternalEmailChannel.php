<?php

namespace App\Libraries\Channel;

class InternalEmailChannel extends EmailChannel
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
            $outgoing_port
        );

        if (getEnv('notification.smtp.configset')) {
            $this->setConfigHeader('X-SES-CONFIGURATION-SET', getEnv('notification.smtp.configset'));
        }

        return $this;
    }
}
