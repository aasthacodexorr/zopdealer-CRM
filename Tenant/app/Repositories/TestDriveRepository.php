<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\TestDriveModel;
use Tenant\app\Models\TestDrivePhotoModel;
use Tenant\app\Models\TestDriveDocumentModel;
use Tenant\app\Models\TestDriveGpsPointModel;
use Tenant\app\Models\TestDriveCustomerModel;
use Tenant\app\Models\InventoryModel;
use Tenant\app\Libraries\Services\GPSIngestService;
use Tenant\app\Models\TestDriveEventModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;

/**
 * TestDriveRepository CRUD and pagination for the Test Drive
 */
class TestDriveRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new TestDriveModel());
        $this->entity = 'Tenant\app\Entities\TestDrive';
        $this->entityLabel = 'Test Drive';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'test_drive_id';
    }

    /**
     * @method prepareDataForFilter : prepares data recieved from the get parameters for filter and search results
     * @param $data : $data recieved.
     * @return $query : String
     */

    public function prepareDataForFilter($data)
    {
        $query = [];
        if (count($data) > 0) {
            //  var_dump($data);
            foreach ($data as $key => $value) {

                if (
                    $key == 'zm_status' || $key == 'zm_sales_rep_id'
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
        json_encode($query);

        return json_encode($query);
    }

    /**
     * Get test drive by session token
     */
    public function getBySessionToken(string $token)
    {
        return $this->model->where('session_token', $token)
            ->where('deleted_at', null)
            ->first();
    }

    /**
     * Get test drives with customer and vehicle details - (UNUSED)
     */
    public function checkIfExists(int $id)
    {
        $count = $this->model->where('id', $id)
            ->where('deleted_at', null)
            ->countAllResults();
        return (int)$count;
    }

    /**
     * Get test drives with customer and vehicle details
     */
    public function getTestDrivesWithAllDetails(int $id)
    {
        $testDrive = $this->model->find($id);

        if (!$testDrive) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Test Drive couldn't be found."])->send();
            return;
        }
        // Get related data
        $customer = (new TestDriveCustomerModel)->find($testDrive->test_drives_customers_id);
        $inventory = (new InventoryModel)->find($testDrive->inventory_id);
        $photos = (new TestDrivePhotoModel)->getByTestDriveId($id);
        $documents = (new TestDriveDocumentModel)->getByTestDriveId($id);
        $gpsPoints = (new TestDriveGpsPointModel)->getByTestDriveId($id);
        $events = (new TestDriveEventModel)->getAuditTrail($id);
        $distance_km = (new GPSIngestService)->calculateDistance($id);
        $sales_person =    (new TenantUserMetaDataRepository())->getUserMetaDataById($testDrive->sales_rep_id);

        // Prepare data for PDF
        $response = [
            'test_drive' => $testDrive,
            'customer' => $customer,
            'inventory' => $inventory,
            'photos' => $photos,
            'documents' => $documents,
            'gps_points' => $gpsPoints,
            'events' => $events,
            'distance_km' => $distance_km,
            'duration_minutes' => $testDrive->getDurationMinutes(),
            'sales_person' => $sales_person[0] ?? null,
        ];

        return $response;
    }

    public function getSalesPersonName(int $id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('user_metadata');
        $builder->select('name');
        $builder->where('user_id', $id);
        return $builder->get()->getRowArray();
    }


    /**
     * updateSearchIndex
     *
     * @param mixed $ids
     * @return array
     */
    public function updateSearchIndex($ids = []): ?array
    {
        try {
            $db = \Config\Database::connect();
            $builder = $db->table('test_drives');

            parent::setModel(new TestDriveModel());

            // Fetch with joins so that all $searchableColumns fields are present in each row.
            // 'first_name','last_name','phone','email' come from test_drives_customers;
            // 'stock_no','vin' come from inventory — none of these exist in test_drives alone.
            $this->model->select("test_drives.*,
            test_drives_customers.first_name,
            test_drives_customers.last_name,
            test_drives_customers.phone,
            test_drives_customers.email,
            test_drives_customers.street_address,
            test_drives_customers.city,
            test_drives_customers.province,
            CASE 
                WHEN TRIM(COALESCE(test_drives.notes, '')) = '' THEN 'No'
                ELSE 'Yes'
            END AS  issue,
            concat(inventory.year, ' ', inventory.make, ' ', inventory.model, ' ', inventory.trim) as vehicle,
            inventory.year,
            inventory.make,
            inventory.model,
            inventory.trim,
            inventory.stock_no as stock_no,
             inventory.vin as vin,
            users.email as sales_rep_email,
            user_metadata.name as sales_rep_name")
                ->join('test_drives_customers', 'test_drives_customers.id = test_drives.test_drives_customers_id', 'left')
                ->join('inventory', 'inventory.stock_no = test_drives.inventory_id COLLATE utf8mb4_general_ci', 'left',false);

            if (empty($ids)) {
                $this->model->groupStart()
                    ->where("{$this->model->table}.search_keyword", null)
                    ->orWhere("{$this->model->table}.search_keyword", '')
                    ->groupEnd();
            } else {
                $this->model->whereIn("{$this->model->table}.id", $ids);
            }

            //@todo: check if model where conditions are working, as it will bypas and still says  reindexed , while not reindexing.
            $all_testdrives = $this->model->asArray()->findAll();

            foreach ($all_testdrives as $value) {
                try {
                    $data['data'] = $value;
                    $testdrives_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])
                            ->set(['search_keyword' => $testdrives_with_updated_search_index['data']['search_keyword']])
                            ->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'TestDrive search index has been updated.'];
    }

    public function availableTestDriveCredits(): ?int
    {
        $testdrive_available_credit_config =  (new ConfigurationRepository())->findConfigurationColumnsByKeyAsArray('testdrive_available_credit', ['value']);
        return intval($testdrive_available_credit_config['value']);
    }

    public function filterByModuleAndId(string $module, string $module_id): ?array
    {
        $module = strtolower($module);
        switch ($module) {
            case 'lead':
                $this->filterData('module', 'Lead')->filterData('module_id', $module_id);
                return $this->model->orderBy("id", "DESC")->asArray()->findAll();
                break;

            default:
                return [];
                break;
        }
    }

}