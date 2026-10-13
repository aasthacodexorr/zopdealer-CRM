<?php

namespace Tenant\app\Repositories;

use App\Entities\MasterEntity;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\DealCustomerInventoryModel;
use Tenant\app\Models\DealModel;
use Tenant\app\Models\DealNoteModel;
use App\Libraries\CloudStorage\CloudStorageFactory;
use Tenant\app\Models\CustomerModel;
use Tenant\app\Models\DealCustomerPivotModel;
use Tenant\app\Models\DealInventoryPivotModel;
use Tenant\app\Models\InventoryModel;

class DealRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new DealModel());
        $this->entity = 'Tenant\app\Entities\Deal';
        $this->entityLabel = 'Deal';
        $this->createSuccessMessage = $this->entityLabel . ' has been added.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'deal_id';
    }

    public function findByDealId($deal_id)
    {
        return $this->model->where('deal_id', $deal_id)->findAll();
    }

    public function filterByDealId($deal_id)
    {
        $this->model->where('deal_id', $deal_id);
        return $this;
    }

    public function deleteWithoutKey()
    {
        $this->model->delete();
    }

    public function allowUpdateOfProtectedFields($id, array $data)
    {
        $this->model->protect(false);
        $this->model->where('id', $id)
            ->set($data)
            ->update();
        $this->model->protect(true);
    }

    /**
     * @method prepareDataForFilter : prepares data recieved.
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
                    $key == 'zm_type' || $key == 'zm_status'

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
                } else if ($key == 'zm_retail_or_wholesale_flag' || $key == 'zm_approved_by' || $key == 'zm_salesperson') {
                    $single_query = [
                        'header' => null,
                        'column' => str_replace('zm_', '', $key),
                        'join' => 'AND',
                        'comparator' => "is",
                        "type" => "Single Select",
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

            if (!empty($data['zm_delivery_date_start']) && !empty($data['zm_delivery_date_end'])) {
                $single_query = [
                    'header' => null,
                    'column' => "delivery_date_time",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_delivery_date_start']),
                    'value_2' => array_shift($data['zm_delivery_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }

    /**
     * dealWithInventoryAndCustomers This method is used to send Delivered or Cancelled deals
     * to third party.
     *
     * @param  mixed $status
     * @return void
     */
    public function dealWithInventoryAndCustomers(String $status = "")
    {
        $column_for_date = "deals.delivery_date_time";
        $column_for_date = $status == "Cancelled" ? "deals.cancellation_date" : $column_for_date;
        return (new DealCustomerInventoryModel())->where("DATE({$column_for_date}) = CURDATE() - INTERVAL 1 DAY", null, false)->where("deals.status", $status)->asArray()->findAll();
        //return (new DealCustomerInventoryModel())->where("deals.status", $status)->asArray()->findAll();
    }


    /**
     * findInventoryAndCustomerDetailsForDeal Find Dependency with Deal ID
     *
     * @param  string $id
     * @return array
     */
    public function findInventoryAndCustomerDetailsForDeal($id): array
    {
        $response = [];

        $available_deal = $this->show($id);

        if (isset($available_deal['id'])) {
            $this->setModel(new DealCustomerPivotModel());
            $response['customers'] = $this->findByDealId($available_deal['id']);
            $this->setModel(new DealInventoryPivotModel());
            $response['inventory'] = $this->findByDealId($available_deal['id']);

            if (!empty($response['inventory'])) {
                foreach ($response['inventory'] as $key => $inventory) {
                    $response['inventory'][$key] = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);
                }
            }

            if (!empty($response['customers'])) {
                foreach ($response['customers'] as $key => $customer) {
                    $deal_customer_type = $customer['customer_type'];
                    $response['customers'][$key] = (new CustomerRepository())->show($customer['customer_id']);
                    $response['customers'][$key]['deal_customer_type'] = $deal_customer_type;
                }
            }
        }

        return $response;
    }

    /**
     * saveNote Here the logic saves the note along with the attachment for the deal
     *
     * @param  int $id
     * @param  mixed $data
     * @return array
     */
    public function saveNote(int $id, $data): array
    {
        $dealNote = new MasterEntity($data);
        $dealNote->deal_id = $id;
        $dealNoteModel = new DealNoteModel();

        if ($dealNoteModel->save($dealNote) === false) {
            return $dealNoteModel->errors();
        } else {

            $deal_note_id = $dealNoteModel->insertID;
            return [
                'success' => 'Note for deal has been created.',
                'deal_note_id' => $deal_note_id,
            ];
        }
    }


    /**
     * allNotes This function returns all the notes related to a deal
     *
     * @param  mixed $id
     * @return array
     */
    public function allNotes(int $id): array
    {
        $notes = (new DealNoteModel())->where('deal_id', $id)->orderBy('created_at', 'DESC')->asArray()->findAll();
        return ['all_notes' => $notes];
    }

    /**
     * showNote This returns a single note by id.
     *
     * @param  mixed $id
     * @return array
     */
    public function showNote($id): ?array
    {
        return (new DealNoteModel())->asArray()->find($id);
    }

    /**
     * updateNote Update note. Accepts id and data to be updated.
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return void
     */
    public function updateNote($id, $data)
    {

        $dealNote = new MasterEntity($data);
        $dealNote->id = $id;
        $dealNoteModel = new DealNoteModel();


        if ($dealNoteModel->save($dealNote) === false) {
            return [
                $dealNoteModel->errors()
            ];
        } else {
            return [
                "success" => 'Deal Note has been updated.',
            ];
        }
    }

    /**
     * deleteNote Delete a note for the deal
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteNote(int $id)
    {
        $note = $this->showNote($id);
        if ($note == null) {
            return ['errors' => 'Could not find the note.'];
        }
        if (!empty($note['attachment'])) {
            $file_path = $note['attachment'];
            if (gettype($file_path) == "string") {
                CloudStorageFactory::getInstance()->delete($file_path);
            }
        }
        (new DealNoteModel())->delete($id);
        return ['success' => 'Deal Note has been deleted successfully.'];
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
            $builder = $db->table('deals');

            parent::setModel(new DealModel());

            $all_deals = NULL;
            if (empty($ids)) {
                $all_deals = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_deals = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_deals as $key => $value) {
                try {
                    $data['data'] = $value;
                    $deal_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $repository = get_called_class();
                        $repository = new $repository;

                        $customers = $repository->setModel(new DealCustomerPivotModel())->findByDealId($value['id']);

                        if (!empty($customers)) {
                            foreach ($customers as $key => $customer) {
                                if ($customer['customer_type'] === 'primary_customer') {
                                    $customer_details['data'] = (new CustomerRepository())->show($customer['customer_id']);
                                    $customer_search_keyword = (new CustomerModel())->createKeyWordString($customer_details);
                                    $deal_with_updated_search_index['data']['search_keyword'] .= " " . $customer_search_keyword['data']['search_keyword'];
                                }
                            }
                        }
                        $inventories = $repository->setModel(new DealInventoryPivotModel())->findByDealId($value['id']);
                        if (!empty($inventories)) {
                            foreach ($inventories as $key => $inventory) {
                                $inventory_details['data'] = (new InventoryRepository())->findSingleInventoryByStockId($inventory['stock_no']);

                                if (!empty($inventory_details['data'])) {
                                    $inventory_search_keyword = (new InventoryModel())->createKeyWordString($inventory_details);
                                    $deal_with_updated_search_index['data']['search_keyword'] .= " " . $inventory_search_keyword['data']['search_keyword'];
                                }
                            }
                        }
                        $builder->where('id', $value['id'])->set(['search_keyword' => $deal_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }

        return ['success' => 'Deal search index has been updated.'];
    }
}
