<?php

namespace Tenant\app\Libraries\Report;

use Tenant\app\Models\InventoryCostReportModel;
use Tenant\app\Repositories\InventoryCostReportViewRepository;
use Tenant\app\Repositories\InventoryCostRepository;

class InventoryCostReport extends ReportBuilderAbstract implements ReportInterface
{
    public function setDataFromView(string $id): self
    {
        $view_repository = new InventoryCostReportViewRepository();
        $current_view = $view_repository->show($id);
        $this->view = json_decode($current_view['view']);

        $entities = $view_repository->result($id, null, false);
        $this->entities = isset($entities['inventory_cost_listing']) ? $entities['inventory_cost_listing'] : [];

        return $this;
    }

    public function setDataFromEntityRepository(string $view = '', array $query = NULL, string $search = NULL, $column = 'created_at', $order = 'DESC', $items = 100, bool $paginate = false): self
    {
        $this->view = json_decode($view);

        $view_repository = new InventoryCostRepository();

        if (count($query) > 0) {
            $query = $view_repository->prepareDataForFilter($query);
        }

        $view_repository->setModel(new InventoryCostReportModel());

        if (!empty($search)) {
            $view_repository = $view_repository->search($search);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $entities = $view_repository->index(null, $header = null, null, $query, $items, $sort, $paginate, 'inventory_cost_view_id', 'inventory_cost_listing');
        $this->entities = isset($entities['inventory_cost_listing']) ? $entities['inventory_cost_listing'] : [];

        return $this;
    }
}
