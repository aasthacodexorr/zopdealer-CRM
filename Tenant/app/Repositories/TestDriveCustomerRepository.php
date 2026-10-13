<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\TestDriveCustomerModel;

/**
 * TestDriveCustomerRepository CRUD and pagination for the Test Drive Customers
 */
class TestDriveCustomerRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new TestDriveCustomerModel());
        $this->entity = 'Tenant\app\Entities\TestDriveCustomer';
        $this->entityLabel = 'Test Drive Customer';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'test_drives_customers_id';
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
            $builder = $db->table('test_drives_customers');

            $all_cusomters = NULL;

            parent::setModel(new TestDriveCustomerModel());

            if (empty($ids)) {
                $all_cusomters = $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_cusomters = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_cusomters as $key => $value) {
                try {
                    $data['data'] = $value;
                    $customer_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])->set(['search_keyword' => $customer_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }
        return ['success' => 'TestDrive Customers search index has been updated.'];
    }


    /**
     * Override index method to include joins with customers and inventory tables
     */
    public function index($id = null, $header = null, $view = null, $query = null, $items = 20, $sort = null, $paginate = true, $view_id_title = 'view_id', $data_list = 'data_list')
    {

        // Call parent index method with joins applied
        return parent::index($id, $header, $view, $query, $items, $sort, $paginate, $view_id_title, $data_list);
    }

    /**
     * Find customer by email - (UNUSED)
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)
            ->where('deleted_at', null)
            ->first();
    }

    /**
     * Find customer by phone - (UNUSED)
     */
    public function findByPhone(string $phone)
    {
        return $this->model->where('phone', $phone)
            ->where('deleted_at', null)
            ->first();
    }

    /**
     * Search customers by name - (UNUSED)
     */
    public function searchByName(string $name)
    {
        return $this->model->groupStart()
            ->like('first_name', $name)
            ->orLike('last_name', $name)
            ->groupEnd()
            ->where('deleted_at', null)
            ->findAll();
    }
}
