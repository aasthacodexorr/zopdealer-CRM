<?php

namespace Tenant\app\Controllers;

use App\Helpers\Utility;
use Carbon\Carbon;
use Knp\Snappy\Pdf;
use Tenant\app\Repositories\ConfigurationRepository;
use Tenant\app\Repositories\DealRepository;
use Tenant\app\Repositories\VoucherRepository;
use Tenant\app\Repositories\VoucherTemplateRepository;

class VoucherController extends \App\Controllers\BaseController
{
    /**
     * repository This repository corresponds to the VoucherTemplateRepository which can be used by any method here.
     *
     * @var VoucherTemplateRepository
     */
    private $template_repository;

    /**
     * repository
     *
     * @var VoucherRepository
     */
    private $repository;

    public function __construct()
    {
        $this->template_repository = new VoucherTemplateRepository();
        $this->repository = new VoucherRepository();
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
                $data = $this->repository->exactSearch($search);
            }
        }

        $sort = '{"' . $column . '" : "' . $order  . '"}';

        $data = $data->index(null, $header = null, null, null, $items, $sort, $paginate, 'voucher_view_id', 'voucher_listing');

        $this->response->setContentType('application/json')->setJSON($data)->send();
    }

    public function create()
    {

        $data = $this->request->getPost();
        $response = $this->repository->create($data);

        if (isset($response['success'])) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
        }
    }

    public function show($id)
    {

        $response = $this->repository->show($id);
        if (isset($response)) {
            $this->response->setContentType('application/json')->setJSON($response)->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["errors" => "Voucher couldn't be found."])->send();
        }
    }

    public function update($id)
    {
        $voucher = $this->repository->show($id);

        if ($voucher == null || !isset($voucher['id'])) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON([
                "errors" => "Voucher couldn't be found."
            ])->send();
        } else {
            $data = $this->request->getPost();

            $response = $this->repository->update($id, $data);
            if (isset($response['success'])) {
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        }
    }

    public function delete($id)
    {

        $this->repository->delete($id);
        $this->response->setContentType('application/json')->setJSON(["success" => "Voucher has been deleted."])->send();
    }

    /**
     * updates This method updates multiple vouchers together.
     *
     * @return void
     */
    public function updates()
    {
        $data = $this->request->getPost();
        $columns = $data;
        if (array_key_exists("voucher_ids", $columns)) {
            unset($columns['voucher_ids']);
        }

        $columns = array_filter($data, function ($column) {
            return (trim($column)  != '');
        });

        $data['voucher_ids'] = explode(',', $data['voucher_ids']);

        if (count($data['voucher_ids']) > 0 && count($columns) > 0) {
            $this->repository->updates($data['voucher_ids'], $columns);
            $this->response->setContentType('application/json')->setJSON(["success" => "Columns Updated Successfully."])->send();
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "No Columns Or Rows Were Selected."])->send();
        }
    }


    public function multipleDeletes()
    {
        $data = $this->request->getPost();
        if (!empty($data['voucher_ids'])) {

            $data['voucher_ids'] = explode(',', $data['voucher_ids']);

            if (count($data['voucher_ids']) > 0) {
                $this->repository->delete($data['voucher_ids']);
                $this->response->setContentType('application/json')->setJSON(["success" => "Selected data has been deleted."])->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(["error" => "Please select Voucher to delete."])->send();
            }
        }
    }

    public function clone()
    {
        $validated = $this->validate([
            'expiry_date'  => [
                "label" => 'Expiry Date',
                "rules" => 'valid_date'
            ],
            'module'  => [
                "label" => 'Module',
                "rules" => 'permit_empty|alpha_numeric_punct'
            ],
            'module_id'  => [
                "label" => 'Module Id',
                "rules" => 'permit_empty|is_natural'
            ],
            'voucher_template_id'  => [
                "label" => 'Voucher Template Id',
                "rules" => 'required|is_natural'
            ]
        ]);

        if ($validated) {
            //Find the voucher template
            $data = $this->request->getPost();
            $voucher_template = $this->template_repository->show($data['voucher_template_id']);

            $data['title'] = $voucher_template['title'];
            $data['description'] = $voucher_template['description'];
            $data['disclosure'] = $voucher_template['disclosure'];
            $data['template'] = $voucher_template['template'];
            $data['value'] = $voucher_template['value'];
            $data['status'] = "VALID";


            $response = $this->repository->create($data);

            if (isset($response['success'])) {
                $this->repository->updateSearchIndex([$response['voucher_id']]);
                $this->response->setContentType('application/json')->setJSON($response)->send();
            } else {
                $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($response)->send();
            }
        } else {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON($this->validator->getErrors())->send();
        }
    }

    public function downloadCoupon($id)
    {
        try {

            $data = $this->prepareCouponData($id);

            $snappy = new Pdf();
            $path = \h4cc\WKHTMLToPDF\WKHTMLToPDF::PATH;
            $snappy->setBinary($path);
            $template = view('Views/voucher_templates/' . $data['template'], $data);

            $this->response->setHeader('Content-Type', 'application/pdf');
            echo $snappy->getOutputFromHtml($template);
        } catch (\Exception $ex) {
            $this->response->setContentType('application/json')->setStatusCode('400')->setJSON(['errors' => $ex->getMessage()])->send();
        }
    }

    public function checkCouponStatus(string $encrypted_id)
    {

        try {
            $decrypted_string = Utility::shortDecryptString($encrypted_id, getenv("password.encryption"));
            $id = str_replace(md5(db_connect()->database) . "-VH-", "", $decrypted_string);

            $data = $this->prepareCouponData($id);
            echo view('voucher_templates/index.php', $data);
            exit;
        } catch (\Exception $ex) {
            echo view('errors/html/error_404.php', []);
            exit;
        }
    }

    private function prepareCouponData($id)
    {

        $business_logo = ROOTPATH . "/public/media/logo/";
        $blogo = (new ConfigurationRepository())->findConfigurationColumnsByKey('business_logo', 'value');

        $voucher = $this->repository->show($id);

        $deal_dependent_details = (new DealRepository())->findInventoryAndCustomerDetailsForDeal($voucher['module_id']);

        $data = [];
        $data['template'] = $voucher['template'];

        $data['business_logo'] = ($blogo->value == "") ? "" : file_get_contents($business_logo . $blogo->value);

        if (!empty($data['business_logo'])) {
            $data['business_logo'] = base64_encode($data['business_logo']);
        }

        $data['title'] = $voucher['title'];
        $data['value'] = $voucher['value'];
        $data['disclosure'] = $voucher['disclosure'];
        $data['description'] = $voucher['description'];
        $data['status'] = $voucher['status'];

        //generate QR Code for the link
        $encrypted_id = Utility::shortEncryptString(md5(db_connect()->database) . "-VH-" . $id, getenv("password.encryption"));
        $validation_url = "https://" . $_SERVER['HTTP_HOST'] . "/api/website/vouchers/coupon/status/" . $encrypted_id;

        $qr_code = $this->repository->getQRCode($validation_url);

        $data['year'] = $data['make'] = $data['model'] = $data['vin'] = "";

        $data['first_name'] = $data['last_name'] = '';

        if (count($deal_dependent_details['inventory']) > 0) {
            foreach ($deal_dependent_details['inventory'] as $key => $inventory) {
                $data['year'] = $inventory['year'];
                $data['make'] = $inventory['make'];
                $data['model'] = $inventory['model'];
                $data['vin'] = $inventory['vin'];
            }
        }

        if (count($deal_dependent_details['customers']) > 0) {
            foreach ($deal_dependent_details['customers'] as $key => $customer) {
                if ($customer['deal_customer_type'] == 'primary_customer') {
                    $data['first_name'] = $customer['first_name'];
                    $data['last_name'] = $customer['last_name'];
                }
            }
        }

        $data['expiry_date'] = (new Carbon($voucher['expiry_date']))->toFormattedDateString();

        $data['qr_code_image'] = "<img src=\"" . $qr_code . "\" />";

        return $data;
    }
}
