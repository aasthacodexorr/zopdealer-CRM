<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DealReportModel;
use Tenant\app\Models\DealReportModelWithDeleted;
use Tenant\app\Models\DealWithDeletedModel;

class DealReportRepository extends CRUDWithAdvanceRepository
{
    /**
     * deal_repository
     *
     * @var DealRepository
     */
    private $deal_repository;
    public function __construct()
    {
        parent::__construct(new DealReportModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Deal Report';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_report_id';
        $this->deal_repository = new DealRepository();
    }

    public function createOrUpdate(array $data): array
    {
        parent::setModel(new DealReportModelWithDeleted());


        try {
            if (empty($data['deal_id'])) {
                throw new \Exception("Deal ID is missing.", 1);
            }

            $instance = $this->model->where('deal_id', $data['deal_id'])->asArray()->first();

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {
            return ["errors" => $e->getMessage()];
        }
    }

    public function hasUpdates(): bool
    {
        $count = (new DealWithDeletedModel())->withDeleted()->whereIn('report_status', ['TO BE ADDED', 'TO BE UPDATED'])->countAllResults();
        return !empty($count);
    }

    public function updateReportEntities(): void
    {
        (new DealWithDeletedModel())->withDeleted()->whereIn('report_status', ['TO BE ADDED', 'TO BE UPDATED'])->orderBy('created_at', 'desc')->asArray()->chunk(100, static function ($data) {
            try {
                $deal_report_repository = new DealReportRepository();
                $entity = $deal_report_repository->prepareEntityReport($data['id']);
                if (!empty($entity['id'])) {
                    $entity['deal_id'] = $entity['id'];
                    unset($entity['id']);
                    $deal_report_repository->createOrUpdate($entity);

                    $db = \Config\Database::connect();
                    $builder = $db->table('deals');
                    $builder->where('id', $entity['deal_id'])->set(['report_status' => 'UPDATED'])->update();
                }
            } catch (\Exception $single_ex) {
                log_message('notice',  db_connect()->getDatabase() . $single_ex->getMessage());
            } catch (\Error $single_er) {
                log_message('notice', db_connect()->getDatabase() . $single_er->getMessage());
            }
        });
    }

    /**
     * prepareEntityReport
     *
     * @param  mixed $id This is the ID of the entity
     * @return array
     */
    private function prepareEntityReport(String $id): array
    {
        $entity = [];

        $entity['selling_price'] =
            $entity['selling_price_primary_tax'] =
            $entity['selling_price_secondary_tax'] =
            $entity['selling_price_total_tax'] =
            $entity['trade_value'] =
            $entity['net_difference'] =
            $entity['total_tax'] =
            $entity['licence_fee'] =
            $entity['gasoline'] =
            $entity['lien_payout'] =
            $entity['lien_registration_fee'] =
            $entity['subtotal'] =
            $entity['total_balance_due'] = 0;

        $entity['vendor_purchase_price'] =
            $entity['vendor_purchase_discount'] =
            $entity['vendor_purchase_primary_tax'] =
            $entity['vendor_purchase_secondary_tax'] =
            $entity['vendor_purchase_total_tax'] = 0;

        $entity['additional_cost'] =
            $entity['additional_cost_discount'] =
            $entity['additional_cost_primary_tax'] =
            $entity['additional_cost_secondary_tax'] =
            $entity['additional_cost_total_tax'] =
            $entity['additional_cost_total'] = 0;

        $entity['selling_price'] =
            $entity['selling_price_primary_tax'] =
            $entity['selling_price_secondary_tax'] =
            $entity['selling_price_total_tax'] = 0;

        $entity['extended_warranty'] =
            $entity['extended_warranty_primary_tax'] =
            $entity['extended_warranty_secondary_tax'] =
            $entity['extended_warranty_total_tax'] = 0;

        $entity['extended_warranty_cost'] =
            $entity['extended_warranty_cost_primary_tax'] =
            $entity['extended_warranty_cost_secondary_tax'] =
            $entity['extended_warranty_cost_total_tax'] = 0;

        $entity['guarantee_cost'] =
            $entity['guarantee_cost_primary_tax'] =
            $entity['guarantee_cost_secondary_tax'] =
            $entity['guarantee_cost_total_tax'] = 0;


        $entity['fees'] =
            $entity['fees_primary_tax'] =
            $entity['fees_secondary_tax'] =
            $entity['fees_total_tax'] = 0;

        $entity['fees_cost'] =
            $entity['fees_cost_primary_tax'] =
            $entity['fees_cost_secondary_tax'] =
            $entity['fees_cost_total_tax'] = 0;

        $entity['accessories'] =
            $entity['accessories_primary_tax'] =
            $entity['accessories_secondary_tax'] =
            $entity['accessories_total_tax'] = 0;

        $entity['accessories_cost'] =
            $entity['accessories_cost_primary_tax'] =
            $entity['accessories_cost_secondary_tax'] =
            $entity['accessories_cost_total_tax'] = 0;

        $entity['payment_total'] = 0;
        $entity['payment_details'] = '';

        $entity['insurance_total'] =
            $entity['insurance_primary_tax'] =
            $entity['insurance_secondary_tax'] =
            $entity['insurance_total_tax'] = 0;

        $entity['insurance_total_cost'] =
            $entity['insurance_cost_primary_tax'] =
            $entity['insurance_cost_secondary_tax'] =
            $entity['insurance_cost_total_tax'] = 0;

        $entity['finance_commission_total'] =
            $entity['sale_commissions_total'] = 0;

        $entity['additional_deal_revenue'] =
            $entity['additional_deal_cost'] =
            $entity['additional_deal_cost_taxes_paid'] = 0;

        $this->deal_repository->setModel(new DealWithDeletedModel());
        $available_deal = $this->deal_repository->show($id);
        if (isset($available_deal['id'])) {
            $entity = array_merge($entity, $available_deal);
            $inventory_and_customer_details = $this->deal_repository->findInventoryAndCustomerDetailsForDeal($available_deal['id']);

            foreach ($inventory_and_customer_details['inventory'] as $inventory) {
                $attributes = ['year', 'make', 'model', 'vin', 'stock_no', 'odometer', 'exterior_color', 'interior_color'];
                foreach ($attributes as $attribute) {
                    if (!empty($inventory[$attribute])) {
                        $entity[$attribute] = $inventory[$attribute];

                        if ($attribute === 'odometer') {
                            $entity[$attribute] = $inventory[$attribute] . ' ' . $inventory['km_miles'];
                        }
                    }
                }

                if (!empty($inventory['inventory_id'])) {
                    $inventory_purchase = (new InventoryPurchaseRepository())->findByInventoryId($inventory['inventory_id']);
                    $inventory_purchase_attributes = [
                        'first_name' => 'vendor_name',
                        'phone' => 'vendor_phone',
                        'mobile' => 'vendor_mobile',
                        'email' => 'vendor_email',
                        'company_name' => 'vendor_company_name',
                        'apt' => 'vendor_apt',
                        'street_address' => 'vendor_street_address',
                        'city' => 'vendor_city',
                        'province' => 'vendor_province',
                        'postal_code' => 'vendor_postal_code',
                        'country' => 'vendor_country',
                        'purchase_date' => 'vendor_purchase_date',

                    ];

                    foreach ($inventory_purchase_attributes as $vendor_key => $entity_key) {
                        if (!empty($inventory_purchase[$vendor_key])) {
                            $entity[$entity_key] = $inventory_purchase[$vendor_key];

                            if ($vendor_key === 'first_name') {
                                $entity[$entity_key] = $inventory_purchase[$vendor_key] . ' ' . $inventory_purchase['last_name'];
                            }
                        }
                    }

                    $inventory_purchase_calculation_attributes = [
                        'price' => 'vendor_purchase_price',
                        'discount' => 'vendor_purchase_discount',
                        'tax_amount' => 'vendor_purchase_primary_tax',
                        'tax_amount_secondary' => 'vendor_purchase_secondary_tax',
                        'tax_amount_total' => 'vendor_purchase_total_tax',

                    ];

                    foreach ($inventory_purchase_calculation_attributes as $vendor_key => $entity_key) {
                        if (!empty($inventory_purchase[$vendor_key])) {
                            $entity[$entity_key] = (float) $inventory_purchase[$vendor_key];
                        }
                    }

                    $inventory_expenses = (new InventoryCostRepository())->findByInventoryId($inventory['inventory_id']);

                    foreach ($inventory_expenses as $inventory_expense) {
                        if (!empty($inventory_expense)) {
                            $entity['additional_cost'] += ((float) $inventory_expense['price'] * (int) $inventory_expense['quantity']);
                            $entity['additional_cost_discount'] += (float) $inventory_expense['discount'];
                            $entity['additional_cost_primary_tax'] += (float) $inventory_expense['tax_amount'];
                            $entity['additional_cost_secondary_tax'] += (float) $inventory_expense['tax_amount_secondary'];
                            $entity['additional_cost_total_tax'] += (float) $inventory_expense['tax_amount_total'];
                            $entity['additional_cost_total'] += (float) $inventory_expense['sub_total'];
                        }
                    }
                }
            }

            foreach ($inventory_and_customer_details['customers'] as $customer) {

                if (!empty($customer)) {

                    if ($customer['deal_customer_type'] === 'primary_customer') {
                        $customer_attributes = [
                            'first_name' => 'customer_name',
                            'phone' => 'customer_phone',
                            'mobile' => 'customer_mobile',
                            'email' => 'customer_email',
                            'company_name' => 'customer_company_name',
                            'apt' => 'customer_apt',
                            'street_address' => 'customer_street_address',
                            'city' => 'customer_city',
                            'province' => 'customer_province',
                            'postal_code' => 'customer_postal_code',
                            'country' => 'customer_country',
                        ];

                        foreach ($customer_attributes as $customer_key => $entity_key) {
                            if (!empty($customer[$customer_key])) {
                                $entity[$entity_key] = $customer[$customer_key];

                                if ($customer_key === 'first_name') {
                                    $entity[$entity_key] = $customer[$customer_key] . ' ' . $customer['last_name'];
                                }
                            }
                        }
                    }
                }
            }

            $deal_breakdown = (new DealCommonDependentRepository())->switchDealCommonDependentState('dealbreakdown')->filterByDealIdAsArray($available_deal['id']);
            $deal_breakdown_attributes = [
                'purchase_price' => 'selling_price',
                'tax_on_purchase_price' => 'selling_price_primary_tax',
                'tax_on_purchase_price_secondary' => 'selling_price_secondary_tax',
                'tax_on_purchase_total' => 'selling_price_total_tax',
                'trade_value' => 'trade_value',
                'net_difference' => 'net_difference',
                'total_tax_amount' => 'total_tax',
                'licence_fee' => 'licence_fee',
                'gasoline' => 'gasoline',
                'lien_payout' => 'lien_payout',
                'lien_registration_fee' => 'lien_registration_fee',
                'secondary_subtotal' => 'subtotal',
                'total_balance_due' => 'total_balance_due',
            ];

            foreach ($deal_breakdown as $deal_breakdown_entity) {
                foreach ($deal_breakdown_attributes as $deal_breakdown_key => $entity_key) {
                    if (!empty($deal_breakdown_entity[$deal_breakdown_key])) {
                        $entity[$entity_key] = (float) $deal_breakdown_entity[$deal_breakdown_key];
                    }
                }
            }

            $deal_extended_warranties = (new DealCommonDependentRepository())->switchDealCommonDependentState('warranties')->filterByDealIdAsArray($available_deal['id']);
            $deal_extended_attributes = [
                'amount' => 'extended_warranty',
                'tax_amount' => 'extended_warranty_primary_tax',
                'tax_amount_secondary' => 'extended_warranty_secondary_tax',
                'tax_amount_total' => 'extended_warranty_total_tax',
                'cost' => 'extended_warranty_cost',
                'cost_tax_amount' => 'extended_warranty_cost_primary_tax',
                'cost_tax_amount_secondary' => 'extended_warranty_cost_secondary_tax',
                'cost_tax_amount_total' => 'extended_warranty_cost_total_tax',
            ];

            foreach ($deal_extended_warranties as $deal_extended_warranty) {
                foreach ($deal_extended_attributes as $deal_extended_warranty_key => $entity_key) {
                    if (!empty($deal_extended_warranty[$deal_extended_warranty_key])) {
                        $entity[$entity_key] += (float) $deal_extended_warranty[$deal_extended_warranty_key];
                    }
                }
            }

            $deal_guarantees = (new DealCommonDependentRepository())->switchDealCommonDependentState('guarantee')->filterByDealIdAsArray($available_deal['id']);
            $deal_guarantee_attributes = [
                'cost' => 'guarantee_cost',
                'cost_tax_amount' => 'guarantee_cost_primary_tax',
                'cost_tax_amount_secondary' => 'guarantee_cost_secondary_tax',
                'cost_tax_amount_total' => 'guarantee_cost_total_tax',
            ];

            foreach ($deal_guarantees as $deal_guaranty) {
                foreach ($deal_guarantee_attributes as $deal_guaranty_key => $entity_key) {
                    if (!empty($deal_guaranty[$deal_guaranty_key])) {
                        $entity[$entity_key] += (float) $deal_guaranty[$deal_guaranty_key];
                    }
                }
            }

            $deal_fees = (new DealCommonDependentRepository())->switchDealCommonDependentState('fees')->filterByDealIdAsArray($available_deal['id']);
            $deal_fees_attributes = [
                'amount' => 'fees',
                'tax_amount' => 'fees_primary_tax',
                'tax_amount_secondary' => 'fees_secondary_tax',
                'tax_amount_total' => 'fees_total_tax',
                'cost' => 'fees_cost',
                'cost_tax_amount' => 'fees_cost_primary_tax',
                'cost_tax_amount_secondary' => 'fees_cost_secondary_tax',
                'cost_tax_amount_total' => 'fees_cost_total_tax',
            ];

            foreach ($deal_fees as $deal_fee) {
                foreach ($deal_fees_attributes as $deal_fee_key => $entity_key) {
                    if (!empty($deal_fee[$deal_fee_key])) {
                        $entity[$entity_key] += (float) $deal_fee[$deal_fee_key];
                    }
                }
            }

            $deal_accessories = (new DealCommonDependentRepository())->switchDealCommonDependentState('accessories')->filterByDealIdAsArray($available_deal['id']);
            $deal_accessories_attributes = [
                'amount' => 'accessories',
                'tax_amount' => 'accessories_primary_tax',
                'tax_amount_secondary' => 'accessories_secondary_tax',
                'tax_amount_total' => 'accessories_total_tax',
                'cost' => 'accessories_cost',
                'cost_tax_amount' => 'accessories_cost_primary_tax',
                'cost_tax_amount_secondary' => 'accessories_cost_secondary_tax',
                'cost_tax_amount_total' => 'accessories_cost_total_tax',
            ];

            foreach ($deal_accessories as $deal_accessory) {
                foreach ($deal_accessories_attributes as $deal_accessory_key => $entity_key) {
                    if (!empty($deal_accessory[$deal_accessory_key])) {
                        $entity[$entity_key] += (float) $deal_accessory[$deal_accessory_key];
                    }
                }
            }

            $deal_payments = (new DealCommonDependentRepository())->switchDealCommonDependentState('payments')->filterByDealIdAsArray($available_deal['id']);

            foreach ($deal_payments as $deal_payment) {
                if (!empty($deal_payment)) {
                    $entity['payment_total'] += (float) $deal_payment['amount'];
                    $entity['payment_details'] .= ' ' . $deal_payment['type'] . ' ' . $deal_payment['amount'];
                }
            }

            $deal_insurances = (new DealCommonDependentRepository())->switchDealCommonDependentState('insurances')->filterByDealIdAsArray($available_deal['id']);
            $deal_insurances_attributes = [
                'amount' => 'insurance_total',
                'tax_amount' => 'insurance_primary_tax',
                'tax_amount_secondary' => 'insurance_secondary_tax',
                'tax_amount_total' => 'insurance_total_tax',
                'cost' => 'insurance_total_cost',
                'cost_tax_amount' => 'insurance_cost_primary_tax',
                'cost_tax_amount_secondary' => 'insurance_cost_secondary_tax',
                'cost_tax_amount_total' => 'insurance_cost_total_tax',
            ];

            foreach ($deal_insurances as $deal_insurance) {
                foreach ($deal_insurances_attributes as $deal_insurance_key => $entity_key) {
                    if (!empty($deal_insurance[$deal_insurance_key])) {
                        $entity[$entity_key] += (float) $deal_insurance[$deal_insurance_key];
                    }
                }
            }

            $deal_finances = (new DealCommonDependentRepository())->switchDealCommonDependentState('finance')->filterByDealIdAsArray($available_deal['id']);
            $deal_finances_attributes = [
                'finance_commission' => 'finance_commission_total',
            ];

            foreach ($deal_finances as $deal_finance) {
                foreach ($deal_finances_attributes as $deal_finance_key => $entity_key) {
                    if (!empty($deal_finance[$deal_finance_key])) {
                        $entity[$entity_key] += (float) $deal_finance[$deal_finance_key];
                    }
                }
            }

            $deal_sales_commissions = (new DealCommonDependentRepository())->switchDealCommonDependentState('sale-commissions')->filterByDealIdAsArray($available_deal['id']);
            $deal_sales_commissions_attributes = [
                'paid_amount' => 'sale_commissions_total',
            ];

            foreach ($deal_sales_commissions as $deal_sales_commision) {
                foreach ($deal_sales_commissions_attributes as $deal_sales_commission_key => $entity_key) {
                    if (!empty($deal_sales_commision[$deal_sales_commission_key])) {
                        $entity[$entity_key] += (float) $deal_sales_commision[$deal_sales_commission_key];
                    }
                }
            }

            $entity['additional_deal_revenue'] = $entity['extended_warranty']
                + $entity['fees']
                + $entity['accessories']
                + $entity['insurance_total'];

            $entity['additional_deal_cost'] = $entity['extended_warranty_cost']
                + $entity['guarantee_cost']
                + $entity['fees_cost']
                + $entity['accessories_cost']
                + $entity['insurance_total_cost'];

            $entity['additional_deal_cost_taxes_paid'] = $entity['extended_warranty_cost_total_tax']
                + $entity['guarantee_cost_total_tax']
                + $entity['fees_cost_total_tax']
                + $entity['accessories_cost_total_tax']
                + $entity['insurance_cost_total_tax'];

            $entity['vehicle_profit_loss'] =
                $entity['selling_price']
                - ($entity['vendor_purchase_price']
                    - $entity['vendor_purchase_discount']
                    + $entity['additional_cost']
                    - $entity['additional_cost_discount']
                );

            $entity['total_profit_loss'] =
                $entity['selling_price']
                + $entity['additional_deal_revenue']
                + $entity['finance_commission_total']
                - (
                    $entity['vendor_purchase_price']
                    - $entity['vendor_purchase_discount']
                    + $entity['additional_cost']
                    - $entity['additional_cost_discount']
                    + $entity['additional_deal_cost']
                    + $entity['sale_commissions_total']
                );

            $entity['total_taxes_collected'] =
                $entity['insurance_total_tax']
                + $entity['total_tax'];

            $entity['total_taxes_paid'] =
                $entity['vendor_purchase_total_tax']
                + $entity['additional_cost_total_tax']
                + $entity['additional_deal_cost_taxes_paid'];

            $entity['total_taxes_difference'] =
                $entity['total_taxes_collected']
                - $entity['total_taxes_paid'];
        }

        return $entity;
    }
}
