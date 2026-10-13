<?php

namespace Tenant\app\Libraries\StripePayment;

use App\Helpers\Utility;
use Exception;
use Tenant\app\Libraries\StripePayment\StripePaymentAbstract;

class PaymentContextState extends StripePaymentAbstract
{    
    /**
     * available_states These are the available mappers for different modules and generic state
     *
     * @var array
     */
    private $available_states = [
        'Lead' => 'Tenant\app\Libraries\StripePayment\LeadPaymentState',
        'Deal' => 'Tenant\app\Libraries\StripePayment\GenericPaymentState',
        'Generic' => 'Tenant\app\Libraries\StripePayment\GenericPaymentState',
    ];

    public function decrypt(string $payload): ?array
    {
        try {
            $this->setUp();

            $decrypted_payload = Utility::decryptString($payload, getenv("password.encryption"));

            extract(json_decode($decrypted_payload, true));

            //If expiry time is less than current time throw Timeout Error
            if ((int) $expiry_time < time()) {
                throw new Exception('Link has expired.');
            }

            if(array_key_exists($module, $this->available_states)){
                $paymentObject = new $this->available_states[$module]();
                return $paymentObject->decrypt($payload);
            } else {
                throw new Exception(`Payment Mapper Not Available`);
            }


        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
