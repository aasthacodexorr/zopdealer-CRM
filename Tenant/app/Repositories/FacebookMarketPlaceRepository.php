<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\FacebookMarketplaceListModel;
use Tenant\app\Models\FacebookMarketPlaceModel;
use Carbon\Carbon;

/**
 * FacebookMarketPlaceRepository CRUD and pagination for the Facebook Marketplace Logs
 */
class FacebookMarketPlaceRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new FacebookMarketPlaceModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'FBMP Log';
        $this->createSuccessMessage = $this->entityLabel . ' has been created.';
        $this->updateSuccessMessage = $this->entityLabel . ' has been updated.';
        $this->id_field = 'fbmp_id';
    }


    public function createOrUpdate(array $data): array
    {
        try {

            if (empty($data['stock_no'])) {
                throw new \Exception("Stock No Missing.", 1);
            }

            $instance = $this->model->where('stock_no', $data['stock_no'])->asArray()->first();

            //Check if page_id exist and verify it with the stock_no
            if (!empty($data['post_id'])) {
                $search_by_post_id = $this->model->where('post_id', $data['post_id'])->asArray()->first();

                if (!empty($search_by_post_id)) {
                    if ($search_by_post_id['stock_no'] != $data['stock_no']) {
                        unset($data['post_id']);
                        throw new \Exception("Stock No Mismatch.", 1);
                    }
                }
            }

            if (empty($instance)) {
                return parent::create($data);
            } else {
                return parent::update($instance['id'], $data);
            }
        } catch (\Exception $e) {

            return ["errors" => $e->getMessage()];
        }
    }

    public function show($id): ?array
    {
        return $this->model->where("post_id", $id)->asArray()->first();
    }

    public function delete($id)
    {

        if (! empty($id) && (is_numeric($id) || is_string($id))) {
            $id = [$id];
        }

        return $this->model->whereIn("post_id", $id)->delete();
    }

    public function filterDataAndPurgeDelete(String $column = '', String $value = ''): void
    {
        if (!empty($column)) {
            $this->model->where($column, $value)->delete();
        } else {
            $this->model->where("1 = 1")->delete();
        }

        $this->model->purgeDeleted();
    }

    public function changeStatusFromToBeUpdatedToUpdated()
    {
        $this->model->set(['status' => 'UPDATED']);
        $this->model->where('status', 'TO BE UPDATED');
        $this->model->update();
    }

    public function checkForInventoryUpdate()
    {
        //get all instock inventories.
        $inventoryRepository = new InventoryRepository();
        $inventoryRepository->setModel(new FacebookMarketplaceListModel());
        //iterate through them
        $fbmp_inventories = $inventoryRepository->index(null, null, null, null, null, null, false, 'inventory_view_id', 'inventory_listing');

        foreach ($fbmp_inventories['inventory_listing'] as $inventory) {

            if ($inventory['modification_hash'] !== $inventory['current_modification_hash']) {
                if (in_array($inventory['facebook_status'], ["PUBLISHED", "UPDATED"]) && !empty($inventory['post_id'])) {
                    $this->createOrUpdate([
                        "status" => 'TO BE UPDATED',
                        "stock_no" => $inventory['stock_no'],
                        "current_modification_hash" => $inventory['modification_hash']
                    ]);
                }
            }
        }
    }
}
