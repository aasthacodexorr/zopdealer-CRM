<?php

namespace App\Libraries\Channel;

class EmailChannelFactory
{    
    /**
     * getInstance Fetches the Instance of Email Channel.
     *
     * @param  mixed $type
     * @return EmailChannel
     */
    public function getInstance(String $type) : EmailChannel
    {
        try {
            switch ($type) {
                case 'internal':
                    return new InternalEmailChannel();
                    break;
                case 'personal':
                    return new PersonalEmailChannel();
                    break;
                default:
                    throw new \Exception("Email Channel Is Not Valid");
                    break;
            }
        } catch (\Exception $ex) {
            echo "An error occurred. {$ex->getMessage()}", PHP_EOL;
        }
       
    }
}
