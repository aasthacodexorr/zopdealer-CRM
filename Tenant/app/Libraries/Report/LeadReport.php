<?php

namespace Tenant\app\Libraries\Report;

use Tenant\app\Models\LeadReportModel;
use Tenant\app\Repositories\LeadReportViewRepository;
use Tenant\app\Repositories\LeadResponseRepository;

class LeadReport extends ReportBuilderAbstract implements ReportInterface
{
    public function setDataFromView(string $id): self
    {
        $view_repository = new LeadReportViewRepository();
        $current_view = $view_repository->show($id);
        $this->view = json_decode($current_view['view']);

        $entities = $view_repository->result($id, false);
        $this->entities = isset($entities['lead_listing']) ? $entities['lead_listing'] : [];

        return $this;
    }

    public function setDataFromEntityRepository(string $view = '', array $query = NULL, string $search = NULL, $column = 'created_at', $order = 'DESC', $items = 100, bool $paginate = false): self
    {
        $this->view = json_decode($view);

        $view_repository = new LeadResponseRepository();

        if (count($query) > 0) {
            $query = $view_repository->prepareDataForFilter($query);
        }

        $view_repository->setModel(new LeadReportModel());

        if (!empty($search)) {
            $view_repository = $view_repository->search($search);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $entities = $view_repository->index(null, $header = null, null, $query, $items, $sort, $paginate, 'lead_view_id', 'lead_listing');
        $this->entities = isset($entities['lead_listing']) ? $entities['lead_listing'] : [];

        return $this;
    }
}
