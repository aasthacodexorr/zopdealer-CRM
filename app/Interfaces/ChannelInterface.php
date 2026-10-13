<?php

namespace App\Interfaces;

/**
 * ChannelInterface This interface defines the contract for communication 
 */
interface ChannelInterface{
    
    /**
     * send This method is responsible for sending the message.
     *
     * @return void
     */
    public function send();
}