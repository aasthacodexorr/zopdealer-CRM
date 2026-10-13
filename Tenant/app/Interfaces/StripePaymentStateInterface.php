<?php

namespace Tenant\app\Interfaces;


/***
 * StripePaymentInterface defines the structure of the Object Mappers, Encryption, Validation and Decryption of various
 * modules
 */
interface StripePaymentStateInterface
{
    /**
     * encrypt This function encrypts the required information for the payload and converts it into a unique string
     *
     * @param  float $amount
     * @param  string $module
     * @param  string $module_id
     * @param  bool $refundable
     * @param  array $additional_information
     * @return string
     */
    public function encrypt(float $amount, string $module = '', string $module_id = '', bool $refundable = false, array $additional_information = []): string;

    /**
     * decrypt This function decrypts the pauload into an object which has the ability to create a Payment Intent for Stripe
     * It returns the information associated with such an intent.
     *
     * @param  string $payload
     * @return array
     */
    public function decrypt(string $payload): ?array;


    /**
     * capture This function captures the reply on the webhook by Stripe with the data.
     * This function cleans the data by the webhook and returns an array of properly formatted data.
     *
     * @return array
     */
    public function capture(): ?array;
    
    /**
     * refund
     *
     * @param  string $transaction_id Id of transaction
     * @param  float $amount Amount to be refunded
     * @return void
     */
    public function refund(string $transaction_id, float $amount = 0);
}
