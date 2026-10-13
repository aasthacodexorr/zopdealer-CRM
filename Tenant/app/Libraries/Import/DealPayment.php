<?php

namespace Tenant\app\Libraries\Import;

use Tenant\app\Repositories\DealCommonDependentRepository;
use Tenant\app\Repositories\ConfigurationRepository;

class DealPayment extends ImportAbstract
{
    /**
     * configuration_repository
     *
     * @var ConfigurationRepository
     */
    protected $configuration_repository;

    public function __construct()
    {
        $this->repository = new DealCommonDependentRepository();
        $this->repository->switchDealCommonDependentState('payments');
        $this->configuration_repository = new ConfigurationRepository();
    }

    public function validate(array $data, array $namespaces = [], array $errors = []): array
    {
        try {
            if (intval($data['retail_or_wholesale_flag']) === 0 || $this->isPaymentSectionEnabledForWholeSaleDeal()) {
                if (!empty($data['payments']) && is_array($data['payments'])) {
                    foreach ($data['payments'] as $payment_key => $value) {
                        $entity_error = $this->repository->validate($value);

                        if (gettype($entity_error) == "array") {
                            $errors['payments']["payment_" . $payment_key] = $entity_error;
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }



        if (count($namespaces) > 0) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->validate($data, $namespaces, $errors);
        } else {
            return $errors;
        }
    }

    public function process(array $data, array $namespaces = [], array $response = []): array
    {
        try {
            if (intval($data['retail_or_wholesale_flag']) === 0 || $this->isPaymentSectionEnabledForWholeSaleDeal()) {
                if (!empty($data['deal_id']) && is_array($data['payments'])) {
                    foreach ($data['payments'] as $payment_key => $payment) {
                        $payment['deal_id'] = $data['deal_id'];
                        $this->repository->create($payment);
                    }
                }
            }
        } catch (\Exception $ex) {
            log_message('notice', $ex->getMessage());
        }


        if (count($namespaces) > 0 && !empty($data['deal_id'])) {
            $import_entity_namespace = array_shift($namespaces);
            $import_instance = new $import_entity_namespace();
            return $import_instance->process($data, $namespaces, $response);
        } else {
            return $response;
        }
    }

    protected function isPaymentSectionEnabledForWholeSaleDeal()
    {
        $enable_payment_section_for_wholesale_deal =  $this->configuration_repository->findConfigurationColumnsByKey('enable_payment_section_for_wholesale_deal', 'value')->value;
        return !empty($enable_payment_section_for_wholesale_deal);
    }
}
