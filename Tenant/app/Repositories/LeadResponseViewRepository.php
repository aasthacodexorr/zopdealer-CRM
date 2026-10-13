<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Traits\EntityBasedFilterTrait;
use Tenant\app\Models\LeadResponseViewModel;
use Tenant\app\Entities\LeadResponseView;
use Tenant\app\Repositories\LeadResponseRepository;

/**
 * LeadResponseViewRepository CRUD and complex queries for LeadResponseView Model
 */
class LeadResponseViewRepository extends BaseRepository
{
    use EntityBasedFilterTrait;

    public function __construct()
    {
        parent::__construct(new LeadResponseViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    /**
     * creates new Lead Response View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new LeadResponseView($data)) === false) {
            return $this->model->errors();
        } else {
            $lead_response_view_id = $this->model->insertID;
            return [
                "success" => 'New Lead Response View Created',
                "id" => $lead_response_view_id
            ];
        }
    }

    /**
     * update Updates the Lead Response View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $leadResponseView = new LeadResponseView($data);
        $leadResponseView->id = $id;

        if ($this->model->save($leadResponseView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Lead View Response has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $leadResponseRepository = new LeadResponseRepository();
        $this->applyEntityFilters($leadResponseRepository);

        $iv =  $this->model->find($id);
        return $leadResponseRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'lead_response_id', 'lead_response_listing');
    }
}
