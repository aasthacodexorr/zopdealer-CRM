<?php

namespace Tenant\app\Libraries\StripePayment;

use App\Helpers\Utility;
use Tenant\app\Libraries\StripePayment\StripePaymentAbstract;

class GenericPaymentState extends StripePaymentAbstract
{
    public function decrypt(string $payload): ?array
    {
        try {
            $this->setUp();
            $decrypted_payload = Utility::decryptString($payload, getenv("password.encryption"));

            extract(json_decode($decrypted_payload, true));

            if (!empty($additional_information)) {
                if (empty($additional_information['email']) || empty($additional_information['name'])) {
                    throw new \Exception("Name or Email is missing.", 1);
                }
            } else {
                throw new \Exception("Name or Email is missing.", 1);
            }

            $reason = '';
            if (!empty($additional_information['reason'])) {
                $reason = $additional_information['reason'];
            }

            $paymentIntent  = \Stripe\PaymentIntent::create([
                'amount' => (int) $amount,
                'currency' => 'cad',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'name' => $additional_information['name'],
                    'email' => $additional_information['email'],
                    'phone' => $additional_information['phone'],
                    'module' => $module,
                    'module_id' => $module_id,
                    'is_refundable' => $is_refundable,
                    'database' => $database,
                    'reason' => $reason
                ],
                'receipt_email' => $additional_information['email']
            ], ['stripe_account' =>  $this->stripe_account]);


            return [
                'clientSecret' => $paymentIntent->client_secret,
                'customer_name' => $additional_information['name'],
                'amount' => (int) $amount / 100,
                'is_refundable' => $is_refundable,
                'stripe_account' =>  $this->stripe_account,
                'expiry_time' => $expiry_time
            ];
            exit;
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
