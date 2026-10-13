<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Repositories\CRUDWithAdvanceRepository;
use Tenant\app\Models\SocialConnectListModel;
use Tenant\app\Models\SocialConnectModel;
use Carbon\Carbon;

/**
 * SocialConnectRepository CRUD and pagination for the Facebook Connect Logs
 */
class SocialConnectRepository extends CRUDWithAdvanceRepository
{
    public function __construct()
    {
        parent::__construct(new SocialConnectModel());
        $this->entity = 'CodeIgniter\Entity';
        $this->entityLabel = 'Social record';
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
        return $this->model->where("id", $id)->asArray()->first();
    }

    public function delete($id)
    {
        return $this->model->where("id", $id)->delete();
    }

    public function checkForInventoryUpdate(){
        //get all instock inventories.
        $inventoryRepository = new InventoryRepository();
        $inventoryRepository->setModel(new SocialConnectListModel());
        //iterate through them
        $fbmp_inventories = $inventoryRepository->index(null, null, null, null, null, null, false, 'inventory_view_id', 'inventory_listing');

        foreach ($fbmp_inventories['inventory_listing'] as $key => $value) {
           $inventory_update_dt = $fbmp_inventories['inventory_listing'][$key]['updated_at'];
           $facebook_update_dt = $fbmp_inventories['inventory_listing'][$key]['facebook_updated_at'];

           if(strtotime($inventory_update_dt) && strtotime($facebook_update_dt)){
                $update_dt = Carbon::parse($inventory_update_dt, 'UTC');
                $facebook_update_dt = Carbon::parse($facebook_update_dt, 'UTC');

                if($update_dt->greaterThanOrEqualTo($facebook_update_dt)){
                    $fb_status = $fbmp_inventories['inventory_listing'][$key]['facebook_status'];
                    $stock_no = $fbmp_inventories['inventory_listing'][$key]['stock_no'];

                    if(in_array($fb_status,["PUBLISHED", "UPDATED"])){
                        $status = "TO BE UPDATED";
                        $this->createOrUpdate(["status" => $status, "stock_no" => $stock_no]);

                    }
                }

           }
        }
        //update
    }
}
