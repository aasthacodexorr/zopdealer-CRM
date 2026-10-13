<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Libraries\DVI\DigitalVehicleInspectionConstant;
use Tenant\app\Models\DigitalVehicleInspectionModel;
use Tenant\app\Models\InventoryModel;

class DigitalVehicleInspectionRepository extends CRUDWithAdvanceRepository
{
    private $digital_vehicle_inspection_item_repository;

    public function __construct()
    {
        parent::__construct(new DigitalVehicleInspectionModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'DVI';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'digital_vehicle_inspection_id';

        $this->digital_vehicle_inspection_item_repository = new DigitalVehicleInspectionItemRepository();
    }

    public function show($id): ?array
    {

        $entity = $this->model->asArray()->find($id);
        $items = [];
        if (!empty($entity)) {
            $items = $this->digital_vehicle_inspection_item_repository->getModel()->where('dvi_id', $id)->orderBy('id', 'ASC')->asArray()->findAll();
            $entity['items'] = $items;
        }

        return $entity;
    }


    public function getFailedItemCount($id): ?int
    {
        $count = $this->digital_vehicle_inspection_item_repository->getModel()->where('dvi_id', $id)->where('status', DigitalVehicleInspectionConstant::FAIL)->countAllResults();
        return is_numeric($count) ? intval($count) : 0;
    }

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            // var_dump($data);
            foreach ($data as $key => $value) {
                if (
                    $key == 'zm_status'

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
                } else if (in_array($key, ['zm_assignee', 'zm_stock_no'])) {
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
                    'column' => "created_at",
                    'join' => 'AND',
                    'comparator' => "between",
                    "type" => "Date Comparision",
                    'value_1' => array_shift($data['zm_date_start']),
                    'value_2' => array_shift($data['zm_date_end']),
                ];
                array_push($query, $single_query);
            }
        }

        return json_encode($query);
    }

    public function updateSearchIndex($ids = []): ?array
    {

        try {

            $db = \Config\Database::connect();
            $builder = $db->table('digital_vehicle_inspections');

            parent::setModel(new DigitalVehicleInspectionModel());

            $all_digital_vehicle_inspections = NULL;
            if (empty($ids)) {
                $all_digital_vehicle_inspections = $this->model->where("{$this->model->table}.search_keyword", NULL)
                    ->orWhere("{$this->model->table}.search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_digital_vehicle_inspections = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_digital_vehicle_inspections as $key => $value) {
                try {
                    $data['data'] = $value;
                    $dvi_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id']) && !empty($value['stock_no'])) {
                        $repository = get_called_class();
                        $repository = new $repository;

                        $inventory_details['data'] = (new InventoryRepository())->findSingleInventoryByStockId($value['stock_no']);

                        if (!empty($inventory_details['data'])) {
                            $inventory_search_keyword = (new InventoryModel())->createKeyWordString($inventory_details);
                            $dvi_with_updated_search_index['data']['search_keyword'] .= " " . $inventory_search_keyword['data']['search_keyword'];
                        }

                        $builder->where('id', $value['id'])->set(['search_keyword' => $dvi_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => $ex->getMessage()];
        }

        return ['success' => 'DVI search index has been updated.'];
    }
}
