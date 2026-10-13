<?php

namespace Tenant\app\Repositories;

use App\Repositories\BaseRepository;
use CodeIgniter\Entity;
use Tenant\app\Models\DigitalVehicleInspectionViewModel;

class DigitalVehicleInspectionViewRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct(new DigitalVehicleInspectionViewModel());
    }


    public function index()
    {
        return $this->model->asObject()->findAll();
    }

    public function show($id): ?array
    {

        return $this->model->asArray()->find($id);
    }

    public function create($data): array
    {

        if ($this->model->save(new Entity($data)) === false) {
            return $this->model->errors();
        } else {
            $digital_vehicle_inspection_view_id = $this->model->insertID;
            return [
                "success" => 'New DVI View Created',
                "id" => $digital_vehicle_inspection_view_id
            ];
        }
    }

    /**
     * update Updates the DVI View.
     *
     * @param  mixed $data
     * @param  mixed $id
     * @return void
     */
    public function update($data, $id): array
    {

        $digitalVehicleInspectionView = new Entity($data);
        $digitalVehicleInspectionView->id = $id;

        if ($this->model->save($digitalVehicleInspectionView) === false) {
            return $this->model->errors();
        } else {
            return [
                "success" => 'DVI View has been updated.',
            ];
        }
    }

    public function result($id)
    {
        $items = 100;
        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }
        $dviRepository = new DigitalVehicleInspectionRepository();

        $iv =  $this->model->find($id);
        return $dviRepository->index($id = $iv->id, $header = $iv->header, $view = $iv->view, $query = $iv->query, $items, $iv->sort, true, 'digital_vehicle_inspection_view_id', 'digital_vehicle_inspection_listing');
    }
}
