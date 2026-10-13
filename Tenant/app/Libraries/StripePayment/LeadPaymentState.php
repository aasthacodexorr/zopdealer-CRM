<?php

namespace Tenant\app\Libraries\StripePayment;

use App\Helpers\Utility;
use Error;
use Tenant\app\Libraries\StripePayment\StripePaymentAbstract;
use Tenant\app\Repositories\InventoryRepository;
use Tenant\app\Repositories\LeadResponseRepository;

class LeadPaymentState extends StripePaymentAbstract
{

    private $lead_response_repository;

    public function __construct()
    {
        $this->lead_response_repository = new LeadResponseRepository();
    }

    public function decrypt(string $payload): ?array
    {
        try {
            $this->setUp();
            $decrypted_payload = Utility::decryptString($payload, getenv("password.encryption"));

            extract(json_decode($decrypted_payload, true));

            $available_lead_response = $this->lead_response_repository->show($module_id);

            if ($available_lead_response == null || !isset($available_lead_response['id'])) {
                throw new Error('Lead could not be found.');
            }

            $inventory = null;
            $vehicle_details = '';

            if (!empty($available_lead_response['stock_no'])) {
                $inventory = (new InventoryRepository())->findSingleInventoryByStockId($available_lead_response['stock_no']);

                if (!empty($inventory)) {
                    $vehicle_details .= $inventory['year'];
                    $vehicle_details .= ' ';
                    $vehicle_details .= $inventory['make'];
                    $vehicle_details .= ' ';
                    $vehicle_details .= $inventory['model'];
                    $vehicle_details .= ' ';
                    $vehicle_details .= $inventory['trim'];
                    $vehicle_details .= '- #';
                    $vehicle_details .= $inventory['stock_no'];
                }
            }

            $reason = '';
            if (!empty($additional_information)) {
                if (!empty($additional_information['reason'])) {
                    $reason = $additional_information['reason'];
                }
            }

            $paymentIntent  = \Stripe\PaymentIntent::create([
                'amount' => (int) $amount,
                'currency' => 'cad',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'name' => $available_lead_response['first_name'] . ' ' . $available_lead_response['last_name'],
                    'email' => $available_lead_response['email'],
                    'phone' => $available_lead_response['phone'],
                    'module' => $module,
                    'module_id' => $module_id,
                    'database' => $database,
                    'is_refundable' => $is_refundable,
                    'reason' => $reason
                ],
                'receipt_email' => $available_lead_response['email']
            ], ['stripe_account' =>  $this->stripe_account]);


            return [
                'clientSecret' => $paymentIntent->client_secret,
                'customer_name' => $available_lead_response['first_name'] . ' ' . $available_lead_response['last_name'],
                'amount' => (int) $amount / 100,
                'vehicle_details' => $vehicle_details,
                'is_refundable' => $is_refundable,
                'stripe_account' =>  $this->stripe_account,
                'expiry_time' => $expiry_time
            ];
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }
    }
}
