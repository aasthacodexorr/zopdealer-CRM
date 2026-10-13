<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use App\Traits\EntityBasedFilterTrait;
use Tenant\app\Models\DealViewModel;
use Tenant\app\Entities\DealView;
use Tenant\app\Repositories\DealRepository;

/**
 * DealViewRepository CRUD and complex queries for DealView Model
 */
class DealViewRepository extends BaseRepository
{
    use EntityBasedFilterTrait;

    public function __construct()
    {
        parent::__construct(new DealViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    /**
     * creates new Deal View.
     *
     * @return array
     */
    public function create($data): array
    {

        if ($this->model->save(new DealView($data)) === false) {
            return $this->model->errors();
        } else {
            $deal_view_id = $this->model->insertID;
            return [
                "success" => 'Deal View Created',
                "id" => $deal_view_id
            ];
        }
    }

    public function show($id): ?array
    {
        return $this->model->asArray()->find($id);
    }

    /**
     * update Updates the Deal View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id)
    {

        $dealView = new DealView($data);
        $dealView->id = $id;

        if ($this->model->save($dealView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'Deal View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $dealRepository = new DealRepository();

        $this->applyEntityFilters($dealRepository);

        $iv =  $this->model->find($id);
        return $dealRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'deal_view_id', 'deal_listing');
    }
}
