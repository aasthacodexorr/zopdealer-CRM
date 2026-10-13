<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Carbon\Carbon;
use Tenant\app\Libraries\CodeItNow\BarcodeBundle\Utils\QrCode;
use Tenant\app\Models\CustomerModel;
use Tenant\app\Models\VoucherModel;

/**
 * VoucherRepository CRUD and pagination for the Voucher
 */
class VoucherRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new VoucherModel());
        $this->entity = 'Tenant\app\Entities\VoucherTemplate';
        $this->entityLabel = 'Voucher';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'voucher_id';
    }

    public function getQRCode($content, $size = 300): string
    {
        $qrCode = (new QrCode())
            ->setText($content)
            ->setSize($size)
            ->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
            ->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
            //->setPadding(5)
            ->setErrorCorrection('high')
            ->setImageType(QrCode::IMAGE_TYPE_PNG);

        return "data:" . $qrCode->getContentType() . ";base64," . $qrCode->generate() . "";
    }

    /**
     * updateSearchIndex
     *
     * @param  mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {

        try {

            $db = \Config\Database::connect();
            $builder = $db->table('vouchers');

            parent::setModel(new VoucherModel());

            $all_vouchers = NULL;
            if (empty($ids)) {
                $all_vouchers = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_vouchers = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_vouchers as $key => $value) {
                $data['data'] = $value;
                $voucher_with_updated_search_index = $this->model->createKeyWordString($data);
                if (isset($value['id'])) {

                    $deal_dependent_details = (new DealRepository())->findInventoryAndCustomerDetailsForDeal($value['module_id']);

                    if (count($deal_dependent_details['customers']) > 0) {
                        foreach ($deal_dependent_details['customers'] as $key => $customer) {
                            if ($customer['deal_customer_type'] === 'primary_customer') {
                                $customer_details['data'] = (new CustomerRepository())->show($customer['id']);
                                $customer_search_keyword = (new CustomerModel())->createKeyWordString($customer_details);
                                $voucher_with_updated_search_index['data']['search_keyword'] .= " " . $customer_search_keyword['data']['search_keyword'];
                            }
                        }
                    }

                    if (count($deal_dependent_details['inventory']) > 0) {
                        foreach ($deal_dependent_details['inventory'] as $key => $inventory) {
                            $inventory_details = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);
                            $voucher_with_updated_search_index['data']['search_keyword'] .= " " . strtolower($inventory_details['stock_no'] . ' ' . $inventory_details['vin']);
                        }
                    }

                    $builder->where('id', $value['id'])->set(['search_keyword' => $voucher_with_updated_search_index['data']['search_keyword']])->update();
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }

        return ['success' => 'Voucher search index has been updated.'];
    }

    
    /**
     * updateExpiredVoucherStatus
     *
     * @return void
     */
    public function updateExpiredVoucherStatus()
    {
        $current = Carbon::now('UTC');
        $future_minutes = $current->copy()->subDay();

        $this->model->where($this->model->table . '.' . 'expiry_date' . " <= '" . $future_minutes . "' ");
        $this->model->where($this->model->table . '.' . 'status', 'VALID' );
        $this->model->set('status', "INVALID");
        $this->model->update();
    }
}
