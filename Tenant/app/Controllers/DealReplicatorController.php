<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use App\Policies\AuthPolicy;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tenant\app\Entities\Inventory;
use Tenant\app\Libraries\PdfFiller\PDFFillerAbstract;
use Tenant\app\Libraries\PdfFiller\PDFFillerFactory;
use Tenant\app\Models\DealReplicatorAccessoryModel;
use Tenant\app\Models\DealReplicatorGuaranteeModel;
use Tenant\app\Models\DealReplicatorInsuranceModel;
use Tenant\app\Models\DealReplicatorPaymentModel;
use Tenant\app\Models\DealReportModel;
use Tenant\app\Models\DealReplicatorWarrantyModel;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\DealReplicatorCommonDependentRepository;
use Tenant\app\Repositories\DealReplicatorRepository;
use Tenant\app\Repositories\LocationRepository;
use Tenant\app\Repositories\TenantUserMetaDataRepository;
use Tenant\app\Models\DealReportViewModel;

class DealReplicatorController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the DealReplicatorRepository which can be used by any method here.
     *
     * @var DealReplicatorRepository
     */
    private $repository;

    private $common_repository;

    public function __construct()
    {
        $this->repository = new DealReplicatorRepository();
        $this->common_repository = new DealReplicatorCommonDependentRepository();
    }

    public function index()
    {

        $items = 100;
        $paginate = true;

        $column = 'created_at';
        $order = 'DESC';


        if ($this->request->getGet('items')) {
            $items = $this->request->getGet('items');
        }

        if ($this->request->getGet('page_no')) {
            $_GET['page'] = $this->request->getGet('page_no');
        }

        if ($this->request->getGet('paginate')) {
            $paginate = $this->request->getGet('paginate');
            $paginate  = $paginate == 'false' ? false : true;
        }

        if ($this->request->getGet('column')) {
            $column = $this->request->getGet('column');
        }

        if ($this->request->getGet('order')) {
            $order = $this->request->getGet('order') == "ASC" ? "ASC" : "DESC";
        }


        $data = $this->repository;

        if ($this->request->getGet('search')) {
            $search = $this->request->getGet('search');
            if ($search != '') {
                $data = $this->repository->search($search);
            }
        }

        if ($this->request->getGet('only_current_month')) {
            if ($this->request->getGet('only_current_month') == "true") {
                $data->filterOnCurrentMonthAndYear("date");
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'deal_replicator_view_id', 'deal_replicator_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {
        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();
            $response = $this->repository->create($data);

            if (isset($response['success'])) {

                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function show($id)
    {
        $response = $this->repository->show($id);

        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Deal couldn't be found."])->send();
        }
    }

    public function update($id)
    {

        if (service('authPolicy')->hasElevatedRoles()) {
            $data = $this->request->getPost();

            $response = $this->repository->update($id, $data);

            if (isset($response['success'])) {

                if ($data['retail_or_wholesale_flag'] == '1') {
                    $this->repository->setModel(new DealReplicatorAccessoryModel())->filterByDealReplicatorId($id)->deleteWithoutKey();
                    $this->repository->setModel(new DealReplicatorWarrantyModel())->filterByDealReplicatorId($id)->deleteWithoutKey();
                    $this->repository->setModel(new DealReplicatorInsuranceModel())->filterByDealReplicatorId($id)->deleteWithoutKey();
                    $this->repository->setModel(new DealReplicatorGuaranteeModel())->filterByDealReplicatorId($id)->deleteWithoutKey();
                    $this->repository->setModel(new DealReplicatorPaymentModel())->filterByDealReplicatorId($id)->deleteWithoutKey();
                }

                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => ERROR_RESOURCE_PERMISSION])->send();
        }
    }

    public function multipleDeletes()
    {
        $data = $this->request->getPost();

        $data['deal_ids'] = explode(',', $data['deal_ids']);

        if (count($data['deal_ids']) > 0) {
            $this->repository->delete($data['deal_ids']);
            $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "There were some errors in deleting data."])->send();
        }
    }
}
