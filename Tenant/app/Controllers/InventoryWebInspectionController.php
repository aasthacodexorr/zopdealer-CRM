<?php

namespace Tenant\app\Controllers;

use Tenant\app\Repositories\InventoryWebInspectionRepository;
use App\Policies\AuthPolicy;
use Tenant\app\Repositories\InventoryRepository;

class InventoryWebInspectionController extends \App\Controllers\BaseController
{
    /**
     * @var InventoryWebInspectionRepository
     */
    private $repository;

    
    /**
     * secondary_repository
     *
     * @var InventoryRepository
     */
    private $secondary_repository;

    public function __construct()
    {
        $this->repository = new InventoryWebInspectionRepository();
        $this->secondary_repository = new InventoryRepository();
    }

    public function create($inventory_id)
    {
        $inventory = $this->secondary_repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $data = $this->request->getPost();
        $data["inventory_id"] = $inventory_id;
        $response = $this->repository->create($data);


        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($inventory_id, $id)
    {

        $response = $this->repository->show($id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Inspection Report couldn't be found for this inventory."])->send();
        }
    }

    public function update($inventory_id, $id)
    {

        $data = $this->request->getPost();

        $response = $this->repository->update($id, $data);
        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function index($inventory_id)
    {

        $inventory = $this->secondary_repository->show($inventory_id);

        if ($inventory == null || !isset($inventory['inventory_id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Inventory couldn't be found."
            ])->send();
            exit;
        }

        $response = $this->repository->findByInventoryId($inventory_id);
        if ($response != null) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => "Inspection Report couldn't be found for this inventory."])->send();
        }
    }

    /**
     * render
     *
     * @param  mixed $vin
     * @return void
     */
    public function render($vin)
    {
        $inventory = $this->secondary_repository->findSingleInventoryByVin($vin);

        if ($inventory == null || !isset($inventory['inventory_id'])) {

            if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                echo view('errors/html/error_404', ['message' => 'Inventory with particular VIN could not be found.']);
                exit;
            }
            exit;
        }

        try {
            $vehicle_inspection = $this->repository->findByInventoryId($inventory['inventory_id']);
            $vehicle_inspection = json_decode($vehicle_inspection['detail'], true);

            if ($vehicle_inspection['visibility'] != "1" || $inventory["visibility"] != "1") {
                throw new \Exception("Inspection Report Not Found", 1);
            }

            if (is_file(APPPATH . 'Views/templates/inspection.php')) {
                echo view('Views/templates/inspection', ['inventory' => $inventory, 'inspection' => $vehicle_inspection]);
                exit;
            }
        } catch (\Exception $e) {
            if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                echo view('errors/html/error_404', ['message' => 'Inspection Report Not Found.']);
                exit;
            }
        }
    }


    /**
     * render
     *
     * @param  mixed $vin
     * @return void
     */
    public function summary($vin)
    {
        $inventory = $this->secondary_repository->findSingleInventoryByVin($vin);

        if ($inventory == null || !isset($inventory['inventory_id'])) {

            if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                //error response
                exit;
            }
            exit;
        }

        try {
            $vehicle_inspection = $this->repository->findByInventoryId($inventory['inventory_id']);
            $vehicle_inspection = json_decode($vehicle_inspection['detail'], true);

            if ($vehicle_inspection['visibility'] != "1" || $inventory["visibility"] != "1") {
                throw new \Exception("Inspection Report Not Found", 1);
            }

            foreach ($vehicle_inspection['sections'] as $index => $section) {
                foreach ($section['list'] as $sub_index => $inspection_point) {
                    if ($inspection_point["visibility"] != "1") {
                        unset($vehicle_inspection['sections'][$index]['list'][$sub_index]);
                    }
                }

                $imperfections = 0;
                $passed = 0;
                $not_applicable = 0;

                foreach ($section['list'] as $sub_index => $inspection_point) {
                    switch ($inspection_point['value']) {
                        case 'Imperfections':
                            ++$imperfections;
                            break;

                        case 'Passed':
                            ++$passed;
                            break;

                        default:
                            ++$not_applicable;
                            break;
                    }
                }

                $vehicle_inspection['sections'][$index]['imperfections'] = $imperfections;
                $vehicle_inspection['sections'][$index]['passed'] = $passed;
                $vehicle_inspection['sections'][$index]['not_applicable'] = $not_applicable;
                unset($vehicle_inspection['sections'][$index]['list']);
            }


            $this->response->setHeader('Access-Control-Allow-Origin', '*') //for allow any domain, insecure
                ->setHeader('Access-Control-Allow-Headers', '*') //for allow any headers, insecure
                ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE') //method allowed
                ->setStatusCode(200)
                ->setContentType('application/json')->setJSON(['inventory' => $inventory, 'inspection' => $vehicle_inspection])->send();
            exit;
        } catch (\Exception $e) {
            if (is_file(APPPATH . 'Views/errors/html/error_404.php')) {
                //error response
                exit;
            }
        }
    }
}
