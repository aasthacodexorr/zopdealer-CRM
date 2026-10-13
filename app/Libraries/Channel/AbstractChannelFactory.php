<?php

namespace App\Libraries\Channel;

class AbstractChannelFactory
{    
    /**
     * getInstance This factory creates all the required instances of all the channel services
     *
     * @param  mixed $type
     * @return void
     */
    public function getInstance(String $type)
    {
        try {
            switch ($type) {
                case 'internal':
                    return [
                        'email' => new InternalEmailChannel(),
                        'push' => new PushChannel(),
                        'sms' => new SMSChannel()
                    ];
                
                case 'personal':
                return [
                        'email' => new PersonalEmailChannel(),
                        'push' => new PushChannel(),
                        'sms' => new SMSChannel()
                    ];
                    break;
                
                default:
                    throw new \Exception("Channel Is Not Valid");
                    break;
            }
        } catch (\Exception $ex) {
            echo "An error occurred. {$ex->getMessage()}", PHP_EOL;
        }
    }
    
}
