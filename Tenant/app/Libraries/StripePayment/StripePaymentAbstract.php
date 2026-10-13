<?php

namespace Tenant\app\Libraries\StripePayment;

use App\Helpers\Utility;
use Error;
use Tenant\app\Interfaces\StripePaymentStateInterface;
use Tenant\app\Repositories\ConfigurationRepository;

abstract class StripePaymentAbstract implements StripePaymentStateInterface
{
    /**
     * stripe_account
     *
     * @var string
     */
    protected $stripe_account = '';

    /**
     * map_statuses These maps the status of Refunded or Succeeded amount from Stripe to a general statuses.
     *
     * @var array
     */
    protected $map_statuses = [
        'charge.refunded' => 'Refund',
        'charge.succeeded' => 'Charge'
    ];

    public function encrypt(float $amount, string $module = '', string $module_id = '', bool $refundable = false, array $additional_information = []): string
    {
        $payment = [];
        $payment['database'] = db_connect()->database;
        $payment['amount'] = round($amount, 2) * 100;
        $payment['module'] = $module;
        $payment['module_id'] = $module_id;
        $payment['expiry_time'] = time() + 600;
        $payment['is_refundable'] = $refundable;
        $payment['additional_information'] = $additional_information;
        return Utility::encryptString(json_encode($payment), getenv("password.encryption"));
    }

    protected function setUp()
    {
        $stripe_account = (new ConfigurationRepository())->findConfigurationColumnsByKey('stripe_account', 'value');
        $stripe_account = $stripe_account->value;

        $this->stripe_account = $stripe_account;
        
        \Stripe\Stripe::setApiKey(getenv('STRIPE.SECRET_KEY'));
        if (empty($this->stripe_account)) {
            throw new Error(`Stripe Account Not Set`);
        }
    }

    public function capture(): ?array
    {

        $payload = @file_get_contents('php://input');

        $event = null;

        try {
            $event = json_decode($payload, true);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return ["errors" => "Invalid payload."];
            exit;
        }

        $data = [];

        $paymentIntent = $event['data']['object'];

        $data =  empty($paymentIntent['metadata']) ? [] : $paymentIntent['metadata'];
        $data['event_id'] = $this->checkIfAttributeIsEmpty($event['id']);
        $data['date'] = $this->checkIfAttributeIsEmpty($paymentIntent['created']);

        if (array_key_exists('amount_captured', $paymentIntent)) {
            $data['amount'] = $this->checkIfAttributeIsEmpty($paymentIntent['amount_captured'], "number");
        }

        $data['receipt_email'] = $this->checkIfAttributeIsEmpty($paymentIntent['receipt_email']);
        $data['transaction_id'] = $this->checkIfAttributeIsEmpty($paymentIntent['id']);

        if (array_key_exists('receipt_url', $paymentIntent)) {
            $data['receipt_url'] = $this->checkIfAttributeIsEmpty($paymentIntent['receipt_url']);
        }

        if (array_key_exists('receipt_number', $paymentIntent)) {
            $data['receipt_number'] = $this->checkIfAttributeIsEmpty($paymentIntent['receipt_number']);
        }

        $data['sub_status'] = $this->checkIfAttributeIsEmpty($paymentIntent['status']);

        $data['status'] = '';

        if (array_key_exists('type', $event)) {
            $data['status'] = $this->checkIfAttributeIsEmpty($event['type']);
        }

        if (array_key_exists($data['status'], $this->map_statuses)) {
            $data['status'] = $this->map_statuses[$data['status']];
        }

        if ($data['status'] === 'Refund') {
            if (array_key_exists('amount_captured', $paymentIntent)) {
                $data['amount'] = $this->checkIfAttributeIsEmpty($paymentIntent['amount_refunded'], "number");
            }
        }

        $data['mode'] = 'Online';
        $data['type'] = 'Credit';

        return $data;
    }

    public function refund(string $transaction_id, float $amount = 0)
    {
        try {
            $this->setUp();
            $refund  = \Stripe\Refund::create([
                'charge' => $transaction_id,
                'amount' => $amount * 100
            ], ['stripe_account' =>  $this->stripe_account]);

            if ((!empty($refund)) and ($refund instanceof \Stripe\Refund)) {
                return ['success' => 'Refund request for #' . $transaction_id . ' was generated.'];
            } else {
                return ['errors' => "We couldn't generate the response for #" . $transaction_id . ". 
                Please validate the transaction from your Stripe Dashboard."];
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }

    private function checkIfAttributeIsEmpty($attribute, $type = "string")
    {
        $placeholder = $type === "number" ? 0 : '';
        return empty($attribute) ? $placeholder : $attribute;
    }
}
