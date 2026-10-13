<?php

namespace Tenant\app\Libraries\Report;

use Tenant\app\Models\PaymentModel;
use Tenant\app\Repositories\PaymentReportViewRepository;
use Tenant\app\Repositories\PaymentRepository;

class PaymentReport extends ReportBuilderAbstract implements ReportInterface
{
    public function setDataFromView(string $id): self
    {
        $view_repository = new PaymentReportViewRepository();
        $current_view = $view_repository->show($id);
        $this->view = json_decode($current_view['view']);

        $entities = $view_repository->result($id, null, false);
        $this->entities = isset($entities['payment_listing']) ? $entities['payment_listing'] : [];

        return $this;
    }

    public function setDataFromEntityRepository(string $view = '', array $query = NULL, string $search = NULL, $column = 'created_at', $order = 'DESC', $items = 100, bool $paginate = false): self
    {
        $this->view = json_decode($view);

        $view_repository = new PaymentRepository();

        if (count($query) > 0) {
            $query = $view_repository->prepareDataForFilter($query);
        }

        $view_repository->setModel(new PaymentModel());

        if (!empty($search)) {
            $view_repository = $view_repository->search($search);
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';
        $entities = $view_repository->index(null, $header = null, null, $query, $items, $sort, $paginate, 'payment_view_id', 'payment_listing');
        $this->entities = isset($entities['payment_listing']) ? $entities['payment_listing'] : [];

        return $this;
    }
}
