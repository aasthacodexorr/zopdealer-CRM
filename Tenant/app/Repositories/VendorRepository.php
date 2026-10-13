<?php

namespace Tenant\app\Repositories;

use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\VendorModel;

/**
 * VendorRepository CRUD and pagination for the Vendor
 */
class VendorRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new VendorModel());
        $this->entity = 'Tenant\app\Entities\Vendor';
        $this->entityLabel = 'Vendor';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'vendor_id';
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
            $builder = $db->table('vendors');

            $all_vendors = NULL;

            parent::setModel(new VendorModel());

            if (empty($ids)) {
                $all_vendors =  $this->model
                    ->where("search_keyword", NULL)
                    ->orWhere("search_keyword", "")
                    ->asArray()->findAll();
            } else {
                $all_vendors = $this->model
                    ->whereIn("{$this->model->table}.id", $ids)
                    ->asArray()
                    ->findAll();
            }

            foreach ($all_vendors as $key => $value) {
                try {
                    $data['data'] = $value;
                    $vendor_with_updated_search_index = $this->model->createKeyWordString($data);
                    if (isset($value['id'])) {
                        $builder->where('id', $value['id'])->set(['search_keyword' => $vendor_with_updated_search_index['data']['search_keyword']])->update();
                    }
                } catch (\Exception $single_ex) {
                    log_message('notice', $single_ex->getMessage());
                }
            }
        } catch (\Exception $ex) {
            return ['errors' => 'There were some errors in updating the index.'];
        }

        return ['success' => 'Vendor search index has been updated.'];
    }
}
