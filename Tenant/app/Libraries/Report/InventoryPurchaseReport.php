<?php

namespace Tenant\app\Libraries\Report;

use Tenant\app\Models\InventoryPurchaseReportModel;
use Tenant\app\Repositories\InventoryPurchaseReportViewRepository;
use Tenant\app\Repositories\InventoryPurchaseRepository;

class InventoryPurchaseReport extends ReportBuilderAbstract implements ReportInterface
{
    public function setDataFromView(string $id): self
    {
        $view_repository = new InventoryPurchaseReportViewRepository();
        $current_view = $view_repository->show($id);
        $this->view = json_decode($current_view['view']);

        $entities = $view_repository->result($id, null, false);
        $this->entities = isset($entities['inventory_purchase_listing']) ? $entities['inventory_purchase_listing'] : [];

        return $this;
    }

    public function setDataFromEntityRepository(string $view = '', array $query = NULL, string $search = NULL, $column = 'created_at', $order = 'DESC', $items = 100, bool $paginate = false): self
    {
        $this->view = json_decode($view);

        $view_repository = new InventoryPurchaseRepository();

        if (count($query) > 0) {
            $query = $view_repository->prepareDataForFilter($query);
        }

        $view_repository->setModel(new InventoryPurchaseReportModel());

        if (!empty($search)) {
            $view_repository = $view_repository->search($search);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $entities = $view_repository->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_purchase_view_id', 'inventory_purchase_listing');
        $this->entities = isset($entities['inventory_purchase_listing']) ? $entities['inventory_purchase_listing'] : [];

        return $this;
    }
}
