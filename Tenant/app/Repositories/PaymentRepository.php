<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\PaymentModel;

/**
 * PaymentRepository CRUD and pagination for the Payment
 */
class PaymentRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new PaymentModel());
        $this->entity = 'Tenant\app\Entities\Payment';
        $this->entityLabel = 'Payment';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'payment_id';
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the website to make search consistent.
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_type'   ||
                    $key == 'zm_status'   ||
                    $key == 'zm_reason'   ||
                    $key == 'zm_module' ||
                    $key == 'zm_module_id'

                ) {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Multi Select",
                        'value_1' => $value,
                        'value_2' => null,
                    ];

                    array_push($query, $single_query);
                }
            }

            if (!empty($data['zm_date_start']) && !empty($data['zm_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "date",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }

            if (!empty($data['zm_amount_start']) && !empty($data['zm_amount_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "amount",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Numeric Comparision",
                    'value_1' => array_shift($data['zm_amount_start']),
                    'value_2' => array_shift($data['zm_amount_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
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
            $builder = $db->table('payments');

            $all_payments = NULL;

            parent::setModel(new PaymentModel());

            if (empty($ids)) {
                $all_payments =  $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_payments = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_payments as $key => $value) {
                try {
                    $data['data'] = $value;
                    $payment_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])->set(['search_keyword' => $payment_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Payment search index has been updated.'];
    }

    public function filterPaymentByModuleAndId(string $module, string $module_id): ?array
    {
        $module = strtolower($module);
        switch ($module) {
            case 'lead':
                $this->filterData('module', 'Lead')->filterData('module_id', $module_id);
                return $this->model->orderBy("date", "DESC")->asArray()->findAll();
                break;

            case 'deal':
                $this->filterData('module', 'Deal')->filterData('module_id', $module_id);
                return $this->model->orderBy("date", "DESC")->asArray()->findAll();
                break;

            case 'customer':
                $this->filterData('module', 'Customer')->filterData('module_id', $module_id);
                return $this->model->orderBy("date", "DESC")->asArray()->findAll();
                break;

            default:
                return [];
                break;
        }
    }
}
